<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'E-Arsip BPJS') }}</title>

        <!-- Pastikan tema terang resmi BPJS aktif konsisten -->
        <script>
            document.documentElement.classList.remove('dark');
            localStorage.removeItem('theme');
        </script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-gray-800 bg-stone-100">
        <!-- Flex-col ditambahkan agar footer selalu menempel di bawah -->
        <div class="min-h-screen bg-stone-100 flex flex-col">
            
            @include('layouts.navigation')

            <!-- Page Heading dengan aksen shadow yang lebih lembut -->
            @if (isset($header))
                <header class="bg-emerald-600 shadow-md relative z-10">
                    <div class="max-w-7xl min-h-[5.25rem] mx-auto py-3 px-4 sm:px-6 lg:px-8 flex items-center">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main class="flex-grow">
                {{ $slot }}
            </main>

            <!-- FOOTER BARU -->
            <footer class="bg-white border-t border-gray-200 mt-12 py-6">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center text-sm text-gray-500">
                    <div class="mb-2 md:mb-0 font-medium">
                        &copy; {{ date('Y') }} BPJS Ketenagakerjaan Kantor Cabang Rawamangun.
                    </div>
                    <div class="flex items-center gap-2">
                        <span>Dikembangkan oleh</span>
                        <span class="bg-emerald-100 text-emerald-800 px-2 py-1 rounded font-bold text-xs">Fajar Dwi Prasetyo (Tim IT)</span>
                    </div>
                </div>
            </footer>
                    </div>
                </div>
            </footer>

        </div>

        <x-command-palette />
    </body>
</html>