<?php

namespace App\Providers;

use App\Models\Peminjaman;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Bagikan jumlah peminjaman terlambat ke navigasi
        View::composer('layouts.navigation', function ($view) {
            $jumlahTerlambat = rescue(fn () => Peminjaman::terlambat()->count(), 0);
            $view->with('jumlahPeminjamanTerlambat', $jumlahTerlambat);
        });
    }
}
