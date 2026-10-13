<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    // Kunci nama tabel agar Laravel tidak mencari tabel "peminjamen"
    protected $table = 'peminjamans';

    protected $fillable = [
        'arsip_id',
        'nama_peminjam',
        'divisi',
        'tanggal_pinjam',
        'tenggat_waktu',
        'tanggal_kembali',
        'status',
        'disetujui_oleh',
    ];

    // Relasi: Satu peminjaman dimiliki oleh satu arsip
    public function arsip()
    {
        return $this->belongsTo(Arsip::class);
    }

    /**
     * Peminjaman yang belum dikembalikan dan sudah melewati tenggat waktu.
     */
    public function scopeTerlambat(Builder $query): Builder
    {
        return $query->where('status', 'Dipinjam')
                     ->whereNotNull('tenggat_waktu')
                     ->whereDate('tenggat_waktu', '<', today());
    }
}
