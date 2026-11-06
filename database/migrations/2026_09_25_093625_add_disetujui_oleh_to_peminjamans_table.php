<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('peminjamans', function (Blueprint $table) {
            // Menambahkan kolom untuk menyimpan nama admin
            $table->string('disetujui_oleh')->nullable()->after('status');
        });
    }

    public function down()
    {
        Schema::table('peminjamans', function (Blueprint $table) {
            $table->dropColumn('disetujui_oleh');
        });
    }
};
