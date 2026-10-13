<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_name',
        'action',
        'description',
    ];

    /**
     * Catat aktivitas admin yang sedang login.
     * Nama admin otomatis ditambahkan di depan deskripsi.
     *
     * Contoh: ActivityLog::catat('TAMBAH', 'menambahkan arsip baru: J0401B');
     */
    public static function catat(string $aksi, string $deskripsi): void
    {
        $nama = auth()->user()?->name ?? 'Sistem';

        static::create([
            'user_name'   => $nama,
            'action'      => $aksi,
            'description' => $nama . ' ' . $deskripsi,
        ]);
    }
}
