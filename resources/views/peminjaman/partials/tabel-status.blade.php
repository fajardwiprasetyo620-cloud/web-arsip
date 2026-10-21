<section class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="flex flex-wrap items-center justify-between gap-3 px-4 sm:px-6 py-4 border-b border-gray-200 {{ $headerClass }}">
        <div>
            <h3 class="text-base sm:text-lg font-bold text-gray-800">{{ $judul }}</h3>
            <p class="mt-0.5 text-xs sm:text-sm text-gray-600">{{ $deskripsi }}</p>
        </div>
        <span class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-bold {{ $countClass }}">
            {{ $peminjamans->total() }} berkas
        </span>
    </div>

    @if ($peminjamans->isEmpty())
        <div class="px-4 py-10 text-center text-sm text-gray-500">{{ $pesanKosong }}</div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full min-w-[900px] text-left text-sm">
                <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-500">
                    <tr>
                        <th class="px-4 py-3 font-bold">Peminjam</th>
                        <th class="px-4 py-3 font-bold">Tanggal Pinjam</th>
                        <th class="px-4 py-3 font-bold">Berkas</th>
                        <th class="px-4 py-3 font-bold">{{ $status === 'Dikembalikan' ? 'Tanggal Kembali' : 'Jatuh Tempo' }}</th>
                        <th class="px-4 py-3 font-bold">Status</th>
                        <th class="px-4 py-3 text-right font-bold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($peminjamans as $pinjam)
                        @php
                            $nama = $pinjam->nama_peminjam;
                            $isOverdue = $status === 'Terlambat';
                            $hariTerlambat = $isOverdue
                                ? now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($pinjam->tenggat_waktu)->startOfDay())
                                : 0;
                        @endphp
                        <tr class="align-top hover:bg-gray-50/70">
                            <td class="px-4 py-4">
                                <div class="font-bold text-gray-800">{{ $nama }}</div>
                                <div class="mt-1 text-xs text-blue-700">{{ $pinjam->divisi ?? '-' }}</div>
                            </td>
                            <td class="whitespace-nowrap px-4 py-4 text-gray-600">
                                {{ \Carbon\Carbon::parse($pinjam->tanggal_pinjam)->format('d M Y') }}
                            </td>
                            <td class="px-4 py-4">
                                <div class="font-semibold text-gray-800">{{ $pinjam->arsip->kode_berkas ?? 'Berkas Dihapus' }}</div>
                                <div class="mt-1 max-w-sm text-xs leading-relaxed text-gray-600">{{ $pinjam->arsip->nama_berkas ?? '-' }}</div>
                            </td>
                            <td class="whitespace-nowrap px-4 py-4 text-xs text-gray-600">
                                @if ($status === 'Dikembalikan')
                                    @if ($pinjam->tanggal_kembali)
                                        <div>{{ \Carbon\Carbon::parse($pinjam->tanggal_kembali)->format('d M Y') }}</div>
                                    @else
                                        <div>-</div>
                                    @endif
                                    @if ($pinjam->disetujui_oleh)
                                        <div class="mt-1 text-gray-400">oleh {{ $pinjam->disetujui_oleh }}</div>
                                    @endif
                                @elseif ($pinjam->tenggat_waktu)
                                    <div>{{ \Carbon\Carbon::parse($pinjam->tenggat_waktu)->format('d M Y') }}</div>
                                    @if ($isOverdue)
                                        <div class="mt-1 font-bold text-red-600">Terlambat {{ $hariTerlambat }} hari</div>
                                    @endif
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-4 py-4">
                                <span class="inline-flex whitespace-nowrap rounded-md border px-2.5 py-1 text-[11px] font-bold {{ $statusClass }}">
                                    {{ $status }}
                                </span>
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex flex-wrap items-center justify-end gap-2">
                                    <input type="checkbox" value="{{ $pinjam->id }}" aria-label="Pilih {{ $pinjam->arsip->kode_berkas ?? 'berkas' }}" class="cetak-checkbox h-4 w-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                                    <a href="{{ route('peminjaman.bap', ['nama' => $nama, 'tanggal' => $pinjam->tanggal_pinjam]) }}" target="_blank" rel="noopener" class="rounded-md border border-slate-300 bg-white px-2.5 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50" title="Cetak Berita Acara">BAP</a>
                                    @if ($isOverdue)
                                        @php
                                            $pesanWa = rawurlencode("Halo {$nama} ({$pinjam->divisi}), mengingatkan bahwa arsip fisik: "
                                                . ($pinjam->arsip->kode_berkas ?? '') . " - " . ($pinjam->arsip->nama_berkas ?? '')
                                                . " yang dipinjam pada " . \Carbon\Carbon::parse($pinjam->tanggal_pinjam)->format('d/m/Y')
                                                . " telah melewati batas tenggat pengembalian (" . \Carbon\Carbon::parse($pinjam->tenggat_waktu)->format('d/m/Y') . ")."
                                                . " Mohon untuk segera dikembalikan ke ruang arsip. Terima kasih.");
                                        @endphp
                                        <a href="https://api.whatsapp.com/send?text={{ $pesanWa }}" target="_blank" rel="noopener" class="rounded-md border border-emerald-300 bg-emerald-50 px-2.5 py-1.5 text-xs font-bold text-emerald-700 hover:bg-emerald-100" title="Kirim pengingat WhatsApp">WA</a>
                                    @endif
                                    @if ($status !== 'Dikembalikan')
                                        <form action="{{ route('peminjaman.update', $pinjam->id) }}" method="POST" class="m-0">
                                            @csrf
                                            @method('PUT')
                                            <button type="button" class="rounded-md bg-emerald-600 px-2.5 py-1.5 text-xs font-bold text-white hover:bg-emerald-700" onclick="konfirmasiAksi(event, 'Tandai berkas ini sudah dikembalikan ke ruang arsip?', this.closest('form'))">
                                                Kembalikan
                                            </button>
                                        </form>
                                    @else
                                        <span class="px-1 text-xs font-semibold text-gray-400">Selesai</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    @if ($peminjamans->hasPages())
        <div class="border-t border-gray-100 bg-white px-4 py-4 sm:px-6">
            {{ $peminjamans->links() }}
        </div>
    @endif
</section>
