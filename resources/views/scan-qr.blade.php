<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pemindai QR Code - E-Arsip BPJS Ketenagakerjaan</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-100 min-h-screen text-slate-800 antialiased flex flex-col">

    <!-- Header Navigation -->
    <header class="bg-emerald-600 shadow-md text-white">
        <div class="max-w-4xl mx-auto px-4 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ auth()->check() ? route('dashboard') : route('katalog.index') }}" class="p-2 hover:bg-emerald-700 rounded-lg transition" title="Kembali">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <div>
                    <h1 class="font-bold text-lg leading-tight">Pemindai QR Code</h1>
                    <p class="text-xs text-emerald-100">Scan label stiker box atau berkas fisik</p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                @auth
                    <a href="{{ route('dashboard') }}" class="text-xs bg-emerald-700 hover:bg-emerald-800 text-white font-semibold py-1.5 px-3 rounded-lg transition">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('katalog.index') }}" class="text-xs bg-emerald-700 hover:bg-emerald-800 text-white font-semibold py-1.5 px-3 rounded-lg transition">
                        Katalog
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <main class="flex-1 max-w-xl w-full mx-auto p-4 sm:p-6 flex flex-col justify-center">
        
        <div class="bg-white rounded-2xl shadow-md border border-gray-200 overflow-hidden">
            
            <!-- Area Kamera / Scanner -->
            <div class="relative bg-black flex items-center justify-center min-h-[320px] sm:min-h-[380px] overflow-hidden">
                <div id="reader" class="w-full"></div>
                
                <!-- Status Overlay ketika belum izin -->
                <div id="scanner-placeholder" class="absolute inset-0 flex flex-col items-center justify-center p-6 text-center text-white bg-slate-900/90 z-10">
                    <div class="w-16 h-16 rounded-2xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center mb-4 border border-emerald-500/30">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                    </div>
                    <h3 class="font-bold text-base text-white">Siap Memindai QR Code</h3>
                    <p class="text-xs text-slate-300 mt-1 max-w-xs">Izinkan akses kamera browser Anda untuk mulai memindai label QR arsip.</p>
                    <button type="button" id="btn-start" onclick="startScanner()" class="mt-5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 px-6 rounded-xl shadow-lg transition flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        Aktifkan Kamera
                    </button>
                </div>
            </div>

            <!-- Kontrol Kamera & Status -->
            <div class="p-5 border-t border-gray-100 bg-white">
                <div id="scan-status" class="hidden mb-4 p-3 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs rounded-xl font-medium flex items-center gap-2 animate-pulse">
                    <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span id="scan-status-text">QR Terdeteksi! Membuka halaman...</span>
                </div>

                <div class="flex items-center justify-between gap-3">
                    <label class="text-xs font-semibold text-slate-600 flex items-center gap-1.5 cursor-pointer hover:text-emerald-700">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <span>Scan dari Galeri / File</span>
                        <input type="file" id="qr-input-file" accept="image/*" class="hidden" onchange="scanFromFile(event)">
                    </label>

                    <button type="button" id="btn-stop" onclick="stopScanner()" class="hidden text-xs text-rose-600 hover:text-rose-800 font-bold py-1 px-2.5 rounded transition">
                        Matikan Kamera
                    </button>
                </div>

                <!-- Opsi Pencarian Manual Fallback -->
                <div class="mt-5 pt-4 border-t border-gray-100">
                    <p class="text-xs text-slate-500 mb-2 font-medium">Atau masukkan kode berkas manual:</p>
                    <form action="{{ route('katalog.index') }}" method="GET" class="flex gap-2">
                        <input type="text" name="search" placeholder="Contoh: J0401B-00001" class="flex-1 text-sm border-gray-300 rounded-lg shadow-2xs focus:ring-emerald-500 focus:border-emerald-500">
                        <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-2 px-4 text-sm rounded-lg shadow-2xs transition">
                            Cari
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </main>

    <footer class="py-4 text-center text-xs text-slate-500">
        &copy; {{ date('Y') }} BPJS Ketenagakerjaan Kantor Cabang Rawamangun
    </footer>

    <script>
        let html5QrCode = null;

        function startScanner() {
            const placeholder = document.getElementById('scanner-placeholder');
            const btnStop = document.getElementById('btn-stop');

            if (!window.Html5Qrcode) {
                alert('Modul scanner sedang dimuat, silakan coba sesaat lagi.');
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

            html5QrCode.start(
                { facingMode: "environment" },
                config,
                onScanSuccess,
                onScanFailure
            ).then(() => {
                placeholder.classList.add('hidden');
                btnStop.classList.remove('hidden');
            }).catch(err => {
                console.error("Gagal membuka kamera:", err);
                alert("Tidak dapat mengakses kamera: " + (err.message || err));
            });
        }

        function stopScanner() {
            if (html5QrCode && html5QrCode.isScanning) {
                html5QrCode.stop().then(() => {
                    document.getElementById('scanner-placeholder').classList.remove('hidden');
                    document.getElementById('btn-stop').classList.add('hidden');
                }).catch(err => console.error("Gagal menghentikan scanner:", err));
            }
        }

        function onScanSuccess(decodedText) {
            const statusBox = document.getElementById('scan-status');
            const statusText = document.getElementById('scan-status-text');

            statusBox.classList.remove('hidden');
            statusText.innerText = "Terdeteksi: " + decodedText;

            // Hentikan pemindaian agar tidak terpicu ganda
            if (html5QrCode && html5QrCode.isScanning) {
                html5QrCode.pause();
            }

            setTimeout(() => {
                // Jika hasil scan berupa URL lengkap
                if (decodedText.startsWith('http://') || decodedText.startsWith('https://')) {
                    window.location.href = decodedText;
                } else {
                    // Jika berupa kode berkas langsung
                    window.location.href = "{{ route('katalog.index') }}?search=" + encodeURIComponent(decodedText);
                }
            }, 600);
        }

        function onScanFailure(error) {
            // Biarkan berulang mencari frame berikutnya
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
                    alert("QR Code tidak terbaca dari gambar ini. Pastikan gambar cukup jelas.");
                });
        }
    </script>
</body>
</html>

