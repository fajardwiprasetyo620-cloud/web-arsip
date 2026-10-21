<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl sm:text-2xl text-white leading-tight flex items-center gap-2.5">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
            {{ __('Daftar Peminjaman Arsip') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg shadow-sm relative font-medium text-sm flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ session('success') }}
                </div>
            @endif
            
            @if(session('error'))
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg shadow-sm relative font-medium text-sm flex items-center gap-2">
                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ session('error') }}
                </div>
            @endif

            <!-- Panel Kontrol Utama: Gabungan Aksi & Pencarian -->
            <div class="bg-white rounded-xl shadow-sm mb-6 border border-gray-200 overflow-hidden">
                <!-- Bagian Atas: Tombol Aksi -->
                <div class="bg-slate-50 px-4 sm:px-6 py-4 border-b border-gray-200 flex flex-col sm:flex-row justify-between items-stretch sm:items-center gap-3 sm:gap-4">
                    <a href="{{ route('peminjaman.create') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 px-5 text-sm rounded-lg shadow-sm flex items-center gap-2 transition duration-200 w-full sm:w-auto justify-center shrink-0">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        <span>Catat Peminjaman Baru</span>
                    </a>
                    
                    <div class="flex flex-col sm:flex-row gap-2.5 w-full sm:w-auto">
                        <button type="button" onclick="cetakTerpilih()" class="bg-white hover:bg-gray-100 text-gray-700 border border-gray-300 font-semibold py-2.5 px-4 text-sm rounded-lg shadow-2xs flex items-center gap-2 transition duration-200 justify-center">
                            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                            <span>Cetak Terpilih</span>
                        </button>
                        
                        <a href="{{ route('peminjaman.pdf') }}" target="_blank"
                           x-data="{ loading: false }"
                           @click="loading = true; setTimeout(() => loading = false, 3000)"
                           :class="loading ? 'opacity-60 pointer-events-none' : ''"
                           class="bg-white hover:bg-gray-100 text-gray-700 border border-gray-300 font-semibold py-2.5 px-4 text-sm rounded-lg shadow-2xs flex items-center gap-2 transition duration-200 justify-center">
                            <svg x-show="!loading" class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            <svg x-show="loading" x-cloak class="w-4 h-4 text-emerald-600 animate-spin shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                            <span x-text="loading ? 'Menyiapkan...' : 'Cetak Semua Laporan'"></span>
                        </a>
                    </div>
                </div>

                <!-- Bagian Bawah: Form Pencarian Grid -->
                <div class="px-6 py-5">
                    <form action="{{ route('peminjaman.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                        
                        <!-- Input Pencarian Teks -->
                        <div class="md:col-span-5">
                            <label class="block text-gray-700 text-xs font-bold mb-1.5 uppercase tracking-wide">Cari Berdasarkan Kata Kunci</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </div>
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama peminjam, berkas, atau divisi..." style="padding-left: 2.75rem;" class="w-full border-gray-300 rounded-lg shadow-sm py-2.5 pr-3 focus:ring-emerald-500 focus:border-emerald-500 text-sm transition">
                            </div>
                        </div>

                        <!-- Input Tanggal -->
                        <div class="md:col-span-4">
                            <label class="block text-gray-700 text-xs font-bold mb-1.5 uppercase tracking-wide">Pilih Tanggal Pinjam</label>
                            <input type="date" name="tanggal" value="{{ request('tanggal') }}" class="w-full border-gray-300 rounded-lg shadow-sm py-2.5 focus:ring-emerald-500 focus:border-emerald-500 text-sm transition">
                        </div>

                        <!-- Tombol Aksi Filter -->
                        <div class="md:col-span-3 flex gap-2">
                            <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 px-4 rounded-lg text-sm shadow-sm transition w-full">
                                Cari Data
                            </button>
                            <a href="{{ route('peminjaman.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-2.5 px-4 rounded-lg text-sm shadow-sm transition text-center w-full">
                                Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="space-y-6">
                @include('peminjaman.partials.tabel-status', [
                    'judul' => 'Terlambat',
                    'deskripsi' => 'Berkas yang belum dikembalikan dan sudah melewati tanggal jatuh tempo.',
                    'pesanKosong' => 'Tidak ada berkas terlambat.',
                    'status' => 'Terlambat',
                    'statusClass' => 'border-red-200 bg-red-100 text-red-700',
                    'headerClass' => 'bg-red-50/70',
                    'countClass' => 'border-red-200 bg-red-100 text-red-700',
                    'peminjamans' => $peminjamanTerlambat,
                ])

                @include('peminjaman.partials.tabel-status', [
                    'judul' => 'Dipinjam',
                    'deskripsi' => 'Berkas yang masih dipinjam dan belum melewati tanggal jatuh tempo.',
                    'pesanKosong' => 'Tidak ada berkas yang sedang dipinjam.',
                    'status' => 'Dipinjam',
                    'statusClass' => 'border-amber-200 bg-amber-100 text-amber-700',
                    'headerClass' => 'bg-amber-50/70',
                    'countClass' => 'border-amber-200 bg-amber-100 text-amber-700',
                    'peminjamans' => $peminjamanAktif,
                ])

                @include('peminjaman.partials.tabel-status', [
                    'judul' => 'Dikembalikan',
                    'deskripsi' => 'Riwayat berkas yang sudah dikembalikan ke ruang arsip.',
                    'pesanKosong' => 'Belum ada berkas yang dikembalikan.',
                    'status' => 'Dikembalikan',
                    'statusClass' => 'border-emerald-200 bg-emerald-100 text-emerald-700',
                    'headerClass' => 'bg-emerald-50/70',
                    'countClass' => 'border-emerald-200 bg-emerald-100 text-emerald-700',
                    'peminjamans' => $peminjamanDikembalikan,
                ])
            </div>

        </div>
    </div>

    <!-- Form Tersembunyi untuk mengirim ID yang diceklis -->
    <form id="form-cetak-terpilih" action="{{ route('peminjaman.pdf.selected') }}" method="POST" class="hidden">
        @csrf
        <div id="hidden-inputs"></div>
    </form>

    <!-- SweetAlert2 sudah dimuat lewat resources/js/app.js (npm), tidak lagi dari CDN -->

    <script>
        // Fungsi universal untuk menggantikan confirm() bawaan
        function konfirmasiAksi(event, pesan, formElement) {
            event.preventDefault(); 
            
            Swal.fire({
                title: 'Apakah Anda Yakin?',
                text: pesan,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#059669', // Emerald-600
                cancelButtonColor: '#94a3b8',  // Slate-400
                confirmButtonText: 'Ya, Lanjutkan!',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'rounded-2xl'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    formElement.submit(); 
                }
            });
        }

        function cetakTerpilih() {
            let selected = document.querySelectorAll('.cetak-checkbox:checked');
            
            if(selected.length === 0) {
                // Mengubah alert biasa menjadi SweetAlert
                Swal.fire({
                    title: 'Pilih Data!',
                    text: 'Tolong ceklis minimal satu arsip terlebih dahulu!',
                    icon: 'warning',
                    confirmButtonColor: '#059669',
                    customClass: {
                        popup: 'rounded-2xl'
                    }
                });
                return;
            }
            
            let container = document.getElementById('hidden-inputs');
            container.innerHTML = ''; 
            
            selected.forEach(function(checkbox) {
                let input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'peminjaman_ids[]';
                input.value = checkbox.value;
                container.appendChild(input);
            });
            
            document.getElementById('form-cetak-terpilih').submit();
            
            setTimeout(() => {
                selected.forEach(cb => cb.checked = false);
            }, 1000);
        }
    </script>
</x-app-layout>