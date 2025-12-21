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
                        Laporan Tiket
                    </x-ui.breadcrumb.page>
                </x-ui.breadcrumb.item>
            </x-ui.breadcrumb.list>
        </x-ui.breadcrumb.breadcrumb>
    </x-slot>

    <div class="p-4 sm:p-6 space-y-6">
        <x-ui.card class="bg-card text-card-foreground border-border shadow-sm">
            <x-ui.card.header>
                <x-ui.card.title class="text-foreground">Filter Laporan</x-ui.card.title>
                <x-ui.card.description class="text-muted-foreground">Filter data berdasarkan tanggal dan status</x-ui.card.description>
            </x-ui.card.header>
            <x-ui.card.content>
                <form method="GET" action="{{ route('admin/laporan.tiket') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="space-y-2">
                        <x-ui.label for="start_date" class="text-foreground">Tanggal Mulai</x-ui.label>
                        <x-datepicker name="start_date" id="start_date" value="{{ $startDate }}" placeholder="Pilih tanggal..." class="bg-background text-foreground border-input" />
                    </div>

                    <div class="space-y-2">
                        <x-ui.label for="end_date" class="text-foreground">Tanggal Akhir</x-ui.label>
                        <x-datepicker name="end_date" id="end_date" value="{{ $endDate }}" placeholder="Pilih tanggal..." class="bg-background text-foreground border-input" />
                    </div>

                    <div class="space-y-2">
                        <x-ui.label for="status" class="text-foreground">Status</x-ui.label>
                        <select name="status" id="status" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 text-foreground dark:bg-background dark:text-foreground">
                            <option value="">Semua Status</option>
                            <option value="dipesan" {{ $status == 'dipesan' ? 'selected' : '' }}>Dipesan</option>
                            <option value="dibayar" {{ $status == 'dibayar' ? 'selected' : '' }}>Dibayar</option>
                            <option value="selesai" {{ $status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="batal" {{ $status == 'batal' ? 'selected' : '' }}>Batal</option>
                        </select>
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

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <x-ui.card class="bg-card text-card-foreground border-border shadow-sm">
                <x-ui.card.content class="pt-6">
                    <div class="flex items-center gap-4">
                        <div class="h-12 w-12 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center shrink-0">
                            <x-lucide-ticket class="h-6 w-6 text-blue-600 dark:text-blue-400" />
                        </div>
                        <div>
                            <p class="text-sm text-muted-foreground">Total Tiket</p>
                            <p class="text-2xl font-bold text-foreground">{{ number_format($totalTiket) }}</p>
                        </div>
                    </div>
                </x-ui.card.content>
            </x-ui.card>

            <x-ui.card class="bg-card text-card-foreground border-border shadow-sm">
                <x-ui.card.content class="pt-6">
                    <div class="flex items-center gap-4">
                        <div class="h-12 w-12 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center shrink-0">
                            <x-lucide-dollar-sign class="h-6 w-6 text-emerald-600 dark:text-emerald-400" />
                        </div>
                        <div>
                            <p class="text-sm text-muted-foreground">Total Pendapatan</p>
                            <p class="text-2xl font-bold text-foreground">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </x-ui.card.content>
            </x-ui.card>
        </div>

        <x-ui.card class="bg-card text-card-foreground border-border shadow-sm">
            <x-ui.card.header>
                <x-ui.card.title class="text-foreground">Daftar Tiket</x-ui.card.title>
                <x-ui.card.description class="text-muted-foreground">Semua tiket dalam periode yang dipilih</x-ui.card.description>
            </x-ui.card.header>
            <x-ui.card.content class="p-0 sm:p-6">
                @if($tikets->count() > 0)
                    <div class="overflow-x-auto -mx-4 sm:mx-0">
                        <x-ui.table>
                            <x-ui.table.header class="bg-muted/50 dark:bg-muted/20">
                                <x-ui.table.row class="border-b dark:border-border hover:bg-transparent">
                                    <x-ui.table.head class="text-muted-foreground">Kode Tiket</x-ui.table.head>
                                    <x-ui.table.head class="text-muted-foreground">Penumpang</x-ui.table.head>
                                    <x-ui.table.head class="hidden lg:table-cell text-muted-foreground">Rute</x-ui.table.head>
                                    <x-ui.table.head class="hidden md:table-cell text-muted-foreground">Kelas</x-ui.table.head>
                                    <x-ui.table.head class="hidden sm:table-cell text-muted-foreground">Harga</x-ui.table.head>
                                    <x-ui.table.head class="text-muted-foreground">Status</x-ui.table.head>
                                    <x-ui.table.head class="hidden lg:table-cell text-muted-foreground">Waktu Pesan</x-ui.table.head>
                                </x-ui.table.row>
                            </x-ui.table.header>
                            <x-ui.table.body>
                                @foreach($tikets as $tiket)
                                    <x-ui.table.row class="border-b dark:border-border hover:bg-muted/50 dark:hover:bg-muted/10 transition-colors">
                                        <x-ui.table.cell>
                                            <span class="font-mono text-xs font-semibold text-foreground">{{ $tiket->kode_tiket }}</span>
                                        </x-ui.table.cell>
                                        <x-ui.table.cell>
                                            <div class="min-w-0">
                                                <p class="text-sm font-medium truncate text-foreground">{{ $tiket->nama_penumpang }}</p>
                                                <p class="text-xs text-muted-foreground truncate">{{ $tiket->user->email ?? '-' }}</p>
                                            </div>
                                        </x-ui.table.cell>
                                        <x-ui.table.cell class="hidden lg:table-cell">
                                            <div class="flex items-center gap-1 text-sm text-foreground">
                                                <span class="font-medium">{{ $tiket->jadwalKelasBus->jadwal->rute->asalTerminal->nama_terminal }}</span>
                                                <x-lucide-arrow-right class="h-3 w-3 text-muted-foreground shrink-0" />
                                                <span class="font-medium">{{ $tiket->jadwalKelasBus->jadwal->rute->tujuanTerminal->nama_terminal }}</span>
                                            </div>
                                        </x-ui.table.cell>
                                        <x-ui.table.cell class="hidden md:table-cell">
                                            <x-ui.badge variant="outline" class="border-border text-muted-foreground">{{ $tiket->jadwalKelasBus->kelasBus->nama_kelas }}</x-ui.badge>
                                        </x-ui.table.cell>
                                        <x-ui.table.cell class="hidden sm:table-cell font-semibold text-sm text-foreground">
                                            Rp {{ number_format($tiket->harga, 0, ',', '.') }}
                                        </x-ui.table.cell>
                                        <x-ui.table.cell>
                                            @php
                                                $status = $tiket->pembayaran ? $tiket->pembayaran->status : $tiket->status;
                                                $statusConfig = [
                                                    'dipesan' => ['class' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400 border-transparent', 'label' => 'Dipesan'],
                                                    'dibayar' => ['class' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400 border-transparent', 'label' => 'Dibayar'],
                                                    'selesai' => ['class' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400 border-transparent', 'label' => 'Selesai'],
                                                    'batal' => ['class' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 border-transparent', 'label' => 'Batal']
                                                ];
                                                $statusData = $statusConfig[$status] ?? ['class' => 'bg-slate-100 text-slate-800 dark:bg-slate-800 dark:text-slate-400', 'label' => ucfirst($status)];
                                            @endphp
                                            <x-ui.badge class="{{ $statusData['class'] }} shadow-none hover:bg-opacity-80">
                                                {{ $statusData['label'] }}
                                            </x-ui.badge>
                                        </x-ui.table.cell>
                                        <x-ui.table.cell class="hidden lg:table-cell">
                                            <p class="text-xs text-muted-foreground">{{ $tiket->waktu_pesan->format('d M Y H:i') }}</p>
                                        </x-ui.table.cell>
                                    </x-ui.table.row>
                                @endforeach
                            </x-ui.table.body>
                        </x-ui.table>
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center py-12 text-center">
                        <div class="h-16 w-16 rounded-full bg-muted dark:bg-muted/50 flex items-center justify-center mb-4">
                            <x-lucide-ticket class="w-8 h-8 text-muted-foreground" />
                        </div>
                        <h3 class="text-lg font-semibold mb-2 text-foreground">Tidak Ada Data</h3>
                        <p class="text-sm text-muted-foreground">Tidak ada tiket ditemukan dalam filter yang dipilih</p>
                    </div>
                @endif
            </x-ui.card.content>
            @if($tikets->count() > 0)
                <x-ui.card.footer class="border-t border-border pt-4">
                    <div class="w-full flex flex-col sm:flex-row items-center justify-between gap-4">
                        <p class="text-sm text-muted-foreground order-2 sm:order-1 text-center sm:text-left">
                            Menampilkan <span class="font-medium text-foreground">{{ $tikets->firstItem() ?? 0 }}</span> - <span class="font-medium text-foreground">{{ $tikets->lastItem() ?? 0 }}</span> dari <span class="font-medium text-foreground">{{ $tikets->total() }}</span> tiket
                        </p>
                        <div class="order-1 sm:order-2">
                            {{ $tikets->appends(request()->query())->links('vendor.pagination.shadcn') }}
                        </div>
                    </div>
                </x-ui.card.footer>
            @endif
        </x-ui.card>
    </div>
</x-admin-layout>
