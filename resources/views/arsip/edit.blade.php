<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl sm:text-2xl text-white leading-tight flex items-center gap-2.5">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            {{ __('Edit Data Arsip') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-md sm:rounded-xl border border-gray-200">
                <div class="p-8 text-gray-900">
                    
                    <x-flash />

                    <form action="{{ route('arsip.update', $arsip->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-5">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Kode Berkas</label>
                            <input type="text" name="kode_berkas" value="{{ $arsip->kode_berkas }}" class="border-gray-300 rounded-lg w-full py-2.5 px-4 text-gray-700 focus:ring-amber-500 focus:border-amber-500 shadow-sm transition" required>
                        </div>

                        <div class="mb-5">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Nama Berkas / Uraian</label>
                            <input type="text" name="nama_berkas" value="{{ $arsip->nama_berkas }}" class="border-gray-300 rounded-lg w-full py-2.5 px-4 text-gray-700 focus:ring-amber-500 focus:border-amber-500 shadow-sm transition" required>
                        </div>

                        <!-- Grid 4 kolom untuk lokasi -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
                            
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Ruangan</label>
                                <select id="ruangan-select" name="ruangan" required class="border-gray-300 rounded-lg w-full py-2.5 px-4 text-gray-700 focus:ring-amber-500 focus:border-amber-500 shadow-sm transition">
                                    <option value="">-- Pilih Ruangan --</option>
                                    <option value="Ruang Arsip Kecil" {{ $arsip->ruangan == 'Ruang Arsip Kecil' ? 'selected' : '' }}>Ruang Arsip Kecil</option>
                                    <option value="Ruang Arsip Kepesertaan" {{ $arsip->ruangan == 'Ruang Arsip Kepesertaan' ? 'selected' : '' }}>Ruang Arsip Kepesertaan</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Rak</label>
                                <input type="text" name="rak" value="{{ $arsip->rak }}" class="border-gray-300 rounded-lg w-full py-2.5 px-4 text-gray-700 focus:ring-amber-500 focus:border-amber-500 shadow-sm transition" required>
                            </div>

                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Baris</label>
                                <select id="baris-select" name="baris" data-selected="{{ $arsip->baris }}" required class="border-gray-300 rounded-lg w-full py-2.5 px-4 text-gray-700 focus:ring-amber-500 focus:border-amber-500 shadow-sm transition">
                                    <option value="">Pilih Ruangan...</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Nomor Box</label>
                                <input type="text" name="box" value="{{ $arsip->box }}" class="border-gray-300 rounded-lg w-full py-2.5 px-4 text-gray-700 focus:ring-amber-500 focus:border-amber-500 shadow-sm transition" required>
                            </div>

                            <!-- Input Tanggal Berkas -->
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal Berkas (Opsional)</label>
                                <input type="date" name="tanggal_berkas" value="{{ old('tanggal_berkas', $arsip->tanggal_berkas) }}" max="{{ now()->toDateString() }}" class="border-gray-300 rounded-lg w-full py-2.5 px-4 text-gray-700 focus:ring-amber-500 focus:border-amber-500 shadow-sm transition">
                                <p class="text-xs text-gray-500 mt-1">Tanggal dokumen dibuat. Dipakai untuk urutan di dalam box dan rentang tanggal di stiker.</p>
                            </div>
                        </div>

                        <!-- Softcopy / Arsip Digital -->
                        <div class="mb-8 bg-slate-50 p-5 rounded-lg border border-slate-200">
                            <label class="block text-slate-700 text-sm font-bold mb-2">Softcopy / Arsip Digital (Opsional)</label>
                            @if($arsip->file_dokumen)
                                <div class="mb-3 space-y-2">
                                    <p class="text-sm text-slate-600">
                                        File saat ini:
                                        <a href="{{ route('arsip.file', $arsip->id) }}" target="_blank" class="text-blue-600 hover:underline font-semibold">Lihat file</a>
                                        <span class="text-slate-400">&middot; unggah file baru di bawah untuk menggantinya</span>
                                    </p>
                                    <label class="inline-flex items-center gap-2 text-sm text-rose-600 cursor-pointer">
                                        <input type="checkbox" name="hapus_file" value="1" class="rounded border-gray-300 text-rose-600 focus:ring-rose-500">
                                        <span>Hapus file dokumen digital ini</span>
                                    </label>
                                </div>
                            @endif
                            <input type="file" name="file_dokumen" accept=".pdf,.jpg,.jpeg,.png" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100">
                            <p class="text-xs text-slate-500 mt-2">Format yang didukung: PDF, JPG, PNG. Ukuran maksimal: 10MB.</p>
                        </div>

                        <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-100">
                            <a href="{{ route('arsip.index') }}" class="text-gray-500 hover:text-gray-700 font-semibold px-4 py-2 transition">Batal</a>
                            <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white font-bold py-2.5 px-6 rounded-lg shadow-sm flex items-center gap-2 transition duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                Update Data Arsip
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <!-- Script JavaScript untuk form edit -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ruanganSelect = document.getElementById('ruangan-select');
            const barisSelect = document.getElementById('baris-select');
            const selectedBaris = barisSelect.getAttribute('data-selected');

            const kapasitasRuangan = {
                'Ruang Arsip Kecil': 4,
                'Ruang Arsip Kepesertaan': 6
            };

            function updateBaris() {
                const ruanganTerpilih = ruanganSelect.value;
                const maksimalBaris = kapasitasRuangan[ruanganTerpilih] || 0;

                barisSelect.innerHTML = '';
                barisSelect.classList.remove('bg-gray-100', 'text-gray-500', 'cursor-not-allowed');

                if (maksimalBaris > 0) {
                    barisSelect.disabled = false;
                    barisSelect.appendChild(new Option('-- Pilih Baris --', ''));

                    for (let i = 1; i <= maksimalBaris; i++) {
                        const option = new Option(`Baris ${i}`, i);
                        if (i == selectedBaris) {
                            option.selected = true;
                        }
                        barisSelect.appendChild(option);
                    }
                } else {
                    barisSelect.disabled = true;
                    barisSelect.classList.add('bg-gray-100', 'text-gray-500', 'cursor-not-allowed');
                    barisSelect.appendChild(new Option('Pilih Ruangan...', ''));
                }
            }

            ruanganSelect.addEventListener('change', updateBaris);
            
            if(ruanganSelect.value) {
                updateBaris();
            }
        });
    </script>
</x-app-layout>