<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class DemoImportBatchExport implements FromView, ShouldAutoSize
{
    public function view(): View
    {
        return view('arsip.demo_import_template');
    }
}
