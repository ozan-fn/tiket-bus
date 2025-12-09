<x-admin-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Dashboard</h2>
    </x-slot>

    <div class="p-6">
        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <!-- Total Bus -->
            <x-ui.card>
                <x-ui.card.content class="p-4">
                    <div class="flex items-center justify-between mb-2">
                        <div class="h-10 w-10 rounded-full bg-primary/10 flex items-center justify-center">
                            <x-lucide-bus class="h-5 w-5 text-primary" />
                        </div>
                        <a href="{{ route('admin/bus.index') }}" class="text-xs text-primary hover:underline">Lihat →</a>
                    </div>
                    <div>
                        <p class="text-2xl font-bold">{{ \App\Models\Bus::count() }}</p>
                        <p class="text-xs text-muted-foreground">Total Bus</p>
                    </div>
                </x-ui.card.content>
            </x-ui.card>

            <!-- Total Sopir -->
            <x-ui.card>
                <x-ui.card.content class="p-4">
                    <div class="flex items-center justify-between mb-2">
                        <div class="h-10 w-10 rounded-full bg-primary/10 flex items-center justify-center">
                            <x-lucide-user-round class="h-5 w-5 text-primary" />
                        </div>
                        <a href="{{ route('admin/sopir.index') }}" class="text-xs text-primary hover:underline">Lihat →</a>
                    </div>
                    <div>
                        <p class="text-2xl font-bold">{{ \App\Models\Sopir::count() }}</p>
                        <p class="text-xs text-muted-foreground">Total Sopir</p>
                    </div>
                </x-ui.card.content>
            </x-ui.card>

            <!-- Total Terminal -->
            <x-ui.card>
                <x-ui.card.content class="p-4">
                    <div class="flex items-center justify-between mb-2">
                        <div class="h-10 w-10 rounded-full bg-primary/10 flex items-center justify-center">
                            <x-lucide-building-2 class="h-5 w-5 text-primary" />
                        </div>
                        <a href="{{ route('admin/terminal.index') }}" class="text-xs text-primary hover:underline">Lihat →</a>
                    </div>
                    <div>
                        <p class="text-2xl font-bold">{{ \App\Models\Terminal::count() }}</p>
                        <p class="text-xs text-muted-foreground">Total Terminal</p>
                    </div>
                </x-ui.card.content>
            </x-ui.card>

            <!-- Total Tiket -->
            <x-ui.card>
                <x-ui.card.content class="p-4">
                    <div class="flex items-center justify-between mb-2">
                        <div class="h-10 w-10 rounded-full bg-primary/10 flex items-center justify-center">
                            <x-lucide-ticket class="h-5 w-5 text-primary" />
                        </div>
                        <a href="{{ route('admin/history-pemesanan') }}" class="text-xs text-primary hover:underline">Lihat →</a>
                    </div>
                    <div>
                        <p class="text-2xl font-bold">{{ \App\Models\Tiket::count() }}</p>
                        <p class="text-xs text-muted-foreground">Total Tiket</p>
                    </div>
                </x-ui.card.content>
            </x-ui.card>
        </div>

        <!-- Recent Activity & Quick Stats -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Jadwal Hari Ini -->
            <x-ui.card>
                <x-ui.card.header>
                    <x-ui.card.title>Jadwal Hari Ini</x-ui.card.title>
                    <x-ui.card.description>Daftar keberangkatan hari ini</x-ui.card.description>
                </x-ui.card.header>
                <x-ui.card.content>
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
                                            <x-lucide-bus class="h-4 w-4 text-primary" />
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium">{{ $jadwal->bus->nama }}</p>
                                            <p class="text-xs text-muted-foreground">
                                                {{ $jadwal->rute->asalTerminal->nama_terminal ?? '-' }} → {{ $jadwal->rute->tujuanTerminal->nama_terminal ?? '-' }}
                                            </p>
                                        </div>
                                    </div>
                                    <x-ui.badge variant="outline">
                                        {{ $jadwal->jam_berangkat->format('H:i') }}
                                    </x-ui.badge>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <x-lucide-calendar-x class="h-12 w-12 text-muted-foreground mx-auto mb-2" />
                            <p class="text-sm text-muted-foreground">Tidak ada jadwal hari ini</p>
                        </div>
                    @endif
                </x-ui.card.content>
                <x-ui.card.footer>
                    <a href="{{ route('admin/jadwal.index') }}" class="text-sm text-primary hover:underline font-medium">
                        Lihat Semua Jadwal →
                    </a>
                </x-ui.card.footer>
            </x-ui.card>

            <!-- Statistik Tiket -->
            <x-ui.card>
                <x-ui.card.header>
                    <x-ui.card.title>Statistik Tiket</x-ui.card.title>
                    <x-ui.card.description>Status pemesanan tiket</x-ui.card.description>
                </x-ui.card.header>
                <x-ui.card.content>
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
                </x-ui.card.content>
                <x-ui.card.footer>
                    <a href="{{ route('admin/history-pemesanan') }}" class="text-sm text-primary hover:underline font-medium">
                        Lihat History Pemesanan →
                    </a>
                </x-ui.card.footer>
            </x-ui.card>
        </div>

        <!-- Rute Populer -->
        <x-ui.card>
            <x-ui.card.header>
                <x-ui.card.title>Rute Populer</x-ui.card.title>
                <x-ui.card.description>Rute dengan pemesanan terbanyak</x-ui.card.description>
            </x-ui.card.header>
            <x-ui.card.content>
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
                                        <x-lucide-route class="h-6 w-6 text-primary" />
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
                        <x-lucide-map-pin-off class="h-12 w-12 text-muted-foreground mx-auto mb-2" />
                        <p class="text-sm text-muted-foreground">Belum ada rute tersedia</p>
                    </div>
                @endif
            </x-ui.card.content>
        </x-ui.card>
    </div>
</x-admin-layout>
