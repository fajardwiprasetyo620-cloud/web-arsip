<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('arsips', function (Blueprint $table) {
            // Tanggal pembuatan/penerbitan berkas. Boleh kosong supaya data lama tetap valid.
            $table->date('tanggal_berkas')->nullable()->after('box');
        });
    }

    public function down()
    {
        Schema::table('arsips', function (Blueprint $table) {
            $table->dropColumn('tanggal_berkas');
        });
    }
};
