<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pemohon extends Model
{
    protected $fillable = ['nama', 'nomor_hp', 'status', 'nama_proses', 'izin_id', 'no_permohonan', 'link_izin'];
    public function izin()
    {
        return $this->belongsTo(Izin::class);
    }
}
