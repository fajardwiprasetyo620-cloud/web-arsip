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
                <span class="inline-block px-3 py-1 text-xs font-bold rounded-full {{ $arsip->status == 'Tersedia' ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }} mb-3 shadow-sm border {{ $arsip->status == 'Tersedia' ? 'border-emerald-200' : 'border-red-200' }}">
                    Status: {{ $arsip->status }}
                </span>
                <h2 class="text-2xl font-extrabold text-gray-800 tracking-tight">{{ $arsip->kode_berkas }}</h2>
                <p class="text-gray-600 text-sm mt-2 font-medium leading-relaxed">{{ $arsip->nama_berkas }}</p>
            </div>

            <hr class="border-gray-100 mb-6">

            <div class="space-y-3">
                <div class="flex items-center p-3 bg-slate-50 rounded-xl border border-gray-100">
                    <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-lg shadow-sm border border-gray-100 mr-4 shrink-0">
                        🏢
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-500 font-bold uppercase tracking-wider">Lokasi Ruangan</p>
                        <p class="text-gray-800 font-bold text-sm">{{ $arsip->ruangan }}</p>
                    </div>
                </div>

                <div class="flex items-center p-3 bg-slate-50 rounded-xl border border-gray-100">
                    <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-lg shadow-sm border border-gray-100 mr-4 shrink-0">
                        📚
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-500 font-bold uppercase tracking-wider">Nomor Rak</p>
                        <p class="text-gray-800 font-bold text-sm">{{ $arsip->rak }}</p>
                    </div>
                </div>

                <!-- Tambahan Detail Baris -->
                <div class="flex items-center p-3 bg-slate-50 rounded-xl border border-gray-100">
                    <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-lg shadow-sm border border-gray-100 mr-4 shrink-0">
                        📍
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-500 font-bold uppercase tracking-wider">Nomor Baris</p>
                        <p class="text-gray-800 font-bold text-sm">Baris {{ $arsip->baris }}</p>
                    </div>
                </div>

                <div class="flex items-center p-3 bg-slate-50 rounded-xl border border-gray-100">
                    <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-lg shadow-sm border border-gray-100 mr-4 shrink-0">
                        📦
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