<?php

namespace App\Http\Controllers\Api;

use App\Models\ApiCredential;
use App\Models\Pemohon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class InteropController extends Controller
{
    /**
     * GET /api/v3/interop/{uuid}
     *
     * Auth: WAJIB dua-duanya — header "auth: Bearer <bearer_token>" DAN header "apikey: <apikey>"
     * Response: { "data": "<hex(iv(12) + ciphertext + tag(16))>" }
     * Dekripsi di sisi konsumen: AES-256-GCM, key = SHA-256(salt_key), sesuai dokumen SPLP.
     */
    public function show(Request $request, string $uuid)
    {
        $credential = ApiCredential::where('uuid', $uuid)->first();

        if (!$credential) {
            return response()->json([
                'message' => 'Integration not found',
            ], 404);
        }

        if (!$this->isAuthorized($request, $credential)) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 401);
        }

        $pemohons = Pemohon::with(['izin.unit'])
            ->where('created_at', '>=', now()->subYear());

        if ($request->has('unit')) {
            $unitId = $request->get('unit');
            $pemohons->whereHas('izin', function ($izinQuery) use ($unitId) {
                $izinQuery->where('unit_id', $unitId);
            });
        }

        $pemohons = $pemohons->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($p) {
                return [
                    'id' => $p->id,
                    'nama' => $p->nama,
                    'no_hp' => $p->nomor_hp,
                    'no_permohonan' => $p->no_permohonan,
                    'jenis_izin' => $p->izin->nama_izin ?? null,
                    'nama_proses' => $p->nama_proses,
                    'link_izin' => $p->link_izin ?? null,
                    'status' => $p->status,
                    'tgl_pengajuan' => $p->created_at->toIso8601String(),
                    'alamat' => $p->alamat ?? null,
                    'email' => $p->email ?? null,
                    'end_date' => $p->end_date ? \Carbon\Carbon::parse($p->end_date)->toIso8601String() : null,
                ];
            });

        // Struktur sama persis kayak /api/v2/pemohon, cuma bedanya v3 di-enkripsi.
        $payload = [
            'data' => $pemohons,
            'message' => 'Berhasil',
            'success' => true,
            'code' => 200,
        ];

        $encrypted = $this->encryptPayload($payload, $credential->salt_key);

        return response()->json([
            'data' => $encrypted,
        ], 200);
    }

    private function isAuthorized(Request $request, ApiCredential $credential): bool
    {
        $authHeader = (string) $request->header('auth', '');
        $bearerValid = false;
        if (str_starts_with($authHeader, 'Bearer ')) {
            $token = substr($authHeader, 7);
            $bearerValid = hash_equals($credential->bearer_token, $token);
        }

        $apiKeyHeader = (string) $request->header('apikey', '');
        $apiKeyValid = $apiKeyHeader !== '' && hash_equals($credential->apikey, $apiKeyHeader);

        // Dikonfirmasi: pusat wajib dua-duanya sekaligus (auth + apikey), bukan salah satu.
        return $bearerValid && $apiKeyValid;
    }

    /**
     * AES-256-GCM encrypt, key = SHA-256(saltKey) raw bytes.
     * Output = hex( iv(12 byte) . ciphertext . tag(16 byte) )
     * Format ini match dengan contoh dekripsi Web Crypto API di dokumen SPLP:
     * iv = 12 byte pertama, sisanya (ciphertext+tag digabung) langsung masuk
     * crypto.subtle.decrypt karena Web Crypto expect tag menempel di akhir ciphertext.
     */
    private function encryptPayload(array $data, string $saltKey): string
    {
        $key = hash('sha256', $saltKey, true); // 32 byte raw key
        $iv = random_bytes(12); // GCM standar pakai 12 byte IV
        $plainText = json_encode($data);

        $tag = '';
        $cipherText = openssl_encrypt(
            $plainText,
            'aes-256-gcm',
            $key,
            OPENSSL_RAW_DATA,
            $iv,
            $tag,
            '',
            16 // panjang auth tag standar (16 byte)
        );

        if ($cipherText === false) {
            throw new \RuntimeException('Encryption failed');
        }

        return bin2hex($iv . $cipherText . $tag);
    }
}