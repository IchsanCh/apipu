<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pemohon extends Model
{
    protected $fillable = ['nama', 'nomor_hp', 'status', 'tahapan', 'izin_id'];
    public function izin()
    {
        return $this->belongsTo(Izin::class);
    }
}
