<?php

namespace App\Http\Controllers\Api;

use App\Models\Pemohon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PemohonController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Pemohon::with('izin')
                ->where('created_at', '>=', now()->subYear());

            // Optional filter by status
            if ($request->has('status')) {
                $status = $request->get('status');
                if (substr($status, 0, 1) === '!') {
                    $query->where('status', '!=', substr($status, 1));
                } else {
                    $query->where('status', $status);
                }
            }

            $pemohons = $query->orderBy('created_at', 'desc')->get();

            if ($pemohons->isEmpty()) {
                return response()->json([
                    'message' => 'Data not found',
                    'data' => null,
                ], 404); // Not Found
            }

            return response()->json([
                'message' => 'Success',
                'data' => $pemohons->map(function ($p) {
                    return [
                        'id' => $p->id,
                        'nama' => $p->nama,
                        'nomor_hp' => $p->nomor_hp,
                        'nama_izin' => $p->izin->nama_izin ?? null,
                        'tahapan' => $p->tahapan,
                        'status' => $p->status,
                        'created_at' => $p->created_at->toDateTimeString(),
                    ];
                })->toArray(),
            ], 200); // OK

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong',
                'error' => $e->getMessage(),
            ], 400); // Bad Request
        }
    }
}
