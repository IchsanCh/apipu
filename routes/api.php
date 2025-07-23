<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PegawaiController;
use App\Http\Controllers\Api\PemohonController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::get('/pemohon', [PemohonController::class, 'index']);
Route::get('/v2/pemohon', [PemohonController::class, 'versi2']);
