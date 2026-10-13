<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peminjamans', function (Blueprint $table) {
            // Menambahkan kolom tenggat waktu setelah tanggal pinjam
            $table->date('tenggat_waktu')->nullable()->after('tanggal_pinjam');
        });
    }

    public function down(): void
    {
        Schema::table('peminjamans', function (Blueprint $table) {
            $table->dropColumn('tenggat_waktu');
        });
    }
};
