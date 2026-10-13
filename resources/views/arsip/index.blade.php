<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <h2 class="font-semibold text-xl text-white leading-tight flex items-center gap-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
                {{ __('Daftar Arsip Fisik') }}
            </h2>
            
            <div class="flex gap-2">
                <a href="{{ route('arsip.logs') }}" class="bg-white hover:bg-emerald-50 text-emerald-700 text-sm font-bold py-2 px-4 rounded-lg shadow-sm flex items-center gap-2 transition duration-200 border border-emerald-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <span class="hidden sm:inline">Log Aktivitas</span>
                </a>

                <a href="{{ route('arsip.history') }}" class="bg-emerald-700 hover:bg-emerald-800 text-emerald-50 hover:text-white text-sm font-semibold py-2 px-4 rounded-lg shadow-sm flex items-center gap-2 transition duration-200 border border-emerald-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    <span class="hidden sm:inline">Riwayat Hapus</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-10" x-data="{ showImportModal: false }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <x-flash />

            <div class="bg-white p-4 rounded-xl shadow-sm mb-6 flex flex-col xl:flex-row justify-between items-center gap-4 border border-gray-100">
                
                <div class="flex flex-wrap items-center gap-2 w-full xl:w-auto">
                    <a href="{{ route('arsip.create') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-2 px-4 text-sm rounded-lg shadow-sm flex items-center gap-1.5 transition duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Tambah Arsip
                    </a>
                    
                    <button @click="showImportModal = true" class="bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 font-semibold py-2 px-4 text-sm rounded-lg shadow-sm flex items-center gap-1.5 transition duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                        Import Excel
                    </button>

                    <div class="h-6 w-px bg-gray-200 mx-1 hidden sm:block"></div>

                    <button type="button" onclick="cetakArsipTerpilih()" class="bg-white hover:bg-gray-50 text-gray-700 border border-gray-300 font-semibold py-2 px-4 text-sm rounded-lg shadow-sm flex items-center gap-1.5 transition duration-200">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                        Cetak Terpilih
                    </button>

                    <a href="{{ route('arsip.pdf') }}" target="_blank"
                       x-data="{ loading: false }"
                       @click="loading = true; setTimeout(() => loading = false, 3000)"
                       :class="loading ? 'opacity-60 pointer-events-none' : ''"
                       class="bg-white hover:bg-gray-50 text-gray-700 border border-gray-300 font-semibold py-2 px-4 text-sm rounded-lg shadow-sm flex items-center gap-1.5 transition duration-200">
                        <svg x-show="!loading" class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        <svg x-show="loading" x-cloak class="w-4 h-4 text-emerald-600 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                        <span x-text="loading ? 'Menyiapkan...' : 'Cetak Semua'"></span>
                    </a>

                    <button type="button" id="btnBulkDelete" onclick="submitBulkDelete()" class="hidden bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 font-semibold py-2 px-4 text-sm rounded-lg shadow-sm flex items-center gap-1.5 transition duration-200 ml-auto">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        Hapus (<span id="selectedCount">0</span>)
                    </button>
                </div>

                <form action="{{ route('arsip.index') }}" method="GET" class="flex flex-wrap lg:flex-nowrap gap-2 w-full xl:w-auto justify-end">
                    
                    <select name="ruangan" class="border-gray-300 text-gray-700 text-sm rounded-lg shadow-sm py-2 pl-3 pr-8 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:outline-none bg-gray-50 font-medium">
                        <option value="">🏢 Semua Ruangan</option>
                        @foreach ($ruangans as $ruang)
                            <option value="{{ $ruang }}" {{ request('ruangan') == $ruang ? 'selected' : '' }}>{{ $ruang }}</option>
                        @endforeach
                    </select>

                    <select name="status" class="border-gray-300 text-gray-700 text-sm rounded-lg shadow-sm py-2 pl-3 pr-8 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:outline-none bg-gray-50 font-medium">
                        <option value="">📌 Semua Status</option>
                        <option value="Tersedia" {{ request('status') == 'Tersedia' ? 'selected' : '' }}>Tersedia</option>
                        <option value="Dipinjam" {{ request('status') == 'Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                    </select>

                    <div class="relative w-full lg:w-auto min-w-[180px]">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input type="text" name="search" placeholder="Cari kode/nama..." value="{{ request('search') }}" class="pl-9 border-gray-300 text-sm text-gray-700 rounded-lg shadow-sm py-2 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:outline-none w-full">
                    </div>
                    
                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 text-sm rounded-lg shadow-sm transition duration-200">
                        Cari
                    </button>
                    
                    @if(request()->anyFilled(['search', 'ruangan', 'status']))
                        <a href="{{ route('arsip.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-2 px-3 text-sm rounded-lg shadow-sm transition duration-200 flex items-center justify-center">
                            Reset
                        </a>
                    @endif
                </form>
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
                                <td class="py-3 px-4 text-sm text-gray-800 leading-snug">{{ $arsip->nama_berkas }}</td>
                                
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
                                
                                <td class="py-3 px-4 text-center whitespace-nowrap">
                                    <span class="px-3 py-1 text-xs font-bold rounded-full shadow-sm {{ $arsip->status == 'Tersedia' ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : 'bg-red-100 text-red-800 border border-red-200' }}">
                                        {{ $arsip->status }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-center whitespace-nowrap">
                                    <div class="flex items-center justify-center gap-2">
                                        
                                        @if($arsip->file_dokumen)
                                            <a href="{{ route('arsip.file', $arsip->id) }}" target="_blank" class="p-1.5 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-lg shadow-sm transition border border-blue-200" title="Lihat Softcopy Digital">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            </a>
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
                                            <button type="submit" class="p-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-lg shadow-sm transition border border-rose-200" title="Hapus Arsip" onclick="return confirm('Yakin ingin menghapus arsip ini?')">
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

                <form action="{{ route('arsip.import') }}" method="POST" enctype="multipart/form-data" x-data="{ loading: false }" @submit="loading = true">
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

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const selectAll = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.arsip-checkbox');
            const btnBulkDelete = document.getElementById('btnBulkDelete');
            const selectedCount = document.getElementById('selectedCount');
            
            function updateBulkDeleteButton() {
                const checkedBoxes = document.querySelectorAll('.arsip-checkbox:checked');
                if (checkedBoxes.length > 0) {
                    btnBulkDelete.classList.remove('hidden');
                    selectedCount.innerText = checkedBoxes.length;
                } else {
                    btnBulkDelete.classList.add('hidden');
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
            if(checkedBoxes.length === 0) return;

            if(!confirm(`Yakin ingin memindahkan ${checkedBoxes.length} arsip ke riwayat hapus?`)) return;
            
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

        function cetakArsipTerpilih() {
            const checkedBoxes = document.querySelectorAll('.arsip-checkbox:checked');
            
            if(checkedBoxes.length === 0) {
                alert('Tolong ceklis minimal satu arsip terlebih dahulu!');
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