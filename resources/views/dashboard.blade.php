<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight flex items-center gap-2">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            {{ __('Dashboard E-Arsip') }}
        </h2>
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
                        <h3 class="text-2xl font-extrabold text-gray-800 mb-2">Selamat datang kembali, {{ Auth::user()->name }}! 👋</h3>
                        <p class="text-sm text-gray-600 font-medium leading-relaxed">
                            Berikut adalah rangkuman aktivitas dan status E-Arsip fisik hari ini. Pastikan untuk selalu memantau arsip yang statusnya sedang dipinjam agar tidak terlewat dari batas waktu pengembalian.
                        </p>
                    </div>
                </div>
            </div>
            
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
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
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