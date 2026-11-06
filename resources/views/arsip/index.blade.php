<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-3 md:gap-4 w-full">
            <h2 class="font-semibold text-xl sm:text-2xl text-white leading-tight flex items-center gap-2.5">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
                {{ __('Daftar Arsip Fisik') }}
            </h2>
            
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('arsip.logs') }}" class="bg-white hover:bg-emerald-50 text-emerald-700 text-sm sm:text-base font-bold py-2.5 px-4 sm:px-5 rounded-lg shadow-sm flex items-center gap-2.5 transition duration-200 border border-emerald-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <span>Log Aktivitas</span>
                </a>

                <a href="{{ route('arsip.history') }}" class="bg-emerald-700 hover:bg-emerald-800 text-emerald-50 hover:text-white text-sm sm:text-base font-semibold py-2.5 px-4 sm:px-5 rounded-lg shadow-sm flex items-center gap-2.5 transition duration-200 border border-emerald-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    <span>Riwayat Hapus</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-10" x-data="{ showImportModal: false, previewModal: false, previewUrl: '', previewTitle: '', isPdf: false }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <x-flash />

            <!-- Card Toolbar & Filter -->
            <div class="bg-white rounded-xl shadow-sm mb-6 border border-gray-200/80 divide-y divide-gray-100">
                
                <!-- Baris 1: Aksi Utama & Ekspor Data -->
                <div class="p-4 sm:p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    
                    <!-- Sisi Kiri: Tambah Arsip & Import Excel -->
                    <div class="flex flex-wrap items-center gap-3 sm:gap-4">
                        <!-- Tambah Arsip -->
                        <a href="{{ route('arsip.create') }}" class="inline-flex items-center gap-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-2.5 px-5 text-sm rounded-lg shadow-sm hover:shadow transition duration-200">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            <span>Tambah Arsip</span>
                        </a>
                        
                        <!-- Import Excel -->
                        <button type="button" @click="showImportModal = true" class="inline-flex items-center gap-2.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border border-emerald-300 font-semibold py-2.5 px-5 text-sm rounded-lg shadow-2xs hover:shadow-xs transition duration-200">
                            <svg class="w-4 h-4 text-emerald-700 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                            <span>Import Excel</span>
                        </button>
                    </div>

                    <!-- Sisi Kanan: Cetak & Ekspor (Padding px-5 dan jarak antar tombol lebih renggang & lega) -->
                    <div class="flex flex-wrap items-center gap-3 sm:gap-4">
                        <!-- Cetak Terpilih -->
                        <button type="button" id="btnCetakSelected" onclick="cetakArsipTerpilih()" class="inline-flex items-center gap-2.5 bg-white hover:bg-emerald-50 text-gray-700 hover:text-emerald-700 border border-gray-300 hover:border-emerald-300 font-semibold py-2.5 px-5 text-sm rounded-lg shadow-2xs hover:shadow-xs transition duration-200" title="Cetak arsip yang diceklis">
                            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                            <span>Cetak Terpilih</span>
                            <span id="selectedPrintBadge" class="hidden ml-1.5 px-2 py-0.5 bg-emerald-600 text-white text-xs font-bold rounded-full">0</span>
                        </button>

                        <!-- Cetak Semua -->
                        <a href="{{ route('arsip.pdf') }}" target="_blank"
                           x-data="{ loading: false }"
                           @click="loading = true; setTimeout(() => loading = false, 3000)"
                           :class="loading ? 'opacity-60 pointer-events-none' : ''"
                           class="inline-flex items-center gap-2.5 bg-white hover:bg-gray-50 text-gray-700 hover:text-gray-900 border border-gray-300 font-semibold py-2.5 px-5 text-sm rounded-lg shadow-2xs hover:shadow-xs transition duration-200" title="Cetak seluruh daftar arsip ke PDF">
                            <svg x-show="!loading" class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                            <svg x-show="loading" x-cloak class="w-4 h-4 text-emerald-600 animate-spin shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                            <span x-text="loading ? 'Menyiapkan...' : 'Cetak Semua'"></span>
                        </a>

                        <!-- Export Excel -->
                        <a href="{{ route('arsip.excel', request()->query()) }}" class="inline-flex items-center gap-2.5 bg-white hover:bg-gray-50 text-gray-700 hover:text-gray-900 border border-gray-300 font-semibold py-2.5 px-5 text-sm rounded-lg shadow-2xs hover:shadow-xs transition duration-200" title="Export data arsip ke spreadsheet Excel sesuai filter">
                            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            <span>Export Excel</span>
                        </a>

                        <!-- Hapus Terpilih (Bulk Delete) -->
                        <button type="button" id="btnBulkDelete" onclick="submitBulkDelete()" class="hidden inline-flex items-center gap-2.5 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-300 font-semibold py-2.5 px-5 text-sm rounded-lg shadow-2xs transition duration-200">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            <span>Hapus (<span id="selectedCount">0</span>)</span>
                        </button>
                    </div>
                </div>

                <!-- Baris 2: Filter Ruangan, Status, dan Pencarian -->
                <div class="p-3.5 sm:p-4 bg-slate-50/70 rounded-b-xl">
                    <form action="{{ route('arsip.index') }}" method="GET" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 sm:gap-4">
                        
                        <!-- Filter Dropdowns -->
                        <div class="flex items-center gap-3">
                            <select name="ruangan" class="border-gray-300 text-gray-700 text-sm rounded-lg shadow-2xs py-2.5 pl-3.5 pr-8 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white font-medium cursor-pointer">
                                <option value="">Semua Ruangan</option>
                                @foreach ($ruangans as $ruang)
                                    <option value="{{ $ruang }}" {{ request('ruangan') == $ruang ? 'selected' : '' }}>{{ $ruang }}</option>
                                @endforeach
                            </select>

                            <select name="status" class="border-gray-300 text-gray-700 text-sm rounded-lg shadow-2xs py-2.5 pl-3.5 pr-8 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white font-medium cursor-pointer">
                                <option value="">Semua Status</option>
                                <option value="Tersedia" {{ request('status') == 'Tersedia' ? 'selected' : '' }}>Tersedia</option>
                                <option value="Dipinjam" {{ request('status') == 'Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                            </select>

                            <select name="digital" class="border-gray-300 text-gray-700 text-sm rounded-lg shadow-2xs py-2.5 pl-3.5 pr-8 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white font-medium cursor-pointer">
                                <option value="">Semua Dokumen</option>
                                <option value="sudah" {{ request('digital') == 'sudah' ? 'selected' : '' }}>Sudah Scan PDF</option>
                                <option value="belum" {{ request('digital') == 'belum' ? 'selected' : '' }}>Hanya Fisik</option>
                            </select>

                            <select name="retensi" class="border-gray-300 text-gray-700 text-sm rounded-lg shadow-2xs py-2.5 pl-3.5 pr-8 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white font-medium cursor-pointer">
                                <option value="">Semua Retensi JRA</option>
                                <option value="aktif" {{ request('retensi') == 'aktif' ? 'selected' : '' }}>Arsip Aktif (&le; 2 Th)</option>
                                <option value="inaktif" {{ request('retensi') == 'inaktif' ? 'selected' : '' }}>Arsip Inaktif (2-5 Th)</option>
                                <option value="jatuh_tempo" {{ request('retensi') == 'jatuh_tempo' ? 'selected' : '' }}>Jatuh Tempo (&gt; 5 Th)</option>
                            </select>
                        </div>

                        <!-- Kotak Input Pencarian -->
                        <div class="relative flex-1 min-w-[200px]">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                            <input type="text" name="search" placeholder="Cari kode berkas, nama berkas, nomor box..." value="{{ request('search') }}" class="pl-10 pr-4 py-2.5 border-gray-300 text-sm text-gray-700 rounded-lg shadow-2xs focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 w-full bg-white">
                        </div>
                        
                        <!-- Tombol Cari & Reset -->
                        <div class="flex items-center gap-2.5 shrink-0">
                            <button type="submit" class="inline-flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-2.5 px-5 text-sm rounded-lg shadow-2xs transition duration-200">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                <span>Cari</span>
                            </button>
                            
                            @if(request()->anyFilled(['search', 'ruangan', 'rak', 'status', 'digital', 'retensi']))
                                <a href="{{ route('arsip.index') }}" class="inline-flex items-center justify-center bg-white hover:bg-gray-100 text-gray-600 font-semibold py-2.5 px-4 text-sm rounded-lg border border-gray-300 shadow-2xs transition duration-200" title="Reset filter">
                                    Reset
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <form id="bulkDeleteForm" action="{{ route('arsip.bulkDelete') }}" method="POST" class="hidden">
                @csrf
                <div id="hiddenInputsContainer"></div>
            </form>

            <form id="form-cetak-arsip" action="{{ route('arsip.pdf.selected') }}" method="POST" class="hidden">
                @csrf
                <div id="wadah-input-cetak"></div>
            </form>

            <div class="bg-white overflow-hidden shadow-md sm:rounded-xl border border-gray-200">
                <div class="p-0 overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-gray-200">
                                <th class="py-4 px-4 text-center w-10">
                                    <input type="checkbox" id="selectAll" class="rounded border-gray-300 text-emerald-600 shadow-sm focus:ring-emerald-500 cursor-pointer">
                                </th>
                                <th class="py-4 px-4 font-bold text-sm text-slate-700 whitespace-nowrap">Kode Berkas</th>
                                <th class="py-4 px-4 font-bold text-sm text-slate-700 w-1/3">Nama Berkas</th>
                                <th class="py-4 px-4 font-bold text-sm text-slate-700 text-left">Lokasi Fisik</th>
                                <th class="py-4 px-4 font-bold text-sm text-slate-700 text-center whitespace-nowrap">Status</th>
                                <th class="py-4 px-4 font-bold text-sm text-slate-700 text-center whitespace-nowrap">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($arsips as $arsip)
                            <tr class="hover:bg-emerald-50/30 transition duration-150">
                                <td class="py-3 px-4 text-center">
                                    <input type="checkbox" value="{{ $arsip->id }}" class="arsip-checkbox rounded border-gray-300 text-emerald-600 shadow-sm focus:ring-emerald-500 cursor-pointer">
                                </td>
                                <td class="py-3 px-4 font-bold text-sm text-emerald-700 whitespace-nowrap">{{ $arsip->kode_berkas }}</td>
                                <td class="py-3 px-4 text-sm text-gray-800 leading-snug">
                                    <div class="font-medium text-gray-900">{{ $arsip->nama_berkas }}</div>
                                    <div class="mt-1.5 flex flex-wrap items-center gap-1.5">
                                        @if($arsip->file_dokumen)
                                            <a href="{{ route('arsip.file', $arsip) }}" target="_blank" class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100 transition shadow-2xs" title="Lihat dokumen digital">
                                                <svg class="w-3.5 h-3.5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                                <span>PDF Scan</span>
                                            </a>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[11px] font-medium bg-gray-50 text-gray-400 border border-gray-200" title="Belum memiliki lampiran file digital">
                                                <svg class="w-3 h-3 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                <span>Fisik Saja</span>
                                            </span>
                                        @endif

                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[11px] font-semibold border {{ $arsip->status_retensi_badge }}" title="Siklus Jadwal Retensi Arsip (JRA): Umur {{ $arsip->umur_tahun }} Tahun">
                                            <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            <span>{{ $arsip->status_retensi }} ({{ $arsip->umur_tahun }} th)</span>
                                        </span>
                                    </div>
                                </td>
                                
                                <td class="py-3 px-4">
                                    <div class="inline-flex flex-col gap-1.5 w-max">
                                        <!-- Baris Atas: Ruangan (Aksen Ungu / Purple) -->
                                        <span class="w-full justify-center inline-flex items-center gap-1.5 bg-purple-100 text-purple-900 px-3 py-1 rounded border border-purple-300 text-[11px] font-bold uppercase tracking-wider shadow-2xs whitespace-nowrap">
                                            <svg class="w-3.5 h-3.5 text-purple-700 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                            {{ $arsip->ruangan }}
                                        </span>
                                        
                                        <!-- Baris Bawah: Rak (Biru), Baris (Hijau), Box (Oranye) -->
                                        <div class="flex items-center justify-between gap-1.5 w-full text-xs">
                                            <span class="flex-1 justify-center whitespace-nowrap inline-flex items-center px-2.5 py-1 rounded border border-blue-400 bg-blue-100 text-blue-900 font-bold shadow-2xs" title="Rak Arsip">
                                                {{ preg_match('/^rak\b/i', trim($arsip->rak)) ? trim($arsip->rak) : 'Rak ' . trim($arsip->rak) }}
                                            </span>
                                            <span class="text-slate-300 font-light">/</span>
                                            <span class="flex-1 justify-center whitespace-nowrap inline-flex items-center px-2.5 py-1 rounded border border-emerald-400 bg-emerald-100 text-emerald-900 font-bold shadow-2xs" title="Baris Rak">
                                                {{ preg_match('/^baris\b/i', trim($arsip->baris)) ? trim($arsip->baris) : 'Baris ' . trim($arsip->baris) }}
                                            </span>
                                            <span class="text-slate-300 font-light">/</span>
                                            <span class="flex-1 justify-center whitespace-nowrap inline-flex items-center px-2.5 py-1 rounded border border-amber-400 bg-amber-100 text-amber-900 font-bold shadow-2xs" title="Nomor Box">
                                                {{ preg_match('/^box\b/i', trim($arsip->box)) ? trim($arsip->box) : 'Box ' . trim($arsip->box) }}
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                
                                <td class="py-3 px-4 text-center whitespace-nowrap">
                                    @if ($arsip->status === 'Tersedia')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 shadow-2xs">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Tersedia
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-full bg-rose-50 text-rose-700 border border-rose-200 shadow-2xs">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span>
                                            Dipinjam
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-center whitespace-nowrap">
                                    <div class="flex items-center justify-center gap-2">
                                        
                                        @if($arsip->file_dokumen)
                                            <button type="button" 
                                                    @click="previewUrl = {{ \Illuminate\Support\Js::from(route('arsip.file', $arsip->id)) }}; previewTitle = {{ \Illuminate\Support\Js::from($arsip->kode_berkas . ' - ' . $arsip->nama_berkas) }}; isPdf = {{ \Illuminate\Support\Js::from(\Illuminate\Support\Str::endsWith(strtolower($arsip->file_dokumen), '.pdf')) }}; previewModal = true;"
                                                    class="p-1.5 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-lg shadow-sm transition border border-blue-200 cursor-pointer" 
                                                    title="Pratinjau Dokumen Digital">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            </button>
                                        @endif

                                        <a href="{{ route('arsip.qrcode', $arsip->id) }}" target="_blank" class="p-1.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-lg shadow-sm transition border border-slate-200" title="Cetak Label QR">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                                        </a>

                                        <a href="{{ route('arsip.edit', $arsip->id) }}" class="p-1.5 bg-amber-50 hover:bg-amber-100 text-amber-600 rounded-lg shadow-sm transition border border-amber-200" title="Edit Arsip">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </a>

                                        <form action="{{ route('arsip.destroy', $arsip->id) }}" method="POST" class="inline-block m-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="p-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-lg shadow-sm transition border border-rose-200" title="Hapus Arsip" onclick="konfirmasiHapus(event, 'Arsip {{ $arsip->kode_berkas }} akan dipindahkan ke Riwayat Terhapus.', this.closest('form'))">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="py-16 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-400">
                                        <svg class="w-12 h-12 mb-3 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path></svg>
                                        <p class="text-sm font-medium text-gray-500">Belum ada data arsip fisik yang tercatat.</p>
                                    </div>
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
            
        </div>

        <div x-show="showImportModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50 backdrop-blur-sm transition-opacity">
            <div class="bg-white rounded-xl shadow-xl max-w-lg w-full p-6 transform transition-all">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                        Import Data Arsip Masal
                    </h3>
                    <button @click="showImportModal = false" class="text-gray-400 hover:text-red-500 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                
                <div class="mb-6 bg-emerald-50 border border-emerald-200 p-4 rounded-lg">
                    <p class="text-sm text-emerald-800 font-medium mb-3">Gunakan template baku sistem agar data masuk dengan sempurna (kolom status otomatis "Tersedia").</p>
                    <a href="{{ route('arsip.template') }}" class="text-sm bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded shadow-sm inline-flex items-center gap-2 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Download Template Excel
                    </a>
                </div>

                <form action="{{ route('arsip.import') }}" method="POST" enctype="multipart/form-data" x-data="{ loading: false }" @submit="loading = true; AppDialog.loading('Mengimpor Data Excel...', 'Sedang membaca dan memvalidasi file Excel...')">
                    @csrf
                    <div class="mb-2">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Pilih File untuk Diunggah</label>
                        <input type="file" name="file" accept=".csv, .xlsx, .xls" required class="block w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 border border-gray-300 rounded-lg p-1.5 focus:ring-emerald-500 focus:border-emerald-500 transition">
                    </div>
                    
                    <div class="flex justify-end gap-3 mt-8 pt-4 border-t border-gray-100">
                        <button type="button" @click="showImportModal = false" :disabled="loading" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-2 px-4 rounded-lg transition disabled:opacity-50">Batal</button>
                        <button type="submit" :disabled="loading" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-6 rounded-lg shadow-sm transition flex items-center gap-2 disabled:opacity-70">
                            <svg x-show="!loading" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                            <svg x-show="loading" x-cloak class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                            <span x-text="loading ? 'Memproses...' : 'Mulai Import'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Preview Dokumen Digital -->
        <div x-show="previewModal" style="display: none;" 
             class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 backdrop-blur-sm p-4 sm:p-6 transition-opacity"
             @keydown.escape.window="previewModal = false">
            <div class="bg-white rounded-2xl shadow-2xl max-w-5xl w-full h-[85vh] flex flex-col overflow-hidden border border-gray-200 animate-in fade-in zoom-in-95 duration-150"
                 @click.away="previewModal = false">
                <!-- Header Modal -->
                <div class="px-6 py-4 bg-slate-50 border-b border-gray-200 flex items-center justify-between shrink-0">
                    <div class="flex items-center gap-3 overflow-hidden">
                        <div class="w-9 h-9 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 border border-blue-100">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <div class="truncate">
                            <h3 class="font-bold text-sm sm:text-base text-gray-900 truncate" x-text="previewTitle"></h3>
                            <p class="text-xs text-gray-500">Pratinjau Dokumen Digital Arsip</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        <a :href="previewUrl" target="_blank" class="px-3 py-1.5 text-xs font-semibold text-gray-700 bg-white hover:bg-gray-100 border border-gray-300 rounded-lg shadow-xs inline-flex items-center gap-1.5 transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                            Buka di Tab Baru
                        </a>
                        <button type="button" @click="previewModal = false; previewUrl = ''" class="p-1.5 text-gray-400 hover:text-gray-700 rounded-lg hover:bg-gray-100 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                </div>

                <!-- Konten Modal: PDF iframe atau Gambar -->
                <div class="flex-1 bg-slate-100 p-2 sm:p-4 flex items-center justify-center overflow-auto relative">
                    <template x-if="isPdf && previewUrl">
                        <iframe :src="previewUrl" class="w-full h-full rounded-xl border border-gray-200 bg-white shadow-inner"></iframe>
                    </template>
                    <template x-if="!isPdf && previewUrl">
                        <img :src="previewUrl" class="max-w-full max-h-full object-contain rounded-xl shadow-md border border-gray-200 bg-white" alt="Preview Dokumen">
                    </template>

                    <!-- Security Watermark Badge -->
                    <div class="absolute bottom-5 left-1/2 -translate-x-1/2 pointer-events-none px-4 py-1.5 rounded-full bg-slate-900/85 text-white/95 text-[11px] font-medium backdrop-blur-md shadow-xl flex items-center gap-2 border border-white/20 select-none z-10">
                        <svg class="w-3.5 h-3.5 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        <span>DOKUMEN RAHASIA BPJS KETENAGAKERJAAN &bull; DIAKSES OLEH {{ Auth::user()->name }} &bull; {{ now()->translatedFormat('d M Y H:i') }} WIB</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const selectAll = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.arsip-checkbox');
            const btnBulkDelete = document.getElementById('btnBulkDelete');
            const selectedCount = document.getElementById('selectedCount');
            const selectedPrintBadge = document.getElementById('selectedPrintBadge');
            
            function updateBulkDeleteButton() {
                const checkedBoxes = document.querySelectorAll('.arsip-checkbox:checked');
                if (checkedBoxes.length > 0) {
                    btnBulkDelete.classList.remove('hidden');
                    selectedCount.innerText = checkedBoxes.length;
                    if (selectedPrintBadge) {
                        selectedPrintBadge.innerText = checkedBoxes.length;
                        selectedPrintBadge.classList.remove('hidden');
                    }
                } else {
                    btnBulkDelete.classList.add('hidden');
                    if (selectedPrintBadge) {
                        selectedPrintBadge.classList.add('hidden');
                    }
                }
            }

            if(selectAll) {
                selectAll.addEventListener('change', function() {
                    checkboxes.forEach(cb => {
                        cb.checked = this.checked;
                    });
                    updateBulkDeleteButton();
                });
            }

            checkboxes.forEach(cb => {
                cb.addEventListener('change', function() {
                    if(!this.checked) selectAll.checked = false;
                    if(document.querySelectorAll('.arsip-checkbox:checked').length === checkboxes.length) selectAll.checked = true;
                    updateBulkDeleteButton();
                });
            });
        });

        function submitBulkDelete() {
            const checkedBoxes = document.querySelectorAll('.arsip-checkbox:checked');
            if(checkedBoxes.length === 0) {
                tampilkanAlert('Pilih Data Arsip', 'Silakan pilih minimal satu arsip terlebih dahulu untuk dihapus.', 'warning');
                return;
            }

            AppDialog.confirmDelete({
                title: 'Hapus Massal Arsip?',
                text: `Apakah Anda yakin ingin memindahkan ${checkedBoxes.length} arsip terpilih ke Riwayat Terhapus?`,
                confirmText: `Ya, Hapus ${checkedBoxes.length} Arsip`,
            }).then((result) => {
                if (result.isConfirmed) {
                    AppDialog.loading('Menghapus Data Massal...', 'Memindahkan arsip terpilih ke riwayat...');
                    const bulkDeleteForm = document.getElementById('bulkDeleteForm');
                    const hiddenInputsContainer = document.getElementById('hiddenInputsContainer');
                    hiddenInputsContainer.innerHTML = ''; 
                    
                    checkedBoxes.forEach(cb => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'ids[]';
                        input.value = cb.value;
                        hiddenInputsContainer.appendChild(input);
                    });
                    
                    bulkDeleteForm.submit();
                }
            });
        }

        function cetakArsipTerpilih() {
            const checkedBoxes = document.querySelectorAll('.arsip-checkbox:checked');
            
            if(checkedBoxes.length === 0) {
                tampilkanAlert('Pilih Data Arsip', 'Tolong ceklis minimal satu arsip terlebih dahulu!', 'warning');
                return;
            }
            
            const formCetak = document.getElementById('form-cetak-arsip');
            const wadahInputCetak = document.getElementById('wadah-input-cetak');
            wadahInputCetak.innerHTML = ''; 
            
            checkedBoxes.forEach(cb => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'ids[]';
                input.value = cb.value;
                wadahInputCetak.appendChild(input);
            });
            
            formCetak.submit();
        }
    </script>
</x-app-layout>