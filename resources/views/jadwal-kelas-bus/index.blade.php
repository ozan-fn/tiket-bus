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
                        Jadwal Kelas Bus
                    </x-ui.breadcrumb.page>
                </x-ui.breadcrumb.item>
            </x-ui.breadcrumb.list>
        </x-ui.breadcrumb.breadcrumb>
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

        @if(session('error'))
            <x-ui.alert class="mb-6" variant="destructive">
                <x-slot:icon>
                    <x-lucide-alert-circle class="w-4 h-4" />
                </x-slot:icon>
                <x-slot:title>Error!</x-slot:title>
                <x-slot:description>
                    {{ session('error') }}
                </x-slot:description>
            </x-ui.alert>
        @endif

        <x-ui.card>
            <x-ui.card.header>
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div class="flex-1">
                        <div class="flex items-center justify-between mb-3">
                            <div>
                                <x-ui.card.title>Harga Tiket</x-ui.card.title>
                                <x-ui.card.description>Atur harga tiket untuk setiap kelas bus</x-ui.card.description>
                            </div>
                            <a href="{{ route('admin/jadwal-kelas-bus.create') }}" class="hidden sm:inline-block">
                                <x-ui.button>
                                    <x-lucide-plus class="w-4 h-4 mr-2" />
                                    Tambah Jadwal Kelas Bus
                                </x-ui.button>
                            </a>
                        </div>

                        <!-- Search Bar & Table Controls -->
                        <div class="flex flex-col gap-2">
                            <div class="flex gap-2 items-center w-full">
                                <form method="GET" action="{{ route('admin/jadwal-kelas-bus.index') }}" class="flex gap-2 flex-1 min-w-0">
                                    <div class="flex flex-row gap-2">
                                        <div class="relative flex-1 min-w-0">
                                            <x-lucide-search class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
                                            <x-ui.input
                                                type="text"
                                                name="search"
                                                placeholder="Cari bus, rute, atau kelas..."
                                                value="{{ $search ?? '' }}"
                                                class="pl-9 h-10 max-w-md"
                                            />
                                        </div>
                                        <x-ui.button size="icon" type="submit" variant="outline" class="h-9 w-9 shrink-0">
                                            <x-lucide-search class="w-4 h-4" />
                                        </x-ui.button>
                                    </div>
                                    @if($search)
                                        <a href="{{ route('admin/jadwal-kelas-bus.index') }}" class="shrink-0">
                                            <x-ui.button size="icon" type="button" variant="outline" class="h-9! w-9! shrink-0">
                                                <x-lucide-x class="w-4 h-4" />
                                            </x-ui.button>
                                        </a>
                                    @endif
                                </form>

                                <!-- Table Controls -->
                                <div class="shrink-0">
                                    <x-table.controls :search="$search" :sort="$sort" :sortField="$sortField" :order="$order" :dateFrom="$dateFrom" :dateTo="$dateTo" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Mobile Add Button -->
                    <a href="{{ route('admin/jadwal-kelas-bus.create') }}" class="sm:hidden w-full">
                        <x-ui.button class="w-full">
                            <x-lucide-plus class="w-4 h-4 mr-2" />
                            Tambah Jadwal Kelas Bus
                        </x-ui.button>
                    </a>
                </div>
            </x-ui.card.header>

            @if($jadwalKelasBus->count() > 0)
                <x-ui.card.content class="p-0 sm:p-6">
                    <div class="overflow-x-auto -mx-4 sm:mx-0">
                        <x-ui.table>
                            <x-ui.table.header class="hidden sm:table-header-group">
                                <x-ui.table.row>
                                    <x-ui.table.head class="w-12 sm:w-16">No</x-ui.table.head>
                                    <x-ui.table.head>Rute</x-ui.table.head>
                                    <x-ui.table.head class="hidden md:table-cell">Bus</x-ui.table.head>
                                    <x-ui.table.head>Kelas</x-ui.table.head>
                                    <x-table.sortable-header name="created_at" class="hidden lg:table-cell">Jadwal</x-table.sortable-header>
                                    <x-table.sortable-header name="harga" class="text-right">Harga</x-table.sortable-header>
                                    <x-ui.table.head class="text-right">Aksi</x-ui.table.head>
                                </x-ui.table.row>
                            </x-ui.table.header>
                            <x-ui.table.body>
                                @foreach($jadwalKelasBus as $index => $jkb)
                                    <x-ui.table.row class="hidden sm:table-row">
                                        <x-ui.table.cell class="font-medium text-xs sm:text-sm">{{ $jadwalKelasBus->firstItem() + $index }}</x-ui.table.cell>
                                        <x-ui.table.cell>
                                            <div class="flex items-center gap-2">
                                                <div class="h-8 w-8 sm:h-10 sm:w-10 rounded-lg bg-primary/10 flex items-center justify-center shrink-0">
                                                    <x-lucide-route class="h-4 w-4 sm:h-5 sm:w-5 text-primary" />
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="font-medium text-sm truncate">{{ $jkb->jadwal->rute->asalTerminal->nama_terminal }}</p>
                                                    <div class="flex items-center gap-1 text-xs text-muted-foreground">
                                                        <x-lucide-arrow-right class="h-3 w-3" />
                                                        <span class="truncate">{{ $jkb->jadwal->rute->tujuanTerminal->nama_terminal }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </x-ui.table.cell>
                                        <x-ui.table.cell class="hidden md:table-cell">
                                            <div class="flex items-center gap-2">
                                                <x-lucide-bus class="h-4 w-4 text-muted-foreground" />
                                                <div>
                                                    <p class="text-sm font-medium">{{ $jkb->jadwal->bus->nama }}</p>
                                                    <p class="text-xs text-muted-foreground">{{ $jkb->jadwal->bus->plat_nomor }}</p>
                                                </div>
                                            </div>
                                        </x-ui.table.cell>
                                        <x-ui.table.cell>
                                            <x-ui.badge class="bg-primary/10 text-primary">
                                                {{ $jkb->kelasBus->nama_kelas }}
                                            </x-ui.badge>
                                        </x-ui.table.cell>
                                        <x-ui.table.cell class="hidden lg:table-cell">
                                            <div class="text-sm">
                                                <div class="flex items-center gap-1 mb-1">
                                                    <x-lucide-calendar class="h-3 w-3 text-muted-foreground" />
                                                    <span class="font-medium">{{ \Carbon\Carbon::parse($jkb->jadwal->tanggal_berangkat)->format('d M Y') }}</span>
                                                </div>
                                                <div class="flex items-center gap-1 text-muted-foreground">
                                                    <x-lucide-clock class="h-3 w-3" />
                                                    <span class="text-xs">{{ \Carbon\Carbon::parse($jkb->jadwal->jam_berangkat)->format('H:i') }}</span>
                                                </div>
                                            </div>
                                        </x-ui.table.cell>
                                        <x-ui.table.cell class="text-right">
                                            <div class="font-semibold text-sm text-primary">
                                                Rp {{ number_format($jkb->harga, 0, ',', '.') }}
                                            </div>
                                        </x-ui.table.cell>
                                        <x-ui.table.cell class="text-right">
                                            <div class="flex items-center justify-end gap-1">
                                                <a href="{{ route('admin/jadwal-kelas-bus.show', $jkb) }}" class="hidden sm:inline-block">
                                                    <x-ui.button variant="ghost" size="sm">
                                                        <x-lucide-eye class="w-4 h-4" />
                                                    </x-ui.button>
                                                </a>
                                                <a href="{{ route('admin/jadwal-kelas-bus.edit', $jkb) }}">
                                                    <x-ui.button variant="ghost" size="sm">
                                                        <x-lucide-pencil class="w-4 h-4" />
                                                    </x-ui.button>
                                                </a>
                                                <div x-data="{ open: false }">
                                                    <x-ui.button @click="open = true" variant="ghost" size="sm" class="text-destructive hover:bg-destructive/10">
                                                        <x-lucide-trash-2 class="w-4 h-4" />
                                                    </x-ui.button>

                                                    <!-- Delete Dialog -->
                                                    <template x-teleport="body">
                                                        <div x-show="open" x-cloak class="fixed inset-0 z-50 overflow-y-auto" @keydown.escape.window="open = false">
                                                            <div x-show="open" x-transition.opacity class="fixed inset-0 bg-black/50" @click="open = false"></div>

                                                            <div class="flex min-h-full items-center justify-center p-4">
                                                                <div x-show="open" x-transition class="relative bg-card border border-border rounded-lg shadow-lg w-full max-w-md">
                                                                    <div class="p-6">
                                                                        <div class="flex items-start gap-4 mb-4">
                                                                            <div class="h-12 w-12 rounded-full bg-destructive/10 flex items-center justify-center shrink-0">
                                                                                <x-lucide-alert-triangle class="h-6 w-6 text-destructive" />
                                                                            </div>
                                                                            <div class="flex-1">
                                                                                <h3 class="text-lg font-semibold mb-2">Hapus Jadwal Kelas Bus</h3>
                                                                                <p class="text-sm text-muted-foreground">
                                                                                    Apakah Anda yakin ingin menghapus jadwal kelas bus ini? Tindakan ini tidak dapat dibatalkan.
                                                                                </p>
                                                                            </div>
                                                                        </div>

                                                                        <div class="flex flex-col-reverse sm:flex-row gap-2 justify-end">
                                                                            <x-ui.button @click="open = false" variant="outline">
                                                                                Batal
                                                                            </x-ui.button>
                                                                            <form method="POST" action="{{ route('admin/jadwal-kelas-bus.destroy', $jkb) }}" class="inline">
                                                                                @csrf
                                                                                @method('DELETE')
                                                                                <x-ui.button type="submit" variant="destructive">
                                                                                    <x-lucide-trash-2 class="w-4 h-4 mr-2" />
                                                                                    Ya, Hapus
                                                                                </x-ui.button>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </template>
                                                </div>
                                            </div>
                                        </x-ui.table.cell>
                                    </x-ui.table.row>
                                @endforeach
                            </x-ui.table.body>
                        </x-ui.table>
                    </div>
                </x-ui.card.content>

                <div class="border-t pt-4 px-6 pb-6">
                    <div class="w-full flex flex-col sm:flex-row items-center justify-between gap-3">
                        <p class="text-xs sm:text-sm text-muted-foreground">
                            Menampilkan {{ $jadwalKelasBus->firstItem() }} - {{ $jadwalKelasBus->lastItem() }} dari {{ $jadwalKelasBus->total() }} jadwal kelas bus
                        </p>
                        <div class="w-full sm:w-auto">
                            {{ $jadwalKelasBus->links('vendor.pagination.shadcn') }}
                        </div>
                    </div>
                </div>
            @else
                <x-ui.card.content>
                    <div class="p-12 text-center">
                        <x-lucide-inbox class="w-12 h-12 text-muted-foreground mx-auto mb-4" />
                        <h3 class="text-lg font-semibold text-foreground mb-2">Tidak ada jadwal kelas bus</h3>
                        <p class="text-muted-foreground mb-6">Tidak ada data jadwal kelas bus untuk ditampilkan</p>
                        <a href="{{ route('admin/jadwal-kelas-bus.create') }}">
                            <x-ui.button>
                                <x-lucide-plus class="w-4 h-4 mr-2" />
                                Tambah Jadwal Kelas Bus
                            </x-ui.button>
                        </a>
                    </div>
                </x-ui.card.content>
            @endif
        </x-ui.card>
    </div>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</x-admin-layout>
