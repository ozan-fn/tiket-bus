<x-admin-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Dashboard</h2>
    </x-slot>

    <div class="p-6">
        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <!-- Total Bus -->
            <x-ui.card.card class="border-l-4 border-l-blue-500">
                <x-ui.card.card-content class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Bus</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ \App\Models\Bus::count() }}</p>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                            </svg>
                        </div>
                    </div>
                </x-ui.card.card-content>
            </x-ui.card.card>

            <!-- Total Sopir -->
            <x-ui.card.card class="border-l-4 border-l-green-500">
                <x-ui.card.card-content class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Sopir</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ \App\Models\Sopir::count() }}</p>
                        </div>
                        <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                    </div>
                </x-ui.card.card-content>
            </x-ui.card.card>

            <!-- Total Terminal -->
            <x-ui.card.card class="border-l-4 border-l-purple-500">
                <x-ui.card.card-content class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Terminal</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ \App\Models\Terminal::count() }}</p>
                        </div>
                        <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                    </div>
                </x-ui.card.card-content>
            </x-ui.card.card>

            <!-- Total Tiket -->
            <x-ui.card.card class="border-l-4 border-l-orange-500">
                <x-ui.card.card-content class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Tiket</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ \App\Models\Tiket::count() }}</p>
                        </div>
                        <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900/30 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                            </svg>
                        </div>
                    </div>
                </x-ui.card.card-content>
            </x-ui.card.card>
        </div>

        <!-- Recent Activity & Quick Stats -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Jadwal Hari Ini -->
            <x-ui.card.card>
                <x-ui.card.card-header>
                    <x-ui.card.card-title>Jadwal Hari Ini</x-ui.card.card-title>
                    <x-ui.card.card-description>Daftar keberangkatan hari ini</x-ui.card.card-description>
                </x-ui.card.card-header>
                <x-ui.card.card-content>
                    @php
                        $jadwalsToday = \App\Models\Jadwal::with('bus', 'rute.asalTerminal', 'rute.tujuanTerminal')
                            ->whereDate('tanggal_berangkat', today())
                            ->take(5)
                            ->get();
                    @endphp

                    @if($jadwalsToday->count() > 0)
                        <div class="space-y-3">
                            @foreach($jadwalsToday as $jadwal)
                                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $jadwal->bus->nama }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $jadwal->rute->asalTerminal->nama_terminal ?? '-' }} → {{ $jadwal->rute->tujuanTerminal->nama_terminal ?? '-' }}
                                            </p>
                                        </div>
                                    </div>
                                    <x-ui.badge.badge variant="outline">
                                        {{ $jadwal->jam_berangkat->format('H:i') }}
                                    </x-ui.badge.badge>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Tidak ada jadwal hari ini</p>
                        </div>
                    @endif
                </x-ui.card.card-content>
                <x-ui.card.card-footer>
                    <a href="{{ route('admin/jadwal.index') }}" class="text-sm text-blue-600 hover:text-blue-700 dark:text-blue-400 font-medium">
                        Lihat Semua Jadwal →
                    </a>
                </x-ui.card.card-footer>
            </x-ui.card.card>

            <!-- Statistik Tiket -->
            <x-ui.card.card>
                <x-ui.card.card-header>
                    <x-ui.card.card-title>Statistik Tiket</x-ui.card.card-title>
                    <x-ui.card.card-description>Status pemesanan tiket</x-ui.card.card-description>
                </x-ui.card.card-header>
                <x-ui.card.card-content>
                    @php
                        $tiketStats = [
                            'dipesan' => \App\Models\Tiket::where('status', 'dipesan')->count(),
                            'dibayar' => \App\Models\Tiket::where('status', 'dibayar')->count(),
                            'selesai' => \App\Models\Tiket::where('status', 'selesai')->count(),
                            'dibatalkan' => \App\Models\Tiket::where('status', 'dibatalkan')->count(),
                        ];
                    @endphp

                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                                <span class="text-sm text-gray-700 dark:text-gray-300">Dipesan</span>
                            </div>
                            <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $tiketStats['dipesan'] }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                                <span class="text-sm text-gray-700 dark:text-gray-300">Dibayar</span>
                            </div>
                            <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $tiketStats['dibayar'] }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                <span class="text-sm text-gray-700 dark:text-gray-300">Selesai</span>
                            </div>
                            <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $tiketStats['selesai'] }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                                <span class="text-sm text-gray-700 dark:text-gray-300">Dibatalkan</span>
                            </div>
                            <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $tiketStats['dibatalkan'] }}</span>
                        </div>
                    </div>
                </x-ui.card.card-content>
                <x-ui.card.card-footer>
                    <a href="{{ route('admin.history-pemesanan') }}" class="text-sm text-blue-600 hover:text-blue-700 dark:text-blue-400 font-medium">
                        Lihat History Pemesanan →
                    </a>
                </x-ui.card.card-footer>
            </x-ui.card.card>
        </div>

        <!-- Rute Populer -->
        <x-ui.card.card>
            <x-ui.card.card-header>
                <x-ui.card.card-title>Rute Populer</x-ui.card.card-title>
                <x-ui.card.card-description>Rute dengan pemesanan terbanyak</x-ui.card.card-description>
            </x-ui.card.card-header>
            <x-ui.card.card-content>
                @php
                    $popularRoutes = \App\Models\Rute::with('asalTerminal', 'tujuanTerminal')
                        ->withCount('jadwals')
                        ->orderBy('jadwals_count', 'desc')
                        ->take(5)
                        ->get();
                @endphp

                @if($popularRoutes->count() > 0)
                    <div class="space-y-3">
                        @foreach($popularRoutes as $rute)
                            <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-800 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                            {{ $rute->asalTerminal->nama_terminal ?? '-' }} → {{ $rute->tujuanTerminal->nama_terminal ?? '-' }}
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $rute->jadwals_count }} jadwal tersedia
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $rute->jarak ?? '-' }} km</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $rute->estimasi_waktu ?? '-' }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                        </svg>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada rute tersedia</p>
                    </div>
                @endif
            </x-ui.card.card-content>
        </x-ui.card.card>
    </div>
</x-admin-layout>
