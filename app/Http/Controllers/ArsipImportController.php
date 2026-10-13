<?php

namespace App\Http\Controllers;

use App\Imports\ArsipImport;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

/**
 * Import data arsip massal dari Excel, beserta template unduhannya.
 */
class ArsipImportController extends Controller
{
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv,xls',
        ]);

        try {
            $import = new ArsipImport;
            Excel::import($import, $request->file('file'));

            ActivityLog::catat('IMPORT', "melakukan import arsip dari Excel ({$import->masuk} masuk, {$import->dilewati} dilewati).");

            $pesan = "{$import->masuk} arsip berhasil di-import, {$import->dilewati} baris dilewati (kode/nama kosong atau kode sudah terdaftar).";

            return redirect()->route('arsip.index')->with($import->masuk > 0 ? 'success' : 'warning', $pesan);
        } catch (\Exception $e) {
            return redirect()->route('arsip.index')->with('error', 'Terjadi kesalahan format: ' . $e->getMessage());
        }
    }

    public function template()
    {
        return Excel::download(new \App\Exports\TemplateArsipExport, 'Template_Import_Arsip.xlsx');
    }
}
