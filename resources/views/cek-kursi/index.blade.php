<x-admin-layout>
    <x-slot name="header">
        <x-ui.breadcrumb.breadcrumb>
            <x-ui.breadcrumb.list class="text-xs">
                <x-ui.breadcrumb.item>
                    <x-ui.breadcrumb.link href="{{ route('dashboard') }}">
                        Home
                    </x-ui.breadcrumb.link>
                </x-ui.breadcrumb.item>
                <x-ui.breadcrumb.separator>
                    <x-lucide-chevron-right class="w-3.5 h-3.5" />
                </x-ui.breadcrumb.separator>
                <x-ui.breadcrumb.item>
                    <x-ui.breadcrumb.page>
                        Cek Kursi
                    </x-ui.breadcrumb.page>
                </x-ui.breadcrumb.item>
            </x-ui.breadcrumb.list>
        </x-ui.breadcrumb.breadcrumb>
    </x-slot>

    <div class="py-6 px-4 sm:px-6 lg:px-8" x-data="kursiManager()">
        <div class="max-w-7xl mx-auto">
            <!-- Jadwal List Container -->
            <div id="jadwal-container" class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                @forelse($jadwals as $jadwal)
                    <div class="bg-card border border-border rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow jadwal-item">
                        <div class="p-4">
                            <!-- Top Section: Route & Time -->
                            <div class="flex flex-col gap-3 mb-3 pb-3 border-b border-border">
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-primary/10 text-primary rounded text-xs font-semibold">
                                        <x-lucide-clock class="w-3 h-3" />
                                        {{ $jadwal->jam_berangkat->format('H:i') }}
                                    </span>
                                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 rounded text-xs font-semibold">
                                        <x-lucide-calendar class="w-3 h-3" />
                                        {{ $jadwal->tanggal_berangkat->format('d M Y') }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-2 text-sm">
                                    <span class="font-semibold text-foreground">{{ $jadwal->rute->asalTerminal->nama_terminal }}</span>
                                    <x-lucide-arrow-right class="w-4 h-4 text-muted-foreground" />
                                    <span class="font-semibold text-foreground">{{ $jadwal->rute->tujuanTerminal->nama_terminal }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <p class="text-xs text-muted-foreground">Status</p>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold {{ $jadwal->status === 'aktif' ? 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400' : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-400' }}">
                                        {{ ucfirst($jadwal->status) }}
                                    </span>
                                </div>
                            </div>

                            <!-- Middle Section: Bus & Crew Info -->
                            <div class="space-y-2 mb-3 pb-3 border-b border-border">
                                <div class="text-sm">
                                    <p class="text-xs text-muted-foreground uppercase tracking-wider">Bus</p>
                                    <p class="font-semibold text-foreground">{{ $jadwal->bus->nama }}</p>
                                    <p class="text-xs text-muted-foreground">{{ $jadwal->bus->plat_nomor }}</p>
                                </div>
                                <div class="text-sm">
                                    <p class="text-xs text-muted-foreground uppercase tracking-wider">Sopir</p>
                                    <p class="font-semibold text-foreground">{{ $jadwal->sopir->user->name }}</p>
                                </div>
                                <div class="text-sm">
                                    <p class="text-xs text-muted-foreground uppercase tracking-wider">Kondektur</p>
                                    <p class="font-semibold text-foreground">{{ $jadwal->conductor?->user->name ?? 'Belum ditugaskan' }}</p>
                                </div>
                            </div>

                            <!-- Bottom Section: Classes Info & Action -->
                            <div class="space-y-3">
                                <div class="flex flex-wrap gap-2">
                                    @forelse($jadwal->jadwalKelasBus as $kelasBus)
                                        <div class="px-2 py-1 bg-accent/50 border border-border rounded-lg text-xs">
                                            <p class="text-muted-foreground">{{ $kelasBus->kelasBus->nama_kelas }}</p>
                                            <p class="font-semibold text-foreground">
                                                Rp {{ number_format($kelasBus->harga, 0, ',', '.') }}
                                            </p>
                                        </div>
                                    @empty
                                        <div class="text-xs text-muted-foreground italic">Belum ada kelas bus</div>
                                    @endforelse
                                </div>
                                <div class="flex gap-2 flex-wrap">
                                    @foreach($jadwal->jadwalKelasBus as $kelasBus)
                                        <button
                                            type="button"
                                            @click="loadKursi({{ $kelasBus->id }}, '{{ $kelasBus->kelasBus->nama_kelas }}', {{ $kelasBus->harga }})"
                                            class="px-3 py-1.5 bg-primary text-primary-foreground rounded-lg hover:bg-primary/90 transition-colors font-medium whitespace-nowrap flex items-center gap-2 text-xs">
                                            <x-lucide-eye class="w-3 h-3" />
                                            Lihat {{ $kelasBus->kelasBus->nama_kelas }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-card border border-border rounded-lg shadow-sm overflow-hidden">
                        <div class="p-12 text-center">
                            <div class="flex justify-center mb-6">
                                <div class="p-3 bg-accent/50 rounded-full">
                                    <x-lucide-inbox class="w-12 h-12 text-muted-foreground" />
                                </div>
                            </div>
                            <h3 class="text-lg font-semibold text-foreground mb-2">Tidak ada jadwal</h3>
                            <p class="text-muted-foreground max-w-md mx-auto">
                                Belum ada jadwal untuk terminal Anda. Silakan buat jadwal terlebih dahulu atau hubungi administrator.
                            </p>
                        </div>
                    </div>
                @endempty
            </div>

            <!-- Pagination Links (Hidden) -->
            @if($jadwals->hasPages())
                <div id="pagination-links" class="mt-6 hidden">
                    {{ $jadwals->links() }}
                </div>
            @endif

            <!-- Loading Indicator -->
            <div id="loading-indicator" class="mt-8 py-8 text-center hidden">
                <div class="bg-card border border-border rounded-lg shadow-sm overflow-hidden p-6">
                    <div class="inline-flex items-center gap-3">
                        <svg class="animate-spin h-6 w-6 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <div class="text-left">
                            <p class="font-semibold text-foreground">Memuat jadwal...</p>
                            <p class="text-xs text-muted-foreground">Tunggu sebentar</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- No More Data Message -->
            <div id="no-more-data" class="mt-8 py-8 text-center hidden">
                <div class="bg-card border border-border rounded-lg shadow-sm overflow-hidden p-8">
                    <div class="flex justify-center mb-4">
                        <div class="p-3 bg-accent/50 rounded-full">
                            <x-lucide-check-circle class="w-6 h-6 text-green-600 dark:text-green-400" />
                        </div>
                    </div>
                    <h3 class="font-semibold text-foreground mb-1">Semua jadwal sudah dimuat</h3>
                    <p class="text-muted-foreground text-sm">Tidak ada jadwal lagi untuk ditampilkan</p>
                </div>
            </div>
        </div>

        <!-- Modal untuk Lihat Kursi -->
        <template x-teleport="body">
            <div x-show="showKursiModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" @keydown.escape.window="closeModal">
                <!-- Overlay -->
                <div x-show="showKursiModal"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     @click="closeModal"
                     class="fixed inset-0 bg-black/50 backdrop-blur-sm">
                </div>

                <!-- Modal Content -->
                <div class="flex min-h-full items-center justify-center p-4">
                    <div x-show="showKursiModal"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         @click.stop
                         class="relative w-full max-w-xl bg-card rounded-lg shadow-lg border border-border p-6">

                        <!-- Header -->
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h3 class="text-xl font-semibold text-foreground">Ketersediaan Kursi</h3>
                                <p class="text-sm text-muted-foreground mt-1">Cek status kursi untuk kelas ini</p>
                            </div>
                            <button @click="closeModal" class="p-1 hover:bg-muted rounded-lg transition-colors">
                                <x-lucide-x class="w-5 h-5 text-muted-foreground" />
                            </button>
                        </div>

                        <!-- Loading State -->
                        <div x-show="isLoadingKursi" class="flex items-center justify-center py-8">
                            <div class="flex items-center gap-3">
                                <svg class="animate-spin h-6 w-6 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span class="text-foreground font-medium">Memuat data kursi...</span>
                            </div>
                        </div>

                        <!-- Content -->
                        <div x-show="!isLoadingKursi && kursiData" class="space-y-6">
                            <!-- Jadwal Info -->
                            <div class="bg-accent/50 border border-border rounded-lg p-4 space-y-3">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-xs text-muted-foreground mb-1">Bus</p>
                                        <p class="font-semibold text-foreground" x-text="kursiData?.jadwal?.bus_nama"></p>
                                        <p class="text-xs text-muted-foreground" x-text="kursiData?.jadwal?.bus_plat"></p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-muted-foreground mb-1">Rute</p>
                                        <p class="font-semibold text-foreground">
                                            <span x-text="kursiData?.jadwal?.asal_terminal"></span>
                                            <x-lucide-arrow-right class="w-3 h-3 inline-block mx-1" />
                                            <span x-text="kursiData?.jadwal?.tujuan_terminal"></span>
                                        </p>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-4 pt-3 border-t border-border">
                                    <div>
                                        <p class="text-xs text-muted-foreground mb-1">Tanggal & Waktu</p>
                                        <p class="font-semibold text-foreground">
                                            <span x-text="kursiData?.jadwal?.tanggal_berangkat"></span>
                                            <span class="text-xs text-muted-foreground ml-2" x-text="kursiData?.jadwal?.jam_berangkat"></span>
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-muted-foreground mb-1">Kelas & Harga</p>
                                        <p class="font-semibold text-foreground">
                                            <span x-text="kursiData?.kelas?.nama_kelas"></span>
                                            <span class="text-primary ml-2">Rp <span x-text="formatCurrency(kursiData?.kelas?.harga)"></span></span>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Kursi Summary -->
                            <div class="grid grid-cols-3 gap-4">
                                <div class="bg-green-100 dark:bg-green-900/30 border border-green-200 dark:border-green-800 rounded-lg p-4 text-center">
                                    <p class="text-xs text-muted-foreground mb-1">TERSEDIA</p>
                                    <p class="text-2xl font-bold text-green-700 dark:text-green-400" x-text="kursiData?.kursi_summary?.available"></p>
                                </div>
                                <div class="bg-red-100 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-lg p-4 text-center">
                                    <p class="text-xs text-muted-foreground mb-1">DIPESAN</p>
                                    <p class="text-2xl font-bold text-red-700 dark:text-red-400" x-text="kursiData?.kursi_summary?.booked"></p>
                                </div>
                                <div class="bg-accent border border-border rounded-lg p-4 text-center">
                                    <p class="text-xs text-muted-foreground mb-1">TOTAL</p>
                                    <p class="text-2xl font-bold text-foreground" x-text="kursiData?.kursi_summary?.total"></p>
                                </div>
                            </div>

                            <!-- Kursi Grid - 2 Kolom Layout -->
                            <div>
                                <p class="text-sm font-semibold text-foreground mb-3">Layout Kursi (2 Kolom):</p>
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                    <!-- Kolom Kiri -->
                                    <div class="space-y-2 max-h-96 overflow-y-auto p-4 bg-accent/50 rounded-lg border border-border">
                                        <p class="text-xs font-medium text-muted-foreground mb-3 sticky top-0 bg-accent/50">Sisi Kiri</p>
                                        <template x-for="(kursi, index) in groupKursiLeft(kursiData?.kursi)" :key="index">
                                            <div class="flex items-center justify-center gap-3">
                                                <template x-for="k in kursi" :key="k.id">
                                                    <div :class="[
                                                        'w-14 h-14 flex items-center justify-center rounded-lg font-bold text-sm transition-colors cursor-pointer hover:shadow-md',
                                                        k.status === 'booked'
                                                            ? 'bg-red-200 dark:bg-red-900/50 text-red-700 dark:text-red-400'
                                                            : 'bg-green-200 dark:bg-green-900/50 text-green-700 dark:text-green-400'
                                                    ]"
                                                    :title="k.status === 'booked' ? 'Kursi Dipesan' : 'Kursi Tersedia'">
                                                        <span x-text="k.nomor_kursi"></span>
                                                    </div>
                                                </template>
                                            </div>
                                        </template>
                                    </div>

                                    <!-- Kolom Kanan -->
                                    <div class="space-y-2 max-h-96 overflow-y-auto p-4 bg-accent/50 rounded-lg border border-border">
                                        <p class="text-xs font-medium text-muted-foreground mb-3 sticky top-0 bg-accent/50">Sisi Kanan</p>
                                        <template x-for="(kursi, index) in groupKursiRight(kursiData?.kursi)" :key="index">
                                            <div class="flex items-center justify-center gap-3">
                                                <template x-for="k in kursi" :key="k.id">
                                                    <div :class="[
                                                        'w-14 h-14 flex items-center justify-center rounded-lg font-bold text-sm transition-colors cursor-pointer hover:shadow-md',
                                                        k.status === 'booked'
                                                            ? 'bg-red-200 dark:bg-red-900/50 text-red-700 dark:text-red-400'
                                                            : 'bg-green-200 dark:bg-green-900/50 text-green-700 dark:text-green-400'
                                                    ]"
                                                    :title="k.status === 'booked' ? 'Kursi Dipesan' : 'Kursi Tersedia'">
                                                        <span x-text="k.nomor_kursi"></span>
                                                    </div>
                                                </template>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                                <div class="flex gap-4 mt-3 text-xs">
                                    <div class="flex items-center gap-2">
                                        <div class="w-4 h-4 bg-green-200 dark:bg-green-900/50 rounded"></div>
                                        <span class="text-muted-foreground">Tersedia</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <div class="w-4 h-4 bg-red-200 dark:bg-red-900/50 rounded"></div>
                                        <span class="text-muted-foreground">Dipesan</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Error State -->
                        <div x-show="!isLoadingKursi && errorMessage" class="text-center py-8">
                            <div class="flex justify-center mb-4">
                                <div class="p-3 bg-destructive/10 rounded-full">
                                    <x-lucide-alert-circle class="w-6 h-6 text-destructive" />
                                </div>
                            </div>
                            <p class="text-foreground font-medium mb-2">Gagal memuat data</p>
                            <p class="text-sm text-muted-foreground" x-text="errorMessage"></p>
                        </div>

                        <!-- Footer -->
                        <div class="mt-6 pt-6 border-t border-border flex justify-end">
                            <button @click="closeModal" class="px-4 py-2 bg-accent border border-input rounded-lg hover:bg-accent/80 transition-colors font-medium text-sm">
                                Tutup
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <script>
        let currentPage = 1;
        let isLoading = false;
        let hasMorePages = {{ $jadwals->hasPages() ? 'true' : 'false' }};

        function loadMoreJadwals() {
            if (isLoading || !hasMorePages) return;

            isLoading = true;
            const loadingIndicator = document.getElementById('loading-indicator');
            loadingIndicator.classList.remove('hidden');

            const nextPage = currentPage + 1;
            const url = `{{ route('admin/cek-kursi.index') }}?page=${nextPage}`;

            fetch(url, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                }
            })
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newItems = doc.querySelectorAll('.jadwal-item');

                if (newItems.length === 0) {
                    hasMorePages = false;
                    document.getElementById('no-more-data').classList.remove('hidden');
                } else {
                    const container = document.getElementById('jadwal-container');
                    newItems.forEach(item => {
                        container.appendChild(item.cloneNode(true));
                    });
                    currentPage = nextPage;
                }

                isLoading = false;
                loadingIndicator.classList.add('hidden');
            })
            .catch(error => {
                console.error('Error loading more jadwals:', error);
                isLoading = false;
                loadingIndicator.classList.add('hidden');
            });
        }

        // Intersection Observer untuk Infinite Scroll
        const observerOptions = {
            root: null,
            rootMargin: '200px',
            threshold: 0
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && hasMorePages && !isLoading) {
                    loadMoreJadwals();
                }
            });
        }, observerOptions);

        // Observe the last item in container
        function observeLastItem() {
            const container = document.getElementById('jadwal-container');
            const items = container.querySelectorAll('.jadwal-item');
            if (items.length > 0) {
                const lastItem = items[items.length - 1];
                observer.observe(lastItem);
            }
        }

        // Initial observation
        document.addEventListener('DOMContentLoaded', () => {
            observeLastItem();
        });

        // Update observer when new items are loaded
        const originalFetch = window.fetch;
        window.fetch = function(...args) {
            return originalFetch.apply(this, args).then(response => {
                if (args[0].includes('page=')) {
                    response.clone().text().then(() => {
                        observeLastItem();
                    });
                }
                return response;
            });
        };
        // Kursi Manager Alpine Data
        document.addEventListener('alpine:init', () => {
            Alpine.data('kursiManager', () => ({
                showKursiModal: false,
                isLoadingKursi: false,
                kursiData: null,
                errorMessage: '',

                loadKursi(jadwalKelasBusId, namaKelas, harga) {
                    this.showKursiModal = true;
                    this.isLoadingKursi = true;
                    this.errorMessage = '';
                    this.kursiData = null;

                    const url = `{{ route('admin/cek-kursi.get-kursi') }}?jadwal_kelas_bus_id=${jadwalKelasBusId}`;

                    fetch(url, {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            this.kursiData = data.data;
                        } else {
                            this.errorMessage = data.message || 'Gagal memuat data kursi';
                        }
                    })
                    .catch(error => {
                        console.error('Error loading kursi:', error);
                        this.errorMessage = 'Terjadi kesalahan saat memuat data';
                    })
                    .finally(() => {
                        this.isLoadingKursi = false;
                    });
                },

                closeModal() {
                    this.showKursiModal = false;
                    this.kursiData = null;
                    this.errorMessage = '';
                },

                formatCurrency(value) {
                    if (!value) return '0';
                    return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                },

                groupKursiLeft(kursiList) {
                    if (!kursiList || kursiList.length === 0) return [];

                    const rows = [];
                    const halfLength = Math.ceil(kursiList.length / 2);
                    const leftKursi = kursiList.slice(0, halfLength);

                    for (let i = 0; i < leftKursi.length; i += 2) {
                        rows.push(leftKursi.slice(i, i + 2));
                    }

                    return rows;
                },

                groupKursiRight(kursiList) {
                    if (!kursiList || kursiList.length === 0) return [];

                    const rows = [];
                    const halfLength = Math.ceil(kursiList.length / 2);
                    const rightKursi = kursiList.slice(halfLength);

                    for (let i = 0; i < rightKursi.length; i += 2) {
                        rows.push(rightKursi.slice(i, i + 2));
                    }

                    return rows;
                }
            }));
        });
     </script>
</x-admin-layout>
