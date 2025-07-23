<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pemohons', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('nomor_hp');
            $table->enum('status', ['proses', 'selesai'])->default('proses');
            $table->string('nama_proses')->default('verifikasi berkas');
            $table->unsignedBigInteger('izin_id');
            $table->timestamps();
            $table->string('no_permohonan');
            $table->foreign('izin_id')->references('id')->on('izins')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemohons');
    }
};
