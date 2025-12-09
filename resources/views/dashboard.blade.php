<x-admin-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Dashboard</h2>
    </x-slot>

    <div class="p-6">
        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <!-- Total Bus -->
            <x-ui::card>
                <x-ui::card.content class="p-4">
                    <div class="flex items-center justify-between mb-2">
                        <div class="h-10 w-10 rounded-full bg-primary/10 flex items-center justify-center">
                            <svg class="h-5 w-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                            </svg>
                        </div>
                        <a href="{{ route('admin/bus.index') }}" class="text-xs text-primary hover:underline">Lihat →</a>
                    </div>
                    <div>
                        <p class="text-2xl font-bold">{{ \App\Models\Bus::count() }}</p>
                        <p class="text-xs text-muted-foreground">Total Bus</p>
                    </div>
                </x-ui::card.content>
            </x-ui::card>

            <!-- Total Sopir -->
            <x-ui::card>
                <x-ui::card.content class="p-4">
                    <div class="flex items-center justify-between mb-2">
                        <div class="h-10 w-10 rounded-full bg-primary/10 flex items-center justify-center">
                            <svg class="h-5 w-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <a href="{{ route('admin/sopir.index') }}" class="text-xs text-primary hover:underline">Lihat →</a>
                    </div>
                    <div>
                        <p class="text-2xl font-bold">{{ \App\Models\Sopir::count() }}</p>
                        <p class="text-xs text-muted-foreground">Total Sopir</p>
                    </div>
                </x-ui::card.content>
            </x-ui::card>

            <!-- Total Terminal -->
            <x-ui::card>
                <x-ui::card.content class="p-4">
                    <div class="flex items-center justify-between mb-2">
                        <div class="h-10 w-10 rounded-full bg-primary/10 flex items-center justify-center">
                            <svg class="h-5 w-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <a href="{{ route('admin/terminal.index') }}" class="text-xs text-primary hover:underline">Lihat →</a>
                    </div>
                    <div>
                        <p class="text-2xl font-bold">{{ \App\Models\Terminal::count() }}</p>
                        <p class="text-xs text-muted-foreground">Total Terminal</p>
                    </div>
                </x-ui::card.content>
            </x-ui::card>

            <!-- Total Tiket -->
            <x-ui::card>
                <x-ui::card.content class="p-4">
                    <div class="flex items-center justify-between mb-2">
                        <div class="h-10 w-10 rounded-full bg-primary/10 flex items-center justify-center">
                            <svg class="h-5 w-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                            </svg>
                        </div>
                        <a href="{{ route('admin.history-pemesanan') }}" class="text-xs text-primary hover:underline">Lihat →</a>
                    </div>
                    <div>
                        <p class="text-2xl font-bold">{{ \App\Models\Tiket::count() }}</p>
                        <p class="text-xs text-muted-foreground">Total Tiket</p>
                    </div>
                </x-ui::card.content>
            </x-ui::card>
        </div>

        <!-- Recent Activity & Quick Stats -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Jadwal Hari Ini -->
            <x-ui::card>
                <x-ui::card.header>
                    <x-ui::card.title>Jadwal Hari Ini</x-ui::card.title>
                    <x-ui::card.description>Daftar keberangkatan hari ini</x-ui::card.description>
                </x-ui::card.header>
                <x-ui::card.content>
                    @php
                        $jadwalsToday = \App\Models\Jadwal::with('bus', 'rute.asalTerminal', 'rute.tujuanTerminal')
                            ->whereDate('tanggal_berangkat', today())
                            ->take(5)
                            ->get();
                    @endphp

                    @if($jadwalsToday->count() > 0)
                        <div class="space-y-2">
                            @foreach($jadwalsToday as $jadwal)
                                <div class="flex items-center justify-between p-3 rounded-lg border bg-card hover:bg-accent/50 transition-colors">
                                    <div class="flex items-center gap-3">
                                        <div class="h-9 w-9 rounded-lg bg-primary/10 flex items-center justify-center">
                                            <svg class="h-4 w-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium">{{ $jadwal->bus->nama }}</p>
                                            <p class="text-xs text-muted-foreground">
                                                {{ $jadwal->rute->asalTerminal->nama_terminal ?? '-' }} → {{ $jadwal->rute->tujuanTerminal->nama_terminal ?? '-' }}
                                            </p>
                                        </div>
                                    </div>
                                    <x-ui::badge variant="outline">
                                        {{ $jadwal->jam_berangkat->format('H:i') }}
                                    </x-ui::badge>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="h-12 w-12 text-muted-foreground mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="text-sm text-muted-foreground">Tidak ada jadwal hari ini</p>
                        </div>
                    @endif
                </x-ui::card.content>
                <x-ui::card.footer>
                    <a href="{{ route('admin/jadwal.index') }}" class="text-sm text-primary hover:underline font-medium">
                        Lihat Semua Jadwal →
                    </a>
                </x-ui::card.footer>
            </x-ui::card>

            <!-- Statistik Tiket -->
            <x-ui::card>
                <x-ui::card.header>
                    <x-ui::card.title>Statistik Tiket</x-ui::card.title>
                    <x-ui::card.description>Status pemesanan tiket</x-ui::card.description>
                </x-ui::card.header>
                <x-ui::card.content>
                    @php
                        $tiketStats = [
                            'dipesan' => \App\Models\Tiket::where('status', 'dipesan')->count(),
                            'dibayar' => \App\Models\Tiket::where('status', 'dibayar')->count(),
                            'selesai' => \App\Models\Tiket::where('status', 'selesai')->count(),
                            'dibatalkan' => \App\Models\Tiket::where('status', 'dibatalkan')->count(),
                        ];
                    @endphp

                    <div class="space-y-3">
                        <div class="flex items-center justify-between p-3 rounded-lg border bg-card">
                            <div class="flex items-center gap-3">
                                <div class="h-2 w-2 bg-yellow-500 rounded-full"></div>
                                <span class="text-sm font-medium">Dipesan</span>
                            </div>
                            <span class="text-sm font-semibold">{{ $tiketStats['dipesan'] }}</span>
                        </div>
                        <div class="flex items-center justify-between p-3 rounded-lg border bg-card">
                            <div class="flex items-center gap-3">
                                <div class="h-2 w-2 bg-blue-500 rounded-full"></div>
                                <span class="text-sm font-medium">Dibayar</span>
                            </div>
                            <span class="text-sm font-semibold">{{ $tiketStats['dibayar'] }}</span>
                        </div>
                        <div class="flex items-center justify-between p-3 rounded-lg border bg-card">
                            <div class="flex items-center gap-3">
                                <div class="h-2 w-2 bg-green-500 rounded-full"></div>
                                <span class="text-sm font-medium">Selesai</span>
                            </div>
                            <span class="text-sm font-semibold">{{ $tiketStats['selesai'] }}</span>
                        </div>
                        <div class="flex items-center justify-between p-3 rounded-lg border bg-card">
                            <div class="flex items-center gap-3">
                                <div class="h-2 w-2 bg-destructive rounded-full"></div>
                                <span class="text-sm font-medium">Dibatalkan</span>
                            </div>
                            <span class="text-sm font-semibold">{{ $tiketStats['dibatalkan'] }}</span>
                        </div>
                    </div>
                </x-ui::card.content>
                <x-ui::card.footer>
                    <a href="{{ route('admin.history-pemesanan') }}" class="text-sm text-primary hover:underline font-medium">
                        Lihat History Pemesanan →
                    </a>
                </x-ui::card.footer>
            </x-ui::card>
        </div>

        <!-- Rute Populer -->
        <x-ui::card>
            <x-ui::card.header>
                <x-ui::card.title>Rute Populer</x-ui::card.title>
                <x-ui::card.description>Rute dengan pemesanan terbanyak</x-ui::card.description>
            </x-ui::card.header>
            <x-ui::card.content>
                @php
                    $popularRoutes = \App\Models\Rute::with('asalTerminal', 'tujuanTerminal')
                        ->withCount('jadwals')
                        ->orderBy('jadwals_count', 'desc')
                        ->take(5)
                        ->get();
                @endphp

                @if($popularRoutes->count() > 0)
                    <div class="space-y-2">
                        @foreach($popularRoutes as $rute)
                            <div class="flex items-center justify-between p-4 rounded-lg border bg-card hover:bg-accent/50 transition-colors">
                                <div class="flex items-center gap-4">
                                    <div class="h-12 w-12 rounded-lg bg-primary/10 flex items-center justify-center">
                                        <svg class="h-6 w-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold">
                                            {{ $rute->asalTerminal->nama_terminal ?? '-' }} → {{ $rute->tujuanTerminal->nama_terminal ?? '-' }}
                                        </p>
                                        <p class="text-xs text-muted-foreground">
                                            {{ $rute->jadwals_count }} jadwal tersedia
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-semibold">{{ $rute->jarak ?? '-' }} km</p>
                                    <p class="text-xs text-muted-foreground">{{ $rute->estimasi_waktu ?? '-' }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="h-12 w-12 text-muted-foreground mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                        </svg>
                        <p class="text-sm text-muted-foreground">Belum ada rute tersedia</p>
                    </div>
                @endif
            </x-ui::card.content>
        </x-ui::card>
    </div>
</x-admin-layout>
