<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PegawaiController;
use App\Http\Controllers\Api\PemohonController;
use App\Http\Controllers\Api\InteropController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::get('/pemohon', [PemohonController::class, 'index']);
Route::get('/v2/pemohon', [PemohonController::class, 'versi2']);
Route::get('/v3/interop/{uuid}', [InteropController::class, 'show']);