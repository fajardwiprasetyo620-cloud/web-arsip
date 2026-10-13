<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'E-Arsip BPJS') }}</title>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-emerald-800">
            <div>
                <a href="/">
                    <!-- Custom Logo E-Arsip -->
                    <div class="flex flex-col items-center transition transform hover:scale-105 duration-300">
                        <div class="bg-white p-3.5 rounded-2xl shadow-lg mb-4 border-b-4 border-emerald-500">
                            <svg class="w-12 h-12 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        </div>
                        <h1 class="text-3xl font-extrabold text-white tracking-wider drop-shadow-md">E-ARSIP</h1>
                        <p class="text-emerald-200 text-sm font-bold tracking-widest mt-1 drop-shadow-sm">BPJS KETENAGAKERJAAN</p>
                    </div>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-8 px-8 py-10 bg-white shadow-2xl overflow-hidden sm:rounded-2xl border border-gray-100">
                {{ $slot }}
            </div>
            
            <div class="mt-10 text-emerald-200/60 text-sm font-medium tracking-wide">
                &copy; {{ date('Y') }} BPJS Ketenagakerjaan.
            </div>
        </div>
    </body>
</html>