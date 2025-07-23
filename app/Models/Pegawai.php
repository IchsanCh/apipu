<?php

namespace App\Models;

use App\Models\Unit;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    protected $fillable = [
        'nama',
        'nomor_hp',
        'unit_id',
        'posisi',
        'email'
    ];

    // Define the relationship
    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }
}
