<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>E-Katalog Arsip Publik</title>
    <!-- Memanggil Tailwind CSS bawaan Laravel -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 font-sans antialiased text-gray-900">
    
    <!-- HEADER / NAVBAR PUBLIK -->
    <header class="bg-emerald-700 shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex flex-col sm:flex-row justify-between items-center gap-4">
            <!-- Logo & Judul -->
            <div class="flex items-center gap-3">
                <div class="bg-white p-2.5 rounded-xl shadow-sm">
                    <svg class="w-7 h-7 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
                <div>
                    <h1 class="text-2xl font-extrabold text-white tracking-tight">E-Katalog Arsip</h1>
                    <p class="text-emerald-100 text-sm font-medium">Pencarian Dokumen Internal Karyawan</p>
                </div>
            </div>
            
            <!-- Tombol Pintu Masuk Admin -->
            <a href="{{ route('login') }}" class="bg-emerald-800 hover:bg-emerald-900 border border-emerald-500 text-white font-semibold py-2 px-5 rounded-full transition duration-300 flex items-center gap-2 shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                Login Admin
            </a>
        </div>
    </header>

    <!-- KONTEN UTAMA -->
    <main class="py-10 px-4 sm:px-0">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Papan Instruksi untuk Karyawan -->
            <div class="bg-blue-50 border-l-4 border-blue-500 p-5 mb-8 rounded-r-xl shadow-sm flex gap-4 items-start">
                <svg class="w-6 h-6 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <div>
                    <h3 class="text-blue-900 font-bold text-lg">Informasi Peminjaman Berkas</h3>
                    <p class="text-blue-800 mt-1 text-sm leading-relaxed">Anda berada di halaman pencarian publik. Untuk melakukan peminjaman arsip fisik, silakan cari data di bawah ini, catat <strong>Kode Berkas</strong> yang Anda butuhkan, dan hubungi langsung Petugas Arsip (Admin) di ruangan.</p>
                </div>
            </div>

            <!-- Area Pencarian -->
            <div class="bg-white p-4 rounded-xl shadow-sm mb-6 flex flex-col xl:flex-row justify-end items-center gap-4 border border-gray-100">
                <form action="{{ route('katalog.index') }}" method="GET" class="flex flex-wrap lg:flex-nowrap gap-2 w-full justify-end">
                    
                    <select name="ruangan" class="border-gray-300 text-gray-700 text-sm rounded-lg shadow-sm py-2 pl-3 pr-8 focus:ring-emerald-500 focus:border-emerald-500 bg-gray-50 font-medium">
                        <option value="">🏢 Semua Ruangan</option>
                        @foreach($ruangans as $ruang)
                            <option value="{{ $ruang }}" {{ request('ruangan') == $ruang ? 'selected' : '' }}>{{ $ruang }}</option>
                        @endforeach
                    </select>

                    <select name="status" class="border-gray-300 text-gray-700 text-sm rounded-lg shadow-sm py-2 pl-3 pr-8 focus:ring-emerald-500 focus:border-emerald-500 bg-gray-50 font-medium">
                        <option value="">📌 Semua Status</option>
                        <option value="Tersedia" {{ request('status') == 'Tersedia' ? 'selected' : '' }}>Tersedia</option>
                        <option value="Dipinjam" {{ request('status') == 'Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                    </select>

                    <div class="relative w-full lg:w-auto min-w-[250px]">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input type="text" name="search" placeholder="Cari berdasarkan kode / nama..." value="{{ request('search') }}" class="pl-9 border-gray-300 text-sm text-gray-700 rounded-lg shadow-sm py-2 focus:ring-emerald-500 focus:border-emerald-500 w-full">
                    </div>
                    
                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-5 text-sm rounded-lg shadow-sm transition duration-200">
                        Cari Arsip
                    </button>
                    
                    @if(request()->anyFilled(['search', 'ruangan', 'status']))
                        <a href="{{ route('katalog.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-2 px-4 text-sm rounded-lg shadow-sm transition duration-200 flex items-center justify-center">
                            Reset
                        </a>
                    @endif
                </form>
            </div>

            <!-- Tabel Data (tampil di layar md ke atas) -->
            <div class="hidden md:block bg-white overflow-hidden shadow-md sm:rounded-xl border border-gray-200">
                <div class="p-0 overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-100 border-b border-gray-200">
                                <th class="py-4 px-6 font-semibold text-sm text-slate-700 whitespace-nowrap">Kode Berkas</th>
                                <th class="py-4 px-6 font-semibold text-sm text-slate-700 w-1/2">Nama Berkas</th>
                                <th class="py-4 px-6 font-semibold text-sm text-slate-700">Lokasi Fisik (Rak/Baris/Box)</th>
                                <th class="py-4 px-6 font-semibold text-sm text-slate-700 text-center whitespace-nowrap">Status Ketersediaan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($arsips as $arsip)
                            <tr class="hover:bg-slate-50 transition duration-150">
                                <td class="py-4 px-6 font-bold text-sm text-emerald-700 whitespace-nowrap">{{ $arsip->kode_berkas }}</td>
                                <td class="py-4 px-6 text-sm text-gray-800 leading-snug">{{ $arsip->nama_berkas }}</td>
                                
                                <td class="py-3 px-4">
                                    <!-- Kontainer utama w-max agar menyesuaikan isi terlebar -->
                                    <div class="inline-flex flex-col gap-1.5 w-max">
                                        
                                        <!-- Baris Atas: Ruangan (w-full dan justify-center agar meregang rata) -->
                                        <span class="w-full justify-center inline-flex items-center gap-1.5 bg-slate-100 text-slate-700 px-2.5 py-1 rounded border border-slate-200 text-[11px] font-bold uppercase shadow-sm whitespace-nowrap">
                                            🏢 {{ $arsip->ruangan }}
                                        </span>
                                        
                                        <!-- Baris Bawah: Rak, Baris, Box (w-full agar meregang mengikuti lebar baris atas) -->
                                        <div class="flex items-center justify-between gap-1.5 w-full">
                                            <span class="flex-1 justify-center whitespace-nowrap inline-flex items-center gap-1 bg-blue-50 text-blue-700 px-2 py-1 rounded border border-blue-200 text-xs font-semibold shadow-sm">
                                                📚 {{ $arsip->rak }}
                                            </span>
                                            <span class="flex-1 justify-center whitespace-nowrap inline-flex items-center gap-1 bg-emerald-50 text-emerald-700 px-2 py-1 rounded border border-emerald-200 text-xs font-semibold shadow-sm">
                                                📍 Baris {{ $arsip->baris }}
                                            </span>
                                            <span class="flex-1 justify-center whitespace-nowrap inline-flex items-center gap-1 bg-amber-50 text-amber-700 px-2 py-1 rounded border border-amber-200 text-xs font-semibold shadow-sm">
                                                📦 Box {{ $arsip->box }}
                                            </span>
                                        </div>
                                        
                                    </div>
                                </td>
                                
                                <td class="py-4 px-6 text-center whitespace-nowrap">
                                    <span class="px-3 py-1 text-xs font-bold rounded-full shadow-sm {{ $arsip->status == 'Tersedia' ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : 'bg-red-100 text-red-800 border border-red-200' }}">
                                        {{ $arsip->status }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="py-16 text-center text-gray-500 bg-gray-50">
                                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path></svg>
                                    <p class="font-medium text-gray-600">Arsip tidak ditemukan.</p>
                                    <p class="text-sm mt-1">Gunakan kata kunci pencarian yang berbeda.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                    <div class="px-6 py-4 border-t border-gray-100">
                        {{ $arsips->links() }}
                    </div>
            </div>

            <!-- Daftar Kartu (tampil di HP, di bawah lebar md) -->
            <div class="md:hidden space-y-4">
                @forelse ($arsips as $arsip)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                        <div class="flex items-start justify-between gap-3 mb-3">
                            <div>
                                <p class="font-bold text-sm text-emerald-700">{{ $arsip->kode_berkas }}</p>
                                <p class="text-sm text-gray-800 leading-snug mt-0.5">{{ $arsip->nama_berkas }}</p>
                            </div>
                            <span class="shrink-0 px-3 py-1 text-xs font-bold rounded-full shadow-sm {{ $arsip->status == 'Tersedia' ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : 'bg-red-100 text-red-800 border border-red-200' }}">
                                {{ $arsip->status }}
                            </span>
                        </div>
                        <div class="flex flex-wrap items-center gap-1.5">
                            <span class="inline-flex items-center gap-1 bg-slate-100 text-slate-700 px-2.5 py-1 rounded border border-slate-200 text-[11px] font-bold uppercase shadow-sm">
                                🏢 {{ $arsip->ruangan }}
                            </span>
                            <span class="inline-flex items-center gap-1 bg-blue-50 text-blue-700 px-2 py-1 rounded border border-blue-200 text-xs font-semibold shadow-sm">
                                📚 {{ $arsip->rak }}
                            </span>
                            <span class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-700 px-2 py-1 rounded border border-emerald-200 text-xs font-semibold shadow-sm">
                                📍 Baris {{ $arsip->baris }}
                            </span>
                            <span class="inline-flex items-center gap-1 bg-amber-50 text-amber-700 px-2 py-1 rounded border border-amber-200 text-xs font-semibold shadow-sm">
                                📦 Box {{ $arsip->box }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-xl border border-gray-200 py-12 text-center text-gray-500">
                        <p class="font-medium text-gray-600">Arsip tidak ditemukan.</p>
                        <p class="text-sm mt-1">Gunakan kata kunci pencarian yang berbeda.</p>
                    </div>
                @endforelse

                <div class="pt-2">
                    {{ $arsips->links() }}
                </div>
            </div>
            
            <div class="mt-8 text-center text-sm text-gray-500">
                &copy; 2026 BPJS Ketenagakerjaan Kantor Cabang Rawamangun. E-Arsip Sistem.
            </div>
        </div>
    </main>

</body>
</html>