<?php

namespace App\Http\Controllers;

use App\Models\Arsip;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

/**
 * Ekspor data arsip ke PDF: seluruh data, atau hanya baris yang dipilih.
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
}
