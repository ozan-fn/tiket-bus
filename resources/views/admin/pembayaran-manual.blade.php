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
                        Pembayaran Manual
                    </x-ui.breadcrumb.page>
                </x-ui.breadcrumb.item>
            </x-ui.breadcrumb.list>
        </x-ui.breadcrumb.breadcrumb>
    </x-slot>

    <div class="p-4 sm:p-6">
        <div class="max-w-7xl mx-auto">
            <x-ui.card>
                <x-ui.card.header>
                    <div class="flex items-center justify-between">
                        <div>
                            <x-ui.card.title>Pembayaran Manual</x-ui.card.title>
                            <x-ui.card.description>Kelola pembayaran manual dari penumpang</x-ui.card.description>
                        </div>
                    </div>
                </x-ui.card.header>
                <x-ui.card.content>
                    <!-- Search and Filters -->
                    <div class="flex flex-col sm:flex-row gap-4 mb-6">
                        <div class="flex-1">
                            <form method="GET" class="flex gap-2">
                                <x-ui.input
                                    type="text"
                                    name="search"
                                    value="{{ request('search') }}"
                                    placeholder="Cari kode transaksi atau nama user..."
                                    class="flex-1"
                                />
                                <x-ui.button type="submit" variant="outline" size="sm">
                                    <x-lucide-search class="w-4 h-4" />
                                </x-ui.button>
                            </form>
                        </div>
                        <div class="flex gap-2">
                            <select
                                name="status"
                                onchange="this.form.submit()"
                                class="rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                                <option value="">Semua Status</option>
                                <option value="dipesan" {{ request('status') == 'dipesan' ? 'selected' : '' }}>Dipesan</option>
                                <option value="dibayar" {{ request('status') == 'dibayar' ? 'selected' : '' }}>Dibayar</option>
                                <option value="batal" {{ request('status') == 'batal' ? 'selected' : '' }}>Batal</option>
                                <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            </select>
                            <select
                                name="metode"
                                onchange="this.form.submit()"
                                class="rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                                <option value="">Semua Metode</option>
                                <option value="tunai" {{ request('metode') == 'tunai' ? 'selected' : '' }}>Tunai</option>
                                <option value="transfer" {{ request('metode') == 'transfer' ? 'selected' : '' }}>Transfer</option>
                                <option value="xendit" {{ request('metode') == 'xendit' ? 'selected' : '' }}>Xendit</option>
                            </select>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="overflow-x-auto -mx-4 sm:mx-0">
                        <x-ui.table>
                            <x-ui.table.header class="hidden sm:table-header-group">
                                <x-ui.table.row>
                                    <x-ui.table.head>Kode Transaksi</x-ui.table.head>
                                    <x-ui.table.head>User</x-ui.table.head>
                                    <x-ui.table.head>Tiket</x-ui.table.head>
                                    <x-ui.table.head>Nominal</x-ui.table.head>
                                    <x-ui.table.head>Metode</x-ui.table.head>
                                    <x-ui.table.head>Status</x-ui.table.head>
                                    <x-ui.table.head>Waktu Bayar</x-ui.table.head>
                                    <x-ui.table.head class="w-12">Aksi</x-ui.table.head>
                                </x-ui.table.row>
                            </x-ui.table.header>
                            <x-ui.table.body>
                                @forelse($pembayaran as $item)
                                    <x-ui.table.row class="hidden sm:table-row">
                                        <x-ui.table.cell class="font-medium">{{ $item->kode_transaksi }}</x-ui.table.cell>
                                        <x-ui.table.cell>
                                            <div class="flex items-center gap-2">
                                                <x-ui.avatar class="h-8 w-8">
                                                    <x-ui.avatar.fallback class="text-xs text-white">{{ strtoupper(substr($item->user->name, 0, 2)) }}</x-ui.avatar.fallback>
                                                    @if($item->user->photo)
                                                        <x-ui.avatar.image src="{{ asset('storage/' . $item->user->photo) }}" alt="{{ $item->user->name }}" />
                                                    @endif
                                                </x-ui.avatar>
                                                <div>
                                                    <p class="font-medium text-sm">{{ $item->user->name }}</p>
                                                    <p class="text-xs text-muted-foreground">{{ $item->user->email }}</p>
                                                </div>
                                            </div>
                                        </x-ui.table.cell>
                                        <x-ui.table.cell>
                                            <div class="text-sm">
                                                <p>{{ $item->tiket->jadwalKelasBus->jadwal->rute->asalTerminal->nama_terminal ?? '-' }} → {{ $item->tiket->jadwalKelasBus->jadwal->rute->tujuanTerminal->nama_terminal ?? '-' }}</p>
                                                <p class="text-muted-foreground">{{ $item->tiket->jadwalKelasBus->jadwal->bus->nama }}</p>
                                            </div>
                                        </x-ui.table.cell>
                                        <x-ui.table.cell>Rp {{ number_format($item->nominal, 0, ',', '.') }}</x-ui.table.cell>
                                        <x-ui.table.cell>
                                            <x-ui.badge variant="{{ $item->metode == 'tunai' ? 'default' : ($item->metode == 'transfer' ? 'secondary' : 'outline') }}">
                                                {{ ucfirst($item->metode) }}
                                            </x-ui.badge>
                                        </x-ui.table.cell>
                                        <x-ui.table.cell>
                                            <x-ui.badge variant="{{ in_array($item->status, ['dibayar', 'selesai']) ? 'default' : ($item->status == 'dipesan' ? 'secondary' : 'destructive') }}">
                                                {{ ucfirst($item->status) }}
                                            </x-ui.badge>
                                        </x-ui.table.cell>
                                        <x-ui.table.cell>{{ $item->waktu_bayar ? $item->waktu_bayar->format('d/m/Y H:i') : '-' }}</x-ui.table.cell>
                                        <x-ui.table.cell>
                                            <div class="flex items-center gap-1">
                                                <a href="{{ route('admin/pembayaran-manual.show', $item) }}" class="p-1 rounded hover:bg-accent">
                                                    <x-lucide-eye class="w-4 h-4" />
                                                </a>
                                                <a href="{{ route('admin/pembayaran-manual.edit', $item) }}" class="p-1 rounded hover:bg-accent">
                                                    <x-lucide-edit class="w-4 h-4" />
                                                </a>
                                            </div>
                                        </x-ui.table.cell>
                                    </x-ui.table.row>

                                    <!-- Mobile Card -->
                                    <div class="sm:hidden p-4 border-b border-border last:border-b-0">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1">
                                                <div class="flex items-center gap-2 mb-2">
                                                    <x-ui.avatar class="h-8 w-8">
                                                        <x-ui.avatar.fallback class="text-xs text-white">{{ strtoupper(substr($item->user->name, 0, 2)) }}</x-ui.avatar.fallback>
                                                        @if($item->user->photo)
                                                            <x-ui.avatar.image src="{{ asset('storage/' . $item->user->photo) }}" alt="{{ $item->user->name }}" />
                                                        @endif
                                                    </x-ui.avatar>
                                                    <div>
                                                        <p class="font-medium">{{ $item->user->name }}</p>
                                                        <p class="text-xs text-muted-foreground">{{ $item->user->email }}</p>
                                                    </div>
                                                </div>
                                                <p class="font-medium">{{ $item->kode_transaksi }}</p>
                                                <p class="text-sm">Rp {{ number_format($item->nominal, 0, ',', '.') }} - {{ ucfirst($item->metode) }}</p>
                                                <div class="flex items-center gap-2 mt-2">
                                                    <x-ui.badge variant="{{ in_array($item->status, ['dibayar', 'selesai']) ? 'default' : ($item->status == 'dipesan' ? 'secondary' : 'destructive') }}">
                                                        {{ ucfirst($item->status) }}
                                                    </x-ui.badge>
                                                    <span class="text-xs text-muted-foreground">{{ $item->waktu_bayar ? $item->waktu_bayar->format('d/m/Y H:i') : '-' }}</span>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-1">
                                                <a href="{{ route('admin/pembayaran-manual.show', $item) }}" class="p-2 rounded hover:bg-accent">
                                                    <x-lucide-eye class="w-4 h-4" />
                                                </a>
                                                <a href="{{ route('admin/pembayaran-manual.edit', $item) }}" class="p-2 rounded hover:bg-accent">
                                                    <x-lucide-edit class="w-4 h-4" />
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <x-ui.table.row>
                                        <x-ui.table.cell colspan="8" class="text-center py-12">
                                            <div class="flex flex-col items-center gap-2">
                                                <x-lucide-credit-card class="w-12 h-12 text-muted-foreground" />
                                                <h3 class="text-lg font-medium">Belum ada pembayaran</h3>
                                                <p class="text-sm text-muted-foreground">Pembayaran manual akan muncul di sini.</p>
                                            </div>
                                        </x-ui.table.cell>
                                    </x-ui.table.row>
                                @endforelse
                            </x-ui.table.body>
                        </x-ui.table>
                    </div>

                    <!-- Pagination -->
                    @if($pembayaran->hasPages())
                        <div class="mt-6">
                            {{ $pembayaran->appends(request()->query())->links() }}
                        </div>
                    @endif
                </x-ui.card.content>
            </x-ui.card>
        </div>
    </div>
</x-admin-layout>
