<?php

namespace App\Http\Requests;

use App\Models\Arsip;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Dipakai bersama oleh store() dan update() agar aturan validasinya sama.
 * Kolom "status" sengaja TIDAK divalidasi/diterima dari form.
 */
class ArsipRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // akses sudah dijaga middleware 'auth' di routes
    }

    public function rules(): array
    {
        $arsip = $this->route('arsip'); // null saat store, model/id saat update
        $arsipId = $arsip instanceof Arsip ? $arsip->id : $arsip;

        return [
            // unique juga mengecek data di Riwayat Hapus (soft delete),
            // sama seperti unique index di database
            'kode_berkas' => [
                'required', 'string', 'max:100',
                Rule::unique('arsips', 'kode_berkas')->ignore($arsipId),
            ],
            'nama_berkas' => ['required', 'string', 'max:255'],
            'ruangan' => ['required', Rule::in(array_keys(Arsip::KAPASITAS_BARIS))],
            'rak' => ['required', 'string', 'max:50'],
            'baris' => [
                'required', 'integer', 'min:1',
                function (string $attribute, mixed $value, \Closure $fail) {
                    $maks = Arsip::KAPASITAS_BARIS[$this->input('ruangan')] ?? 0;
                    if ($maks > 0 && (int) $value > $maks) {
                        $fail("Baris maksimal untuk ruangan ini adalah {$maks}.");
                    }
                },
            ],
            'box' => ['required', 'string', 'max:50'],
            // Tanggal berkas boleh kosong; jika diisi tidak boleh melewati hari ini
            'tanggal_berkas' => ['nullable', 'date', 'before_or_equal:today'],
            'file_dokumen' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
            'hapus_file' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'kode_berkas.required' => 'Kode berkas wajib diisi.',
            'kode_berkas.unique' => 'Kode berkas sudah dipakai (cek juga Riwayat Hapus).',
            'nama_berkas.required' => 'Nama berkas wajib diisi.',
            'ruangan.required' => 'Ruangan wajib dipilih.',
            'ruangan.in' => 'Ruangan yang dipilih tidak valid.',
            'rak.required' => 'Rak wajib diisi.',
            'baris.required' => 'Baris wajib dipilih.',
            'baris.integer' => 'Baris harus berupa angka.',
            'box.required' => 'Nomor box wajib diisi.',
            'tanggal_berkas.date' => 'Tanggal berkas tidak valid.',
            'tanggal_berkas.before_or_equal' => 'Tanggal berkas tidak boleh melebihi hari ini.',
            'file_dokumen.mimes' => 'File dokumen harus berformat PDF, JPG, atau PNG.',
            'file_dokumen.max' => 'Ukuran file dokumen maksimal 10 MB.',
        ];
    }
}
