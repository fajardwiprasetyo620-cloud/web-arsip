<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight flex items-center gap-2">
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
                <div class="bg-slate-50 px-6 py-4 border-b border-gray-200 flex flex-col sm:flex-row justify-between items-center gap-4">
                    <a href="{{ route('peminjaman.create') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 px-5 text-sm rounded-lg shadow-sm flex items-center gap-2 transition duration-200 w-full sm:w-auto justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Catat Peminjaman Baru
                    </a>
                    
                    <div class="flex gap-2 w-full sm:w-auto">
                        <button type="button" onclick="cetakTerpilih()" class="bg-white hover:bg-gray-100 text-gray-700 border border-gray-300 font-semibold py-2.5 px-4 text-sm rounded-lg shadow-sm flex items-center gap-2 transition duration-200 w-full sm:w-auto justify-center">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                            Cetak Terpilih
                        </button>
                        
                        <a href="{{ route('peminjaman.pdf') }}" target="_blank"
                           x-data="{ loading: false }"
                           @click="loading = true; setTimeout(() => loading = false, 3000)"
                           :class="loading ? 'opacity-60 pointer-events-none' : ''"
                           class="bg-white hover:bg-gray-100 text-gray-700 border border-gray-300 font-semibold py-2.5 px-4 text-sm rounded-lg shadow-sm flex items-center gap-2 transition duration-200 w-full sm:w-auto justify-center">
                            <svg x-show="!loading" class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            <svg x-show="loading" x-cloak class="w-4 h-4 text-emerald-600 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
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

            <!-- Tabel Data -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200">
                <div class="p-0 overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-max">
                        <thead>
                            <tr class="bg-emerald-50/50 border-b border-gray-200">
                                <th class="py-4 px-6 font-bold text-sm text-emerald-800 w-1/3 uppercase tracking-wider">Informasi Transaksi & Peminjam</th>
                                <th class="py-4 px-6 font-bold text-sm text-emerald-800 uppercase tracking-wider">Daftar Arsip & Status Pengembalian</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y-2 divide-gray-300">
                            @forelse ($groupedPeminjamans as $key => $group)
                                @php
                                    list($nama, $tanggal) = explode('#', $key);
                                    $totalArsip = $group->count();
                                    $sudahKembali = $group->where('status', 'Dikembalikan')->count();
                                    $statusSelesai = $totalArsip === $sudahKembali;
                                @endphp
                                <tr class="hover:bg-gray-50/50 transition duration-150" style="border-bottom: 2px solid #cbd5e1;">
                                    
                                    <td class="py-5 px-6 align-top bg-white border-r border-gray-100">
                                        <div class="font-extrabold text-lg text-gray-800 mb-1">{{ $nama }}</div>
                                        
                                        <!-- Divisi -->
                                        <div class="text-[11px] text-blue-600 font-bold mb-3 uppercase tracking-wider bg-blue-50 inline-block px-2 py-0.5 rounded border border-blue-100">
                                            {{ $group->first()->divisi ?? '-' }}
                                        </div>

                                        <div class="text-xs text-gray-500 mb-3 flex items-center gap-1.5 font-medium">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            Pinjam: <span class="text-gray-700 font-semibold">{{ \Carbon\Carbon::parse($tanggal)->format('d M Y') }}</span>
                                        </div>
                                        <div class="inline-block px-3 py-1.5 text-xs font-bold rounded-md shadow-sm {{ $statusSelesai ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : 'bg-amber-100 text-amber-800 border border-amber-200' }}">
                                            {{ $statusSelesai ? 'Semua Dikembalikan ✓' : 'Masih Dipinjam ('.($totalArsip - $sudahKembali).' Berkas)' }}
                                        </div>
                                    </td>
                                    
                                    <td class="py-5 px-6 align-top bg-slate-50/20">
                                        <div class="flex flex-col gap-3">
                                            @foreach ($group as $pinjam)
                                                @php
                                                    $isOverdue = false;
                                                    $hariTerlambat = 0;
                                                    if ($pinjam->status == 'Dipinjam' && !empty($pinjam->tenggat_waktu)) {
                                                        $tenggat = \Carbon\Carbon::parse($pinjam->tenggat_waktu)->startOfDay();
                                                        $hariIni = \Carbon\Carbon::now()->startOfDay();
                                                        
                                                        if ($hariIni->gt($tenggat)) {
                                                            $isOverdue = true;
                                                            $hariTerlambat = $hariIni->diffInDays($tenggat);
                                                        }
                                                    }
                                                @endphp
                                                <div class="flex items-center justify-between bg-white p-3.5 border {{ $pinjam->status == 'Dikembalikan' ? 'border-gray-200 opacity-60 bg-gray-50' : ($isOverdue ? 'border-red-300 bg-red-50/50 shadow-sm' : 'border-emerald-200 shadow-sm') }} rounded-lg transition hover:shadow-md">
                                                    
                                                    <div class="flex flex-col">
                                                        <div class="flex items-center">
                                                            <input type="checkbox" value="{{ $pinjam->id }}" style="margin-right: 12px;" class="cetak-checkbox w-4 h-4 text-emerald-600 bg-gray-100 border-gray-300 rounded focus:ring-emerald-500 cursor-pointer shadow-sm">
                                                            <span class="font-bold text-sm {{ $pinjam->status == 'Dikembalikan' ? 'text-gray-500' : ($isOverdue ? 'text-red-700' : 'text-gray-800') }}">{{ $pinjam->arsip->kode_berkas ?? 'Berkas Dihapus' }}</span>
                                                        </div>
                                                        <span class="text-xs ml-7 mt-0.5 {{ $pinjam->status == 'Dikembalikan' ? 'text-gray-400' : 'text-gray-600' }} font-medium">{{ $pinjam->arsip->nama_berkas ?? '-' }}</span>
                                                        
                                                        @if($pinjam->status == 'Dikembalikan')
                                                        <span class="text-[11px] text-emerald-600/80 mt-1.5 ml-7 font-semibold flex items-center gap-1">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                            Dikembalikan pd: {{ \Carbon\Carbon::parse($pinjam->tanggal_kembali)->format('d M Y') }}
                                                            @if($pinjam->disetujui_oleh)
                                                                &middot; oleh: {{ $pinjam->disetujui_oleh }}
                                                            @endif
                                                        </span>
                                                        @elseif(!empty($pinjam->tenggat_waktu))
                                                            <span class="text-[11px] mt-1.5 ml-7 font-semibold flex items-center gap-1 {{ $isOverdue ? 'text-red-600' : 'text-amber-600/90' }}">
                                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                                Jatuh Tempo: {{ \Carbon\Carbon::parse($pinjam->tenggat_waktu)->format('d M Y') }}
                                                                @if($isOverdue)
                                                                    <span class="text-red-600 font-bold ml-1">(Terlambat {{ $hariTerlambat }} hari)</span>
                                                                @endif
                                                            </span>
                                                        @endif
                                                    </div>
                                                    
                                                    <div class="flex items-center gap-3 ml-4">
                                                        <span class="px-3 py-1.5 text-[11px] font-bold rounded shadow-sm border {{ $pinjam->status == 'Dikembalikan' ? 'bg-gray-100 text-gray-600 border-gray-200' : ($isOverdue ? 'bg-red-100 text-red-700 border-red-200' : 'bg-amber-100 text-amber-700 border-amber-200') }}">
                                                            {{ $isOverdue ? 'Terlambat' : $pinjam->status }}
                                                        </span>

                                                        @if($pinjam->status == 'Dipinjam')
                                                        <form action="{{ route('peminjaman.update', $pinjam->id) }}" method="POST" class="m-0">
                                                            @csrf
                                                            @method('PUT')
                                                            <!-- Ubah type button dan panggil konfirmasiAksi -->
                                                            <button type="button" class="bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-1.5 px-3 rounded shadow-sm text-xs inline-flex items-center gap-1.5 transition duration-200" onclick="konfirmasiAksi(event, 'Tandai berkas ini sudah dikembalikan ke ruang arsip?', this.closest('form'))">
                                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                                Kembalikan
                                                            </button>
                                                        </form>
                                                        @else
                                                        <span class="text-gray-400 text-sm font-bold inline-flex items-center gap-1 px-2">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                            Selesai
                                                        </span>
                                                        @endif
                                                    </div>

                                                </div>
                                            @endforeach
                                        </div>
                                    </td>
                                </tr>
                                
                            @empty
                                <tr>
                                    <td colspan="2" class="py-16 text-center">
                                        <div class="flex flex-col items-center justify-center text-gray-400">
                                            <svg class="w-12 h-12 mb-3 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            <p class="text-sm font-medium text-gray-500">Data tidak ditemukan atau belum ada peminjaman.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                    <div class="px-6 py-4 border-t border-gray-100">
                        {{ $groupedPeminjamans->links() }}
                    </div>
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