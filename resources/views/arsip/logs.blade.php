<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight flex items-center gap-2">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            {{ __('Log Aktivitas Sistem (Audit Trail)') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-md sm:rounded-xl border border-gray-200">
                
                <!-- Bagian Header Tabel & Form Pencarian -->
                <div class="p-6 border-b border-gray-100 bg-slate-50 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-700">Riwayat Pergerakan Admin</h3>
                        <p class="text-sm text-gray-500 mt-1">Sistem merekam aktivitas penambahan, perubahan, dan penghapusan data secara otomatis.</p>
                    </div>
                    
                    <!-- Form Pencarian -->
                    <form action="{{ route('arsip.logs') }}" method="GET" class="flex flex-wrap sm:flex-nowrap gap-2 w-full md:w-auto">
                        <div class="relative w-full sm:w-64">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                            <input type="text" name="search" placeholder="Cari admin, aksi, arsip..." value="{{ request('search') }}" class="pl-9 border-gray-300 text-sm text-gray-700 rounded-lg shadow-sm py-2 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:outline-none w-full">
                        </div>
                        
                        <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 text-sm rounded-lg shadow-sm transition duration-200 whitespace-nowrap">
                            Cari Log
                        </button>
                        
                        @if(request()->filled('search'))
                            <a href="{{ route('arsip.logs') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-2 px-3 text-sm rounded-lg shadow-sm transition duration-200 flex items-center justify-center">
                                Reset
                            </a>
                        @endif
                    </form>
                </div>
                
                <div class="p-0 overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-100 border-b border-gray-200">
                                <th class="py-3 px-4 font-bold text-sm text-slate-700 w-48">Waktu & Tanggal</th>
                                <th class="py-3 px-4 font-bold text-sm text-slate-700 w-40">Nama Admin</th>
                                <th class="py-3 px-4 font-bold text-sm text-slate-700 w-32 text-center">Tindakan</th>
                                <th class="py-3 px-4 font-bold text-sm text-slate-700">Keterangan Detail</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($logs as $log)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="py-3 px-4 text-sm text-gray-600 font-medium">
                                    {{ $log->created_at->format('d M Y, H:i') }} WIB
                                </td>
                                <td class="py-3 px-4 text-sm font-bold text-gray-800">
                                    {{ $log->user_name }}
                                </td>
                                <td class="py-3 px-4 text-center">
                                    @php
                                        $color = 'bg-gray-100 text-gray-700';
                                        if(str_contains($log->action, 'TAMBAH')) $color = 'bg-emerald-100 text-emerald-800 border-emerald-200';
                                        elseif(str_contains($log->action, 'EDIT')) $color = 'bg-amber-100 text-amber-800 border-amber-200';
                                        elseif(str_contains($log->action, 'HAPUS')) $color = 'bg-rose-100 text-rose-800 border-rose-200';
                                        elseif(str_contains($log->action, 'PULIHKAN')) $color = 'bg-blue-100 text-blue-800 border-blue-200';
                                        elseif(str_contains($log->action, 'IMPORT')) $color = 'bg-purple-100 text-purple-800 border-purple-200';
                                    @endphp
                                    <span class="px-2.5 py-1 text-[11px] font-bold rounded-md border shadow-sm {{ $color }}">
                                        {{ $log->action }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-sm text-gray-600">
                                    {{ $log->description }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="py-12 text-center text-gray-500 text-sm">
                                    @if(request()->filled('search'))
                                        Pencarian "<b>{{ request('search') }}</b>" tidak ditemukan di log aktivitas.
                                    @else
                                        Belum ada log aktivitas yang tercatat.
                                    @endif
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($logs->hasPages())
                    <div class="p-4 border-t border-gray-100 bg-slate-50">
                        {{ $logs->links() }}
                    </div>
                @endif
                
            </div>
        </div>
    </div>
</x-app-layout>