<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
    ];
    public function pegawais()
    {
        return $this->hasMany(Pegawai::class, 'unit_id');
    }
    public function izins()
    {
        return $this->hasMany(Izin::class, 'unit_id');
    }
}
