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
                        Kelas Bus
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

        <x-ui.card>
            <x-ui.card.header>
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div class="flex-1">
                        <div class="flex items-center justify-between mb-3">
                            <div>
                                <x-ui.card.title>Daftar Kelas Bus</x-ui.card.title>
                                <x-ui.card.description>Semua kelas bus yang terdaftar dalam sistem</x-ui.card.description>
                            </div>
                            <a href="{{ route('admin/kelas-bus.create') }}" class="hidden sm:inline-block">
                                <x-ui.button>
                                    <x-lucide-plus class="w-4 h-4 mr-2" />
                                    Tambah Kelas Bus
                                </x-ui.button>
                            </a>
                        </div>

                        <!-- Search Bar & Table Controls -->
                        <div class="flex flex-col gap-2">
                            <div class="flex gap-2 items-center w-full">
                                <form method="GET" action="{{ route('admin/kelas-bus.index') }}" class="flex gap-2 flex-1 min-w-0">
                                    <div class="flex flex-row gap-2">
                                        <div class="relative flex-1 min-w-0">
                                            <x-lucide-search class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
                                            <x-ui.input
                                                type="text"
                                                name="search"
                                                placeholder="Cari nama kelas, bus, atau harga..."
                                                value="{{ $search ?? '' }}"
                                                class="pl-9 h-10 max-w-md"
                                            />
                                        </div>
                                        <x-ui.button size="icon" type="submit" variant="outline" class="h-9 w-9 shrink-0">
                                            <x-lucide-search class="w-4 h-4" />
                                        </x-ui.button>
                                    </div>
                                    @if($search)
                                        <a href="{{ route('admin/kelas-bus.index') }}" class="shrink-0">
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
                    <a href="{{ route('admin/kelas-bus.create') }}" class="sm:hidden w-full">
                        <x-ui.button class="w-full">
                            <x-lucide-plus class="w-4 h-4 mr-2" />
                            Tambah Kelas Bus
                        </x-ui.button>
                    </a>
                </div>
            </x-ui.card.header>
            <x-ui.card.content class="p-0 sm:p-6">
                @if($kelasBus->count() > 0)
                    <div class="overflow-x-auto -mx-4 sm:mx-0">
                        <x-ui.table>
                            <x-ui.table.header class="hidden sm:table-header-group">
                                <x-ui.table.row>
                                    <x-ui.table.head class="w-12 sm:w-16">No</x-ui.table.head>
                                    <x-ui.table.head>Bus</x-ui.table.head>
                                    <x-table.sortable-header name="nama_kelas">Nama Kelas</x-table.sortable-header>
                                    <x-ui.table.head class="hidden md:table-cell">Deskripsi</x-ui.table.head>
                                    <x-ui.table.head class="hidden sm:table-cell text-center">Jumlah Kursi</x-ui.table.head>
                                    <x-table.sortable-header name="created_at" class="hidden lg:table-cell">Tanggal Dibuat</x-table.sortable-header>
                                    <x-ui.table.head class="text-right">Aksi</x-ui.table.head>
                                </x-ui.table.row>
                            </x-ui.table.header>
                            <x-ui.table.body>
                                @foreach($kelasBus as $index => $item)
                                    <!-- Desktop View -->
                                    <x-ui.table.row class="hidden sm:table-row">
                                        <x-ui.table.cell class="font-medium text-xs sm:text-sm">{{ $kelasBus->firstItem() + $index }}</x-ui.table.cell>
                                        <x-ui.table.cell>
                                            <div class="text-sm font-medium">{{ $item->bus->nama }}</div>
                                            <div class="text-xs text-muted-foreground">{{ $item->bus->plat_nomor }}</div>
                                        </x-ui.table.cell>
                                        <x-ui.table.cell>
                                            <div class="flex items-center gap-2 sm:gap-3">
                                                <div class="h-8 w-8 sm:h-10 sm:w-10 rounded-lg bg-primary/10 flex items-center justify-center shrink-0">
                                                    <x-lucide-tag class="h-4 w-4 sm:h-5 sm:w-5 text-primary" />
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="font-medium text-sm sm:text-base truncate">{{ $item->nama_kelas }}</p>
                                                </div>
                                            </div>
                                        </x-ui.table.cell>
                                        <x-ui.table.cell class="hidden md:table-cell text-sm text-muted-foreground">
                                            {{ $item->deskripsi ?? '-' }}
                                        </x-ui.table.cell>
                                        <x-ui.table.cell class="hidden sm:table-cell text-center">
                                            <div class="inline-flex items-center gap-1 px-2 py-1 bg-muted rounded-md">
                                                <x-lucide-users class="h-4 w-4 text-muted-foreground" />
                                                <span class="text-sm font-medium">{{ $item->jumlah_kursi }}</span>
                                            </div>
                                        </x-ui.table.cell>
                                        <x-ui.table.cell class="hidden lg:table-cell text-sm text-muted-foreground">
                                            {{ $item->created_at->format('d M Y') }}
                                        </x-ui.table.cell>
                                        <x-ui.table.cell class="text-right">
                                            <div class="flex items-center justify-end gap-1">
                                                <a href="{{ route('admin/kelas-bus.show', $item) }}" class="hidden sm:inline-block">
                                                    <x-ui.button variant="ghost" size="sm">
                                                        <x-lucide-eye class="w-4 h-4" />
                                                    </x-ui.button>
                                                </a>
                                                <a href="{{ route('admin/kelas-bus.edit', $item) }}">
                                                    <x-ui.button variant="ghost" size="sm">
                                                        <x-lucide-pencil class="w-4 h-4" />
                                                    </x-ui.button>
                                                </a>
                                                <div x-data="{ open: false }">
                                                    <x-ui.button @click="open = true" variant="ghost" size="sm" class="text-destructive hover:bg-destructive/10">
                                                        <x-lucide-trash-2 class="w-4 h-4" />
                                                    </x-ui.button>
                                                    <template x-teleport="body">
                                                        <div x-show="open" x-cloak class="fixed inset-0 z-50 overflow-y-auto" @keydown.escape.window="open = false">
                                                            <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="open = false" class="fixed inset-0 bg-black/50 backdrop-blur-sm"></div>
                                                            <div class="flex min-h-full items-center justify-center p-4">
                                                                <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" @click.stop class="relative w-full max-w-lg bg-card rounded-lg shadow-lg border border-border p-6">
                                                                    <div class="flex items-start gap-4">
                                                                        <div class="h-12 w-12 rounded-full bg-destructive/10 flex items-center justify-center shrink-0">
                                                                            <x-lucide-alert-triangle class="h-6 w-6 text-destructive" />
                                                                        </div>
                                                                        <div class="flex-1">
                                                                            <h3 class="text-lg font-semibold mb-2">Hapus Kelas Bus</h3>
                                                                            <p class="text-sm text-muted-foreground mb-4">Apakah Anda yakin ingin menghapus kelas bus <strong>{{ $item->nama_kelas }}</strong>? Tindakan ini tidak dapat dibatalkan.</p>
                                                                            <div class="flex flex-col-reverse sm:flex-row gap-2 justify-end">
                                                                                <x-ui.button type="button" variant="outline" @click="open = false" size="sm">Batal</x-ui.button>
                                                                                <form method="POST" action="{{ route('admin/kelas-bus.destroy', $item) }}" class="inline">
                                                                                    @csrf
                                                                                    @method('DELETE')
                                                                                    <x-ui.button type="submit" size="sm" class="w-full sm:w-auto bg-destructive text-destructive-foreground hover:bg-destructive/90">
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
                                            </div>
                                        </x-ui.table.cell>
                                    </x-ui.table.row>

                                    <!-- Mobile View -->
                                    <x-ui.table.row class="sm:hidden">
                                        <x-ui.table.cell colspan="3">
                                            <div class="flex items-start justify-between gap-2">
                                                <div class="flex items-center gap-2 flex-1">
                                                    <div class="h-8 w-8 rounded-lg bg-primary/10 flex items-center justify-center shrink-0">
                                                        <x-lucide-tag class="h-4 w-4 text-primary" />
                                                    </div>
                                                    <div class="min-w-0 flex-1">
                                                        <p class="font-medium text-sm truncate">{{ $item->nama_kelas }}</p>
                                                        <p class="text-xs text-muted-foreground">{{ $item->bus->nama }} - {{ $item->jumlah_kursi }} kursi</p>
                                                        <p class="text-xs text-muted-foreground">{{ $item->created_at->format('d M Y') }}</p>
                                                    </div>
                                                </div>
                                                <div class="flex items-center justify-end gap-1 shrink-0">
                                                    <a href="{{ route('admin/kelas-bus.show', $item) }}">
                                                        <x-ui.button variant="ghost" size="sm">
                                                            <x-lucide-eye class="w-4 h-4" />
                                                        </x-ui.button>
                                                    </a>
                                                    <a href="{{ route('admin/kelas-bus.edit', $item) }}">
                                                        <x-ui.button variant="ghost" size="sm">
                                                            <x-lucide-pencil class="w-4 h-4" />
                                                        </x-ui.button>
                                                    </a>
                                                    <div x-data="{ open: false }">
                                                        <x-ui.button @click="open = true" variant="ghost" size="sm" class="text-destructive hover:bg-destructive/10">
                                                            <x-lucide-trash-2 class="w-4 h-4" />
                                                        </x-ui.button>
                                                        <template x-teleport="body">
                                                            <div x-show="open" x-cloak class="fixed inset-0 z-50 overflow-y-auto" @keydown.escape.window="open = false">
                                                                <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="open = false" class="fixed inset-0 bg-black/50 backdrop-blur-sm"></div>
                                                                <div class="flex min-h-full items-center justify-center p-4">
                                                                    <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" @click.stop class="relative w-full max-w-lg bg-card rounded-lg shadow-lg border border-border p-6">
                                                                        <div class="flex items-start gap-4">
                                                                            <div class="h-12 w-12 rounded-full bg-destructive/10 flex items-center justify-center shrink-0">
                                                                                <x-lucide-alert-triangle class="h-6 w-6 text-destructive" />
                                                                            </div>
                                                                            <div class="flex-1">
                                                                                <h3 class="text-lg font-semibold mb-2">Hapus Kelas Bus</h3>
                                                                                <p class="text-sm text-muted-foreground mb-4">Apakah Anda yakin ingin menghapus kelas bus <strong>{{ $item->nama_kelas }}</strong>? Tindakan ini tidak dapat dibatalkan.</p>
                                                                                <div class="flex flex-col-reverse sm:flex-row gap-2 justify-end">
                                                                                    <x-ui.button type="button" variant="outline" @click="open = false" size="sm">Batal</x-ui.button>
                                                                                    <form method="POST" action="{{ route('admin/kelas-bus.destroy', $item) }}" class="inline">
                                                                                        @csrf
                                                                                        @method('DELETE')
                                                                                        <x-ui.button type="submit" size="sm" class="w-full sm:w-auto bg-destructive text-destructive-foreground hover:bg-destructive/90">
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
                                                </div>
                                            </div>
                                        </x-ui.table.cell>
                                    </x-ui.table.row>
                                @endforeach
                            </x-ui.table.body>
                        </x-ui.table>
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="h-16 w-16 rounded-full bg-muted flex items-center justify-center mx-auto mb-4">
                            @if($search)
                                <x-lucide-search-x class="h-8 w-8 text-muted-foreground" />
                            @else
                                <x-lucide-tag class="h-8 w-8 text-muted-foreground" />
                            @endif
                        </div>
                        <h3 class="text-base sm:text-lg font-medium mb-1">Belum ada kelas bus</h3>
                        <p class="text-sm text-muted-foreground mb-4">Mulai tambahkan kelas bus pertama</p>
                        <a href="{{ route('admin/kelas-bus.create') }}" class="inline-block">
                            <x-ui.button class="w-full sm:w-auto">
                                <x-lucide-plus class="w-4 h-4" />
                                Tambah Kelas Bus
                            </x-ui.button>
                        </a>
                    </div>
                @endif
            </x-ui.card.content>
            @if($kelasBus->count() > 0)
                <x-ui.card.footer>
                    <div class="w-full flex flex-col sm:flex-row items-center justify-between gap-3">
                        <p class="text-xs sm:text-sm text-muted-foreground text-center sm:text-left">
                            Menampilkan {{ $kelasBus->firstItem() }} - {{ $kelasBus->lastItem() }} dari {{ $kelasBus->total() }} kelas bus
                        </p>
                        <div class="w-full sm:w-auto flex justify-center">
                            {{ $kelasBus->links('vendor.pagination.shadcn') }}
                        </div>
                    </div>
                </x-ui.card.footer>
            @endif
        </x-ui.card>
    </div>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</x-admin-layout>
