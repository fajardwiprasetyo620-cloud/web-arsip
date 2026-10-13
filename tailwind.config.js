import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',

    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                // "Plus Jakarta Sans" dipilih karena ini sistem milik instansi
                // di Jakarta -- lebih punya identitas dibanding font bawaan.
                // Dipasang lewat npm (self-hosted), tidak lagi dari CDN.
                sans: ['Plus Jakarta Sans Variable', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    // Plugin ini sebelumnya sudah di-import tapi tidak pernah didaftarkan,
    // jadi styling bawaan untuk checkbox/radio/select belum sepenuhnya aktif.
    plugins: [forms],
};
