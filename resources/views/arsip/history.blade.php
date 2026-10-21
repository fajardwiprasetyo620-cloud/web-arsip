<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('arsip.index') }}" class="text-white hover:text-gray-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h2 class="font-semibold text-xl sm:text-2xl text-white leading-tight flex items-center gap-2.5">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                {{ __('Riwayat Data Arsip Terhapus') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-flash />
            <div class="bg-white overflow-hidden shadow-md sm:rounded-xl border border-rose-200">
                <div class="p-0 overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-max">
                        <thead>
                            <tr class="bg-rose-50 border-b border-rose-200">
                                <th class="py-4 px-6 font-semibold text-sm text-rose-800">Kode Berkas</th>
                                <th class="py-4 px-6 font-semibold text-sm text-rose-800">Nama Berkas</th>
                                <th class="py-4 px-6 font-semibold text-sm text-rose-800">Tanggal Dihapus</th>
                                <th class="py-4 px-6 font-semibold text-sm text-rose-800 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($arsips as $arsip)
                            <tr class="hover:bg-rose-50/50 transition duration-150">
                                <td class="py-3 px-6 font-semibold text-gray-700">{{ $arsip->kode_berkas }}</td>
                                <td class="py-3 px-6 text-gray-600 line-through">{{ $arsip->nama_berkas }}</td>
                                <td class="py-3 px-6 text-gray-500">{{ $arsip->deleted_at->format('d/m/Y H:i') }}</td>
                                <td class="py-3 px-6 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <form action="{{ route('arsip.restore', $arsip->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            <button type="submit" class="bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-1.5 px-3 rounded-lg shadow-sm text-xs inline-flex items-center gap-1 transition duration-200" onclick="return confirm('Pulihkan arsip ini kembali ke daftar utama?')">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path></svg>
                                                Pulihkan
                                            </button>
                                        </form>

                                        <form action="{{ route('arsip.forceDelete', $arsip->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 font-bold py-1.5 px-3 rounded-lg shadow-sm text-xs inline-flex items-center gap-1 transition duration-200" onclick="return confirm('PERINGATAN: Arsip {{ $arsip->kode_berkas }} dan file fisiknya akan dihapus permanen dari sistem. Lanjutkan?')">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                Hapus Permanen
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="py-12 text-center text-gray-500">
                                    Tidak ada data arsip yang terhapus.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if ($arsips->hasPages())
                <div class="mt-4">
                    {{ $arsips->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
