@extends('layouts.admin')
@section('content')
    @push('header')
        <x-ui.breadcrumb.breadcrumb>
            <x-ui.breadcrumb.list class="text-xs">
                <x-ui.breadcrumb.item>
                    <x-ui.breadcrumb.link href="{{ route('dashboard') }}">
                        Home
                    </x-ui.breadcrumb.link>
                </x-ui.breadcrumb.item>
                <x-ui.breadcrumb.separator>
                    <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                </x-ui.breadcrumb.separator>
                <x-ui.breadcrumb.item>
                    <x-ui.breadcrumb.page>
                        Cek Kursi
                    </x-ui.breadcrumb.page>
                </x-ui.breadcrumb.item>
            </x-ui.breadcrumb.list>
        </x-ui.breadcrumb.breadcrumb>
@endpush

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
                                        <i data-lucide="clock" class="w-3 h-3"></i>
                                        {{ $jadwal->jam_berangkat->format('H:i') }}
                                    </span>
                                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 rounded text-xs font-semibold">
                                        <i data-lucide="calendar" class="w-3 h-3"></i>
                                        {{ $jadwal->tanggal_berangkat->format('d M Y') }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-2 text-sm">
                                    <span class="font-semibold text-foreground">{{ $jadwal->rute->asalTerminal->nama_terminal }}</span>
                                    <i data-lucide="arrow-right" class="w-4 h-4 text-muted-foreground"></i>
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
                                    <p class="text-xs text-muted-foreground uppercase tracking-wider">Kondektur</p>
                                    <p class="font-semibold text-foreground">{{ $jadwal->conductor?->user?->name ?? 'Belum ditugaskan' }}</p>
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
                                            @click="loadKursi({{ $jadwal->id }})"
                                            class="px-3 py-1.5 bg-primary text-primary-foreground rounded-lg hover:bg-primary/90 transition-colors font-medium whitespace-nowrap flex items-center gap-2 text-xs">
                                            <i data-lucide="eye" class="w-3 h-3"></i>
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
                                    <i data-lucide="inbox" class="w-12 h-12 text-muted-foreground"></i>
                                </div>
                            </div>
                            <h3 class="text-lg font-semibold text-foreground mb-2">Tidak ada jadwal</h3>
                            <p class="text-muted-foreground max-w-md mx-auto">
                                Belum ada jadwal untuk Anda. Silakan hubungi administrator.
                            </p>
                        </div>
                    </div>
                @endempty
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
                         class="relative w-full max-w-2xl bg-card rounded-lg shadow-lg border border-border p-6">

                        <!-- Header -->
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h3 class="text-xl font-semibold text-foreground">Ketersediaan Kursi</h3>
                                <p class="text-sm text-muted-foreground mt-1">Cek status kursi untuk jadwal ini</p>
                            </div>
                            <button @click="closeModal" class="p-1 hover:bg-muted rounded-lg transition-colors">
                                <i data-lucide="x" class="w-5 h-5 text-muted-foreground"></i>
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
                        <div x-show="!isLoadingKursi" class="space-y-6">
                            <!-- Jadwal Info -->
                            <div x-show="kursiData && kursiData.jadwal" class="p-4 bg-primary/5 rounded-lg border border-primary/20">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-xs text-muted-foreground uppercase">Bus</p>
                                        <p class="font-semibold text-foreground" x-text="kursiData?.jadwal?.bus_nama"></p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-muted-foreground uppercase">Plat Nomor</p>
                                        <p class="font-semibold text-foreground" x-text="kursiData?.jadwal?.bus_plat"></p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-muted-foreground uppercase">Tanggal & Jam</p>
                                        <p class="font-semibold text-foreground">
                                            <span x-text="kursiData?.jadwal?.tanggal_berangkat"></span>
                                            <span class="text-muted-foreground mx-1">|</span>
                                            <span x-text="kursiData?.jadwal?.jam_berangkat"></span>
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-muted-foreground uppercase">Rute</p>
                                        <p class="font-semibold text-sm">
                                            <span x-text="kursiData?.jadwal?.asal_terminal"></span>
                                            <span class="text-muted-foreground mx-1">→</span>
                                            <span x-text="kursiData?.jadwal?.tujuan_terminal"></span>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Kursi Summary -->
                            <div x-show="kursiData && kursiData.kursi_summary" class="grid grid-cols-3 gap-4">
                                <div class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                                    <p class="text-xs text-muted-foreground uppercase mb-1">Total Kursi</p>
                                    <p class="text-2xl font-bold text-blue-600" x-text="kursiData?.kursi_summary?.total"></p>
                                </div>
                                <div class="p-3 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg border border-yellow-200 dark:border-yellow-800">
                                    <p class="text-xs text-muted-foreground uppercase mb-1">Dipesan</p>
                                    <p class="text-2xl font-bold text-yellow-600" x-text="kursiData?.kursi_summary?.booked"></p>
                                </div>
                                <div class="p-3 bg-green-50 dark:bg-green-900/20 rounded-lg border border-green-200 dark:border-green-800">
                                    <p class="text-xs text-muted-foreground uppercase mb-1">Tersedia</p>
                                    <p class="text-2xl font-bold text-green-600" x-text="kursiData?.kursi_summary?.available"></p>
                                </div>
                            </div>

                            <!-- Kursi Grid -->
                            <div x-show="kursiData && kursiData.kursi && kursiData.kursi.length > 0" class="space-y-4">
                                <div>
                                    <h4 class="text-sm font-semibold text-foreground mb-3">Layout Kursi</h4>
                                    <div class="grid grid-cols-6 gap-2">
                                        <template x-for="kursi in (kursiData?.kursi || [])" :key="kursi.id">
                                            <div
                                                :class="[
                                                    'aspect-square flex items-center justify-center rounded-lg font-bold text-xs transition-all cursor-default',
                                                    kursi.status === 'booked' 
                                                        ? 'bg-red-100 dark:bg-red-900/30 text-red-800 border border-red-300' 
                                                        : 'bg-green-100 dark:bg-green-900/30 text-green-800 border border-green-300'
                                                ]"
                                                :title="kursi.status === 'booked' ? 'Dipesan' : 'Tersedia'"
                                            >
                                                <span x-text="kursi.nomor_kursi"></span>
                                            </div>
                                        </template>
                                    </div>
                                </div>

                                <!-- Legend -->
                                <div class="flex gap-6 pt-4 border-t border-border">
                                    <div class="flex items-center gap-2">
                                        <div class="w-4 h-4 bg-green-100 dark:bg-green-900/30 border border-green-300 rounded"></div>
                                        <span class="text-xs text-muted-foreground">Tersedia</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <div class="w-4 h-4 bg-red-100 dark:bg-red-900/30 border border-red-300 rounded"></div>
                                        <span class="text-xs text-muted-foreground">Dipesan</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <script>
        function kursiManager() {
            return {
                showKursiModal: false,
                isLoadingKursi: false,
                kursiData: null,

                loadKursi(jadwalId) {
                    this.showKursiModal = true;
                    this.isLoadingKursi = true;
                    this.kursiData = null;

                    fetch(`{{ route('sopir.cek-kursi.get') }}?jadwal_id=${jadwalId}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                this.kursiData = {
                                    jadwal: data.jadwal,
                                    kursi: data.kursi,
                                    kursi_summary: data.kursi_summary,
                                };
                            }
                        })
                        .catch(error => {
                            console.error('Error loading kursi:', error);
                            this.kursiData = null;
                        })
                        .finally(() => {
                            this.isLoadingKursi = false;
                        });
                },

                closeModal() {
                    this.showKursiModal = false;
                    this.kursiData = null;
                }
            };
        }
    </script>

@endsection
