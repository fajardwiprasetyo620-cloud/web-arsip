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
                        @if($peminjamanTerlambat->total() > 0)
                            @php
                                $rekapText = "📢 *PENGINGAT ARSIP TERLAMBAT - BPJS KETENAGAKERJAAN*\n";
                                $rekapText .= "Per: " . now()->translatedFormat('d F Y') . "\n\n";
                                $rekapText .= "Yth. Rekan-rekan peminjam, berikut berkas fisik yang telah melewati batas waktu pengembalian:\n\n";
                                
                                $no = 1;
                                foreach ($peminjamanTerlambat as $pinjam) {
                                    $hari = now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($pinjam->tenggat_waktu)->startOfDay());
                                    $rekapText .= "{$no}. *" . ($pinjam->arsip->kode_berkas ?? 'Berkas') . "* - " . ($pinjam->arsip->nama_berkas ?? '-') . "\n";
                                    $rekapText .= "   Peminjam: {$pinjam->nama_peminjam} ({$pinjam->divisi})\n";
                                    $rekapText .= "   Terlambat: {$hari} hari (Jatuh tempo: " . \Carbon\Carbon::parse($pinjam->tenggat_waktu)->format('d/m/Y') . ")\n\n";
                                    $no++;
                                }
                                $rekapText .= "Mohon kesediaannya untuk segera mengembalikan berkas ke ruang arsip. Terima kasih. 🙏";
                            @endphp
                            <button type="button" onclick="bukaModalBroadcastWa()" class="bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border border-emerald-300 font-bold py-2.5 px-4 text-sm rounded-lg shadow-2xs flex items-center gap-2 transition duration-200 justify-center" title="Rekap & Kirim Pengingat Massal WhatsApp">
                                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                                <span>Broadcast WA ({{ $peminjamanTerlambat->total() }})</span>
                            </button>
                        @endif

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

    <script>
        function cetakTerpilih() {
            let selected = document.querySelectorAll('.cetak-checkbox:checked');
            
            if(selected.length === 0) {
                tampilkanAlert('Pilih Data!', 'Tolong ceklis minimal satu arsip terlebih dahulu!', 'warning');
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

        const broadcastWaText = @json($rekapText ?? '');

        function bukaModalBroadcastWa() {
            if (!broadcastWaText) return;
            const encoded = encodeURIComponent(broadcastWaText);
            const waUrl = 'https://api.whatsapp.com/send?text=' + encoded;

            const htmlContent = `
                <div class="text-left text-xs text-slate-600 space-y-3">
                    <p>Pesan ringkasan berikut siap dikirimkan atau disalin untuk mengingatkan peminjam yang berkasnya telah lewat tenggat:</p>
                    <textarea id="broadcast-textarea" readonly class="w-full h-44 p-3 bg-slate-50 border border-slate-200 rounded-xl font-mono text-[11px] leading-relaxed select-all text-slate-800 focus:ring-emerald-500 focus:border-emerald-500">${broadcastWaText}</textarea>
                    <div class="flex flex-wrap gap-2 justify-end pt-1">
                        <button type="button" onclick="navigator.clipboard.writeText(broadcastWaText); AppDialog.toast({ title: 'Teks broadcast berhasil disalin ke clipboard!' });" class="px-3 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs inline-flex items-center gap-1.5 transition cursor-pointer">
                            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                            <span>Salin Teks</span>
                        </button>
                        <a href="${waUrl}" target="_blank" rel="noopener" class="px-4 py-2 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-bold text-xs inline-flex items-center gap-1.5 transition shadow-sm cursor-pointer">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                            <span>Buka WhatsApp Web</span>
                        </a>
                    </div>
                </div>
            `;

            AppDialog.confirm({
                title: 'Broadcast WhatsApp Terlambat',
                html: htmlContent,
                icon: 'info',
                confirmText: 'Selesai',
                cancelText: 'Tutup',
            });
        }
    </script>
</x-app-layout>