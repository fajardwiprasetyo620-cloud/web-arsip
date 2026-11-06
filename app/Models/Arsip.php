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
        'Ruang Arsip Kecil' => 4,
        'Ruang Arsip Kepesertaan' => 6,
    ];

    protected $fillable = [
        'kode_berkas',
        'nama_berkas',
        'ruangan',
        'rak',
        'baris',
        'box',
        'tanggal_berkas',
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
     * Menghitung umur arsip dalam tahun berdasarkan tanggal berkas (atau tanggal dibuat).
     */
    public function getUmurTahunAttribute(): float
    {
        $tanggal = $this->tanggal_berkas ? \Carbon\Carbon::parse($this->tanggal_berkas) : $this->created_at;

        return round($tanggal->diffInDays(now()) / 365.25, 1);
    }

    /**
     * Status Siklus Retensi Arsip berdasarkan Pedoman ANRI / JRA BPJS:
     * - Aktif: 0 - 2 tahun
     * - Inaktif: > 2 - 5 tahun
     * - Jatuh Tempo Retensi: > 5 tahun (Siap usul musnah/permanen)
     */
    public function getStatusRetensiAttribute(): string
    {
        $umur = $this->umur_tahun;
        if ($umur <= 2.0) {
            return 'Aktif';
        } elseif ($umur <= 5.0) {
            return 'Inaktif';
        }

        return 'Jatuh Tempo';
    }

    public function getStatusRetensiBadgeAttribute(): string
    {
        return match ($this->status_retensi) {
            'Aktif' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            'Inaktif' => 'bg-amber-50 text-amber-700 border-amber-200',
            'Jatuh Tempo' => 'bg-rose-50 text-rose-700 border-rose-200',
            default => 'bg-slate-50 text-slate-700 border-slate-200',
        };
    }

    /**
     * Filter bersama untuk katalog publik dan halaman admin.
     * Kunci yang dipakai: search, ruangan, status.
     */
    public function scopeFilter(Builder $query, array $filter): Builder
    {
        return $query
            ->when($filter['search'] ?? null, function ($q, $cari) {
                $cariTrim = trim($cari);
                $q->where(function ($w) use ($cariTrim) {
                    $w->where('nama_berkas', 'like', "%{$cariTrim}%")
                        ->orWhere('kode_berkas', 'like', "%{$cariTrim}%")
                        ->orWhere('rak', 'like', "%{$cariTrim}%")
                        ->orWhere('box', 'like', "%{$cariTrim}%");

                    // Toleransi variasi nomor box (contoh: "Box 3", "3", "Box 03")
                    if (preg_match('/^(?:box\s*)?0*(\d+)$/i', $cariTrim, $matches)) {
                        $nomor = (int) $matches[1];
                        $pad2 = sprintf('%02d', $nomor);
                        $w->orWhere('box', 'like', "%Box {$nomor}%")
                            ->orWhere('box', 'like', "%Box {$pad2}%")
                            ->orWhere('box', $pad2)
                            ->orWhere('box', (string) $nomor);
                    }
                });
            })
            ->when($filter['ruangan'] ?? null, fn ($q, $ruangan) => $q->where('ruangan', $ruangan))
            ->when($filter['rak'] ?? null, fn ($q, $rak) => $q->where('rak', $rak))
            ->when($filter['status'] ?? null, fn ($q, $status) => $q->where('status', $status))
            ->when($filter['digital'] ?? null, function ($q, $digital) {
                if ($digital === 'sudah') {
                    $q->whereNotNull('file_dokumen');
                } elseif ($digital === 'belum') {
                    $q->whereNull('file_dokumen');
                }
            })
            ->when($filter['retensi'] ?? null, function ($q, $retensi) {
                $duaTahunLalu = now()->subYears(2)->toDateString();
                $limaTahunLalu = now()->subYears(5)->toDateString();

                if ($retensi === 'aktif') {
                    $q->where(fn ($w) => $w->where('tanggal_berkas', '>=', $duaTahunLalu)->orWhereNull('tanggal_berkas'));
                } elseif ($retensi === 'inaktif') {
                    $q->where('tanggal_berkas', '<', $duaTahunLalu)
                        ->where('tanggal_berkas', '>=', $limaTahunLalu);
                } elseif ($retensi === 'jatuh_tempo') {
                    $q->where('tanggal_berkas', '<', $limaTahunLalu);
                }
            });
    }
}
