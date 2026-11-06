<?php

namespace Tests\Feature;

use App\Exports\ArsipDataExport;
use App\Models\Arsip;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;

class ArsipExportExcelTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create();
    }

    public function test_export_excel_arsip_berhasil(): void
    {
        Carbon::setTestNow(now());
        Excel::fake();

        Arsip::factory()->count(3)->create([
            'ruangan' => 'Ruang Arsip Kecil',
        ]);

        $response = $this->actingAs($this->admin)->get(route('arsip.excel', ['ruangan' => 'Ruang Arsip Kecil']));

        $response->assertOk();
        $expectedName = 'Data_Arsip_'.now()->format('Ymd_His').'.xlsx';

        Excel::assertDownloaded($expectedName, function (ArsipDataExport $export) {
            return $export->collection()->count() === 3;
        });
    }
}
