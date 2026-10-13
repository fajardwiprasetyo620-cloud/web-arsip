<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight flex items-center gap-2">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            {{ __('Form Peminjaman Arsip') }}
        </h2>
    </x-slot>

    <!-- CSS Tom Select sudah dimuat lewat resources/css/app.css (npm), tidak lagi dari CDN -->
    <style>
        .ts-control { padding: 0.65rem 1rem; border-radius: 0.5rem; border-color: #d1d5db; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); }
        .ts-control.focus { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.5); }
        /* Style untuk tag/item yang dipilih */
        .ts-control .item { background-color: #dbeafe !important; color: #1e3a8a !important; border: 1px solid #bfdbfe !important; border-radius: 4px; padding: 2px 8px; font-weight: 600; margin-bottom: 4px; }
    </style>

    <div class="py-10">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-md sm:rounded-xl border border-gray-200">
                <div class="p-8 text-gray-900">
                    
                    <!-- Kotak Peringatan Error (Akan muncul jika validasi gagal) -->
                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6">
                            <strong>Terjadi Kesalahan!</strong>
                            <ul class="list-disc mt-2 ml-4 text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('peminjaman.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-5">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Pilih Arsip (Bisa Pilih Lebih Dari 1)</label>
                            
                            <select id="arsip-select" name="arsip_id[]" multiple required placeholder="Ketik untuk mencari arsip lalu tekan enter/klik...">
                                <option value="">Ketik untuk mencari arsip...</option>
                                @foreach($arsips as $arsip)
                                    <option value="{{ $arsip->id }}">
                                        {{ $arsip->kode_berkas }} - {{ $arsip->nama_berkas }} [{{ $arsip->ruangan }} | {{ $arsip->rak }} | Box {{ $arsip->box }}]
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-500 mt-2">*Ketik kode atau nama arsip, klik hasilnya. Ulangi untuk menambahkan arsip lainnya.</p>
                        </div>

                        <div class="mb-5">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Nama Peminjam</label>
                            <input type="text" name="nama_peminjam" value="{{ old('nama_peminjam') }}" class="border-gray-300 rounded-lg w-full py-2.5 px-4 text-gray-700 focus:ring-blue-500 focus:border-blue-500 shadow-sm transition" required placeholder="Contoh: Budi">
                        </div>

                        <div class="mb-5">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Divisi / Unit Kerja</label>
                            <input type="text" name="divisi" value="{{ old('divisi') }}" class="border-gray-300 rounded-lg w-full py-2.5 px-4 text-gray-700 focus:ring-blue-500 focus:border-blue-500 shadow-sm transition" required placeholder="Contoh: Divisi Keuangan">
                        </div>

                        <div class="mb-5">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal Pinjam</label>
                            <input type="date" name="tanggal_pinjam" value="{{ old('tanggal_pinjam', \Carbon\Carbon::now()->format('Y-m-d')) }}" required class="w-full border-gray-300 text-sm text-gray-700 rounded-lg shadow-sm py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition duration-200">
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Batas Waktu Pengembalian</label>
                            <input type="date" name="tenggat_waktu" value="{{ old('tenggat_waktu') }}" required class="w-full border-gray-300 text-sm text-gray-700 rounded-lg shadow-sm py-2 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:outline-none transition duration-200">
                            <p class="text-xs text-gray-500 mt-1">Tentukan maksimal tanggal arsip ini harus dikembalikan.</p>
                        </div>

                        <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-100">
                            <a href="{{ route('peminjaman.index') }}" class="text-gray-500 hover:text-gray-700 font-semibold px-4 py-2 transition">Batal</a>
                            <!-- Mengubah bg-blue-600 menjadi bg-emerald-600 -->
                            <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 px-6 rounded-lg shadow-sm flex items-center gap-2 transition duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                                Catat Peminjaman
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <!-- Tom Select sudah dimuat lewat resources/js/app.js (npm), tidak lagi dari CDN -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            new TomSelect('#arsip-select', {
                plugins: ['remove_button'], 
                create: false,
                sortField: {
                    field: "text",
                    direction: "asc"
                },
                placeholder: 'Ketik Kode, Nama Arsip, atau Lokasi Rak...'
            });
        });
    </script>
</x-app-layout>