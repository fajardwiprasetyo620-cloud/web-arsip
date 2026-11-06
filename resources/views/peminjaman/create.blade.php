<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl sm:text-2xl text-white leading-tight flex items-center gap-2.5">
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

                    <form id="form-peminjaman" action="{{ route('peminjaman.store') }}" method="POST" onsubmit="return handlePeminjamanSubmit(event)">
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

                        <div class="mb-5">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Batas Waktu Pengembalian</label>
                            <input type="date" name="tenggat_waktu" value="{{ old('tenggat_waktu') }}" required class="w-full border-gray-300 text-sm text-gray-700 rounded-lg shadow-sm py-2 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:outline-none transition duration-200">
                            <p class="text-xs text-gray-500 mt-1">Tentukan maksimal tanggal arsip ini harus dikembalikan.</p>
                        </div>

                        <!-- Tanda Tangan Digital Peminjam (Signature Pad) -->
                        <div class="mb-6 p-4 rounded-xl bg-slate-50 border border-slate-200">
                            <div class="flex items-center justify-between mb-2">
                                <div>
                                    <label class="block text-gray-800 text-sm font-bold flex items-center gap-2">
                                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        <span>Tanda Tangan Peminjam (Opsional / Digital)</span>
                                    </label>
                                    <p class="text-xs text-gray-500 mt-0.5">Bubuhkan tanda tangan peminjam di layar ini untuk dicantumkan otomatis pada Berita Acara (BAP).</p>
                                </div>
                                <button type="button" onclick="clearSignature()" class="px-2.5 py-1 text-xs font-semibold text-rose-600 hover:text-rose-700 hover:bg-rose-50 border border-rose-200 rounded-lg transition" title="Hapus tanda tangan">
                                    Hapus / Reset
                                </button>
                            </div>

                            <div class="relative w-full bg-white rounded-lg border-2 border-dashed border-slate-300 overflow-hidden cursor-crosshair">
                                <canvas id="signature-pad" class="w-full h-36 block touch-none"></canvas>
                                <div id="signature-guide" class="pointer-events-none absolute bottom-4 left-6 right-6 border-b border-slate-200 text-center pb-1">
                                    <span class="text-[11px] text-slate-400 select-none">( Tanda Tangan Peminjam di Sini )</span>
                                </div>
                            </div>
                            <input type="hidden" name="tanda_tangan" id="tanda_tangan_input">
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
        let tomSelectInstance = null;
        let sigCanvas = null;
        let sigCtx = null;
        let isDrawing = false;
        let hasSignature = false;

        document.addEventListener('DOMContentLoaded', function () {
            tomSelectInstance = new TomSelect('#arsip-select', {
                plugins: ['remove_button'], 
                create: false,
                sortField: {
                    field: "text",
                    direction: "asc"
                },
                placeholder: 'Ketik Kode, Nama Arsip, atau Lokasi Rak...'
            });

            initSignaturePad();
        });

        function initSignaturePad() {
            sigCanvas = document.getElementById('signature-pad');
            if (!sigCanvas) return;
            sigCtx = sigCanvas.getContext('2d');

            // Set resolusi internal canvas sesuai dimensi tampilannya
            function resizeCanvas() {
                const rect = sigCanvas.getBoundingClientRect();
                const dpr = window.devicePixelRatio || 1;
                sigCanvas.width = rect.width * dpr;
                sigCanvas.height = rect.height * dpr;
                sigCtx.scale(dpr, dpr);
                sigCtx.strokeStyle = '#0f172a';
                sigCtx.lineWidth = 2.2;
                sigCtx.lineCap = 'round';
                sigCtx.lineJoin = 'round';
            }

            resizeCanvas();
            window.addEventListener('resize', () => {
                // Jangan reset jika sudah ada tanda tangan
                if (!hasSignature) resizeCanvas();
            });

            function getPos(e) {
                const rect = sigCanvas.getBoundingClientRect();
                const clientX = e.touches ? e.touches[0].clientX : e.clientX;
                const clientY = e.touches ? e.touches[0].clientY : e.clientY;
                return {
                    x: clientX - rect.left,
                    y: clientY - rect.top
                };
            }

            function startDraw(e) {
                isDrawing = true;
                const pos = getPos(e);
                sigCtx.beginPath();
                sigCtx.moveTo(pos.x, pos.y);
                document.getElementById('signature-guide').style.opacity = '0.3';
            }

            function draw(e) {
                if (!isDrawing) return;
                e.preventDefault();
                const pos = getPos(e);
                sigCtx.lineTo(pos.x, pos.y);
                sigCtx.stroke();
                hasSignature = true;
            }

            function stopDraw() {
                if (isDrawing) {
                    sigCtx.closePath();
                    isDrawing = false;
                }
            }

            // Mouse Events
            sigCanvas.addEventListener('mousedown', startDraw);
            sigCanvas.addEventListener('mousemove', draw);
            window.addEventListener('mouseup', stopDraw);

            // Touch Events (HP / Tablet / Layar Sentuh)
            sigCanvas.addEventListener('touchstart', startDraw, { passive: false });
            sigCanvas.addEventListener('touchmove', draw, { passive: false });
            sigCanvas.addEventListener('touchend', stopDraw);
        }

        function clearSignature() {
            if (!sigCanvas || !sigCtx) return;
            const dpr = window.devicePixelRatio || 1;
            sigCtx.clearRect(0, 0, sigCanvas.width / dpr, sigCanvas.height / dpr);
            hasSignature = false;
            document.getElementById('tanda_tangan_input').value = '';
            document.getElementById('signature-guide').style.opacity = '1';
        }

        function handlePeminjamanSubmit(event) {
            const select = document.getElementById('arsip-select');
            const values = tomSelectInstance ? tomSelectInstance.getValue() : (select ? Array.from(select.selectedOptions).map(o => o.value) : []);
            
            if (!values || (Array.isArray(values) && values.length === 0) || values === '') {
                event.preventDefault();
                tampilkanAlert('Pilih Data Arsip', 'Silakan pilih minimal satu berkas arsip yang akan dipinjam.', 'warning');
                return false;
            }

            if (hasSignature && sigCanvas) {
                document.getElementById('tanda_tangan_input').value = sigCanvas.toDataURL('image/png');
            }

            AppDialog.loading('Mencatat Peminjaman...', 'Sedang menyimpan data peminjaman arsip...');
            return true;
        }
    </script>
</x-app-layout>