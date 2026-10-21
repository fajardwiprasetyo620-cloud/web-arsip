<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Arsip - {{ $arsip->kode_berkas }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 min-h-screen flex items-center justify-center p-4">
    
    <div class="bg-white rounded-2xl shadow-xl max-w-sm w-full overflow-hidden border border-gray-200">
        <!-- Header Kartu -->
        <div class="bg-emerald-600 p-6 text-center relative overflow-hidden">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-white opacity-10 rounded-full"></div>
            <div class="absolute -left-4 -bottom-4 w-16 h-16 bg-white opacity-10 rounded-full"></div>
            <h1 class="text-2xl font-bold text-white mb-1 relative z-10">E-Arsip BPJS</h1>
            <p class="text-emerald-100 text-xs font-medium relative z-10">Informasi Detail Dokumen Fisik</p>
        </div>
        
        <!-- Isi Kartu -->
        <div class="p-6">
            <div class="mb-6 text-center">
                @if ($arsip->status === 'Tersedia')
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-semibold rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 mb-3 shadow-sm">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        Status: Tersedia
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-semibold rounded-full bg-rose-50 text-rose-700 border border-rose-200 mb-3 shadow-sm">
                        <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
                        Status: Dipinjam
                    </span>
                @endif
                <h2 class="text-2xl font-extrabold text-gray-800 tracking-tight">{{ $arsip->kode_berkas }}</h2>
                <p class="text-gray-600 text-sm mt-2 font-medium leading-relaxed">{{ $arsip->nama_berkas }}</p>
            </div>

            <hr class="border-gray-100 mb-6">

            <div class="space-y-3">
                <div class="flex items-center p-3 bg-slate-50 rounded-xl border border-gray-100">
                    <div class="w-10 h-10 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center shadow-sm border border-emerald-100 mr-4 shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-500 font-bold uppercase tracking-wider">Lokasi Ruangan</p>
                        <p class="text-gray-800 font-bold text-sm">{{ $arsip->ruangan }}</p>
                    </div>
                </div>

                <div class="flex items-center p-3 bg-slate-50 rounded-xl border border-gray-100">
                    <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-lg shadow-sm border border-gray-100 mr-4 shrink-0">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-500 font-bold uppercase tracking-wider">Nomor Rak</p>
                        <p class="text-gray-800 font-bold text-sm">{{ $arsip->rak }}</p>
                    </div>
                </div>

                <!-- Tambahan Detail Baris -->
                <div class="flex items-center p-3 bg-slate-50 rounded-xl border border-gray-100">
                    <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-lg shadow-sm border border-gray-100 mr-4 shrink-0">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-500 font-bold uppercase tracking-wider">Nomor Baris</p>
                        <p class="text-gray-800 font-bold text-sm">Baris {{ $arsip->baris }}</p>
                    </div>
                </div>

                <div class="flex items-center p-3 bg-slate-50 rounded-xl border border-gray-100">
                    <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-lg shadow-sm border border-gray-100 mr-4 shrink-0">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-500 font-bold uppercase tracking-wider">Nomor Box</p>
                        <p class="text-gray-800 font-bold text-sm">Box {{ $arsip->box }}</p>
                    </div>
                </div>
            </div>
        </div>

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