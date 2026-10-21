{{-- Pesan sukses/peringatan/error ditampilkan sebagai toast (muncul lalu hilang
     sendiri), pakai SweetAlert2 yang sudah dimuat lewat resources/js/app.js.
     Error validasi (per kolom form) tetap ditampilkan sebagai kotak statis,
     supaya tidak hilang sebelum pengguna sempat memperbaiki form. Pakai: <x-flash /> --}}
@if (session('success') || session('warning') || session('error') || session('info'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3500,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                },
            });

            const messages = [];
            @if (session('success'))
                messages.push({ icon: 'success', title: @json(session('success')) });
            @endif
            @if (session('warning'))
                messages.push({ icon: 'warning', title: @json(session('warning')) });
            @endif
            @if (session('info'))
                messages.push({ icon: 'info', title: @json(session('info')) });
            @endif
            @if (session('error'))
                messages.push({ icon: 'error', title: @json(session('error')) });
            @endif

            messages.forEach((msg, idx) => {
                setTimeout(() => {
                    Toast.fire(msg);
                }, idx * 3700);
            });
        });
    </script>
@endif

@if ($errors->any())
    <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700" role="alert">
        <strong>Terjadi kesalahan:</strong>
        <ul class="mt-1 list-disc pl-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (session('import_errors.total', 0) > 0)
    <div class="mb-6 rounded-xl border border-amber-300 bg-amber-50/80 p-4 text-sm text-amber-900 shadow-2xs" role="alert" x-data="{ open: true }">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2 font-bold">
                <svg class="h-5 w-5 text-amber-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <span>Catatan Hasil Impor Excel ({{ session('import_errors.total') }} Baris):</span>
            </div>
            <button type="button" @click="open = !open" class="text-xs font-semibold text-amber-800 underline hover:text-amber-950">
                <span x-text="open ? 'Sembunyikan' : 'Lihat Rincian'"></span>
            </button>
        </div>
        <div x-show="open" class="mt-3 max-h-60 overflow-y-auto rounded-lg border border-amber-200 bg-white p-3 text-xs divide-y divide-amber-100">
            @foreach (session('import_errors.items', []) as $catatan)
                <div class="py-1.5 flex items-start gap-1.5 font-medium text-slate-700">
                    <span class="text-amber-600 font-bold shrink-0">•</span>
                    <span>{{ $catatan }}</span>
                </div>
            @endforeach
            @if (session('import_errors.total') > count(session('import_errors.items', [])))
                <p class="pt-2 font-semibold text-amber-800">
                    Rincian dibatasi hingga {{ count(session('import_errors.items', [])) }} catatan pertama.
                </p>
            @endif
        </div>
    </div>
@endif
