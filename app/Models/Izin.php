<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Izin extends Model
{
    protected $fillable = ['nama_izin'];

    public function pemohons()
    {
        return $this->hasMany(Pemohon::class);
    }
}
