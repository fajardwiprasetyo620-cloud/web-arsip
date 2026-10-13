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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            // Kita simpan nama langsung (bukan ID) agar log tetap bisa dibaca 
            // meskipun akun admin tersebut suatu saat dihapus.
            $table->string('user_name'); 
            $table->string('action'); // Contoh: 'TAMBAH', 'EDIT', 'HAPUS'
            $table->text('description'); // Contoh: 'Fajar memindahkan arsip dari Rak A ke Rak B'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
