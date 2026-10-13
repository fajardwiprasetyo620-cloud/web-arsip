{{-- Pesan sukses/peringatan/error ditampilkan sebagai toast (muncul lalu hilang
     sendiri), pakai SweetAlert2 yang sudah dimuat lewat resources/js/app.js.
     Error validasi (per kolom form) tetap ditampilkan sebagai kotak statis,
     supaya tidak hilang sebelum pengguna sempat memperbaiki form. Pakai: <x-flash /> --}}
@if (session('success') || session('warning') || session('error'))
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

            @if (session('success'))
                Toast.fire({ icon: 'success', title: @json(session('success')) });
            @endif
            @if (session('warning'))
                Toast.fire({ icon: 'warning', title: @json(session('warning')) });
            @endif
            @if (session('error'))
                Toast.fire({ icon: 'error', title: @json(session('error')) });
            @endif
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
