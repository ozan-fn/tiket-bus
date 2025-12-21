<x-admin-layout>
    <x-slot name="header">
        <x-ui.breadcrumb.breadcrumb>
            <x-ui.breadcrumb.list class="text-xs">
                <x-ui.breadcrumb.item>
                    <x-ui.breadcrumb.link href="{{ route('dashboard') }}">
                        Beranda
                    </x-ui.breadcrumb.link>
                </x-ui.breadcrumb.item>
                <x-ui.breadcrumb.separator>
                    <x-lucide-chevron-right class="w-3.5 h-3.5" />
                </x-ui.breadcrumb.separator>
                <x-ui.breadcrumb.item>
                    <x-ui.breadcrumb.link href="{{ route('admin/laporan.index') }}">
                        Laporan
                    </x-ui.breadcrumb.link>
                </x-ui.breadcrumb.item>
                <x-ui.breadcrumb.separator>
                    <x-lucide-chevron-right class="w-3.5 h-3.5" />
                </x-ui.breadcrumb.separator>
                <x-ui.breadcrumb.item>
                    <x-ui.breadcrumb.page>
                        Laporan Penumpang
                    </x-ui.breadcrumb.page>
                </x-ui.breadcrumb.item>
            </x-ui.breadcrumb.list>
        </x-ui.breadcrumb.breadcrumb>
    </x-slot>

    <div class="p-4 sm:p-6 space-y-6">
        <x-ui.card class="bg-card text-card-foreground border-border shadow-sm">
            <x-ui.card.header>
                <x-ui.card.title class="text-foreground">Filter Laporan</x-ui.card.title>
                <x-ui.card.description class="text-muted-foreground">Filter data berdasarkan periode waktu</x-ui.card.description>
            </x-ui.card.header>
            <x-ui.card.content>
                <form method="GET" action="{{ route('admin/laporan.penumpang') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="space-y-2">
                        <x-ui.label for="start_date" class="text-foreground">Tanggal Mulai</x-ui.label>
                        <x-datepicker name="start_date" id="start_date" value="{{ $startDate }}" placeholder="Pilih tanggal..." class="bg-background text-foreground border-input" />
                    </div>

                    <div class="space-y-2">
                        <x-ui.label for="end_date" class="text-foreground">Tanggal Akhir</x-ui.label>
                        <x-datepicker name="end_date" id="end_date" value="{{ $endDate }}" placeholder="Pilih tanggal..." class="bg-background text-foreground border-input" />
                    </div>

                    <div class="flex items-end">
                        <x-ui.button type="submit" class="w-full">
                            <x-lucide-search class="w-4 h-4 mr-2" />
                            Terapkan Filter
                        </x-ui.button>
                    </div>
                </form>
            </x-ui.card.content>
        </x-ui.card>

        <x-ui.card class="bg-card text-card-foreground border-border shadow-sm">
            <x-ui.card.content class="pt-6">
                <div class="flex items-center gap-4">
                    <div class="h-12 w-12 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center shrink-0">
                        <x-lucide-users class="h-6 w-6 text-purple-700 dark:text-purple-400" />
                    </div>
                    <div>
                        <p class="text-sm text-muted-foreground">Total Penumpang</p>
                        <p class="text-2xl font-bold text-foreground">{{ number_format($totalPenumpang) }}</p>
                        <p class="text-xs text-muted-foreground mt-1">
                            Dalam periode yang dipilih
                        </p>
                    </div>
                </div>
            </x-ui.card.content>
        </x-ui.card>

        <x-ui.card class="bg-card text-card-foreground border-border shadow-sm">
            <x-ui.card.header>
                <x-ui.card.title class="text-foreground">Penumpang per Hari</x-ui.card.title>
                <x-ui.card.description class="text-muted-foreground">Grafik jumlah penumpang harian</x-ui.card.description>
            </x-ui.card.header>
            <x-ui.card.content>
                @if($penumpangPerHari->count() > 0)
                    <div class="space-y-4">
                        @php
                            $maxPenumpang = $penumpangPerHari->max('jumlah') ?: 1;
                        @endphp
                        @foreach($penumpangPerHari as $item)
                            @php
                                $percentage = ($item->jumlah / $maxPenumpang) * 100;
                            @endphp
                            <div>
                                <div class="flex items-center justify-between mb-1.5">
                                    <span class="text-sm font-medium text-foreground">{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}</span>
                                    <span class="text-sm font-semibold text-foreground">{{ number_format($item->jumlah) }} penumpang</span>
                                </div>
                                <div class="w-full bg-secondary dark:bg-secondary/50 rounded-full h-2.5 overflow-hidden">
                                    <div class="bg-purple-600 dark:bg-purple-500 h-full rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center py-12 text-center">
                        <div class="h-16 w-16 rounded-full bg-muted dark:bg-muted/50 flex items-center justify-center mb-4">
                            <x-lucide-bar-chart-3 class="w-8 h-8 text-muted-foreground" />
                        </div>
                        <p class="text-muted-foreground">Tidak ada data penumpang</p>
                    </div>
                @endif
            </x-ui.card.content>
        </x-ui.card>

        <x-ui.card class="bg-card text-card-foreground border-border shadow-sm">
            <x-ui.card.header>
                <x-ui.card.title class="text-foreground">Penumpang per Rute</x-ui.card.title>
                <x-ui.card.description class="text-muted-foreground">Rincian penumpang berdasarkan rute perjalanan</x-ui.card.description>
            </x-ui.card.header>
            <x-ui.card.content class="p-0 sm:p-6">
                @if($penumpangPerRute->count() > 0)
                    <div class="overflow-x-auto -mx-4 sm:mx-0">
                        <x-ui.table>
                            <x-ui.table.header class="bg-muted/50 dark:bg-muted/20">
                                <x-ui.table.row class="border-b dark:border-border hover:bg-transparent">
                                    <x-ui.table.head class="w-12 text-muted-foreground">#</x-ui.table.head>
                                    <x-ui.table.head class="text-muted-foreground">Rute</x-ui.table.head>
                                    <x-ui.table.head class="text-center text-muted-foreground">Jumlah Penumpang</x-ui.table.head>
                                    <x-ui.table.head class="text-right hidden sm:table-cell text-muted-foreground">Persentase</x-ui.table.head>
                                </x-ui.table.row>
                            </x-ui.table.header>
                            <x-ui.table.body>
                                @foreach($penumpangPerRute as $index => $rute)
                                    @php
                                        $percentage = $totalPenumpang > 0 ? ($rute->jumlah_penumpang / $totalPenumpang) * 100 : 0;
                                    @endphp
                                    <x-ui.table.row class="border-b dark:border-border hover:bg-muted/50 dark:hover:bg-muted/10 transition-colors">
                                        <x-ui.table.cell class="font-medium text-muted-foreground">{{ $index + 1 }}</x-ui.table.cell>
                                        <x-ui.table.cell>
                                            <div class="flex items-center gap-1 text-sm text-foreground">
                                                <span class="font-medium">{{ $rute->asal }}</span>
                                                <x-lucide-arrow-right class="h-3 w-3 text-muted-foreground shrink-0" />
                                                <span class="font-medium">{{ $rute->tujuan }}</span>
                                            </div>
                                        </x-ui.table.cell>
                                        <x-ui.table.cell class="text-center">
                                            <x-ui.badge variant="outline" class="border-border text-foreground">{{ number_format($rute->jumlah_penumpang) }}</x-ui.badge>
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
                        <div class="h-16 w-16 rounded-full bg-muted dark:bg-muted/50 flex items-center justify-center mb-4">
                            <x-lucide-route class="w-8 h-8 text-muted-foreground" />
                        </div>
                        <h3 class="text-lg font-semibold mb-2 text-foreground">Tidak Ada Data</h3>
                        <p class="text-sm text-muted-foreground">Tidak ada penumpang dalam periode yang dipilih</p>
                    </div>
                @endif
            </x-ui.card.content>
        </x-ui.card>

        <x-ui.card class="bg-card text-card-foreground border-border shadow-sm">
            <x-ui.card.header>
                <x-ui.card.title class="text-foreground">Top 10 Penumpang</x-ui.card.title>
                <x-ui.card.description class="text-muted-foreground">Penumpang dengan pembelian tiket terbanyak</x-ui.card.description>
            </x-ui.card.header>
            <x-ui.card.content class="p-0 sm:p-6">
                @if($topPenumpang->count() > 0)
                    <div class="overflow-x-auto -mx-4 sm:mx-0">
                        <x-ui.table>
                            <x-ui.table.header class="bg-muted/50 dark:bg-muted/20">
                                <x-ui.table.row class="border-b dark:border-border hover:bg-transparent">
                                    <x-ui.table.head class="w-12 text-muted-foreground">#</x-ui.table.head>
                                    <x-ui.table.head class="text-muted-foreground">Nama</x-ui.table.head>
                                    <x-ui.table.head class="hidden md:table-cell text-muted-foreground">Email</x-ui.table.head>
                                    <x-ui.table.head class="text-center text-muted-foreground">Total Tiket</x-ui.table.head>
                                    <x-ui.table.head class="text-right text-muted-foreground">Total Pengeluaran</x-ui.table.head>
                                </x-ui.table.row>
                            </x-ui.table.header>
                            <x-ui.table.body>
                                @foreach($topPenumpang as $index => $penumpang)
                                    <x-ui.table.row class="border-b dark:border-border hover:bg-muted/50 dark:hover:bg-muted/10 transition-colors">
                                        <x-ui.table.cell class="font-medium text-muted-foreground">{{ $index + 1 }}</x-ui.table.cell>
                                        <x-ui.table.cell>
                                            <div class="flex items-center gap-3">
                                                <div class="h-8 w-8 rounded-full bg-primary/10 dark:bg-primary/20 flex items-center justify-center shrink-0">
                                                    <span class="text-xs font-semibold text-primary dark:text-primary-foreground">{{ strtoupper(substr($penumpang->name, 0, 2)) }}</span>
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="font-medium text-sm truncate text-foreground">{{ $penumpang->name }}</p>
                                                    <p class="text-xs text-muted-foreground md:hidden truncate">{{ $penumpang->email }}</p>
                                                </div>
                                            </div>
                                        </x-ui.table.cell>
                                        <x-ui.table.cell class="hidden md:table-cell text-sm text-muted-foreground">
                                            {{ $penumpang->email }}
                                        </x-ui.table.cell>
                                        <x-ui.table.cell class="text-center">
                                            <x-ui.badge class="bg-blue-100 text-blue-700 hover:bg-blue-100 dark:bg-blue-900/30 dark:text-blue-400 dark:hover:bg-blue-900/40 shadow-none border-transparent">
                                                {{ number_format($penumpang->total_tiket) }}
                                            </x-ui.badge>
                                        </x-ui.table.cell>
                                        <x-ui.table.cell class="text-right font-semibold text-emerald-600 dark:text-emerald-400">
                                            Rp {{ number_format($penumpang->total_pengeluaran, 0, ',', '.') }}
                                        </x-ui.table.cell>
                                    </x-ui.table.row>
                                @endforeach
                            </x-ui.table.body>
                        </x-ui.table>
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center py-12 text-center">
                        <div class="h-16 w-16 rounded-full bg-muted dark:bg-muted/50 flex items-center justify-center mb-4">
                            <x-lucide-users class="w-8 h-8 text-muted-foreground" />
                        </div>
                        <h3 class="text-lg font-semibold mb-2 text-foreground">Tidak Ada Data</h3>
                        <p class="text-sm text-muted-foreground">Tidak ada data penumpang ditemukan</p>
                    </div>
                @endif
            </x-ui.card.content>
        </x-ui.card>
    </div>
</x-admin-layout>
