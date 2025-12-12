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
                    <x-ui.breadcrumb.link href="{{ route('admin/jadwal-kelas-bus.index') }}">
                        Jadwal Kelas Bus
                    </x-ui.breadcrumb.link>
                </x-ui.breadcrumb.item>
                <x-ui.breadcrumb.separator>
                    <x-lucide-chevron-right class="w-3.5 h-3.5" />
                </x-ui.breadcrumb.separator>
                <x-ui.breadcrumb.item>
                    <x-ui.breadcrumb.page>
                        Detail Jadwal Kelas Bus
                    </x-ui.breadcrumb.page>
                </x-ui.breadcrumb.item>
            </x-ui.breadcrumb.list>
        </x-ui.breadcrumb.breadcrumb>
    </x-slot>

    <div class="p-4 sm:p-6">
        <div class="max-w-7xl mx-auto space-y-6">
            <!-- Main Info Card -->
            <x-ui.card>
                <x-ui.card.header>
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="h-16 w-16 rounded-lg bg-primary/10 flex items-center justify-center">
                                <x-lucide-ticket class="h-8 w-8 text-primary" />
                            </div>
                            <div>
                                <x-ui.card.title class="text-2xl">Jadwal Kelas Bus</x-ui.card.title>
                                <x-ui.card.description>{{ $jadwalKelasBu->jadwal->rute->asalTerminal->nama_kota ?? '-' }} → {{ $jadwalKelasBu->jadwal->rute->tujuanTerminal->nama_kota ?? '-' }}</x-ui.card.description>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 w-full sm:w-auto">
                            <a href="{{ route('admin/jadwal-kelas-bus.edit', $jadwalKelasBu) }}" class="flex-1 sm:flex-initial">
                                <x-ui.button variant="outline" class="w-full">
                                    <x-lucide-pencil class="w-4 h-4 mr-2" />
                                    Edit
                                </x-ui.button>
                            </a>

                            @if($jadwalKelasBu->tikets->count() == 0)
                            <!-- Delete Dialog -->
                            <div x-data="{ open: false }">
                                <x-ui.button @click="open = true" variant="outline" class="text-destructive hover:bg-destructive/10">
                                    <x-lucide-trash-2 class="w-4 h-4 mr-2" />
                                    Hapus
                                </x-ui.button>

                                <!-- Dialog Overlay & Content -->
                                <template x-teleport="body">
                                    <div x-show="open"
                                         x-cloak
                                         class="fixed inset-0 z-50 overflow-y-auto"
                                         @keydown.escape.window="open = false">
                                        <!-- Overlay -->
                                        <div x-show="open"
                                             x-transition:enter="transition ease-out duration-200"
                                             x-transition:enter-start="opacity-0"
                                             x-transition:enter-end="opacity-100"
                                             x-transition:leave="transition ease-in duration-150"
                                             x-transition:leave-start="opacity-100"
                                             x-transition:leave-end="opacity-0"
                                             @click="open = false"
                                             class="fixed inset-0 bg-black/50 backdrop-blur-sm">
                                        </div>

                                        <!-- Dialog Content -->
                                        <div class="flex min-h-full items-center justify-center p-4">
                                            <div x-show="open"
                                                 x-transition:enter="transition ease-out duration-200"
                                                 x-transition:enter-start="opacity-0 scale-95"
                                                 x-transition:enter-end="opacity-100 scale-100"
                                                 x-transition:leave="transition ease-in duration-150"
                                                 x-transition:leave-start="opacity-100 scale-100"
                                                 x-transition:leave-end="opacity-0 scale-95"
                                                 @click.stop
                                                 class="relative w-full max-w-lg bg-card rounded-lg shadow-lg border border-border p-6">

                                                <div class="flex items-start gap-4">
                                                    <div class="h-12 w-12 rounded-full bg-destructive/10 flex items-center justify-center shrink-0">
                                                        <x-lucide-alert-triangle class="h-6 w-6 text-destructive" />
                                                    </div>
                                                    <div class="flex-1">
                                                        <h3 class="text-lg font-semibold mb-2">Hapus Jadwal Kelas Bus</h3>
                                                        <p class="text-sm text-muted-foreground mb-4">
                                                            Apakah Anda yakin ingin menghapus jadwal kelas bus ini?
                                                            Tindakan ini tidak dapat dibatalkan.
                                                        </p>

                                                        <div class="flex flex-col-reverse sm:flex-row gap-2 justify-end">
                                                            <x-ui.button type="button" variant="outline" @click="open = false">
                                                                Batal
                                                            </x-ui.button>
                                                            <form method="POST" action="{{ route('admin/jadwal-kelas-bus.destroy', $jadwalKelasBu) }}" class="inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <x-ui.button type="submit" class="w-full sm:w-auto bg-destructive text-destructive-foreground hover:bg-destructive/90">
                                                                    <x-lucide-trash-2 class="w-4 h-4 mr-2" />
                                                                    Ya, Hapus
                                                                </x-ui.button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                            @else
                            <x-ui.button variant="outline" disabled class="cursor-not-allowed opacity-50">
                                <x-lucide-trash-2 class="w-4 h-4 mr-2" />
                                Tidak Dapat Dihapus
                            </x-ui.button>
                            @endif

                            <a href="{{ route('admin/jadwal-kelas-bus.index') }}">
                                <x-ui.button variant="outline">
                                    <x-lucide-arrow-left class="w-4 h-4 mr-2" />
                                    Kembali
                                </x-ui.button>
                            </a>
                        </div>
                    </div>
                </x-ui.card.header>
                <x-ui.card.content>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <!-- ID -->
                        <div class="flex items-start gap-3 p-4 rounded-lg bg-muted/50">
                            <div class="h-10 w-10 rounded-lg bg-blue-500/10 flex items-center justify-center shrink-0">
                                <x-lucide-fingerprint class="h-5 w-5 text-blue-600 dark:text-blue-400" />
                            </div>
                            <div>
                                <p class="text-sm text-muted-foreground">ID</p>
                                <p class="text-xl font-bold">#{{ $jadwalKelasBu->id }}</p>
                            </div>
                        </div>

                        <!-- Harga -->
                        <div class="flex items-start gap-3 p-4 rounded-lg bg-muted/50">
                            <div class="h-10 w-10 rounded-lg bg-green-500/10 flex items-center justify-center shrink-0">
                                <x-lucide-banknote class="h-5 w-5 text-green-600 dark:text-green-400" />
                            </div>
                            <div>
                                <p class="text-sm text-muted-foreground">Harga</p>
                                <p class="text-xl font-bold">Rp {{ number_format($jadwalKelasBu->harga, 0, ',', '.') }}</p>
                            </div>
                        </div>

                        <!-- Tiket Terjual -->
                        <div class="flex items-start gap-3 p-4 rounded-lg bg-muted/50">
                            <div class="h-10 w-10 rounded-lg bg-purple-500/10 flex items-center justify-center shrink-0">
                                <x-lucide-ticket class="h-5 w-5 text-purple-600 dark:text-purple-400" />
                            </div>
                            <div>
                                <p class="text-sm text-muted-foreground">Tiket Terjual</p>
                                <p class="text-2xl font-bold">{{ $jadwalKelasBu->tikets->count() }}</p>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="flex items-start gap-3 p-4 rounded-lg bg-muted/50">
                            <div class="h-10 w-10 rounded-lg bg-orange-500/10 flex items-center justify-center shrink-0">
                                <x-lucide-info class="h-5 w-5 text-orange-600 dark:text-orange-400" />
                            </div>
                            <div>
                                <p class="text-sm text-muted-foreground">Status</p>
                                @if($jadwalKelasBu->tikets->count() > 0)
                                    <x-ui.badge class="bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                                        <x-lucide-circle-check class="h-3 w-3" />
                                        Ada Tiket
                                    </x-ui.badge>
                                @else
                                    <x-ui.badge variant="secondary">
                                        <x-lucide-circle class="h-3 w-3" />
                                        Belum Ada Tiket
                                    </x-ui.badge>
                                @endif
                            </div>
                        </div>
                    </div>
                </x-ui.card.content>
            </x-ui.card>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Informasi Jadwal -->
                <x-ui.card>
                    <x-ui.card.header>
                        <div class="flex items-center gap-2">
                            <x-lucide-calendar class="w-5 h-5 text-primary" />
                            <x-ui.card.title>Informasi Jadwal</x-ui.card.title>
                        </div>
                        <x-ui.card.description>Detail waktu keberangkatan</x-ui.card.description>
                    </x-ui.card.header>
                    <x-ui.card.content>
                        <div class="space-y-4">
                            <!-- Tanggal Berangkat -->
                            <div class="flex items-center gap-4 p-3 rounded-lg border border-border bg-card">
                                <div class="h-12 w-12 rounded-lg bg-blue-100 dark:bg-blue-900/20 flex items-center justify-center shrink-0">
                                    <x-lucide-calendar class="h-6 w-6 text-blue-600 dark:text-blue-400" />
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm text-muted-foreground">Tanggal Berangkat</p>
                                    <p class="text-lg font-semibold">{{ $jadwalKelasBu->jadwal->tanggal_berangkat->format('d F Y') }}</p>
                                    <p class="text-xs text-muted-foreground">{{ $jadwalKelasBu->jadwal->tanggal_berangkat->diffForHumans() }}</p>
                                </div>
                            </div>

                            <!-- Jam Berangkat -->
                            <div class="flex items-center gap-4 p-3 rounded-lg border border-border bg-card">
                                <div class="h-12 w-12 rounded-lg bg-purple-100 dark:bg-purple-900/20 flex items-center justify-center shrink-0">
                                    <x-lucide-clock class="h-6 w-6 text-purple-600 dark:text-purple-400" />
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm text-muted-foreground">Jam Berangkat</p>
                                    <p class="text-lg font-semibold">{{ $jadwalKelasBu->jadwal->jam_berangkat->format('H:i') }} WIB</p>
                                </div>
                            </div>

                            <!-- Status Jadwal -->
                            <div class="flex items-center gap-4 p-3 rounded-lg border border-border bg-card">
                                <div class="h-12 w-12 rounded-lg bg-green-100 dark:bg-green-900/20 flex items-center justify-center shrink-0">
                                    <x-lucide-power class="h-6 w-6 text-green-600 dark:text-green-400" />
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm text-muted-foreground">Status Jadwal</p>
                                    @if($jadwalKelasBu->jadwal->status === 'aktif')
                                        <x-ui.badge class="bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                                            <x-lucide-circle-check class="h-3 w-3" />
                                            Aktif
                                        </x-ui.badge>
                                    @else
                                        <x-ui.badge variant="secondary">
                                            <x-lucide-circle-x class="h-3 w-3" />
                                            Tidak Aktif
                                        </x-ui.badge>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </x-ui.card.content>
                </x-ui.card>

                <!-- Informasi Kelas -->
                <x-ui.card>
                    <x-ui.card.header>
                        <div class="flex items-center gap-2">
                            <x-lucide-armchair class="w-5 h-5 text-primary" />
                            <x-ui.card.title>Informasi Kelas</x-ui.card.title>
                        </div>
                        <x-ui.card.description>Detail kelas bus</x-ui.card.description>
                    </x-ui.card.header>
                    <x-ui.card.content>
                        <div class="space-y-4">
                            <!-- Nama Kelas -->
                            <div class="flex items-center gap-4 p-3 rounded-lg border border-border bg-card">
                                <div class="h-12 w-12 rounded-lg bg-orange-100 dark:bg-orange-900/20 flex items-center justify-center shrink-0">
                                    <x-lucide-tag class="h-6 w-6 text-orange-600 dark:text-orange-400" />
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm text-muted-foreground">Nama Kelas</p>
                                    <p class="text-lg font-semibold">{{ $jadwalKelasBu->kelasBus->nama_kelas }}</p>
                                </div>
                                <a href="{{ route('admin/kelas-bus.show', $jadwalKelasBu->kelasBus) }}">
                                    <x-ui.button variant="ghost" size="sm">
                                        <x-lucide-eye class="w-4 h-4" />
                                    </x-ui.button>
                                </a>
                            </div>

                            <!-- Jumlah Kursi -->
                            <div class="flex items-center gap-4 p-3 rounded-lg border border-border bg-card">
                                <div class="h-12 w-12 rounded-lg bg-cyan-100 dark:bg-cyan-900/20 flex items-center justify-center shrink-0">
                                    <x-lucide-armchair class="h-6 w-6 text-cyan-600 dark:text-cyan-400" />
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm text-muted-foreground">Jumlah Kursi</p>
                                    <p class="text-lg font-semibold">{{ $jadwalKelasBu->kelasBus->jumlah_kursi }} Kursi</p>
                                </div>
                            </div>

                            <!-- Deskripsi Kelas -->
                            @if($jadwalKelasBu->kelasBus->deskripsi)
                            <div class="p-3 rounded-lg border border-border bg-card">
                                <p class="text-sm text-muted-foreground mb-2">Deskripsi Kelas</p>
                                <p class="text-sm">{{ $jadwalKelasBu->kelasBus->deskripsi }}</p>
                            </div>
                            @endif
                        </div>
                    </x-ui.card.content>
                </x-ui.card>
            </div>

            <!-- Rute Perjalanan -->
            <x-ui.card>
                <x-ui.card.header>
                    <div class="flex items-center gap-2">
                        <x-lucide-route class="w-5 h-5 text-primary" />
                        <x-ui.card.title>Rute Perjalanan</x-ui.card.title>
                    </div>
                    <x-ui.card.description>Informasi rute dan terminal</x-ui.card.description>
                </x-ui.card.header>
                <x-ui.card.content>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Terminal Asal -->
                        <div class="space-y-4 p-4 rounded-lg border border-border bg-card">
                            <div class="flex items-center gap-3 pb-3 border-b border-border">
                                <div class="h-12 w-12 rounded-lg bg-blue-100 dark:bg-blue-900/20 flex items-center justify-center">
                                    <x-lucide-map-pin class="h-6 w-6 text-blue-600 dark:text-blue-400" />
                                </div>
                                <div>
                                    <p class="text-sm text-muted-foreground">Terminal Asal</p>
                                    <p class="text-lg font-semibold">Keberangkatan</p>
                                </div>
                            </div>

                            <div class="space-y-3">
                                <div>
                                    <p class="text-xs text-muted-foreground mb-1">Nama Terminal</p>
                                    <p class="text-sm font-medium">{{ $jadwalKelasBu->jadwal->rute->asalTerminal->nama_terminal ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-muted-foreground mb-1">Kota</p>
                                    <div class="flex items-center gap-2">
                                        <x-lucide-map-pin class="h-4 w-4 text-muted-foreground" />
                                        <p class="text-sm font-medium">{{ $jadwalKelasBu->jadwal->rute->asalTerminal->nama_kota ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Terminal Tujuan -->
                        <div class="space-y-4 p-4 rounded-lg border border-border bg-card">
                            <div class="flex items-center gap-3 pb-3 border-b border-border">
                                <div class="h-12 w-12 rounded-lg bg-green-100 dark:bg-green-900/20 flex items-center justify-center">
                                    <x-lucide-map-pin-check-inside class="h-6 w-6 text-green-600 dark:text-green-400" />
                                </div>
                                <div>
                                    <p class="text-sm text-muted-foreground">Terminal Tujuan</p>
                                    <p class="text-lg font-semibold">Kedatangan</p>
                                </div>
                            </div>

                            <div class="space-y-3">
                                <div>
                                    <p class="text-xs text-muted-foreground mb-1">Nama Terminal</p>
                                    <p class="text-sm font-medium">{{ $jadwalKelasBu->jadwal->rute->tujuanTerminal->nama_terminal ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-muted-foreground mb-1">Kota</p>
                                    <div class="flex items-center gap-2">
                                        <x-lucide-map-pin class="h-4 w-4 text-muted-foreground" />
                                        <p class="text-sm font-medium">{{ $jadwalKelasBu->jadwal->rute->tujuanTerminal->nama_kota ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </x-ui.card.content>
            </x-ui.card>

            <!-- Informasi Bus & Sopir -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Bus -->
                <x-ui.card>
                    <x-ui.card.header>
                        <div class="flex items-center gap-2">
                            <x-lucide-bus class="w-5 h-5 text-primary" />
                            <x-ui.card.title>Informasi Bus</x-ui.card.title>
                        </div>
                        <x-ui.card.description>Detail bus yang digunakan</x-ui.card.description>
                    </x-ui.card.header>
                    <x-ui.card.content>
                        <div class="space-y-4">
                            <div class="flex items-center gap-4 p-4 rounded-lg border border-border bg-card">
                                <div class="h-16 w-16 rounded-lg bg-primary/10 flex items-center justify-center shrink-0">
                                    <x-lucide-bus class="h-8 w-8 text-primary" />
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-lg font-semibold">{{ $jadwalKelasBu->jadwal->bus->nama }}</h4>
                                    <div class="flex items-center gap-4 mt-2 text-sm text-muted-foreground">
                                        <div class="flex items-center gap-1">
                                            <x-lucide-hash class="h-4 w-4" />
                                            <span>{{ $jadwalKelasBu->jadwal->bus->plat_nomor }}</span>
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <x-lucide-users class="h-4 w-4" />
                                            <span>{{ $jadwalKelasBu->jadwal->bus->kapasitas }} kursi</span>
                                        </div>
                                    </div>
                                </div>
                                <a href="{{ route('admin/bus.show', $jadwalKelasBu->jadwal->bus) }}">
                                    <x-ui.button variant="outline" size="sm">
                                        <x-lucide-eye class="w-4 h-4" />
                                    </x-ui.button>
                                </a>
                            </div>
                        </div>
                    </x-ui.card.content>
                </x-ui.card>

                <!-- Sopir -->
                <x-ui.card>
                    <x-ui.card.header>
                        <div class="flex items-center gap-2">
                            <x-lucide-user-round class="w-5 h-5 text-primary" />
                            <x-ui.card.title>Informasi Sopir</x-ui.card.title>
                        </div>
                        <x-ui.card.description>Detail sopir yang bertugas</x-ui.card.description>
                    </x-ui.card.header>
                    <x-ui.card.content>
                        <div class="space-y-4">
                            <div class="flex items-center gap-4 p-4 rounded-lg border border-border bg-card">
                                <div class="h-16 w-16 rounded-lg bg-primary/10 flex items-center justify-center shrink-0">
                                    <x-lucide-user-round class="h-8 w-8 text-primary" />
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-lg font-semibold">{{ $jadwalKelasBu->jadwal->sopir->user->name ?? 'N/A' }}</h4>
                                    <div class="flex items-center gap-4 mt-2 text-sm text-muted-foreground">
                                        <div class="flex items-center gap-1">
                                            <x-lucide-id-card class="h-4 w-4" />
                                            <span>{{ $jadwalKelasBu->jadwal->sopir->nomor_sim }}</span>
                                        </div>
                                    </div>
                                </div>
                                <a href="{{ route('admin/sopir.show', $jadwalKelasBu->jadwal->sopir) }}">
                                    <x-ui.button variant="outline" size="sm">
                                        <x-lucide-eye class="w-4 h-4" />
                                    </x-ui.button>
                                </a>
                            </div>
                        </div>
                    </x-ui.card.content>
                </x-ui.card>
            </div>

            <!-- Daftar Tiket -->
            @if($jadwalKelasBu->tikets && $jadwalKelasBu->tikets->count() > 0)
            <x-ui.card>
                <x-ui.card.header>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <x-lucide-ticket class="w-5 h-5 text-primary" />
                            <x-ui.card.title>Daftar Tiket</x-ui.card.title>
                        </div>
                        <x-ui.badge variant="secondary">
                            {{ $jadwalKelasBu->tikets->count() }} Tiket
                        </x-ui.badge>
                    </div>
                    <x-ui.card.description>Tiket yang telah dipesan pada jadwal kelas bus ini</x-ui.card.description>
                </x-ui.card.header>
                <x-ui.card.content>
                    <div class="overflow-x-auto">
                        <table class="w-full caption-bottom text-sm">
                            <thead class="[&_tr]:border-b">
                                <tr class="border-b transition-colors hover:bg-muted/50">
                                    <th class="h-10 px-2 text-left align-middle font-medium">No Tiket</th>
                                    <th class="h-10 px-2 text-left align-middle font-medium">Penumpang</th>
                                    <th class="h-10 px-2 text-left align-middle font-medium">Kursi</th>
                                    <th class="h-10 px-2 text-left align-middle font-medium">Status</th>
                                    <th class="h-10 px-2 text-right align-middle font-medium">Harga</th>
                                </tr>
                            </thead>
                            <tbody class="[&_tr:last-child]:border-0">
                                @foreach($jadwalKelasBu->tikets as $tiket)
                                <tr class="border-b transition-colors hover:bg-muted/50">
                                    <td class="p-2 align-middle">
                                        <span class="font-mono text-xs">{{ $tiket->kode_tiket }}</span>
                                    </td>
                                    <td class="p-2 align-middle">
                                        <div>
                                            <p class="font-medium">{{ $tiket->nama_penumpang }}</p>
                                            <p class="text-xs text-muted-foreground">{{ $tiket->email ?? '-' }}</p>
                                        </div>
                                    </td>
                                    <td class="p-2 align-middle">
                                        @if($tiket->kursi)
                                            <x-ui.badge variant="outline">
                                                <x-lucide-armchair class="h-3 w-3 mr-1" />
                                                {{ $tiket->kursi->nomor_kursi }}
                                            </x-ui.badge>
                                        @else
                                            <span class="text-xs text-muted-foreground">-</span>
                                        @endif
                                    </td>
                                    <td class="p-2 align-middle">
                                        @if($tiket->status === 'dibayar')
                                            <x-ui.badge class="bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                                                Dibayar
                                            </x-ui.badge>
                                        @elseif($tiket->status === 'pending')
                                            <x-ui.badge class="bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400">
                                                Pending
                                            </x-ui.badge>
                                        @else
                                            <x-ui.badge variant="secondary">
                                                {{ ucfirst($tiket->status) }}
                                            </x-ui.badge>
                                        @endif
                                    </td>
                                    <td class="p-2 align-middle text-right">
                                        <span class="font-semibold">Rp {{ number_format($tiket->harga, 0, ',', '.') }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </x-ui.card.content>
            </x-ui.card>
            @endif

            <!-- Informasi Tambahan -->
            <x-ui.card>
                <x-ui.card.header>
                    <div class="flex items-center gap-2">
                        <x-lucide-info class="w-5 h-5 text-primary" />
                        <x-ui.card.title>Informasi Tambahan</x-ui.card.title>
                    </div>
                </x-ui.card.header>
                <x-ui.card.content>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="flex items-start gap-3">
                            <x-lucide-calendar-plus class="w-5 h-5 text-muted-foreground mt-0.5" />
                            <div>
                                <p class="text-sm text-muted-foreground">Dibuat</p>
                                <p class="text-sm font-medium">{{ $jadwalKelasBu->created_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <x-lucide-calendar-check class="w-5 h-5 text-muted-foreground mt-0.5" />
                            <div>
                                <p class="text-sm text-muted-foreground">Terakhir Diupdate</p>
                                <p class="text-sm font-medium">{{ $jadwalKelasBu->updated_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </x-ui.card.content>
            </x-ui.card>
        </div>
    </div>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</x-admin-layout>
