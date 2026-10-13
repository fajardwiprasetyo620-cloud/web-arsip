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
    Schema::create('arsips', function (Blueprint $table) {
        $table->id();
        $table->string('kode_berkas')->unique();
        $table->string('nama_berkas');
        $table->string('ruangan'); // Contoh: Ruang A / Ruang B
        $table->string('rak');     // Contoh: Rak 1
        $table->string('box');     // Contoh: Box 12
        $table->enum('status', ['Tersedia', 'Dipinjam'])->default('Tersedia');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arsips');
    }
};
