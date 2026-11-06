<div class="space-y-6">

    <!-- Kartu Utama Scanner -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        
        <!-- Area Kamera / Scanner Viewport -->
        <div class="relative bg-slate-950 flex flex-col items-center justify-center min-h-[340px] sm:min-h-[400px] overflow-hidden">
            <div id="reader" class="w-full"></div>
            
            <!-- Reticle / Bingkai Bidik saat Kamera Aktif -->
            <div id="camera-reticle" class="hidden pointer-events-none absolute inset-0 flex items-center justify-center z-10">
                <div class="relative w-64 h-64 border border-emerald-400/50 rounded-2xl shadow-[0_0_20px_rgba(16,185,129,0.3)]">
                    <!-- Siku-siku Pembidik -->
                    <div class="absolute -top-1 -left-1 w-6 h-6 border-t-4 border-l-4 border-emerald-400 rounded-tl-lg"></div>
                    <div class="absolute -top-1 -right-1 w-6 h-6 border-t-4 border-r-4 border-emerald-400 rounded-tr-lg"></div>
                    <div class="absolute -bottom-1 -left-1 w-6 h-6 border-b-4 border-l-4 border-emerald-400 rounded-bl-lg"></div>
                    <div class="absolute -bottom-1 -right-1 w-6 h-6 border-b-4 border-r-4 border-emerald-400 rounded-br-lg"></div>
                    <!-- Garis Laser Pemindai Animasi -->
                    <div class="w-full h-0.5 bg-gradient-to-r from-transparent via-emerald-400 to-transparent shadow-[0_0_12px_#10b981] animate-pulse"></div>
                </div>
            </div>

            <!-- Tombol Menghentikan Kamera (Melayang di Pojok Kanan Atas saat Streaming) -->
            <button type="button" 
                    id="btn-stop" 
                    onclick="stopScanner()" 
                    class="hidden absolute top-3 right-3 z-30 inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-rose-600/90 hover:bg-rose-700 text-white shadow-md backdrop-blur-xs transition cursor-pointer">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                <span>Matikan Kamera</span>
            </button>

            <!-- Tampilan Placeholder Bersih & Terang (Ketika Kamera Belum Aktif) -->
            <div id="scanner-placeholder" class="absolute inset-0 flex flex-col items-center justify-center p-6 sm:p-10 text-center bg-gradient-to-b from-emerald-50/90 via-white to-slate-50 z-20">
                
                <!-- Badge Ikon QR Pulsing -->
                <div class="relative mb-4">
                    <div class="w-20 h-20 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center shadow-inner border border-emerald-200">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                    </div>
                    <span class="absolute -top-1 -right-1 flex h-4 w-4">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-4 w-4 bg-emerald-500"></span>
                    </span>
                </div>

                <h3 class="text-xl font-extrabold text-gray-900 tracking-tight">Siap Memindai QR Code</h3>
                <p class="text-xs sm:text-sm text-gray-600 mt-1.5 max-w-sm leading-relaxed">
                    Izinkan akses kamera browser Anda untuk mulai memindai label QR pada stiker box atau berkas fisik secara instan.
                </p>

                <!-- Tombol Aksi Utama -->
                <div class="mt-6 flex flex-col sm:flex-row gap-3 w-full max-w-sm justify-center">
                    <button type="button" 
                            id="btn-start" 
                            onclick="startScanner()" 
                            class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 px-5 rounded-xl shadow-xs hover:shadow-md transition flex items-center justify-center gap-2 text-sm cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                        <span>Aktifkan Kamera Live</span>
                    </button>

                    <button type="button" 
                            onclick="document.getElementById('qr-input-file').click()" 
                            class="bg-white hover:bg-slate-50 border border-slate-300 text-slate-700 font-semibold py-2.5 px-4 rounded-xl shadow-2xs hover:border-slate-400 transition flex items-center justify-center gap-2 text-sm cursor-pointer">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <span>Ambil Foto (Kamera HP)</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Notifikasi Status Deteksi QR -->
        <div id="scan-status" class="hidden m-4 p-3.5 bg-emerald-50 border border-emerald-200 text-emerald-900 text-sm rounded-xl font-bold flex items-center gap-3 shadow-xs animate-pulse">
            <div class="w-7 h-7 rounded-full bg-emerald-600 text-white flex items-center justify-center shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <div>
                <span id="scan-status-text">QR Terdeteksi! Membuka halaman...</span>
            </div>
        </div>

        <!-- Metode 2: Unggah Foto dari Perangkat -->
        <div class="p-4 sm:p-5 bg-white border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-3 text-left w-full sm:w-auto">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200/60 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <div>
                    <h4 class="text-xs sm:text-sm font-bold text-gray-800">Unggah Gambar atau Foto QR</h4>
                    <p class="text-[11px] sm:text-xs text-gray-500">Pilih berkas foto stiker atau tangkapan layar dari galeri Anda</p>
                </div>
            </div>

            <button type="button" 
                    onclick="document.getElementById('qr-input-file').click()" 
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-slate-50 hover:bg-emerald-50 hover:text-emerald-800 text-slate-700 font-bold text-xs py-2.5 px-4 rounded-xl border border-slate-200 hover:border-emerald-300 transition shrink-0 cursor-pointer shadow-2xs">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                <span>Pilih File Gambar</span>
            </button>
            <input type="file" id="qr-input-file" accept="image/*" capture="environment" class="hidden" onchange="scanFromFile(event)">
        </div>

        <!-- Metode 3: Pencarian Manual Kode Berkas -->
        <div class="p-4 sm:p-5 bg-slate-50/70 border-t border-slate-100">
            <label class="block text-xs font-bold text-gray-700 mb-2">Atau Masukkan Kode Berkas Secara Manual:</label>
            <form action="{{ route('katalog.index') }}" method="GET" class="flex flex-col sm:flex-row gap-2">
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" 
                           name="search" 
                           placeholder="Contoh: JHT-2026-001 atau J0401B-00001" 
                           class="pl-10 pr-4 py-2.5 border-gray-300 text-sm text-gray-800 rounded-lg shadow-2xs focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 w-full bg-white font-medium">
                </div>
                <button type="submit" 
                        class="bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-2.5 px-5 text-sm rounded-lg shadow-2xs transition shrink-0 cursor-pointer flex items-center justify-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <span>Cari Berkas</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Panduan Pemindaian (3 Kolom Kartu Panduan) -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5">
        <div class="bg-white p-4 rounded-xl border border-slate-200/80 shadow-2xs flex items-start gap-3">
            <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 border border-amber-200/60 flex items-center justify-center shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
            </div>
            <div>
                <h5 class="text-xs font-bold text-gray-800">Pencahayaan Cukup</h5>
                <p class="text-[11px] text-gray-500 mt-0.5 leading-snug">Pastikan stiker QR tidak tertutup bayangan atau terkena silau lampu kuat.</p>
            </div>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200/80 shadow-2xs flex items-start gap-3">
            <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 border border-blue-200/60 flex items-center justify-center shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h4a1 1 0 010 2H6.414l5.293 5.293a1 1 0 01-1.414 1.414L5 6.414V8a1 1 0 01-2 0V4zm9 1a1 1 0 010-2h4a1 1 0 011 1v4a1 1 0 01-2 0V6.414l-5.293 5.293a1 1 0 01-1.414-1.414L13.586 5H12z"></path></svg>
            </div>
            <div>
                <h5 class="text-xs font-bold text-gray-800">Jarak 15&ndash;30 cm</h5>
                <p class="text-[11px] text-gray-500 mt-0.5 leading-snug">Arahkan kamera tegak lurus dan tahan sejenak hingga fokus terbentuk.</p>
            </div>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200/80 shadow-2xs flex items-start gap-3">
            <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 border border-emerald-200/60 flex items-center justify-center shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            </div>
            <div>
                <h5 class="text-xs font-bold text-gray-800">Deteksi Otomatis</h5>
                <p class="text-[11px] text-gray-500 mt-0.5 leading-snug">Sistem otomatis berbunyi beep dan langsung membuka berkas terkait.</p>
            </div>
        </div>
    </div>

</div>

<script>
    let html5QrCode = null;

    async function startScanner() {
        const placeholder = document.getElementById('scanner-placeholder');
        const reticle = document.getElementById('camera-reticle');
        const btnStop = document.getElementById('btn-stop');

        if (!window.Html5Qrcode) {
            if (window.tampilkanAlert) {
                tampilkanAlert('Memuat Modul', 'Modul pemindai kamera sedang dimuat, silakan coba 1 detik lagi.', 'info');
            } else if (window.Swal) {
                Swal.fire({
                    title: 'Memuat Modul',
                    text: 'Modul pemindai kamera sedang dimuat, silakan coba 1 detik lagi.',
                    icon: 'info',
                });
            } else {
                alert('Modul scanner sedang dimuat, silakan coba sesaat lagi.');
            }
            return;
        }

        if (!html5QrCode) {
            html5QrCode = new window.Html5Qrcode("reader");
        }

        const config = {
            fps: 15,
            qrbox: { width: 250, height: 250 },
            aspectRatio: 1.0
        };

        try {
            const devices = await window.Html5Qrcode.getCameras().catch(() => []);
            
            if (devices && devices.length > 0) {
                let selectedCamId = devices[0].id;
                for (const cam of devices) {
                    if (/back|rear|environment/i.test(cam.label)) {
                        selectedCamId = cam.id;
                        break;
                    }
                }
                await html5QrCode.start(selectedCamId, config, onScanSuccess, onScanFailure);
            } else {
                try {
                    await html5QrCode.start({ facingMode: "environment" }, config, onScanSuccess, onScanFailure);
                } catch {
                    await html5QrCode.start({ facingMode: "user" }, config, onScanSuccess, onScanFailure);
                }
            }

            placeholder.classList.add('hidden');
            if (reticle) reticle.classList.remove('hidden');
            btnStop.classList.remove('hidden');
        } catch (err) {
            console.error("Gagal membuka kamera:", err);
            const errMsg = err.message || String(err);

            if (!window.isSecureContext) {
                const pesanHttp = `
                    <div class="text-left text-xs text-slate-600 space-y-2.5">
                        <p>Browser (Chrome/Safari) secara otomatis membatasi streaming video kamera langsung pada alamat HTTP melalui IP lokal (<code>${window.location.origin}</code>).</p>
                        <div class="bg-amber-50 border border-amber-200 rounded-xl p-2.5 text-amber-900">
                            <p class="font-bold mb-1">Pilihan Cara Scan di HP:</p>
                            <ul class="list-disc pl-4 space-y-1">
                                <li><strong>Opsi Cepat:</strong> Klik tombol hijau di bawah untuk <strong>langsung memotret QR dengan kamera HP</strong> Anda.</li>
                                <li><strong>Opsi Live:</strong> Buka web lewat <code>http://localhost:8000</code> di laptop, atau aktifkan izin IP di <code>chrome://flags/#unsafely-treat-insecure-origin-as-secure</code> pada Chrome HP.</li>
                            </ul>
                        </div>
                    </div>
                `;

                if (window.AppDialog) {
                    AppDialog.confirm({
                        title: 'Izin Kamera Browser',
                        html: pesanHttp,
                        icon: 'info',
                        confirmText: 'Ambil Foto QR Sekarang',
                        cancelText: 'Tutup',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('qr-input-file').click();
                        }
                    });
                } else {
                    alert("Kamera live memerlukan HTTPS atau localhost. Silakan gunakan tombol ambil foto.");
                }
            } else {
                const message = `Tidak dapat membuka kamera (${errMsg}). Pastikan izin kamera browser telah diaktifkan dan kamera tidak sedang digunakan aplikasi lain.`;
                if (window.tampilkanAlert) {
                    tampilkanAlert('Kamera Tidak Tersedia', message, 'warning');
                } else {
                    alert(message);
                }
            }
        }
    }

    function stopScanner() {
        if (html5QrCode && html5QrCode.isScanning) {
            html5QrCode.stop().then(() => {
                document.getElementById('scanner-placeholder').classList.remove('hidden');
                const reticle = document.getElementById('camera-reticle');
                if (reticle) reticle.classList.add('hidden');
                document.getElementById('btn-stop').classList.add('hidden');
            }).catch(err => console.error("Gagal menghentikan scanner:", err));
        }
    }

    function playScanBeep() {
        try {
            const AudioCtx = window.AudioContext || window.webkitAudioContext;
            if (!AudioCtx) return;
            const audioCtx = new AudioCtx();
            const oscillator = audioCtx.createOscillator();
            const gainNode = audioCtx.createGain();

            oscillator.type = 'sine';
            oscillator.frequency.setValueAtTime(1046.5, audioCtx.currentTime); // Nada C6 jernih
            gainNode.gain.setValueAtTime(0.2, audioCtx.currentTime);
            gainNode.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.12);

            oscillator.connect(gainNode);
            gainNode.connect(audioCtx.destination);

            oscillator.start();
            oscillator.stop(audioCtx.currentTime + 0.12);

            if (navigator.vibrate) {
                navigator.vibrate(80);
            }
        } catch (e) {
            // Abaikan jika browser memblokir audio autoplay
        }
    }

    function onScanSuccess(decodedText) {
        playScanBeep();

        const statusBox = document.getElementById('scan-status');
        const statusText = document.getElementById('scan-status-text');

        statusBox.classList.remove('hidden');
        statusText.innerText = "Terdeteksi: " + decodedText;

        if (html5QrCode && html5QrCode.isScanning) {
            html5QrCode.pause();
        }

        setTimeout(() => {
            if (decodedText.startsWith('http://') || decodedText.startsWith('https://')) {
                window.location.href = decodedText;
            } else {
                window.location.href = "{{ route('katalog.index') }}?search=" + encodeURIComponent(decodedText);
            }
        }, 600);
    }

    function onScanFailure(error) {
        // Abaikan frame yang belum terdeteksi QR
    }

    function scanFromFile(event) {
        const file = event.target.files[0];
        if (!file) return;

        if (!html5QrCode) {
            html5QrCode = new window.Html5Qrcode("reader");
        }

        html5QrCode.scanFile(file, true)
            .then(decodedText => {
                onScanSuccess(decodedText);
            })
            .catch(err => {
                if (window.tampilkanAlert) {
                    tampilkanAlert('QR Tidak Terdeteksi', 'QR Code tidak terbaca dari gambar ini. Pastikan foto cukup jelas, tegak, dan tidak buram.', 'warning');
                } else {
                    alert("QR Code tidak terbaca dari gambar ini. Pastikan gambar cukup jelas.");
                }
            });
    }
</script>
