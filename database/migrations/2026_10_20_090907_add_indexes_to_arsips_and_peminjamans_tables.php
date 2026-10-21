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
        Schema::table('arsips', function (Blueprint $table) {
            $table->index(['ruangan', 'status'], 'idx_arsip_ruangan_status');
            $table->index('box', 'idx_arsip_box');
        });

        Schema::table('peminjamans', function (Blueprint $table) {
            $table->index(['status', 'tenggat_waktu'], 'idx_peminjaman_status_tenggat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('arsips', function (Blueprint $table) {
            $table->dropIndex('idx_arsip_ruangan_status');
            $table->dropIndex('idx_arsip_box');
        });

        Schema::table('peminjamans', function (Blueprint $table) {
            $table->dropIndex('idx_peminjaman_status_tenggat');
        });
    }
};
