<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl sm:text-2xl text-white leading-tight flex items-center gap-2.5">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            {{ __('Tambah Arsip Baru') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-md sm:rounded-xl border border-gray-200">
                <div class="p-8 text-gray-900">
                    
                    <x-flash />

                    <!-- WAJIB: Tambahkan enctype untuk mengizinkan upload file -->
                    <form action="{{ route('arsip.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-5">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Kode Berkas / Transaksi</label>
                            <input type="text" name="kode_berkas" class="border-gray-300 rounded-lg w-full py-2.5 px-4 text-gray-700 focus:ring-emerald-500 focus:border-emerald-500 shadow-sm transition" required placeholder="Contoh: J0401B-0001">
                        </div>

                        <div class="mb-5">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Nama Berkas / Uraian</label>
                            <input type="text" name="nama_berkas" class="border-gray-300 rounded-lg w-full py-2.5 px-4 text-gray-700 focus:ring-emerald-500 focus:border-emerald-500 shadow-sm transition" required placeholder="Contoh: Pembayaran ATK Cabang">
                        </div>

                        <!-- Grid diubah menjadi 4 kolom untuk Ruangan, Rak, Baris, Box -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
                            
                            <!-- Input Dropdown Ruangan -->
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Ruangan</label>
                                <select id="ruangan-select" name="ruangan" required class="border-gray-300 rounded-lg w-full py-2.5 px-4 text-gray-700 focus:ring-emerald-500 focus:border-emerald-500 shadow-sm transition">
                                    <option value="">-- Pilih Ruangan --</option>
                                    <option value="Ruang Arsip Kecil">Ruang Arsip Kecil</option>
                                    <option value="Ruang Arsip Kepesertaan">Ruang Arsip Kepesertaan</option>
                                </select>
                            </div>
                            
                            <!-- Input Rak -->
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Rak</label>
                                <input type="text" name="rak" class="border-gray-300 rounded-lg w-full py-2.5 px-4 text-gray-700 focus:ring-emerald-500 focus:border-emerald-500 shadow-sm transition" required placeholder="Contoh: Rak A">
                            </div>

                            <!-- Input Dropdown Baris (Otomatis berdasarkan Ruangan) -->
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Baris</label>
                                <select id="baris-select" name="baris" required disabled class="bg-gray-100 border-gray-300 rounded-lg w-full py-2.5 px-4 text-gray-500 cursor-not-allowed focus:ring-emerald-500 focus:border-emerald-500 shadow-sm transition">
                                    <option value="">Pilih Ruangan...</option>
                                </select>
                            </div>

                            <!-- Input Box -->
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Nomor Box</label>
                                <input type="text" name="box" class="border-gray-300 rounded-lg w-full py-2.5 px-4 text-gray-700 focus:ring-emerald-500 focus:border-emerald-500 shadow-sm transition" required placeholder="Contoh: 12">
                            </div>

                            <!-- Input Tanggal Berkas -->
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal Berkas (Opsional)</label>
                                <input type="date" name="tanggal_berkas" value="{{ old('tanggal_berkas') }}" max="{{ now()->toDateString() }}" class="border-gray-300 rounded-lg w-full py-2.5 px-4 text-gray-700 focus:ring-emerald-500 focus:border-emerald-500 shadow-sm transition">
                                <p class="text-xs text-gray-500 mt-1">Tanggal dokumen dibuat. Dipakai untuk urutan di dalam box dan rentang tanggal di stiker.</p>
                            </div>
                        </div>

                        <!-- Area Unggah Softcopy (Arsip Digital) -->
                        <div class="mb-8 bg-slate-50 p-5 rounded-lg border border-slate-200">
                            <label class="block text-slate-700 text-sm font-bold mb-2">Upload Softcopy / Arsip Digital (Opsional)</label>
                            <input type="file" name="file_dokumen" accept=".pdf,.jpg,.jpeg,.png" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 border border-slate-300 rounded-lg p-1.5 focus:ring-emerald-500 focus:border-emerald-500 transition bg-white cursor-pointer">
                            <p class="text-xs text-slate-500 mt-2">Format yang didukung: PDF, JPG, PNG. Ukuran maksimal: 10MB.</p>
                        </div>

                        <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-100">
                            <a href="{{ route('arsip.index') }}" class="text-gray-500 hover:text-gray-700 font-semibold px-4 py-2 transition">
                                Batal
                            </a>
                            <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 px-6 rounded-lg shadow-sm flex items-center gap-2 transition duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                                Simpan Data Arsip
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <!-- Script JavaScript untuk Dropdown Dinamis Ruangan -> Baris -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ruanganSelect = document.getElementById('ruangan-select');
            const barisSelect = document.getElementById('baris-select');

            // Peta kapasitas baris berdasarkan nama ruangan
            const kapasitasRuangan = {
                'Ruang Arsip Kecil': 4,
                'Ruang Arsip Kepesertaan': 6
            };

            ruanganSelect.addEventListener('change', function() {
                const ruanganTerpilih = this.value;
                const maksimalBaris = kapasitasRuangan[ruanganTerpilih] || 0;

                // Kosongkan isi dropdown baris setiap kali ruangan diganti
                barisSelect.innerHTML = '';
                
                // Hapus class warna abu-abu (disabled state)
                barisSelect.classList.remove('bg-gray-100', 'text-gray-500', 'cursor-not-allowed');

                if (maksimalBaris > 0) {
                    // Aktifkan dropdown
                    barisSelect.disabled = false;
                    barisSelect.appendChild(new Option('-- Pilih Baris --', ''));

                    // Looping otomatis untuk membuat opsi 1 sampai maksimal baris
                    for (let i = 1; i <= maksimalBaris; i++) {
                        barisSelect.appendChild(new Option(`Baris ${i}`, i));
                    }
                } else {
                    // Kunci kembali jika user kembali memilih opsi kosong
                    barisSelect.disabled = true;
                    barisSelect.classList.add('bg-gray-100', 'text-gray-500', 'cursor-not-allowed');
                    barisSelect.appendChild(new Option('Pilih Ruangan...', ''));
                }
            });
        });
    </script>
</x-app-layout>