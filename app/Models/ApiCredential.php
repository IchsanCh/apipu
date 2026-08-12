<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiCredential extends Model
{
    protected $fillable = [
        'uuid',
        'bearer_token',
        'apikey',
        'salt_key',
        'label',
    ];
}