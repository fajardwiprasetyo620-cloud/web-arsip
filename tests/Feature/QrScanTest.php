<?php

namespace Tests\Feature;

use Tests\TestCase;

class QrScanTest extends TestCase
{
    public function test_halaman_scanner_qr_bisa_dibuka_oleh_publik(): void
    {
        $response = $this->get(route('qr.scan'));

        $response->assertOk();
        $response->assertSee('Pemindai QR Code');
    }
}

