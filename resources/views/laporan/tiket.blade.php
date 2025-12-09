<x-admin-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="text-xl sm:text-2xl font-bold">Laporan Tiket</h2>
                <p class="text-sm text-muted-foreground mt-1">Detail semua tiket yang terjual</p>
            </div>
        </div>
    </x-slot>

    <div class="p-4 sm:p-6 space-y-6">
        <!-- Filter Card -->
        <x-ui.card>
            <x-ui.card.header>
                <x-ui.card.title>Filter Laporan</x-ui.card.title>
                <x-ui.card.description>Filter data berdasarkan tanggal dan status</x-ui.card.description>
            </x-ui.card.header>
            <x-ui.card.content>
                <form method="GET" action="{{ route('admin/laporan.tiket') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="space-y-2">
                        <x-ui.label for="start_date">Tanggal Mulai</x-ui.label>
                        <x-datepicker name="start_date" id="start_date" value="{{ $startDate }}" placeholder="Pilih tanggal..." />
                    </div>

                    <div class="space-y-2">
                        <x-ui.label for="end_date">Tanggal Akhir</x-ui.label>
                        <x-datepicker name="end_date" id="end_date" value="{{ $endDate }}" placeholder="Pilih tanggal..." />
                    </div>

                    <div class="space-y-2">
                        <x-ui.label for="status">Status</x-ui.label>
                        <select name="status" id="status" class="w-full rounded-md border-input bg-background px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">
                            <option value="">Semua Status</option>
                            <option value="dipesan" {{ $status == 'dipesan' ? 'selected' : '' }}>Dipesan</option>
                            <option value="dibayar" {{ $status == 'dibayar' ? 'selected' : '' }}>Dibayar</option>
                            <option value="digunakan" {{ $status == 'digunakan' ? 'selected' : '' }}>Digunakan</option>
                            <option value="dibatalkan" {{ $status == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
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
        </div>

        <!-- Table -->
        <x-ui.card>
            <x-ui.card.header>
                <x-ui.card.title>Daftar Tiket</x-ui.card.title>
                <x-ui.card.description>Semua tiket dalam periode yang dipilih</x-ui.card.description>
            </x-ui.card.header>
            <x-ui.card.content class="p-0 sm:p-6">
                @if($tikets->count() > 0)
                    <div class="overflow-x-auto -mx-4 sm:mx-0">
                        <x-ui.table>
                            <x-ui.table.header>
                                <x-ui.table.row>
                                    <x-ui.table.head>Kode Tiket</x-ui.table.head>
                                    <x-ui.table.head>Penumpang</x-ui.table.head>
                                    <x-ui.table.head class="hidden lg:table-cell">Rute</x-ui.table.head>
                                    <x-ui.table.head class="hidden md:table-cell">Kelas</x-ui.table.head>
                                    <x-ui.table.head class="hidden sm:table-cell">Harga</x-ui.table.head>
                                    <x-ui.table.head>Status</x-ui.table.head>
                                    <x-ui.table.head class="hidden lg:table-cell">Waktu Pesan</x-ui.table.head>
                                </x-ui.table.row>
                            </x-ui.table.header>
                            <x-ui.table.body>
                                @foreach($tikets as $tiket)
                                    <x-ui.table.row>
                                        <x-ui.table.cell>
                                            <span class="font-mono text-xs font-semibold">{{ $tiket->kode_tiket }}</span>
                                        </x-ui.table.cell>
                                        <x-ui.table.cell>
                                            <div class="min-w-0">
                                                <p class="text-sm font-medium truncate">{{ $tiket->nama_penumpang }}</p>
                                                <p class="text-xs text-muted-foreground truncate">{{ $tiket->user->email ?? 'N/A' }}</p>
                                            </div>
                                        </x-ui.table.cell>
                                        <x-ui.table.cell class="hidden lg:table-cell">
                                            <div class="flex items-center gap-1 text-sm">
                                                <span class="font-medium">{{ $tiket->jadwalKelasBus->jadwal->rute->asalTerminal->nama_terminal }}</span>
                                                <x-lucide-arrow-right class="h-3 w-3 text-muted-foreground shrink-0" />
                                                <span class="font-medium">{{ $tiket->jadwalKelasBus->jadwal->rute->tujuanTerminal->nama_terminal }}</span>
                                            </div>
                                        </x-ui.table.cell>
                                        <x-ui.table.cell class="hidden md:table-cell">
                                            <x-ui.badge variant="outline">{{ $tiket->jadwalKelasBus->kelasBus->nama_kelas }}</x-ui.badge>
                                        </x-ui.table.cell>
                                        <x-ui.table.cell class="hidden sm:table-cell font-semibold text-sm">
                                            Rp {{ number_format($tiket->harga, 0, ',', '.') }}
                                        </x-ui.table.cell>
                                        <x-ui.table.cell>
                                            @php
                                                $statusConfig = [
                                                    'dipesan' => ['class' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400', 'label' => 'Dipesan'],
                                                    'dibayar' => ['class' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400', 'label' => 'Dibayar'],
                                                    'digunakan' => ['class' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400', 'label' => 'Digunakan'],
                                                    'dibatalkan' => ['class' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400', 'label' => 'Dibatalkan']
                                                ];
                                                $statusData = $statusConfig[$tiket->status] ?? ['class' => 'bg-gray-100 text-gray-700', 'label' => ucfirst($tiket->status)];
                                            @endphp
                                            <x-ui.badge class="{{ $statusData['class'] }}">
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
                        <div class="h-16 w-16 rounded-full bg-muted flex items-center justify-center mb-4">
                            <x-lucide-ticket class="w-8 h-8 text-muted-foreground" />
                        </div>
                        <h3 class="text-lg font-semibold mb-2">Tidak Ada Data</h3>
                        <p class="text-sm text-muted-foreground">Tidak ada tiket dalam periode yang dipilih</p>
                    </div>
                @endif
            </x-ui.card.content>
            @if($tikets->count() > 0)
                <x-ui.card.footer class="border-t pt-4">
                    <div class="w-full flex items-center justify-between">
                        <p class="text-sm text-muted-foreground">
                            Menampilkan {{ $tikets->firstItem() ?? 0 }} - {{ $tikets->lastItem() ?? 0 }} dari {{ $tikets->total() }} tiket
                        </p>
                        <div>
                            {{ $tikets->appends(request()->query())->links('vendor.pagination.shadcn') }}
                        </div>
                    </div>
                </x-ui.card.footer>
            @endif
        </x-ui.card>
    </div>
</x-admin-layout>
