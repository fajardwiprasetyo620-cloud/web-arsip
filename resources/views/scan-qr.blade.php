@auth
    <x-app-layout>
        <x-slot name="header">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="font-semibold text-xl sm:text-2xl text-white leading-tight flex items-center gap-2.5">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                        {{ __('Pemindai QR Code') }}
                    </h2>
                    <p class="text-xs sm:text-sm text-emerald-100 mt-1">Arahkan kamera ke stiker label QR berkas fisik atau label box untuk membuka informasi detail secara instan.</p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-1.5 bg-white/10 hover:bg-white/20 border border-white/20 text-white font-bold text-xs py-2 px-3.5 rounded-xl transition shadow-sm backdrop-blur-xs">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        <span>Dashboard</span>
                    </a>
                </div>
            </div>
        </x-slot>

        <div class="py-6 sm:py-8 max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            @include('partials.qr-scanner-content')
        </div>
    </x-app-layout>
@else
    <!DOCTYPE html>
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Pemindai QR Code - E-Arsip BPJS Ketenagakerjaan</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-stone-100 min-h-screen text-slate-800 antialiased flex flex-col font-sans">
        
        <!-- Public Navbar BPJS -->
        <nav class="bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center gap-3">
                        <a href="{{ route('katalog.index') }}" class="flex items-center gap-2.5">
                            <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                        </a>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <a href="{{ route('katalog.index') }}" class="text-xs font-bold text-gray-600 hover:text-emerald-700 py-2 px-3 rounded-lg transition">Katalog Publik</a>
                        <a href="{{ route('login') }}" class="text-xs font-bold bg-emerald-600 hover:bg-emerald-700 text-white py-2 px-3.5 rounded-lg transition shadow-2xs">Masuk Petugas</a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Public Header Banner -->
        <header class="bg-emerald-600 shadow-md text-white py-4 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto flex items-center justify-between">
                <div>
                    <h1 class="font-bold text-lg sm:text-xl leading-tight flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                        <span>Pemindai QR Code</span>
                    </h1>
                    <p class="text-xs text-emerald-100 mt-0.5">Scan label stiker box atau berkas fisik BPJS Ketenagakerjaan</p>
                </div>
            </div>
        </header>

        <main class="flex-1 max-w-2xl w-full mx-auto p-4 sm:p-6 lg:p-8 flex flex-col justify-center">
            @include('partials.qr-scanner-content')
        </main>

        <footer class="bg-white border-t border-gray-200 mt-12 py-6 text-center text-xs text-gray-500">
            &copy; {{ date('Y') }} BPJS Ketenagakerjaan Kantor Cabang Rawamangun.
        </footer>
    </body>
    </html>
@endauth
