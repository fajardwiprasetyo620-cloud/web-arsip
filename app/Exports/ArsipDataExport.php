<?php

namespace App\Exports;

use App\Models\Arsip;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ArsipDataExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping
{
    protected ?array $filter;

    public function __construct(?array $filter = null)
    {
        $this->filter = $filter;
    }

    public function collection(): Collection
    {
        $query = Arsip::query();
        if ($this->filter) {
            $query->filter($this->filter);
        }

        return $query->orderBy('kode_berkas')->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Kode Berkas',
            'Nama Berkas',
            'Ruangan',
            'Rak',
            'Baris',
            'Box',
            'Tanggal Berkas',
            'Status',
            'Dokumen Digital',
        ];
    }

    public function map($arsip): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $arsip->kode_berkas,
            $arsip->nama_berkas,
            $arsip->ruangan,
            $arsip->rak,
            $arsip->baris,
            $arsip->box,
            $arsip->tanggal_berkas ? \Carbon\Carbon::parse($arsip->tanggal_berkas)->format('d/m/Y') : '-',
            $arsip->status,
            $arsip->file_dokumen ? 'Tersedia' : 'Belum Ada',
        ];
    }
}
