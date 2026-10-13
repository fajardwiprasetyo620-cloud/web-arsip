import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Chart.js, SweetAlert2, dan Tom Select dipasang lewat npm (bukan CDN), supaya
// dashboard, konfirmasi hapus, dan form peminjaman tetap berfungsi walau koneksi
// internet kantor sedang bermasalah.
// "chart.js/auto" otomatis mendaftarkan semua jenis grafik (donat, batang, dst).
import Chart from 'chart.js/auto';
window.Chart = Chart;

import Swal from 'sweetalert2';
window.Swal = Swal;

// Build "complete" menyertakan plugin remove_button bawaan, sama seperti versi
// CDN (tom-select.complete.min.js) yang dipakai sebelumnya di form peminjaman.
import TomSelect from 'tom-select/dist/js/tom-select.complete.js';
window.TomSelect = TomSelect;
