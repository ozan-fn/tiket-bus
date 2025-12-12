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
                        Analytics
                    </x-ui.breadcrumb.page>
                </x-ui.breadcrumb.item>
            </x-ui.breadcrumb.list>
        </x-ui.breadcrumb.breadcrumb>
    </x-slot>

    <div class="p-4 sm:p-6 space-y-6">
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Total Tiket -->
            <x-ui.card>
                <x-ui.card.content class="pt-6">
                    <div class="flex items-center gap-4">
                        <div class="h-12 w-12 rounded-lg bg-blue-500/10 flex items-center justify-center shrink-0">
                            <x-lucide-ticket class="h-6 w-6 text-blue-600" />
                        </div>
                        <div>
                            <p class="text-sm text-muted-foreground">Total Tiket</p>
                            <p class="text-2xl font-bold">{{ number_format($totalTiket) }}</p>
                        </div>
                    </div>
                </x-ui.card.content>
            </x-ui.card>

            <!-- Total Pendapatan -->
            <x-ui.card>
                <x-ui.card.content class="pt-6">
                    <div class="flex items-center gap-4">
                        <div class="h-12 w-12 rounded-lg bg-green-500/10 flex items-center justify-center shrink-0">
                            <x-lucide-dollar-sign class="h-6 w-6 text-green-600" />
                        </div>
                        <div>
                            <p class="text-sm text-muted-foreground">Total Pendapatan</p>
                            <p class="text-2xl font-bold">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </x-ui.card.content>
            </x-ui.card>

            <!-- Total Penumpang -->
            <x-ui.card>
                <x-ui.card.content class="pt-6">
                    <div class="flex items-center gap-4">
                        <div class="h-12 w-12 rounded-lg bg-purple-500/10 flex items-center justify-center shrink-0">
                            <x-lucide-users class="h-6 w-6 text-purple-600" />
                        </div>
                        <div>
                            <p class="text-sm text-muted-foreground">Total Penumpang</p>
                            <p class="text-2xl font-bold">{{ number_format($totalPenumpang) }}</p>
                        </div>
                    </div>
                </x-ui.card.content>
            </x-ui.card>

            <!-- Total Bus -->
            <x-ui.card>
                <x-ui.card.content class="pt-6">
                    <div class="flex items-center gap-4">
                        <div class="h-12 w-12 rounded-lg bg-orange-500/10 flex items-center justify-center shrink-0">
                            <x-lucide-bus class="h-6 w-6 text-orange-600" />
                        </div>
                        <div>
                            <p class="text-sm text-muted-foreground">Total Bus</p>
                            <p class="text-2xl font-bold">{{ number_format($totalBus) }}</p>
                        </div>
                    </div>
                </x-ui.card.content>
            </x-ui.card>
        </div>

        <!-- Charts Row 1 -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Tiket per Status (Pie Chart) -->
            <x-ui.card>
                <x-ui.card.header>
                    <x-ui.card.title>Status Tiket</x-ui.card.title>
                    <x-ui.card.description>Distribusi status tiket</x-ui.card.description>
                </x-ui.card.header>
                <x-ui.card.content>
                    <div class="space-y-3">
                        @foreach($tiketPerStatus as $status => $total)
                            @php
                                $percentage = $totalTiket > 0 ? ($total / $totalTiket) * 100 : 0;
                                $colors = [
                                    'Dipesan' => 'bg-yellow-500',
                                    'Dibayar' => 'bg-green-500',
                                    'Digunakan' => 'bg-blue-500',
                                    'Dibatalkan' => 'bg-red-500'
                                ];
                                $color = $colors[$status] ?? 'bg-gray-500';
                            @endphp
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-sm font-medium">{{ $status }}</span>
                                    <span class="text-sm text-muted-foreground">{{ number_format($total) }} ({{ number_format($percentage, 1) }}%)</span>
                                </div>
                                <div class="w-full bg-muted rounded-full h-2">
                                    <div class="{{ $color }} h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </x-ui.card.content>
            </x-ui.card>

            <!-- Pendapatan per Bulan (Line Chart) -->
            <x-ui.card>
                <x-ui.card.header>
                    <x-ui.card.title>Pendapatan per Bulan</x-ui.card.title>
                    <x-ui.card.description>6 bulan terakhir</x-ui.card.description>
                </x-ui.card.header>
                <x-ui.card.content>
                    <div class="space-y-3">
                        @php
                            $maxPendapatan = $pendapatanPerBulan->max() ?: 1;
                        @endphp
                        @foreach($pendapatanPerBulan as $bulan => $total)
                            @php
                                $percentage = ($total / $maxPendapatan) * 100;
                            @endphp
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-sm font-medium">{{ $bulan }}</span>
                                    <span class="text-sm text-muted-foreground">Rp {{ number_format($total, 0, ',', '.') }}</span>
                                </div>
                                <div class="w-full bg-muted rounded-full h-2">
                                    <div class="bg-primary h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </x-ui.card.content>
            </x-ui.card>
        </div>

        <!-- Tiket per Hari (Bar Chart) -->
        <x-ui.card>
            <x-ui.card.header>
                <x-ui.card.title>Tiket Terjual per Hari</x-ui.card.title>
                <x-ui.card.description>{{ $periode }} hari terakhir</x-ui.card.description>
            </x-ui.card.header>
            <x-ui.card.content>
                <div class="space-y-2">
                    @php
                        $maxTiket = $tiketPerHari->max() ?: 1;
                    @endphp
                    @forelse($tiketPerHari as $tanggal => $total)
                        @php
                            $percentage = ($total / $maxTiket) * 100;
                        @endphp
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-xs font-medium">{{ $tanggal }}</span>
                                <span class="text-xs text-muted-foreground">{{ number_format($total) }} tiket</span>
                            </div>
                            <div class="w-full bg-muted rounded-full h-1.5">
                                <div class="bg-blue-500 h-1.5 rounded-full" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-muted-foreground text-center py-8">Tidak ada data</p>
                    @endforelse
                </div>
            </x-ui.card.content>
        </x-ui.card>

        <!-- Tables Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Rute Terpopuler -->
            <x-ui.card>
                <x-ui.card.header>
                    <x-ui.card.title>Rute Terpopuler</x-ui.card.title>
                    <x-ui.card.description>Top 5 rute dengan tiket terbanyak</x-ui.card.description>
                </x-ui.card.header>
                <x-ui.card.content class="p-0 sm:p-6">
                    <x-ui.table>
                        <x-ui.table.header>
                            <x-ui.table.row>
                                <x-ui.table.head>Rute</x-ui.table.head>
                                <x-ui.table.head class="text-right">Tiket</x-ui.table.head>
                                <x-ui.table.head class="text-right">Pendapatan</x-ui.table.head>
                            </x-ui.table.row>
                        </x-ui.table.header>
                        <x-ui.table.body>
                            @forelse($ruteTerpopuler as $rute)
                                <x-ui.table.row>
                                    <x-ui.table.cell>
                                        <div class="flex items-center gap-1 text-sm">
                                            <span class="font-medium">{{ $rute->asal }}</span>
                                            <x-lucide-arrow-right class="h-3 w-3 text-muted-foreground" />
                                            <span class="font-medium">{{ $rute->tujuan }}</span>
                                        </div>
                                    </x-ui.table.cell>
                                    <x-ui.table.cell class="text-right">
                                        <x-ui.badge variant="outline">{{ number_format($rute->total_tiket) }}</x-ui.badge>
                                    </x-ui.table.cell>
                                    <x-ui.table.cell class="text-right font-medium">
                                        Rp {{ number_format($rute->total_pendapatan, 0, ',', '.') }}
                                    </x-ui.table.cell>
                                </x-ui.table.row>
                            @empty
                                <x-ui.table.row>
                                    <x-ui.table.cell colspan="3" class="text-center text-muted-foreground py-8">
                                        Tidak ada data
                                    </x-ui.table.cell>
                                </x-ui.table.row>
                            @endforelse
                        </x-ui.table.body>
                    </x-ui.table>
                </x-ui.card.content>
            </x-ui.card>

            <!-- Bus dengan Occupancy Tertinggi -->
            <x-ui.card>
                <x-ui.card.header>
                    <x-ui.card.title>Bus Terpopuler</x-ui.card.title>
                    <x-ui.card.description>Top 5 bus dengan occupancy tertinggi</x-ui.card.description>
                </x-ui.card.header>
                <x-ui.card.content class="p-0 sm:p-6">
                    <x-ui.table>
                        <x-ui.table.header>
                            <x-ui.table.row>
                                <x-ui.table.head>Bus</x-ui.table.head>
                                <x-ui.table.head class="text-right">Tiket</x-ui.table.head>
                                <x-ui.table.head class="text-right">Pendapatan</x-ui.table.head>
                            </x-ui.table.row>
                        </x-ui.table.header>
                        <x-ui.table.body>
                            @forelse($busOccupancy as $bus)
                                <x-ui.table.row>
                                    <x-ui.table.cell>
                                        <div class="flex items-center gap-2">
                                            <x-lucide-bus class="h-4 w-4 text-muted-foreground" />
                                            <span class="font-medium text-sm">{{ $bus->nama_bus }}</span>
                                        </div>
                                    </x-ui.table.cell>
                                    <x-ui.table.cell class="text-right">
                                        <x-ui.badge variant="outline">{{ number_format($bus->total_tiket) }}</x-ui.badge>
                                    </x-ui.table.cell>
                                    <x-ui.table.cell class="text-right font-medium">
                                        Rp {{ number_format($bus->total_pendapatan, 0, ',', '.') }}
                                    </x-ui.table.cell>
                                </x-ui.table.row>
                            @empty
                                <x-ui.table.row>
                                    <x-ui.table.cell colspan="3" class="text-center text-muted-foreground py-8">
                                        Tidak ada data
                                    </x-ui.table.cell>
                                </x-ui.table.row>
                            @endforelse
                        </x-ui.table.body>
                    </x-ui.table>
                </x-ui.card.content>
            </x-ui.card>
        </div>

        <!-- Quick Links -->
        <x-ui.card>
            <x-ui.card.header>
                <x-ui.card.title>Laporan Lainnya</x-ui.card.title>
                <x-ui.card.description>Lihat laporan detail</x-ui.card.description>
            </x-ui.card.header>
            <x-ui.card.content>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <a href="{{ route('admin/laporan.tiket') }}" class="group">
                        <div class="p-4 rounded-lg border border-border hover:border-primary transition-colors">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="h-10 w-10 rounded-lg bg-blue-500/10 flex items-center justify-center">
                                    <x-lucide-ticket class="h-5 w-5 text-blue-600" />
                                </div>
                                <div>
                                    <h4 class="font-medium">Laporan Tiket</h4>
                                    <p class="text-xs text-muted-foreground">Detail tiket terjual</p>
                                </div>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('admin/laporan.pendapatan') }}" class="group">
                        <div class="p-4 rounded-lg border border-border hover:border-primary transition-colors">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="h-10 w-10 rounded-lg bg-green-500/10 flex items-center justify-center">
                                    <x-lucide-dollar-sign class="h-5 w-5 text-green-600" />
                                </div>
                                <div>
                                    <h4 class="font-medium">Laporan Pendapatan</h4>
                                    <p class="text-xs text-muted-foreground">Analisis revenue</p>
                                </div>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('admin/laporan.penumpang') }}" class="group">
                        <div class="p-4 rounded-lg border border-border hover:border-primary transition-colors">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="h-10 w-10 rounded-lg bg-purple-500/10 flex items-center justify-center">
                                    <x-lucide-users class="h-5 w-5 text-purple-600" />
                                </div>
                                <div>
                                    <h4 class="font-medium">Laporan Penumpang</h4>
                                    <p class="text-xs text-muted-foreground">Data penumpang</p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </x-ui.card.content>
        </x-ui.card>
    </div>
</x-admin-layout>
