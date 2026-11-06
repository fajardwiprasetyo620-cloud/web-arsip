<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between w-full">
            <h2 class="font-semibold text-xl sm:text-2xl text-white leading-tight flex items-center gap-2.5">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                {{ __('Dashboard E-Arsip') }}
            </h2>

            <a href="{{ route('dashboard.executivePdf') }}" target="_blank" class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 border border-white/20 text-white font-bold text-xs py-2 px-3.5 rounded-xl transition shadow-sm backdrop-blur-xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <span>Cetak Ringkasan Eksekutif (PDF)</span>
            </a>
        </div>
    </x-slot>

    {{-- Data disiapkan oleh DashboardController --}}

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- 0. BANNER SELAMAT DATANG -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-emerald-100 mb-8 relative">
                <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-emerald-500 rounded-full mix-blend-multiply filter blur-2xl opacity-10"></div>
                
                <div class="p-6 sm:p-8 text-gray-900 flex flex-col sm:flex-row items-center sm:items-start gap-5 relative z-10">
                    <div class="p-4 bg-emerald-50 text-emerald-600 rounded-full shadow-inner border border-emerald-100">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div class="text-center sm:text-left flex-1">
                        <h3 class="text-2xl font-extrabold text-gray-800 mb-2">Selamat datang kembali, {{ Auth::user()->name }}!</h3>
                        <p class="text-sm text-gray-600 font-medium leading-relaxed">
                            Berikut adalah rangkuman aktivitas dan status E-Arsip fisik hari ini. Pastikan untuk selalu memantau arsip yang statusnya sedang dipinjam agar tidak terlewat dari batas waktu pengembalian.
                        </p>
                    </div>
                </div>
            </div>

            @if($jumlahTerlambat > 0)
                <!-- BANNER PERINGATAN KETERLAMBATAN REAL-TIME -->
                <div class="mb-8 bg-gradient-to-r from-rose-500 to-red-600 rounded-2xl p-5 sm:p-6 text-white shadow-md flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-white/20 backdrop-blur-sm rounded-xl shrink-0">
                            <svg class="w-8 h-8 text-white animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        </div>
                        <div>
                            <h4 class="font-extrabold text-lg text-white">Perhatian: Ada {{ $jumlahTerlambat }} Peminjaman Melewati Batas Waktu!</h4>
                            <p class="text-rose-100 text-xs sm:text-sm font-medium mt-0.5">Segera kirimkan pesan pengingat WhatsApp kepada peminjam agar berkas fisik segera dikembalikan ke ruang arsip.</p>
                        </div>
                    </div>
                    <a href="{{ route('peminjaman.index') }}" class="shrink-0 bg-white hover:bg-rose-50 text-rose-700 font-extrabold px-5 py-2.5 rounded-xl text-sm shadow transition duration-200 inline-flex items-center gap-2">
                        <span>Tindak Lanjut & Kirim WA</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                </div>
            @endif
            
            <!-- 1. KARTU RINGKASAN ANGKA -->
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
                <!-- Kartu Total -->
                <div class="bg-white rounded-2xl shadow-[0_2px_16px_rgba(0,0,0,0.05)] border border-gray-100 p-6 flex items-center gap-4 hover:shadow-[0_8px_24px_rgba(0,0,0,0.08)] hover:-translate-y-0.5 transition-all duration-300">
                    <div class="p-4 bg-blue-50 text-blue-600 rounded-full">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-500">Total Arsip Terdaftar</p>
                        <h3 class="text-3xl font-bold text-gray-800"
                            x-data="{ n: 0 }"
                            x-init="$nextTick(() => { let t = {{ $totalArsip }}, s = null; function step(ts) { if (!s) s = ts; let p = Math.min((ts - s) / 700, 1); n = Math.floor(p * t); if (p < 1) requestAnimationFrame(step); else n = t; } requestAnimationFrame(step); })"
                            x-text="n"></h3>
                    </div>
                </div>

                <!-- Kartu Tersedia -->
                <div class="bg-white rounded-2xl shadow-[0_2px_16px_rgba(0,0,0,0.05)] border border-gray-100 p-6 flex items-center gap-4 hover:shadow-[0_8px_24px_rgba(0,0,0,0.08)] hover:-translate-y-0.5 transition-all duration-300">
                    <div class="p-4 bg-emerald-50 text-emerald-600 rounded-full">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-500">Arsip Tersedia di Rak</p>
                        <h3 class="text-3xl font-bold text-emerald-600"
                            x-data="{ n: 0 }"
                            x-init="$nextTick(() => { let t = {{ $tersedia }}, s = null; function step(ts) { if (!s) s = ts; let p = Math.min((ts - s) / 700, 1); n = Math.floor(p * t); if (p < 1) requestAnimationFrame(step); else n = t; } requestAnimationFrame(step); })"
                            x-text="n"></h3>
                    </div>
                </div>

                <!-- Kartu Dipinjam -->
                <div class="bg-white rounded-2xl shadow-[0_2px_16px_rgba(0,0,0,0.05)] border border-gray-100 p-6 flex items-center gap-4 hover:shadow-[0_8px_24px_rgba(0,0,0,0.08)] hover:-translate-y-0.5 transition-all duration-300">
                    <div class="p-4 bg-amber-50 text-amber-600 rounded-full">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-500">Arsip Sedang Dipinjam</p>
                        <h3 class="text-3xl font-bold text-amber-600"
                            x-data="{ n: 0 }"
                            x-init="$nextTick(() => { let t = {{ $dipinjam }}, s = null; function step(ts) { if (!s) s = ts; let p = Math.min((ts - s) / 700, 1); n = Math.floor(p * t); if (p < 1) requestAnimationFrame(step); else n = t; } requestAnimationFrame(step); })"
                            x-text="n"></h3>
                    </div>
                </div>

                <!-- Kartu Terlambat -->
                <div class="bg-white rounded-2xl shadow-[0_2px_16px_rgba(0,0,0,0.05)] border {{ $jumlahTerlambat > 0 ? 'border-red-200' : 'border-gray-100' }} p-6 flex items-center gap-4 hover:shadow-[0_8px_24px_rgba(0,0,0,0.08)] hover:-translate-y-0.5 transition-all duration-300">
                    <div class="p-4 bg-red-50 text-red-600 rounded-full">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-500">Peminjaman Terlambat</p>
                        <h3 class="text-3xl font-bold text-red-600"
                            x-data="{ n: 0 }"
                            x-init="$nextTick(() => { let t = {{ $jumlahTerlambat }}, s = null; function step(ts) { if (!s) s = ts; let p = Math.min((ts - s) / 700, 1); n = Math.floor(p * t); if (p < 1) requestAnimationFrame(step); else n = t; } requestAnimationFrame(step); })"
                            x-text="n"></h3>
                    </div>
                </div>
            </div>

            <!-- 2. AREA GRAFIK INTERAKTIF -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Grafik Donat -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-700 mb-6 text-center">Perbandingan Status Arsip</h3>
                    <div class="relative h-64 w-full flex justify-center">
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>
                <!-- Grafik Batang -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-700 mb-6 text-center">Distribusi Arsip per Ruangan</h3>
                    <div class="relative h-64 w-full flex justify-center">
                        <canvas id="ruanganChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- 2b. PEMINJAMAN TERLAMBAT -->
            <div class="bg-white rounded-xl shadow-sm border {{ $jumlahTerlambat > 0 ? 'border-red-200' : 'border-gray-100' }} p-6 mb-8">
                <div class="flex justify-between items-center mb-5">
                    <h3 class="text-lg font-bold text-gray-700 flex items-center gap-2">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        Peminjaman Melewati Tenggat
                    </h3>
                    <a href="{{ route('peminjaman.index') }}" class="text-sm text-emerald-600 hover:text-emerald-700 font-bold transition">Kelola Peminjaman</a>
                </div>

                @if ($peminjamanTerlambat->isEmpty())
                    <p class="py-6 text-center text-sm text-emerald-700 bg-emerald-50 rounded-lg font-semibold">
                        Tidak ada peminjaman yang melewati tenggat. Semua aman.
                    </p>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-red-50 border-y border-red-100">
                                    <th class="py-3 px-4 font-bold text-sm text-slate-700">Peminjam</th>
                                    <th class="py-3 px-4 font-bold text-sm text-slate-700">Dokumen Arsip</th>
                                    <th class="py-3 px-4 font-bold text-sm text-slate-700 text-center">Tenggat</th>
                                    <th class="py-3 px-4 font-bold text-sm text-slate-700 text-center">Terlambat</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($peminjamanTerlambat as $pinjam)
                                    <tr class="hover:bg-red-50/40 transition">
                                        <td class="py-3 px-4 text-sm">
                                            <span class="font-semibold text-gray-800">{{ $pinjam->nama_peminjam }}</span><br>
                                            <span class="text-xs text-gray-500">{{ $pinjam->divisi }}</span>
                                        </td>
                                        <td class="py-3 px-4 text-sm">
                                            <span class="font-bold text-emerald-700">{{ $pinjam->arsip->kode_berkas ?? 'Dihapus' }}</span><br>
                                            <span class="text-xs text-gray-500">{{ $pinjam->arsip->nama_berkas ?? '-' }}</span>
                                        </td>
                                        <td class="py-3 px-4 text-center text-sm text-gray-600">
                                            {{ \Carbon\Carbon::parse($pinjam->tenggat_waktu)->format('d M Y') }}
                                        </td>
                                        <td class="py-3 px-4 text-center">
                                            <span class="px-2.5 py-1 text-[11px] font-bold rounded-md border bg-red-50 text-red-700 border-red-100">
                                                {{ abs((int) \Carbon\Carbon::parse($pinjam->tenggat_waktu)->startOfDay()->diffInDays(today())) }} hari
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if ($jumlahTerlambat > $peminjamanTerlambat->count())
                        <p class="mt-3 text-xs text-gray-500">
                            Menampilkan {{ $peminjamanTerlambat->count() }} yang paling lama dari {{ $jumlahTerlambat }} peminjaman terlambat.
                        </p>
                    @endif
                @endif
            </div>

            <!-- 3. TABEL AKTIVITAS PEMINJAMAN TERBARU -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
                <div class="flex justify-between items-center mb-5">
                    <h3 class="text-lg font-bold text-gray-700 flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Aktivitas Peminjaman Terakhir
                    </h3>
                    <a href="{{ route('peminjaman.index') }}" class="text-sm text-emerald-600 hover:text-emerald-700 font-bold transition flex items-center gap-1">
                        Lihat Semua <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-y border-gray-200">
                                <th class="py-3 px-4 font-bold text-sm text-slate-700">Nama Peminjam</th>
                                <th class="py-3 px-4 font-bold text-sm text-slate-700">Dokumen Arsip</th>
                                <th class="py-3 px-4 font-bold text-sm text-slate-700 text-center">Tanggal Pinjam</th>
                                <th class="py-3 px-4 font-bold text-sm text-slate-700 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($peminjamanTerbaru as $pinjam)
                                <tr class="hover:bg-slate-50/50 transition">
                                    <td class="py-3 px-4 text-sm font-semibold text-gray-800">{{ $pinjam->nama_peminjam }}</td>
                                    <td class="py-3 px-4 text-sm">
                                        <span class="font-bold text-emerald-700">{{ $pinjam->arsip->kode_berkas ?? 'Dihapus' }}</span><br>
                                        <span class="text-xs text-gray-500">{{ $pinjam->arsip->nama_berkas ?? '-' }}</span>
                                    </td>
                                    <td class="py-3 px-4 text-center text-sm text-gray-600">
                                        {{ \Carbon\Carbon::parse($pinjam->tanggal_pinjam)->format('d M Y') }}
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        <span class="px-2.5 py-1 text-[11px] font-bold rounded-md border {{ $pinjam->status == 'Dikembalikan' ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : 'bg-amber-50 text-amber-600 border-amber-100' }}">
                                            {{ $pinjam->status }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-8 text-center text-gray-500 text-sm">
                                        Belum ada data aktivitas peminjaman.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 4. MONITORING KAPASITAS & TATA LETAK RAK FISIK -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-7 mb-8" x-data="{ activeRuang: '{{ array_key_first($kapasitasRuang) }}' }">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6 pb-5 border-b border-gray-100">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            <span>Monitoring Kapasitas & Tata Letak Rak Fisik</span>
                        </h3>
                        <p class="text-xs sm:text-sm text-gray-500 mt-1">Pemetaan daya tampung baris rak dan box arsip pada masing-masing ruangan penyimpanan.</p>
                    </div>

                    <!-- Room Tab Buttons -->
                    <div class="inline-flex p-1 bg-slate-100 rounded-xl gap-1 shrink-0">
                        @foreach($kapasitasRuang as $namaRuang => $rData)
                            <button type="button" @click="activeRuang = '{{ $namaRuang }}'"
                                    :class="activeRuang === '{{ $namaRuang }}' ? 'bg-white text-emerald-800 shadow-2xs font-bold' : 'text-gray-600 hover:text-gray-900 font-medium'"
                                    class="px-4 py-2 text-xs sm:text-sm rounded-lg transition duration-150">
                                {{ $namaRuang }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Tab Content per Ruangan -->
                @foreach($kapasitasRuang as $namaRuang => $rData)
                    <div x-show="activeRuang === '{{ $namaRuang }}'" x-cloak class="space-y-6">
                        <!-- Ringkasan Ruangan -->
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 p-4 bg-emerald-50/40 rounded-xl border border-emerald-100/70">
                            <div>
                                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Berkas</span>
                                <div class="text-xl sm:text-2xl font-black text-emerald-700">{{ $rData['total_arsip'] }} Berkas</div>
                            </div>
                            <div>
                                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Box Fisik</span>
                                <div class="text-xl sm:text-2xl font-black text-emerald-700">{{ $rData['total_box'] }} Box</div>
                            </div>
                            <div class="col-span-2 sm:col-span-1">
                                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Kapasitas Maksimal</span>
                                <div class="text-xl sm:text-2xl font-black text-emerald-700">{{ $rData['max_baris'] }} Baris / Rak</div>
                            </div>
                        </div>

                        <!-- Grid 4 Rak -->
                        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5">
                            @foreach($rData['raks'] as $namaRak => $rakInfo)
                                @php
                                    $badgeColor = $rakInfo['persen'] >= 85 ? 'bg-red-100 text-red-700 border-red-200' : ($rakInfo['persen'] >= 50 ? 'bg-amber-100 text-amber-700 border-amber-200' : 'bg-emerald-100 text-emerald-700 border-emerald-200');
                                    $barColor = $rakInfo['persen'] >= 85 ? 'bg-red-500' : ($rakInfo['persen'] >= 50 ? 'bg-amber-500' : 'bg-emerald-500');
                                @endphp
                                <div class="bg-white rounded-xl border border-gray-200/90 shadow-2xs p-4 flex flex-col justify-between hover:shadow-sm transition">
                                    <div>
                                        <div class="flex items-center justify-between mb-3">
                                            <span class="font-extrabold text-base text-gray-800">{{ $namaRak }}</span>
                                            <span class="px-2 py-0.5 text-[11px] font-bold rounded border {{ $badgeColor }}">
                                                {{ $rakInfo['baris_terisi'] }}/{{ $rakInfo['max_baris'] }} Baris
                                            </span>
                                        </div>

                                        <!-- Progress Bar -->
                                        <div class="w-full bg-gray-100 rounded-full h-2 mb-3 overflow-hidden">
                                            <div class="{{ $barColor }} h-2 rounded-full transition-all duration-500" style="width: {{ $rakInfo['persen'] }}%"></div>
                                        </div>

                                        <!-- Level Baris Shelf Visualizer -->
                                        <div class="space-y-1.5 my-3">
                                            @foreach($rakInfo['detail_baris'] as $nomorBaris => $barisDetail)
                                                <div class="p-2.5 rounded-lg text-xs border {{ $barisDetail['total_arsip'] > 0 ? 'bg-slate-50/90 border-slate-200 text-gray-800' : 'bg-gray-50/50 border-dashed border-gray-200 text-gray-400' }}">
                                                    <div class="flex items-center justify-between font-semibold">
                                                        <span class="flex items-center gap-1">
                                                            <span class="w-2 h-2 rounded-full {{ $barisDetail['total_arsip'] > 0 ? 'bg-emerald-500' : 'bg-slate-300' }}"></span>
                                                            <span>Baris {{ $nomorBaris }}</span>
                                                        </span>
                                                        @if($barisDetail['total_arsip'] > 0)
                                                            <span class="text-[11px] text-emerald-700 font-bold">{{ $barisDetail['total_box'] }} Box &middot; {{ $barisDetail['total_arsip'] }} Berkas</span>
                                                        @else
                                                            <span class="text-[10px] text-gray-400 italic">Tersedia</span>
                                                        @endif
                                                    </div>

                                                    @if($barisDetail['total_arsip'] > 0 && !empty($barisDetail['boxes']))
                                                        <div class="flex flex-wrap gap-1 mt-2 pt-2 border-t border-slate-200/70">
                                                            @foreach($barisDetail['boxes'] as $boxName)
                                                                <a href="{{ route('katalog.box', ['ruangan' => $namaRuang, 'rak' => $namaRak, 'box' => $boxName]) }}" 
                                                                   target="_blank"
                                                                   title="Klik untuk membuka daftar berkas di dalam Box {{ $boxName }}" 
                                                                   class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[10px] font-bold bg-white hover:bg-emerald-100 hover:text-emerald-900 border border-slate-200 text-slate-700 shadow-2xs transition">
                                                                    <svg class="w-2.5 h-2.5 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z"></path><path fill-rule="evenodd" d="M3 8h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8zm5 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" clip-rule="evenodd"></path></svg>
                                                                    <span>Box {{ $boxName }}</span>
                                                                </a>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <div class="pt-3 border-t border-gray-100 flex items-center justify-between">
                                        <span class="text-xs text-gray-500 font-medium">{{ $rakInfo['total_box'] }} Box terdaftar</span>
                                        <a href="{{ route('arsip.index', ['ruangan' => $namaRuang, 'search' => $namaRak]) }}" class="text-xs font-bold text-emerald-600 hover:text-emerald-700 inline-flex items-center gap-1 transition">
                                            <span>Lihat Berkas</span>
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </div>

    <!-- Chart.js sudah dimuat lewat resources/js/app.js (npm), tidak lagi dari CDN -->
    <!-- SCRIPT RENDER GRAFIK -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Render Grafik Status (Donat)
            const ctxStatus = document.getElementById('statusChart').getContext('2d');
            new Chart(ctxStatus, {
                type: 'doughnut',
                data: {
                    labels: ['Tersedia', 'Dipinjam'],
                    datasets: [{
                        data: [{{ $tersedia }}, {{ $dipinjam }}],
                        backgroundColor: ['#10b981', '#f59e0b'], 
                        borderWidth: 0,
                        hoverOffset: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom', labels: { font: { family: "'Nunito', sans-serif", weight: 'bold' } } }
                    }
                }
            });

            // 2. Render Grafik Ruangan (Batang)
            const ctxRuangan = document.getElementById('ruanganChart').getContext('2d');
            new Chart(ctxRuangan, {
                type: 'bar',
                data: {
                    labels: {!! $labelRuangan !!},
                    datasets: [{
                        label: 'Total Arsip',
                        data: {!! $dataRuangan !!},
                        backgroundColor: '#059669', 
                        borderRadius: 6,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: { beginAtZero: true, ticks: { stepSize: 1 } },
                        x: { grid: { display: false } }
                    },
                    plugins: { legend: { display: false } }
                }
            });
        });
    </script>
</x-app-layout>