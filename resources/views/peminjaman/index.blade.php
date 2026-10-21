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

            <!-- Daftar Transaksi Peminjaman: Responsive & Tanpa Scroll Horizontal -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <!-- Header Kolom Desktop (Hanya muncul di md ke atas) -->
                <div class="hidden md:flex bg-emerald-50/60 border-b border-gray-200 text-xs font-bold text-emerald-800 uppercase tracking-wider">
                    <div class="py-3.5 px-6 w-72 lg:w-80 shrink-0 border-r border-emerald-100/80">
                        Informasi Transaksi & Peminjam
                    </div>
                    <div class="py-3.5 px-6 flex-1">
                        Daftar Arsip & Status Pengembalian
                    </div>
                </div>

                <!-- Kontainer Daftar Transaksi -->
                <div class="divide-y-2 divide-gray-200">
                    @php $grupSebelumnya = null; @endphp
                    @forelse ($groupedPeminjamans as $key => $group)
                        @php
                            list($nama, $tanggal) = explode('#', $key);
                            $totalArsip = $group->count();
                            $sudahKembali = $group->where('status', 'Dikembalikan')->count();
                            $statusSelesai = $totalArsip === $sudahKembali;
                            $isGrupTerlambat = in_array($key, $kunciTerlambat, true);
                        @endphp

                        {{-- Label Pembatas: Muncul saat jenis grup berganti (terlambat -> lainnya) --}}
                        @if ($jumlahTransaksiTerlambat > 0 && $grupSebelumnya !== $isGrupTerlambat)
                            @if ($isGrupTerlambat)
                                <div class="px-4 sm:px-6 py-2.5 bg-red-50 border-y border-red-200 flex flex-wrap items-center gap-x-2 gap-y-1 text-xs font-bold uppercase tracking-wider text-red-700">
                                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                    <span>Perlu Perhatian: {{ $jumlahTransaksiTerlambat }} Transaksi Terlambat</span>
                                    <span class="font-medium normal-case tracking-normal text-red-600/80">&mdash; yang paling lama lewat tenggat ada di paling atas</span>
                                </div>
                            @else
                                <div class="px-4 sm:px-6 py-2.5 bg-gray-50 border-y border-gray-200 text-xs font-bold uppercase tracking-wider text-gray-500">
                                    Transaksi Lainnya
                                </div>
                            @endif
                        @endif

                        <!-- Baris Transaksi: Vertikal di HP, 2 Kolom di Desktop -->
                        <div class="flex flex-col md:flex-row hover:bg-gray-50/40 transition duration-150 {{ $isGrupTerlambat ? 'border-l-4 border-l-red-500' : '' }}">
                            
                            <!-- Kolom Kiri (Desktop) / Header Kartu (Mobile) -->
                            <div class="p-4 sm:p-5 md:py-5 md:px-6 md:w-72 lg:w-80 shrink-0 bg-white md:border-r border-b md:border-b-0 border-gray-100 flex flex-col justify-between">
                                <div>
                                    <div class="font-extrabold text-base sm:text-lg text-gray-800 mb-1 leading-snug">{{ $nama }}</div>
                                    
                                    <!-- Divisi -->
                                    <div class="text-[11px] text-blue-600 font-bold mb-2.5 uppercase tracking-wider bg-blue-50 inline-block px-2 py-0.5 rounded border border-blue-100">
                                        {{ $group->first()->divisi ?? '-' }}
                                    </div>

                                    <div class="text-xs text-gray-500 mb-3 flex items-center gap-1.5 font-medium">
                                        <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        <span>Pinjam: <strong class="text-gray-700 font-semibold">{{ \Carbon\Carbon::parse($tanggal)->format('d M Y') }}</strong></span>
                                    </div>
                                </div>

                                <div>
                                    <span class="inline-block px-3 py-1.5 text-xs font-bold rounded-md shadow-2xs {{ $statusSelesai ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : 'bg-amber-100 text-amber-800 border border-amber-200' }}">
                                        {{ $statusSelesai ? 'Semua Dikembalikan ✓' : 'Masih Dipinjam ('.($totalArsip - $sudahKembali).' Berkas)' }}
                                    </span>

                                    <!-- Tombol Cetak Berita Acara (BAP) -->
                                    <div class="mt-3 pt-3 border-t border-gray-100">
                                        <a href="{{ route('peminjaman.bap', ['nama' => $nama, 'tanggal' => $tanggal]) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-300 shadow-2xs transition w-full justify-center" title="Cetak Berita Acara Serah Terima (BAP) Resmi">
                                            <svg class="w-3.5 h-3.5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                            <span>Cetak BAP (PDF)</span>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Kolom Kanan: Daftar Berkas Peminjaman -->
                            <div class="p-3.5 sm:p-5 md:py-5 md:px-6 flex-1 min-w-0 bg-slate-50/20">
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
                                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white p-3.5 sm:p-4 border {{ $pinjam->status == 'Dikembalikan' ? 'border-gray-200 opacity-60 bg-gray-50' : ($isOverdue ? 'border-red-300 bg-red-50/50 shadow-xs' : 'border-emerald-200 shadow-xs') }} rounded-lg transition hover:shadow-sm">
                                            
                                            <div class="flex flex-col min-w-0 flex-1">
                                                <div class="flex items-center gap-2.5">
                                                    <input type="checkbox" value="{{ $pinjam->id }}" class="cetak-checkbox w-4 h-4 text-emerald-600 bg-gray-100 border-gray-300 rounded focus:ring-emerald-500 cursor-pointer shadow-2xs shrink-0">
                                                    <span class="font-bold text-sm truncate {{ $pinjam->status == 'Dikembalikan' ? 'text-gray-500' : ($isOverdue ? 'text-red-700' : 'text-gray-800') }}">{{ $pinjam->arsip->kode_berkas ?? 'Berkas Dihapus' }}</span>
                                                </div>
                                                <span class="text-xs ml-6.5 mt-1 {{ $pinjam->status == 'Dikembalikan' ? 'text-gray-400' : 'text-gray-600' }} font-medium break-words leading-relaxed">{{ $pinjam->arsip->nama_berkas ?? '-' }}</span>
                                                
                                                @if($pinjam->status == 'Dikembalikan')
                                                <span class="text-[11px] text-emerald-600/80 mt-1.5 ml-6.5 font-semibold flex items-center gap-1">
                                                    <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                    Dikembalikan pd: {{ \Carbon\Carbon::parse($pinjam->tanggal_kembali)->format('d M Y') }}
                                                    @if($pinjam->disetujui_oleh)
                                                        &middot; oleh: {{ $pinjam->disetujui_oleh }}
                                                    @endif
                                                </span>
                                                @elseif(!empty($pinjam->tenggat_waktu))
                                                    <span class="text-[11px] mt-1.5 ml-6.5 font-semibold flex items-center gap-1 {{ $isOverdue ? 'text-red-600' : 'text-amber-600/90' }}">
                                                        <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                        Jatuh Tempo: {{ \Carbon\Carbon::parse($pinjam->tenggat_waktu)->format('d M Y') }}
                                                        @if($isOverdue)
                                                            <span class="text-red-600 font-bold ml-1">(Terlambat {{ $hariTerlambat }} hari)</span>
                                                        @endif
                                                    </span>
                                                @endif
                                            </div>
                                            
                                            <div class="flex items-center gap-2 sm:gap-2.5 sm:ml-4 shrink-0 self-end sm:self-center">
                                                <span class="px-2.5 py-1 text-[11px] font-bold rounded shadow-2xs border {{ $pinjam->status == 'Dikembalikan' ? 'bg-gray-100 text-gray-600 border-gray-200' : ($isOverdue ? 'bg-red-100 text-red-700 border-red-200' : 'bg-amber-100 text-amber-700 border-amber-200') }}">
                                                    {{ $isOverdue ? 'Terlambat' : $pinjam->status }}
                                                </span>

                                                @if($pinjam->status == 'Dipinjam')
                                                    @if($isOverdue)
                                                        @php
                                                            $pesanWa = rawurlencode("Halo {$nama} ({$group->first()->divisi}), mengingatkan bahwa arsip fisik: "
                                                                . ($pinjam->arsip->kode_berkas ?? '') . " - " . ($pinjam->arsip->nama_berkas ?? '')
                                                                . " yang dipinjam pada " . \Carbon\Carbon::parse($pinjam->tanggal_pinjam)->format('d/m/Y')
                                                                . " telah melewati batas tenggat pengembalian (" . \Carbon\Carbon::parse($pinjam->tenggat_waktu)->format('d/m/Y') . ")."
                                                                . " Mohon untuk segera dikembalikan ke ruang arsip. Terima kasih.");
                                                        @endphp
                                                        <a href="https://api.whatsapp.com/send?text={{ $pesanWa }}" target="_blank" class="bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-300 font-bold py-1.5 px-3 rounded shadow-2xs text-xs inline-flex items-center gap-1.5 transition" title="Kirim Pengingat WhatsApp ke Peminjam">
                                                            <svg class="w-3.5 h-3.5 fill-current shrink-0" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.275.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.099.824zm-3.423-14.416c-6.627 0-12 5.373-12 12 0 2.158.57 4.184 1.564 5.941l-1.564 5.713 5.861-1.537c1.716.947 3.684 1.483 5.78 1.483 6.627 0 12-5.373 12-12s-5.373-12-12-12z"/></svg>
                                                            <span>WA</span>
                                                        </a>
                                                    @endif
                                                    <form action="{{ route('peminjaman.update', $pinjam->id) }}" method="POST" class="m-0">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="button" class="bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-1.5 px-3 rounded shadow-2xs text-xs inline-flex items-center gap-1.5 transition duration-200" onclick="konfirmasiAksi(event, 'Tandai berkas ini sudah dikembalikan ke ruang arsip?', this.closest('form'))">
                                                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                            <span>Kembalikan</span>
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="text-gray-400 text-xs sm:text-sm font-bold inline-flex items-center gap-1 px-1">
                                                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                        <span>Selesai</span>
                                                    </span>
                                                @endif
                                            </div>

                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        
                        @php $grupSebelumnya = $isGrupTerlambat; @endphp
                    @empty
                        <div class="py-16 text-center">
                            <div class="flex flex-col items-center justify-center text-gray-400">
                                <svg class="w-12 h-12 mb-3 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                <p class="text-sm font-medium text-gray-500">Data tidak ditemukan atau belum ada peminjaman.</p>
                            </div>
                        </div>
                    @endforelse
                </div>

                <div class="px-4 sm:px-6 py-4 border-t border-gray-100 bg-white">
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