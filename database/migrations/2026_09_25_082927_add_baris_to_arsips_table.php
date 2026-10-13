<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('arsips', function (Blueprint $table) {
            // Menambahkan kolom baris setelah kolom rak
            // Diberi nilai default 1 agar arsip lama yang sudah ada di database tidak error
            $table->integer('baris')->default(1)->after('rak');
        });
    }

    public function down()
    {
        Schema::table('arsips', function (Blueprint $table) {
            // Membatalkan penambahan kolom jika di-rollback
            $table->dropColumn('baris');
        });
    }
};