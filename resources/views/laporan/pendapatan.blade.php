<x-admin-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="text-xl sm:text-2xl font-bold">Laporan Pendapatan</h2>
                <p class="text-sm text-muted-foreground mt-1">Analisis pendapatan dan revenue</p>
            </div>
        </div>
    </x-slot>

    <div class="p-4 sm:p-6 space-y-6">
        <!-- Filter Card -->
        <x-ui.card>
            <x-ui.card.header>
                <x-ui.card.title>Filter Laporan</x-ui.card.title>
                <x-ui.card.description>Filter data berdasarkan periode dan pengelompokan</x-ui.card.description>
            </x-ui.card.header>
            <x-ui.card.content>
                <form method="GET" action="{{ route('admin/laporan.pendapatan') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="space-y-2">
                        <x-ui.label for="start_date">Tanggal Mulai</x-ui.label>
                        <x-datepicker name="start_date" id="start_date" value="{{ $startDate }}" placeholder="Pilih tanggal..." />
                    </div>

                    <div class="space-y-2">
                        <x-ui.label for="end_date">Tanggal Akhir</x-ui.label>
                        <x-datepicker name="end_date" id="end_date" value="{{ $endDate }}" placeholder="Pilih tanggal..." />
                    </div>

                    <div class="space-y-2">
                        <x-ui.label for="group_by">Kelompokkan Per</x-ui.label>
                        <select name="group_by" id="group_by" class="w-full rounded-md border-input bg-background px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">
                            <option value="day" {{ $groupBy == 'day' ? 'selected' : '' }}>Hari</option>
                            <option value="month" {{ $groupBy == 'month' ? 'selected' : '' }}>Bulan</option>
                            <option value="year" {{ $groupBy == 'year' ? 'selected' : '' }}>Tahun</option>
                        </select>
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

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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

            <x-ui.card>
                <x-ui.card.content class="pt-6">
                    <div class="flex items-center gap-4">
                        <div class="h-12 w-12 rounded-lg bg-blue-500/10 flex items-center justify-center shrink-0">
                            <x-lucide-ticket class="h-6 w-6 text-blue-600" />
                        </div>
                        <div>
                            <p class="text-sm text-muted-foreground">Total Tiket Terjual</p>
                            <p class="text-2xl font-bold">{{ number_format($totalTiket) }}</p>
                            @if($totalTiket > 0)
                                <p class="text-xs text-muted-foreground mt-1">
                                    Rata-rata: Rp {{ number_format($totalPendapatan / $totalTiket, 0, ',', '.') }}/tiket
                                </p>
                            @endif
                        </div>
                    </div>
                </x-ui.card.content>
            </x-ui.card>
        </div>

        <!-- Pendapatan per Periode Chart -->
        <x-ui.card>
            <x-ui.card.header>
                <x-ui.card.title>Pendapatan per
                    @if($groupBy == 'month')
                        Bulan
                    @elseif($groupBy == 'year')
                        Tahun
                    @else
                        Hari
                    @endif
                </x-ui.card.title>
                <x-ui.card.description>Grafik pendapatan dalam periode yang dipilih</x-ui.card.description>
            </x-ui.card.header>
            <x-ui.card.content>
                @if($pendapatan->count() > 0)
                    <div class="space-y-3">
                        @php
                            $maxPendapatan = $pendapatan->max('total_pendapatan') ?: 1;
                        @endphp
                        @foreach($pendapatan as $item)
                            @php
                                $percentage = ($item->total_pendapatan / $maxPendapatan) * 100;
                            @endphp
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-sm font-medium">{{ $item->periode }}</span>
                                    <div class="text-right">
                                        <span class="text-sm font-semibold">Rp {{ number_format($item->total_pendapatan, 0, ',', '.') }}</span>
                                        <span class="text-xs text-muted-foreground ml-2">({{ $item->jumlah_tiket }} tiket)</span>
                                    </div>
                                </div>
                                <div class="w-full bg-muted rounded-full h-2">
                                    <div class="bg-primary h-2 rounded-full transition-all" style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-muted-foreground">
                        <x-lucide-bar-chart-3 class="w-12 h-12 mx-auto mb-2 opacity-50" />
                        <p>Tidak ada data pendapatan</p>
                    </div>
                @endif
            </x-ui.card.content>
        </x-ui.card>

        <!-- Pendapatan per Rute Table -->
        <x-ui.card>
            <x-ui.card.header>
                <x-ui.card.title>Pendapatan per Rute</x-ui.card.title>
                <x-ui.card.description>Breakdown pendapatan berdasarkan rute</x-ui.card.description>
            </x-ui.card.header>
            <x-ui.card.content class="p-0 sm:p-6">
                @if($pendapatanPerRute->count() > 0)
                    <div class="overflow-x-auto -mx-4 sm:mx-0">
                        <x-ui.table>
                            <x-ui.table.header>
                                <x-ui.table.row>
                                    <x-ui.table.head class="w-12">#</x-ui.table.head>
                                    <x-ui.table.head>Rute</x-ui.table.head>
                                    <x-ui.table.head class="text-center">Jumlah Tiket</x-ui.table.head>
                                    <x-ui.table.head class="text-right">Total Pendapatan</x-ui.table.head>
                                    <x-ui.table.head class="text-right hidden sm:table-cell">Rata-rata</x-ui.table.head>
                                </x-ui.table.row>
                            </x-ui.table.header>
                            <x-ui.table.body>
                                @foreach($pendapatanPerRute as $index => $rute)
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
                                            <x-ui.badge variant="outline">{{ number_format($rute->jumlah_tiket) }}</x-ui.badge>
                                        </x-ui.table.cell>
                                        <x-ui.table.cell class="text-right font-semibold text-primary">
                                            Rp {{ number_format($rute->total_pendapatan, 0, ',', '.') }}
                                        </x-ui.table.cell>
                                        <x-ui.table.cell class="text-right text-sm text-muted-foreground hidden sm:table-cell">
                                            @if($rute->jumlah_tiket > 0)
                                                Rp {{ number_format($rute->total_pendapatan / $rute->jumlah_tiket, 0, ',', '.') }}
                                            @else
                                                -
                                            @endif
                                        </x-ui.table.cell>
                                    </x-ui.table.row>
                                @endforeach
                                <x-ui.table.row class="bg-muted/50 font-bold">
                                    <x-ui.table.cell colspan="2" class="text-right">TOTAL</x-ui.table.cell>
                                    <x-ui.table.cell class="text-center">
                                        <x-ui.badge>{{ number_format($totalTiket) }}</x-ui.badge>
                                    </x-ui.table.cell>
                                    <x-ui.table.cell class="text-right text-primary">
                                        Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                                    </x-ui.table.cell>
                                    <x-ui.table.cell class="hidden sm:table-cell"></x-ui.table.cell>
                                </x-ui.table.row>
                            </x-ui.table.body>
                        </x-ui.table>
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center py-12 text-center">
                        <div class="h-16 w-16 rounded-full bg-muted flex items-center justify-center mb-4">
                            <x-lucide-route class="w-8 h-8 text-muted-foreground" />
                        </div>
                        <h3 class="text-lg font-semibold mb-2">Tidak Ada Data</h3>
                        <p class="text-sm text-muted-foreground">Tidak ada pendapatan dalam periode yang dipilih</p>
                    </div>
                @endif
            </x-ui.card.content>
        </x-ui.card>
    </div>
</x-admin-layout>
