import Swal from 'sweetalert2';

/**
 * Konfigurasi tema dialog modal SweetAlert2 terpusat resmi E-Arsip BPJS Ketenagakerjaan.
 * Desain modern dengan:
 * 1. Squircle icon badge pastel di posisi paling atas
 * 2. Judul tebal elegan (slate-900) di bawah ikon
 * 3. Teks deskripsi informatif (slate-500)
 * 4. Tombol aksi bergradien kontras tinggi (Emerald BPJS / Rose Bahaya) dan Slate untuk Batal
 */

function getIconSvg(type) {
    switch (type) {
        case 'question':
            return `
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            `;
        case 'warning':
            return `
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            `;
        case 'danger':
        case 'error':
            return `
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
            `;
        case 'success':
            return `
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M5 13l4 4L19 7"></path>
                </svg>
            `;
        case 'info':
            return `
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            `;
        case 'loading':
            return `
                <svg class="w-8 h-8 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
            `;
        default:
            return '';
    }
}

function renderModalContent({ iconType, title, text, html }) {
    const iconSvg = getIconSvg(iconType);
    const bodyContent = html !== undefined ? html : `<p class="max-w-xs mx-auto text-slate-500">${text || ''}</p>`;

    return `
        <div class="bpjs-modal-card">
            <div class="bpjs-modal-icon bpjs-icon-${iconType}">
                ${iconSvg}
            </div>
            ${title ? `<h3 class="bpjs-modal-title">${title}</h3>` : ''}
            <div class="bpjs-modal-body">
                ${bodyContent}
            </div>
        </div>
    `;
}

export const AppDialog = {
    /**
     * Dialog konfirmasi umum (misal: pengembalian arsip, verifikasi data)
     */
    confirm({
        title = 'Apakah Anda Yakin?',
        text = '',
        html = undefined,
        icon = 'question',
        confirmText = 'Ya, Lanjutkan',
        cancelText = 'Batal',
        isDanger = false,
    } = {}) {
        const iconType = isDanger ? 'danger' : icon;
        const modalHtml = renderModalContent({
            iconType,
            title,
            text,
            html,
        });

        return Swal.fire({
            html: modalHtml,
            showCancelButton: true,
            confirmButtonText: confirmText,
            cancelButtonText: cancelText,
            reverseButtons: true,
            buttonsStyling: false,
            focusConfirm: false,
            customClass: {
                popup: 'bpjs-swal-popup',
                confirmButton: isDanger ? 'bpjs-btn-danger' : 'bpjs-btn-primary',
                cancelButton: 'bpjs-btn-secondary',
            },
        });
    },

    /**
     * Dialog konfirmasi hapus lunak (soft-delete / pindah ke riwayat)
     */
    confirmDelete({
        title = 'Hapus Massal Arsip?',
        text = 'Apakah Anda yakin ingin memindahkan arsip terpilih ke Riwayat Terhapus?',
        confirmText = 'Ya, Hapus Arsip',
        cancelText = 'Batal',
    } = {}) {
        return this.confirm({
            title,
            text,
            icon: 'danger',
            confirmText,
            cancelText,
            isDanger: true,
        });
    },

    /**
     * Dialog konfirmasi hapus permanen (hard-delete)
     */
    confirmPermanentDelete({
        title = 'Hapus Permanen?',
        text = 'Peringatan: Berkas ini beserta file digital fisiknya akan dihapus selamanya dari sistem dan tidak dapat dipulihkan!',
        confirmText = 'Ya, Hapus Selamanya',
        cancelText = 'Batalkan',
    } = {}) {
        return this.confirm({
            title,
            text,
            icon: 'danger',
            confirmText,
            cancelText,
            isDanger: true,
        });
    },

    /**
     * Dialog konfirmasi pemulihan arsip (restore)
     */
    confirmRestore({
        title = 'Pulihkan Arsip Ini?',
        text = 'Arsip akan dikembalikan ke daftar arsip aktif dan dapat dicari kembali oleh unit kerja.',
        confirmText = 'Ya, Pulihkan',
        cancelText = 'Batal',
    } = {}) {
        return this.confirm({
            title,
            text,
            icon: 'question',
            confirmText,
            cancelText,
            isDanger: false,
        });
    },

    /**
     * Dialog pesan informasi / peringatan pengganti alert() browser
     */
    alert(title, text, icon = 'warning', confirmText = 'Mengerti') {
        const modalHtml = renderModalContent({
            iconType: icon,
            title,
            text,
        });

        return Swal.fire({
            html: modalHtml,
            showCancelButton: false,
            confirmButtonText: confirmText,
            buttonsStyling: false,
            focusConfirm: false,
            customClass: {
                popup: 'bpjs-swal-popup',
                confirmButton: 'bpjs-btn-primary',
            },
        });
    },

    /**
     * Modal Loading indikator saat proses berjalan (mencegah klik ganda)
     */
    loading(title = 'Memproses Permintaan...', text = 'Mohon tunggu, sistem sedang memproses data Anda...') {
        const modalHtml = renderModalContent({
            iconType: 'loading',
            title,
            text,
        });

        return Swal.fire({
            html: modalHtml,
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            buttonsStyling: false,
            customClass: {
                popup: 'bpjs-swal-popup',
            },
        });
    },

    /**
     * Tutup popup aktif
     */
    close() {
        Swal.close();
    },

    /**
     * Toast notification kecil di pojok layar
     */
    toast({ title, icon = 'success', timer = 3500 } = {}) {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer,
            timerProgressBar: true,
            customClass: {
                popup: 'rounded-xl shadow-xl border border-slate-100 font-sans text-xs',
            },
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            },
        });
        return Toast.fire({ icon, title });
    },
};

/**
 * Helper global yang langsung dapat dipanggil dari atribut onclick di Blade HTML:
 */

// 1. Konfirmasi Hapus Arsip Biasa
window.konfirmasiHapus = function (event, pesan, formElement, judul = 'Hapus Arsip Ini?') {
    if (event) event.preventDefault();
    const form = formElement || (event && event.target ? event.target.closest('form') : null);

    AppDialog.confirmDelete({
        title: judul,
        text: pesan || 'Arsip ini akan dipindahkan ke Riwayat Terhapus.',
        confirmText: 'Ya, Hapus Arsip',
    }).then((result) => {
        if (result.isConfirmed && form) {
            AppDialog.loading('Menghapus Data...', 'Memindahkan arsip ke riwayat terhapus...');
            form.submit();
        }
    });
    return false;
};

// 2. Konfirmasi Hapus Permanen
window.konfirmasiHapusPermanen = function (event, pesan, formElement) {
    if (event) event.preventDefault();
    const form = formElement || (event && event.target ? event.target.closest('form') : null);

    AppDialog.confirmPermanentDelete({
        title: 'Hapus Permanen?',
        text: pesan || 'Peringatan: Berkas ini dan dokumen digitalnya akan dihapus selamanya!',
        confirmText: 'Ya, Hapus Selamanya',
    }).then((result) => {
        if (result.isConfirmed && form) {
            AppDialog.loading('Menghapus Permanen...', 'Menghapus data dan berkas fisik dari server...');
            form.submit();
        }
    });
    return false;
};

// 3. Konfirmasi Pulihkan Arsip
window.konfirmasiPulihkan = function (event, pesan, formElement) {
    if (event) event.preventDefault();
    const form = formElement || (event && event.target ? event.target.closest('form') : null);

    AppDialog.confirmRestore({
        title: 'Pulihkan Arsip?',
        text: pesan || 'Arsip akan dikembalikan ke daftar arsip aktif.',
        confirmText: 'Ya, Pulihkan',
    }).then((result) => {
        if (result.isConfirmed && form) {
            AppDialog.loading('Memulihkan...', 'Mengembalikan arsip ke daftar aktif...');
            form.submit();
        }
    });
    return false;
};

// 4. Konfirmasi Tindakan Transaksional / Pengembalian Berkas
window.konfirmasiAksi = function (event, pesan, formElement, judul = 'Apakah Anda Yakin?', confirmText = 'Ya, Lanjutkan') {
    if (event) event.preventDefault();
    const form = formElement || (event && event.target ? event.target.closest('form') : null);

    AppDialog.confirm({
        title: judul,
        text: pesan,
        confirmText,
    }).then((result) => {
        if (result.isConfirmed && form) {
            AppDialog.loading('Memproses...', 'Menyimpan perubahan data...');
            form.submit();
        }
    });
    return false;
};

// 5. Konfirmasi Logout
window.konfirmasiLogout = function (event, formElement) {
    if (event) event.preventDefault();
    const form = formElement || (event && event.target ? event.target.closest('form') : null);

    AppDialog.confirm({
        title: 'Konfirmasi Keluar',
        text: 'Apakah Anda yakin ingin mengakhiri sesi dan keluar dari sistem E-Arsip BPJS Ketenagakerjaan?',
        confirmText: 'Ya, Keluar',
        cancelText: 'Tetap di Sini',
    }).then((result) => {
        if (result.isConfirmed && form) {
            AppDialog.loading('Mengeluarkan Sesi...', 'Sampai jumpa kembali...');
            form.submit();
        }
    });
    return false;
};

// 6. Alert Pengganti alert() Bawaan
window.tampilkanAlert = function (judul, pesan, ikon = 'warning') {
    return AppDialog.alert(judul, pesan, ikon);
};

window.AppDialog = AppDialog;
