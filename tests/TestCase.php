<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        // Saat testing, aset frontend (Vite) belum tentu sudah di-build (npm run build),
        // jadi setiap test yang merender halaman dimatikan pemanggilan @vite()-nya.
        $this->withoutVite();
    }
}
