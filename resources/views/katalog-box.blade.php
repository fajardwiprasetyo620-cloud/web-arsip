<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Isi {{ preg_match('/^box\b/i', trim($lokasi['box'])) ? trim($lokasi['box']) : 'Box ' . trim($lokasi['box']) }} - E-Arsip BPJS</title>
    {{-- CSS dari file aplikasi sendiri (hasil npm run build), bukan dari CDN --}}
    @vite('resources/css/app.css')
</head>
<body class="bg-slate-100 min-h-screen flex items-start justify-center p-4 font-sans">
    @php $fmt = fn ($tgl) => \Carbon\Carbon::parse($tgl)->format('d/m/Y'); @endphp

    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full overflow-hidden border border-gray-200 my-4">
        <!-- Header Kartu -->
        <div class="bg-emerald-600 p-6 text-center relative overflow-hidden">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-white opacity-10 rounded-full"></div>
            <div class="absolute -left-4 -bottom-4 w-16 h-16 bg-white opacity-10 rounded-full"></div>
            <h1 class="text-2xl font-bold text-white mb-1 relative z-10">E-Arsip BPJS</h1>
            <p class="text-emerald-100 text-xs font-medium relative z-10">Isi Box Arsip Fisik</p>
        </div>

        <!-- Lokasi Box -->
        <div class="p-6 pb-4 text-center">
            <p class="text-[10px] text-gray-500 font-bold uppercase tracking-wider">Kotak Arsip Fisik</p>
            <h2 class="text-3xl font-extrabold text-gray-800 tracking-tight">{{ preg_match('/^box\b/i', trim($lokasi['box'])) ? trim($lokasi['box']) : 'Box ' . trim($lokasi['box']) }}</h2>
            <p class="text-gray-600 text-sm mt-1 font-medium">{{ $lokasi['ruangan'] }}</p>

            <div class="flex items-center justify-center gap-1.5 mt-3 text-xs">
                <span class="inline-flex items-center px-2.5 py-1 rounded-md font-bold bg-blue-100 text-blue-900 border border-blue-400 shadow-2xs">
                    {{ preg_match('/^rak\b/i', trim($lokasi['rak'])) ? trim($lokasi['rak']) : 'Rak ' . trim($lokasi['rak']) }}
                </span>
                @if(!empty($lokasi['baris']))
                    <span class="text-slate-300 font-light">/</span>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-md font-bold bg-emerald-100 text-emerald-900 border border-emerald-400 shadow-2xs">
                        {{ preg_match('/^baris\b/i', trim((string)$lokasi['baris'])) ? trim((string)$lokasi['baris']) : 'Baris ' . trim((string)$lokasi['baris']) }}
                    </span>
                @endif
            </div>

            <div class="flex items-center justify-center gap-2 mt-4">
                <span class="text-xs font-bold text-gray-700 bg-slate-100 border border-gray-200 px-3 py-1 rounded-full">{{ $arsips->count() }} berkas</span>
                @if ($jumlahDipinjam > 0)
                    <span class="text-xs font-bold text-amber-800 bg-amber-100 border border-amber-200 px-3 py-1 rounded-full">{{ $jumlahDipinjam }} sedang dipinjam</span>
                @else
                    <span class="text-xs font-bold text-emerald-800 bg-emerald-100 border border-emerald-200 px-3 py-1 rounded-full">Semua tersedia</span>
                @endif
            </div>

            @if ($tanggalAwal)
                <p class="text-xs text-gray-500 mt-4">
                    Periode berkas:
                    <span class="font-bold text-gray-700">{{ $fmt($tanggalAwal) }}@if ($tanggalAkhir !== $tanggalAwal) &ndash; {{ $fmt($tanggalAkhir) }}@endif</span>
                </p>
            @endif
        </div>

        <hr class="border-gray-100">

        <!-- Daftar berkas dalam box -->
        <ul class="divide-y divide-gray-100">
            @foreach ($arsips as $arsip)
                <li>
                    <a href="{{ route('katalog.show', $arsip->id) }}" class="flex items-center gap-3 px-5 py-3.5 hover:bg-slate-50 transition">
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-extrabold text-emerald-700">{{ $arsip->kode_berkas }}</p>
                            <p class="text-xs text-gray-600 leading-snug mt-0.5">{{ $arsip->nama_berkas }}</p>
                            @if ($arsip->tanggal_berkas)
                                <p class="text-[11px] text-gray-500 mt-0.5 flex items-center gap-1">
                                    <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    {{ $fmt($arsip->tanggal_berkas) }}
                                </p>
                            @endif
                        </div>
                        @if ($arsip->status === 'Tersedia')
                            <span class="shrink-0 inline-flex items-center gap-1.5 px-2.5 py-1 text-[11px] font-semibold rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                Tersedia
                            </span>
                        @else
                            <span class="shrink-0 inline-flex items-center gap-1.5 px-2.5 py-1 text-[11px] font-semibold rounded-full bg-rose-50 text-rose-700 border border-rose-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span>
                                Dipinjam
                            </span>
                        @endif
                        <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </li>
            @endforeach
        </ul>

        <!-- Footer Kartu -->
        <div class="bg-gray-50 p-4 text-center border-t border-gray-100">
            <a href="{{ route('katalog.index') }}" class="text-emerald-600 hover:text-emerald-700 text-sm font-bold flex items-center justify-center gap-2 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Pencarian Katalog
            </a>
        </div>
    </div>

</body>
</html>
