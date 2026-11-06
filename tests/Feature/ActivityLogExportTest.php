<?php

namespace Tests\Feature;

use App\Exports\ActivityLogExport;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;

class ActivityLogExportTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create();
    }

    public function test_export_excel_log_aktivitas_berhasil(): void
    {
        Carbon::setTestNow(now());
        ActivityLog::create([
            'user_name' => 'Admin Test',
            'action' => 'TAMBAH',
            'description' => 'Admin Test menambahkan arsip baru: ARS-001',
        ]);

        Excel::fake();

        $response = $this->actingAs($this->admin)->get(route('arsip.logs.excel'));

        $response->assertSuccessful();
        $expectedName = 'log-aktivitas-arsip-'.now()->format('Y-m-d_His').'.xlsx';
        Excel::assertDownloaded($expectedName, function (ActivityLogExport $export) {
            return $export->collection()->count() === 1;
        });
    }

    public function test_export_excel_log_aktivitas_dengan_pencarian(): void
    {
        Carbon::setTestNow(now());
        ActivityLog::create([
            'user_name' => 'Admin Fajar',
            'action' => 'TAMBAH',
            'description' => 'Admin Fajar menambahkan arsip baru: ARS-100',
        ]);

        ActivityLog::create([
            'user_name' => 'Admin Firman',
            'action' => 'HAPUS',
            'description' => 'Admin Firman menghapus arsip: ARS-200',
        ]);

        Excel::fake();

        $response = $this->actingAs($this->admin)->get(route('arsip.logs.excel', ['search' => 'Fajar']));

        $response->assertSuccessful();
        $expectedName = 'log-aktivitas-arsip-'.now()->format('Y-m-d_His').'.xlsx';
        Excel::assertDownloaded($expectedName, function (ActivityLogExport $export) {
            return $export->collection()->count() === 1;
        });
    }
}
