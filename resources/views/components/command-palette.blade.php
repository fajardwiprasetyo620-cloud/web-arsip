<div x-data="{
        open: false,
        query: '',
        results: [],
        loading: false,
        selectedIndex: 0,
        actions: [
            { title: 'Tambah Arsip Baru', subtitle: 'Catat berkas fisik atau softcopy baru', icon: 'plus', url: '{{ route('arsip.create') }}', badge: 'Arsip' },
            { title: 'Catat Peminjaman', subtitle: 'Form peminjaman berkas untuk unit kerja', icon: 'share', url: '{{ route('peminjaman.create') }}', badge: 'Peminjaman' },
            { title: 'Pemindai QR Code', subtitle: 'Scan label berkas atau stiker box', icon: 'camera', url: '{{ route('qr.scan') }}', badge: 'Scanner' },
            { title: 'Download Backup Database', subtitle: 'Ekspor cadangan basis data (.sql)', icon: 'database', url: '{{ route('database.backup') }}', badge: 'Sistem' },
            { title: 'Log Aktivitas Sistem', subtitle: 'Riwayat audit trail transaksi admin', icon: 'clock', url: '{{ route('arsip.logs') }}', badge: 'Audit' },
            { title: 'Riwayat Arsip Terhapus', subtitle: 'Pemulihan atau penghapusan permanen', icon: 'trash', url: '{{ route('arsip.history') }}', badge: 'Recycle' }
        ],
        async search() {
            this.loading = true;
            try {
                const res = await fetch('{{ route('arsip.quickSearch') }}?q=' + encodeURIComponent(this.query));
                const data = await res.json();
                this.results = data.arsips || [];
            } catch (e) {
                console.error(e);
            } finally {
                this.loading = false;
                this.selectedIndex = 0;
            }
        },
        getAllItems() {
            if (this.query.trim() === '') {
                return [...this.actions, ...this.results];
            }
            return [...this.results, ...this.actions.filter(a => a.title.toLowerCase().includes(this.query.toLowerCase()))];
        },
        navigate(step) {
            const total = this.getAllItems().length;
            if (total === 0) return;
            this.selectedIndex = (this.selectedIndex + step + total) % total;
            this.$nextTick(() => {
                const el = document.getElementById('cmd-item-' + this.selectedIndex);
                if (el) el.scrollIntoView({ block: 'nearest' });
            });
        },
        selectCurrent() {
            const items = this.getAllItems();
            if (items[this.selectedIndex]) {
                window.location.href = items[this.selectedIndex].url;
            }
        }
    }"
    x-init="
        $watch('query', () => { search(); });
        search();
    "
    @open-command-palette.window="open = true; $nextTick(() => $refs.searchInput.focus())"
    @keydown.window.prevent.cmd.k="open = true; $nextTick(() => $refs.searchInput.focus())"
    @keydown.window.prevent.ctrl.k="open = true; $nextTick(() => $refs.searchInput.focus())"
    @keydown.escape.window="open = false"
    x-cloak
    class="relative z-50">

    <!-- Backdrop -->
    <div x-show="open" 
         x-transition:enter="ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-slate-950/50 backdrop-blur-xs transition-opacity"
         @click="open = false"></div>

    <!-- Modal Dialog -->
    <div x-show="open" 
         x-transition:enter="ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95 translate-y-4"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
         x-transition:leave-end="opacity-0 scale-95 translate-y-4"
         class="fixed inset-0 z-10 overflow-y-auto p-4 sm:p-6 md:p-20 flex justify-center items-start">
        
        <div class="w-full max-w-2xl bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden divide-y divide-slate-100"
             @click.away="open = false"
             @keydown.down.prevent="navigate(1)"
             @keydown.up.prevent="navigate(-1)"
             @keydown.enter.prevent="selectCurrent()">
            
            <!-- Input Bar -->
            <div class="relative flex items-center px-4 py-3.5 bg-white">
                <svg class="w-5 h-5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <input x-ref="searchInput"
                       x-model.debounce.200ms="query"
                       type="text" 
                       placeholder="Cari arsip, kode klaim, atau menu (ketik 'tambah', 'pinjam', dsb)..." 
                       class="w-full pl-3 pr-10 py-1 text-slate-800 placeholder-slate-400 text-sm border-0 focus:ring-0 focus:outline-hidden">
                
                <div class="flex items-center gap-1">
                    <span x-show="loading" class="animate-spin w-4 h-4 border-2 border-emerald-600 border-t-transparent rounded-full mr-1"></span>
                    <kbd class="hidden sm:inline-block px-1.5 py-0.5 text-[10px] font-semibold text-slate-400 bg-slate-100 border border-slate-200 rounded-md">ESC</kbd>
                </div>
            </div>

            <!-- List of Results -->
            <div class="max-h-96 overflow-y-auto p-2 space-y-1">
                
                <!-- Section Data Arsip -->
                <template x-if="results.length > 0">
                    <div>
                        <div class="px-3 py-1.5 text-[11px] font-bold uppercase tracking-wider text-slate-400">
                            Berkas Arsip Ditemukan (<span x-text="results.length"></span>)
                        </div>
                        <template x-for="(item, idx) in results" :key="'arsip-' + item.id">
                            <a :href="item.url" 
                               :id="'cmd-item-' + (query.trim() === '' ? actions.length + idx : idx)"
                               @mouseenter="selectedIndex = (query.trim() === '' ? actions.length + idx : idx)"
                               :class="{ 'bg-emerald-50/80 text-emerald-900': selectedIndex === (query.trim() === '' ? actions.length + idx : idx) }"
                               class="flex items-center justify-between px-3 py-2 rounded-xl text-xs transition cursor-pointer hover:bg-emerald-50/80">
                                <div class="flex items-center gap-3 truncate">
                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0"
                                         :class="item.status === 'Tersedia' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700'">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    </div>
                                    <div class="truncate">
                                        <div class="flex items-center gap-2">
                                            <span class="font-bold text-slate-800" x-text="item.kode"></span>
                                            <span x-show="item.has_file" class="px-1.5 py-0.2 text-[10px] font-semibold bg-blue-100 text-blue-700 rounded">PDF</span>
                                        </div>
                                        <p class="text-slate-500 truncate" x-text="item.nama"></p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 shrink-0">
                                    <span class="hidden md:inline-block text-[11px] text-slate-400" x-text="item.lokasi"></span>
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold"
                                          :class="item.status === 'Tersedia' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700'"
                                          x-text="item.status"></span>
                                </div>
                            </a>
                        </template>
                    </div>
                </template>

                <!-- Section Navigasi & Pintasan Menu -->
                <div>
                    <div class="px-3 py-1.5 text-[11px] font-bold uppercase tracking-wider text-slate-400">
                        Pintasan Menu Cepat
                    </div>
                    <template x-for="(action, idx) in actions" :key="'action-' + idx">
                        <a :href="action.url"
                           :id="'cmd-item-' + (query.trim() === '' ? idx : results.length + idx)"
                           @mouseenter="selectedIndex = (query.trim() === '' ? idx : results.length + idx)"
                           :class="{ 'bg-emerald-50/80 text-emerald-900': selectedIndex === (query.trim() === '' ? idx : results.length + idx) }"
                           class="flex items-center justify-between px-3 py-2 rounded-xl text-xs transition cursor-pointer hover:bg-emerald-50/80">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                </div>
                                <div>
                                    <div class="font-bold text-slate-800" x-text="action.title"></div>
                                    <p class="text-slate-400 text-[11px]" x-text="action.subtitle"></p>
                                </div>
                            </div>
                            <span class="text-[10px] font-semibold text-slate-400 bg-slate-100 px-2 py-0.5 rounded" x-text="action.badge"></span>
                        </a>
                    </template>
                </div>

                <!-- Empty State -->
                <template x-if="results.length === 0 && query.trim() !== ''">
                    <div class="py-8 text-center text-slate-400 text-xs">
                        Tidak ada arsip yang cocok dengan kata kunci "<span class="font-bold text-slate-600" x-text="query"></span>".
                    </div>
                </template>

            </div>

            <!-- Footer Hint -->
            <div class="px-4 py-2.5 bg-slate-50 flex items-center justify-between text-[11px] text-slate-400">
                <div class="flex items-center gap-3">
                    <span>Gunakan <kbd class="px-1 py-0.5 bg-white border border-slate-200 rounded font-mono text-[10px] text-slate-600">&uarr;</kbd> <kbd class="px-1 py-0.5 bg-white border border-slate-200 rounded font-mono text-[10px] text-slate-600">&darr;</kbd> untuk navigasi</span>
                    <span><kbd class="px-1 py-0.5 bg-white border border-slate-200 rounded font-mono text-[10px] text-slate-600">&crarr;</kbd> untuk buka</span>
                </div>
                <span>E-Arsip BPJS Ketenagakerjaan</span>
            </div>

        </div>
    </div>
</div>

