<?php

namespace App\Http\Controllers;

use App\Exports\ArsipDataExport;
use App\Models\Arsip;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

/**
 * Ekspor data arsip ke PDF & Excel: seluruh data, atau sesuai filter pencarian.
 */
class ArsipExportController extends Controller
{
    public function pdf()
    {
        $arsips = Arsip::all();

        $pdf = Pdf::loadView('arsip.pdf', compact('arsips'));
        $pdf->setPaper('A4', 'landscape');

        return $pdf->download('Laporan_Data_Arsip_BPJS.pdf');
    }

    public function pdfSelected(Request $request)
    {
        $ids = $request->ids ?? $request->arsip_ids;

        if (!$ids || empty($ids)) {
            return redirect()->back()->with('error', 'Pilih minimal satu data arsip untuk dicetak!');
        }

        $arsips = Arsip::whereIn('id', $ids)->orderBy('kode_berkas', 'asc')->get();

        $pdf = Pdf::loadView('arsip.pdf', compact('arsips'));

        return $pdf->download('Laporan_Arsip_Terpilih.pdf');
    }

    public function excel(Request $request)
    {
        $filter = $request->only('search', 'ruangan', 'status', 'digital');
        $namaFile = 'Data_Arsip_' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new ArsipDataExport($filter), $namaFile);
    }
}
