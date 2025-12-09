<x-admin-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="text-xl sm:text-2xl font-bold">Laporan Penumpang</h2>
                <p class="text-sm text-muted-foreground mt-1">Data dan analisis penumpang</p>
            </div>
        </div>
    </x-slot>

    <div class="p-4 sm:p-6 space-y-6">
        <!-- Filter Card -->
        <x-ui.card>
            <x-ui.card.header>
                <x-ui.card.title>Filter Laporan</x-ui.card.title>
                <x-ui.card.description>Filter data berdasarkan periode</x-ui.card.description>
            </x-ui.card.header>
            <x-ui.card.content>
                <form method="GET" action="{{ route('admin/laporan.penumpang') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="space-y-2">
                        <x-ui.label for="start_date">Tanggal Mulai</x-ui.label>
                        <x-datepicker name="start_date" id="start_date" value="{{ $startDate }}" placeholder="Pilih tanggal..." />
                    </div>

                    <div class="space-y-2">
                        <x-ui.label for="end_date">Tanggal Akhir</x-ui.label>
                        <x-datepicker name="end_date" id="end_date" value="{{ $endDate }}" placeholder="Pilih tanggal..." />
                    </div>

                    <div class="flex items-end">
                        <x-ui.button type="submit" class="w-full">
                            <x-lucide-search class="w-4 h-4 mr-2" />
                            Filter
                        </x-ui.button>
                    </div>
                </form>
            </x-ui.card.content>
        </x-ui.card>

        <!-- Summary Card -->
        <x-ui.card>
            <x-ui.card.content class="pt-6">
                <div class="flex items-center gap-4">
                    <div class="h-12 w-12 rounded-lg bg-purple-500/10 flex items-center justify-center shrink-0">
                        <x-lucide-users class="h-6 w-6 text-purple-600" />
                    </div>
                    <div>
                        <p class="text-sm text-muted-foreground">Total Penumpang</p>
                        <p class="text-2xl font-bold">{{ number_format($totalPenumpang) }}</p>
                        <p class="text-xs text-muted-foreground mt-1">
                            Dalam periode yang dipilih
                        </p>
                    </div>
                </div>
            </x-ui.card.content>
        </x-ui.card>

        <!-- Penumpang per Hari Chart -->
        <x-ui.card>
            <x-ui.card.header>
                <x-ui.card.title>Penumpang per Hari</x-ui.card.title>
                <x-ui.card.description>Grafik jumlah penumpang harian</x-ui.card.description>
            </x-ui.card.header>
            <x-ui.card.content>
                @if($penumpangPerHari->count() > 0)
                    <div class="space-y-2">
                        @php
                            $maxPenumpang = $penumpangPerHari->max('jumlah') ?: 1;
                        @endphp
                        @foreach($penumpangPerHari as $item)
                            @php
                                $percentage = ($item->jumlah / $maxPenumpang) * 100;
                            @endphp
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-sm font-medium">{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</span>
                                    <span class="text-sm font-semibold">{{ number_format($item->jumlah) }} penumpang</span>
                                </div>
                                <div class="w-full bg-muted rounded-full h-2">
                                    <div class="bg-purple-500 h-2 rounded-full transition-all" style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-muted-foreground">
                        <x-lucide-bar-chart-3 class="w-12 h-12 mx-auto mb-2 opacity-50" />
                        <p>Tidak ada data penumpang</p>
                    </div>
                @endif
            </x-ui.card.content>
        </x-ui.card>

        <!-- Penumpang per Rute Table -->
        <x-ui.card>
            <x-ui.card.header>
                <x-ui.card.title>Penumpang per Rute</x-ui.card.title>
                <x-ui.card.description>Breakdown penumpang berdasarkan rute</x-ui.card.description>
            </x-ui.card.header>
            <x-ui.card.content class="p-0 sm:p-6">
                @if($penumpangPerRute->count() > 0)
                    <div class="overflow-x-auto -mx-4 sm:mx-0">
                        <x-ui.table>
                            <x-ui.table.header>
                                <x-ui.table.row>
                                    <x-ui.table.head class="w-12">#</x-ui.table.head>
                                    <x-ui.table.head>Rute</x-ui.table.head>
                                    <x-ui.table.head class="text-center">Jumlah Penumpang</x-ui.table.head>
                                    <x-ui.table.head class="text-right hidden sm:table-cell">Persentase</x-ui.table.head>
                                </x-ui.table.row>
                            </x-ui.table.header>
                            <x-ui.table.body>
                                @foreach($penumpangPerRute as $index => $rute)
                                    @php
                                        $percentage = $totalPenumpang > 0 ? ($rute->jumlah_penumpang / $totalPenumpang) * 100 : 0;
                                    @endphp
                                    <x-ui.table.row>
                                        <x-ui.table.cell class="font-medium text-muted-foreground">{{ $index + 1 }}</x-ui.table.cell>
                                        <x-ui.table.cell>
                                            <div class="flex items-center gap-1 text-sm">
                                                <span class="font-medium">{{ $rute->asal }}</span>
                                                <x-lucide-arrow-right class="h-3 w-3 text-muted-foreground shrink-0" />
                                                <span class="font-medium">{{ $rute->tujuan }}</span>
                                            </div>
                                        </x-ui.table.cell>
                                        <x-ui.table.cell class="text-center">
                                            <x-ui.badge variant="outline">{{ number_format($rute->jumlah_penumpang) }}</x-ui.badge>
                                        </x-ui.table.cell>
                                        <x-ui.table.cell class="text-right text-sm text-muted-foreground hidden sm:table-cell">
                                            {{ number_format($percentage, 1) }}%
                                        </x-ui.table.cell>
                                    </x-ui.table.row>
                                @endforeach
                            </x-ui.table.body>
                        </x-ui.table>
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center py-12 text-center">
                        <div class="h-16 w-16 rounded-full bg-muted flex items-center justify-center mb-4">
                            <x-lucide-route class="w-8 h-8 text-muted-foreground" />
                        </div>
                        <h3 class="text-lg font-semibold mb-2">Tidak Ada Data</h3>
                        <p class="text-sm text-muted-foreground">Tidak ada penumpang dalam periode yang dipilih</p>
                    </div>
                @endif
            </x-ui.card.content>
        </x-ui.card>

        <!-- Top Penumpang Table -->
        <x-ui.card>
            <x-ui.card.header>
                <x-ui.card.title>Top 10 Penumpang</x-ui.card.title>
                <x-ui.card.description>Penumpang dengan tiket terbanyak</x-ui.card.description>
            </x-ui.card.header>
            <x-ui.card.content class="p-0 sm:p-6">
                @if($topPenumpang->count() > 0)
                    <div class="overflow-x-auto -mx-4 sm:mx-0">
                        <x-ui.table>
                            <x-ui.table.header>
                                <x-ui.table.row>
                                    <x-ui.table.head class="w-12">#</x-ui.table.head>
                                    <x-ui.table.head>Nama</x-ui.table.head>
                                    <x-ui.table.head class="hidden md:table-cell">Email</x-ui.table.head>
                                    <x-ui.table.head class="text-center">Total Tiket</x-ui.table.head>
                                    <x-ui.table.head class="text-right">Total Pengeluaran</x-ui.table.head>
                                </x-ui.table.row>
                            </x-ui.table.header>
                            <x-ui.table.body>
                                @foreach($topPenumpang as $index => $penumpang)
                                    <x-ui.table.row>
                                        <x-ui.table.cell class="font-medium text-muted-foreground">{{ $index + 1 }}</x-ui.table.cell>
                                        <x-ui.table.cell>
                                            <div class="flex items-center gap-2">
                                                <div class="h-8 w-8 rounded-full bg-primary/10 flex items-center justify-center shrink-0">
                                                    <span class="text-xs font-semibold text-primary">{{ strtoupper(substr($penumpang->name, 0, 2)) }}</span>
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="font-medium text-sm truncate">{{ $penumpang->name }}</p>
                                                    <p class="text-xs text-muted-foreground md:hidden truncate">{{ $penumpang->email }}</p>
                                                </div>
                                            </div>
                                        </x-ui.table.cell>
                                        <x-ui.table.cell class="hidden md:table-cell text-sm text-muted-foreground">
                                            {{ $penumpang->email }}
                                        </x-ui.table.cell>
                                        <x-ui.table.cell class="text-center">
                                            <x-ui.badge class="bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">
                                                {{ number_format($penumpang->total_tiket) }}
                                            </x-ui.badge>
                                        </x-ui.table.cell>
                                        <x-ui.table.cell class="text-right font-semibold">
                                            Rp {{ number_format($penumpang->total_pengeluaran, 0, ',', '.') }}
                                        </x-ui.table.cell>
                                    </x-ui.table.row>
                                @endforeach
                            </x-ui.table.body>
                        </x-ui.table>
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center py-12 text-center">
                        <div class="h-16 w-16 rounded-full bg-muted flex items-center justify-center mb-4">
                            <x-lucide-users class="w-8 h-8 text-muted-foreground" />
                        </div>
                        <h3 class="text-lg font-semibold mb-2">Tidak Ada Data</h3>
                        <p class="text-sm text-muted-foreground">Tidak ada data penumpang</p>
                    </div>
                @endif
            </x-ui.card.content>
        </x-ui.card>
    </div>
</x-admin-layout>
