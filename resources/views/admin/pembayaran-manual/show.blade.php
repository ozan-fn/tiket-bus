tiket-bus\resources\views\admin\pembayaran-manual\show.blade.php
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
                    <x-ui.breadcrumb.link href="{{ route('admin/pembayaran-manual.index') }}">
                        Pembayaran Manual
                    </x-ui.breadcrumb.link>
                </x-ui.breadcrumb.item>
                <x-ui.breadcrumb.separator>
                    <x-lucide-chevron-right class="w-3.5 h-3.5" />
                </x-ui.breadcrumb.separator>
                <x-ui.breadcrumb.item>
                    <x-ui.breadcrumb.page>
                        Detail Pembayaran
                    </x-ui.breadcrumb.page>
                </x-ui.breadcrumb.item>
            </x-ui.breadcrumb.list>
        </x-ui.breadcrumb.breadcrumb>
    </x-slot>

    <div class="p-4 sm:p-6">
        <div class="max-w-4xl mx-auto">
            <x-ui.card>
                <x-ui.card.header>
                    <div class="flex items-center justify-between">
                        <div>
                            <x-ui.card.title>Detail Pembayaran Manual</x-ui.card.title>
                            <x-ui.card.description>{{ $pembayaran->kode_transaksi }}</x-ui.card.description>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('admin/pembayaran-manual.edit', $pembayaran) }}">
                                <x-ui.button variant="outline" size="sm">
                                    <x-lucide-edit class="w-4 h-4 mr-2" />
                                    Edit
                                </x-ui.button>
                            </a>
                            <a href="{{ route('admin/pembayaran-manual.index') }}">
                                <x-ui.button variant="outline" size="sm">
                                    <x-lucide-arrow-left class="w-4 h-4 mr-2" />
                                    Kembali
                                </x-ui.button>
                            </a>
                        </div>
                    </div>
                </x-ui.card.header>
                <x-ui.card.content>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Kode Transaksi -->
                        <div class="space-y-2">
                            <x-ui.label>Kode Transaksi</x-ui.label>
                            <p class="text-sm font-medium">{{ $pembayaran->kode_transaksi }}</p>
                        </div>

                        <!-- User -->
                        <div class="space-y-2">
                            <x-ui.label>User</x-ui.label>
                            <div class="flex items-center gap-2">
                                <x-ui.avatar class="h-8 w-8">
                                    <x-ui.avatar.fallback class="text-xs">{{ strtoupper(substr($pembayaran->user->name, 0, 2)) }}</x-ui.avatar.fallback>
                                </x-ui.avatar>
                                <div>
                                    <p class="text-sm font-medium">{{ $pembayaran->user->name }}</p>
                                    <p class="text-xs text-muted-foreground">{{ $pembayaran->user->email }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Tiket -->
                        <div class="space-y-2">
                            <x-ui.label>Tiket</x-ui.label>
                            <div class="text-sm">
                                <p class="font-medium">{{ $pembayaran->tiket->kode_tiket }}</p>
                                <p class="text-muted-foreground">{{ $pembayaran->tiket->jadwalKelasBus->jadwal->rute->asalTerminal->nama_terminal ?? '-' }} → {{ $pembayaran->tiket->jadwalKelasBus->jadwal->rute->tujuanTerminal->nama_terminal ?? '-' }}</p>
                                <p class="text-muted-foreground">{{ $pembayaran->tiket->jadwalKelasBus->jadwal->bus->nama }}</p>
                            </div>
                        </div>

                        <!-- Nominal -->
                        <div class="space-y-2">
                            <x-ui.label>Nominal</x-ui.label>
                            <p class="text-sm font-medium">Rp {{ number_format($pembayaran->nominal, 0, ',', '.') }}</p>
                        </div>

                        <!-- Metode -->
                        <div class="space-y-2">
                            <x-ui.label>Metode Pembayaran</x-ui.label>
                            <x-ui.badge variant="{{ $pembayaran->metode == 'tunai' ? 'default' : ($pembayaran->metode == 'transfer' ? 'secondary' : 'outline') }}">
                                {{ ucfirst($pembayaran->metode) }}
                            </x-ui.badge>
                        </div>

                        <!-- Status -->
                        <div class="space-y-2">
                            <x-ui.label>Status</x-ui.label>
                            <x-ui.badge variant="{{ in_array($pembayaran->status, ['dibayar', 'selesai']) ? 'default' : ($pembayaran->status == 'dipesan' ? 'secondary' : 'destructive') }}">
                                {{ ucfirst($pembayaran->status) }}
                            </x-ui.badge>
                        </div>

                        <!-- Waktu Bayar -->
                        <div class="space-y-2">
                            <x-ui.label>Waktu Bayar</x-ui.label>
                            <p class="text-sm">{{ $pembayaran->waktu_bayar ? $pembayaran->waktu_bayar->format('d/m/Y H:i') : '-' }}</p>
                        </div>

                        <!-- Dibuat -->
                        <div class="space-y-2">
                            <x-ui.label>Dibuat</x-ui.label>
                            <p class="text-sm">{{ $pembayaran->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </x-ui.card.content>
            </x-ui.card>
        </div>
    </div>
</x-admin-layout>
