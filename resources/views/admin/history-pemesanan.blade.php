<x-admin-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="text-xl sm:text-2xl font-bold">History Pemesanan</h2>
                <p class="text-sm text-muted-foreground mt-1">Riwayat semua pemesanan tiket bus</p>
            </div>
        </div>
    </x-slot>

    <div class="p-4 sm:p-6">
        @if(session('success'))
            <x-ui.alert class="mb-6">
                <x-slot:icon>
                    <x-lucide-check-circle class="w-4 h-4" />
                </x-slot:icon>
                <x-slot:title>Berhasil!</x-slot:title>
                <x-slot:description>
                    {{ session('success') }}
                </x-slot:description>
            </x-ui.alert>
        @endif

        <!-- Filter Card -->
        <x-ui.card class="mb-6">
            <x-ui.card.header>
                <x-ui.card.title>Filter Pemesanan</x-ui.card.title>
                <x-ui.card.description>Cari dan filter data pemesanan tiket</x-ui.card.description>
            </x-ui.card.header>
            <x-ui.card.content>
                <form method="GET" action="{{ route('admin/history-pemesanan') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="space-y-2">
                        <x-ui.label for="nama">Nama Penumpang</x-ui.label>
                        <x-ui.input type="text" name="nama" id="nama" value="{{ request('nama') }}"
                            placeholder="Cari nama..." />
                    </div>

                    <div class="space-y-2">
                        <x-ui.label for="kode_tiket">Kode Tiket</x-ui.label>
                        <x-ui.input type="text" name="kode_tiket" id="kode_tiket" value="{{ request('kode_tiket') }}"
                            placeholder="Cari kode..." />
                    </div>

                    <div class="space-y-2">
                        <x-ui.label for="status">Status</x-ui.label>
                        <select name="status" id="status" class="w-full rounded-md border-input bg-background px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">
                            <option value="">Semua Status</option>
                            <option value="dipesan" {{ request('status') == 'dipesan' ? 'selected' : '' }}>Dipesan</option>
                            <option value="dibayar" {{ request('status') == 'dibayar' ? 'selected' : '' }}>Dibayar</option>
                            <option value="digunakan" {{ request('status') == 'digunakan' ? 'selected' : '' }}>Digunakan</option>
                            <option value="dibatalkan" {{ request('status') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                    </div>

                    <div class="space-y-2">
                        <x-ui.label for="tanggal">Tanggal Pesan</x-ui.label>
                        <x-datepicker name="tanggal" id="tanggal" value="{{ request('tanggal') }}" placeholder="Pilih tanggal..." />
                    </div>

                    <div class="md:col-span-2 lg:col-span-4 flex gap-2">
                        <x-ui.button type="submit">
                            <x-lucide-search class="w-4 h-4 mr-2" />
                            Filter
                        </x-ui.button>
                        <a href="{{ route('admin/history-pemesanan') }}">
                            <x-ui.button type="button" variant="outline">
                                <x-lucide-x class="w-4 h-4 mr-2" />
                                Reset
                            </x-ui.button>
                        </a>
                    </div>
                </form>
            </x-ui.card.content>
        </x-ui.card>

        <!-- Statistics Card -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <x-ui.card>
                <x-ui.card.content class="pt-6">
                    <div class="flex items-center gap-4">
                        <div class="h-12 w-12 rounded-lg bg-blue-500/10 flex items-center justify-center shrink-0">
                            <x-lucide-ticket class="h-6 w-6 text-blue-600" />
                        </div>
                        <div>
                            <p class="text-sm text-muted-foreground">Total Tiket</p>
                            <p class="text-2xl font-bold">{{ $tikets->total() }}</p>
                        </div>
                    </div>
                </x-ui.card.content>
            </x-ui.card>
        </div>

        <!-- Table Card -->
        <x-ui.card>
            <x-ui.card.header>
                <x-ui.card.title>Daftar Pemesanan</x-ui.card.title>
                <x-ui.card.description>Semua riwayat pemesanan tiket yang terdaftar dalam sistem</x-ui.card.description>
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
                                    <x-ui.table.head class="hidden md:table-cell">Tanggal</x-ui.table.head>
                                    <x-ui.table.head class="hidden xl:table-cell text-center">Kursi</x-ui.table.head>
                                    <x-ui.table.head class="hidden sm:table-cell">Harga</x-ui.table.head>
                                    <x-ui.table.head>Status</x-ui.table.head>
                                    <x-ui.table.head class="hidden lg:table-cell">Waktu Pesan</x-ui.table.head>
                                    <x-ui.table.head class="text-right">Aksi</x-ui.table.head>
                                </x-ui.table.row>
                            </x-ui.table.header>
                            <x-ui.table.body>
                                @forelse($tikets as $tiket)
                                    <x-ui.table.row>
                                        <x-ui.table.cell>
                                            <span class="font-mono text-xs sm:text-sm font-semibold">{{ $tiket->kode_tiket }}</span>
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
                                            <p class="text-xs text-muted-foreground mt-1">{{ $tiket->jadwalKelasBus->jadwal->bus->nama_bus }} - {{ $tiket->jadwalKelasBus->kelasBus->nama_kelas }}</p>
                                        </x-ui.table.cell>
                                        <x-ui.table.cell class="hidden md:table-cell">
                                            <div>
                                                <p class="text-sm font-medium">{{ $tiket->jadwalKelasBus->jadwal->tanggal_berangkat->format('d M Y') }}</p>
                                                <p class="text-xs text-muted-foreground">{{ $tiket->jadwalKelasBus->jadwal->jam_berangkat }}</p>
                                            </div>
                                        </x-ui.table.cell>
                                        <x-ui.table.cell class="hidden xl:table-cell text-center">
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-primary/10 text-primary font-semibold text-sm">
                                                {{ $tiket->kursi->nomor_kursi ?? $tiket->kursi ?? '-' }}
                                            </span>
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
                                                $status = $statusConfig[$tiket->status] ?? ['class' => 'bg-gray-100 text-gray-700', 'label' => ucfirst($tiket->status)];
                                            @endphp
                                            <x-ui.badge class="{{ $status['class'] }}">
                                                {{ $status['label'] }}
                                            </x-ui.badge>
                                        </x-ui.table.cell>
                                        <x-ui.table.cell class="hidden lg:table-cell">
                                            <p class="text-xs text-muted-foreground">{{ $tiket->waktu_pesan->format('d/m/Y H:i') }}</p>
                                        </x-ui.table.cell>
                                        <x-ui.table.cell class="text-right">
                                            <a href="{{ route('pemesanan.show', $tiket->id) }}">
                                                <x-ui.button variant="ghost" size="icon">
                                                    <x-lucide-eye class="w-4 h-4" />
                                                </x-ui.button>
                                            </a>
                                        </x-ui.table.cell>
                                    </x-ui.table.row>
                                @empty
                                    <x-ui.table.row>
                                        <x-ui.table.cell colspan="9" class="text-center py-12">
                                            <div class="flex flex-col items-center">
                                                <div class="h-16 w-16 rounded-full bg-muted flex items-center justify-center mb-4">
                                                    <x-lucide-ticket class="w-8 h-8 text-muted-foreground" />
                                                </div>
                                                <h3 class="text-lg font-semibold mb-2">Tidak Ada Data</h3>
                                                <p class="text-sm text-muted-foreground">Belum ada pemesanan tiket yang tercatat</p>
                                            </div>
                                        </x-ui.table.cell>
                                    </x-ui.table.row>
                                @endforelse
                            </x-ui.table.body>
                        </x-ui.table>
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center py-12 text-center">
                        <div class="h-16 w-16 rounded-full bg-muted flex items-center justify-center mb-4">
                            <x-lucide-ticket class="w-8 h-8 text-muted-foreground" />
                        </div>
                        <h3 class="text-lg font-semibold mb-2">Belum Ada Pemesanan</h3>
                        <p class="text-sm text-muted-foreground">Belum ada pemesanan tiket yang tercatat dalam sistem.</p>
                    </div>
                @endif
            </x-ui.card.content>
            @if($tikets->count() > 0)
                <x-ui.card.footer class="border-t pt-4">
                    <div class="w-full flex items-center justify-between">
                        <p class="text-sm text-muted-foreground">
                            Menampilkan {{ $tikets->firstItem() ?? 0 }} - {{ $tikets->lastItem() ?? 0 }} dari {{ $tikets->total() }} pemesanan
                        </p>
                        <div>
                            {{ $tikets->links('vendor.pagination.shadcn') }}
                        </div>
                    </div>
                </x-ui.card.footer>
            @endif
        </x-ui.card>
    </div>
</x-admin-layout>
