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
            $query = Pemohon::with(['izin.unit']) // Biar eager load semuanya
                ->where('created_at', '>=', now()->subYear());

            // Filter berdasarkan unit_id lewat relasi ke izin
            if ($request->has('unit_id')) {
                $unitId = $request->get('unit_id');
                $query->whereHas('izin', function ($izinQuery) use ($unitId) {
                    $izinQuery->where('unit_id', $unitId);
                });
            }

            // Filter status (boleh !=status juga)
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
                ], 404);
            }

            return response()->json([
                'message' => 'Success',
                'data' => $pemohons->map(function ($p) {
                    return [
                        'id' => $p->id,
                        'nama' => $p->nama,
                        'no_hp' => $p->nomor_hp,
                        'no_permohonan' => $p->no_permohonan,
                        'jenis_izin' => $p->izin->nama_izin ?? null,
                        'nama_proses' => $p->nama_proses,
                        'link_izin' => $p->link_izin ?? null,
                        'status' => $p->status,
                        'tgl_pengajuan' => $p->created_at,
                    ];
                })->toArray(),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong',
                'error' => $e->getMessage(),
            ], 400);
        }
    }
    public function versi2(Request $request)
    {
        try {
            $query = Pemohon::with(['izin.unit']) // untuk eager load
                ->where('created_at', '>=', now()->subYear());

            if ($request->has('unit_id')) {
                $unitId = $request->get('unit_id');
                $query->whereHas('izin', function ($izinQuery) use ($unitId) {
                    $izinQuery->where('unit_id', $unitId);
                });
            }

            if ($request->has('status')) {
                $status = $request->get('status');
                if (substr($status, 0, 1) === '!') {
                    $query->where('status', '!=', substr($status, 1));
                } else {
                    $query->where('status', $status);
                }
            }

            $pemohons = $query->orderBy('created_at', 'desc')->get();

            $data = $pemohons->map(function ($p) {
                return [
                    'id' => $p->id,
                    'nama' => $p->nama,
                    'no_hp' => $p->nomor_hp,
                    'no_permohonan' => $p->no_permohonan,
                    'jenis_izin' => $p->izin->nama_izin ?? null,
                    'nama_proses' => $p->nama_proses,
                    'link_izin' => $p->link_izin ?? null,
                    'status' => $p->status,
                    'tgl_pengajuan' => $p->created_at->toIso8601String(), // Format ISO8601
                    'alamat' => $p->alamat ?? null,
                    'email' => $p->email ?? null,
                    'end_date' => $p->end_date ? \Carbon\Carbon::parse($p->end_date)->toIso8601String() : null,
                ];
            });

            return response()->json([
                'data' => [
                    'data' => $data,
                ],
                'message' => 'Berhasil',
                'success' => true,
                'code' => 200,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan',
                'error' => $e->getMessage(),
                'success' => false,
                'code' => 500,
            ], 500);
        }
    }
}
