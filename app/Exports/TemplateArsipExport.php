<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TemplateArsipExport implements FromView, ShouldAutoSize
{
    public function view(): View
    {
        // Memanggil file desain (blade) yang akan kita buat di Step 3
        return view('arsip.template_excel');
    }
}
