<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Arsip extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Daftar ruangan yang valid beserta jumlah baris maksimalnya.
     * Sama dengan kapasitas yang dipakai di form create/edit.
     */
    public const KAPASITAS_BARIS = [
        'Ruang Arsip Kecil'       => 4,
        'Ruang Arsip Kepesertaan' => 6,
    ];

    protected $fillable = [
        'kode_berkas',
        'nama_berkas',
        'ruangan',
        'rak',
        'baris',
        'box',
        'status',
        'file_dokumen',
    ];

    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class);
    }

    public function sedangDipinjam(): bool
    {
        return $this->status === 'Dipinjam';
    }

    /**
     * Filter bersama untuk katalog publik dan halaman admin.
     * Kunci yang dipakai: search, ruangan, status.
     */
    public function scopeFilter(Builder $query, array $filter): Builder
    {
        return $query
            ->when($filter['search'] ?? null, function ($q, $cari) {
                $q->where(function ($w) use ($cari) {
                    $w->where('nama_berkas', 'like', "%{$cari}%")
                      ->orWhere('kode_berkas', 'like', "%{$cari}%");
                });
            })
            ->when($filter['ruangan'] ?? null, fn ($q, $ruangan) => $q->where('ruangan', $ruangan))
            ->when($filter['status'] ?? null, fn ($q, $status) => $q->where('status', $status));
    }
}
