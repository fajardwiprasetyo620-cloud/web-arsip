<?php

namespace App\Exports;

use App\Models\ActivityLog;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ActivityLogExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping
{
    protected ?string $search;

    public function __construct(?string $search = null)
    {
        $this->search = $search;
    }

    public function collection(): Collection
    {
        $query = ActivityLog::query();

        if ($this->search) {
            $search = $this->search;
            $query->where(function ($q) use ($search) {
                $q->where('user_name', 'LIKE', '%'.$search.'%')
                    ->orWhere('action', 'LIKE', '%'.$search.'%')
                    ->orWhere('description', 'LIKE', '%'.$search.'%')
                    ->orWhere('created_at', 'LIKE', '%'.$search.'%');
            });
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Waktu & Tanggal',
            'Nama Admin',
            'Tindakan',
            'Keterangan Detail',
        ];
    }

    public function map($log): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $log->created_at ? $log->created_at->format('d/m/Y H:i:s') : '-',
            $log->user_name,
            $log->action,
            $log->description,
        ];
    }
}
