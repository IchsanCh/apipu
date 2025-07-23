<?php

namespace App\Http\Controllers\Api;

use App\Models\Pegawai;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PegawaiController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Pegawai::with('unit');

            // Filter by unit_id (optional)
            if ($request->has('unit_id')) {
                $unitId = $request->get('unit_id');
                $query->where('unit_id', $unitId);
            }

            $pegawais = $query->orderBy('nama')->get();

            if ($pegawais->isEmpty()) {
                return response()->json([
                    'message' => 'Data not found',
                    'data' => null,
                ], 404);
            }

            return response()->json([
                'message' => 'Success',
                'data' => $pegawais->map(function ($p) {
                    return [
                        'id' => $p->id,
                        'nama' => $p->nama,
                        'no_hp' => $p->nomor_hp,
                        'email' => $p->email,
                        'posisi' => $p->posisi,
                        'unit_nama' => $p->unit->name ?? null,
                        'created_at' => $p->created_at->toDateTimeString(),
                    ];
                }),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong',
                'error' => $e->getMessage(),
            ], 400);
        }
    }
}
