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
                        Bus
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
                                <x-ui.card.title>Daftar Bus</x-ui.card.title>
                                <x-ui.card.description>Semua armada bus yang terdaftar dalam sistem</x-ui.card.description>
                            </div>
                            <a href="{{ route('admin/bus.create') }}" class="hidden sm:inline-block">
                                <x-ui.button>
                                    <x-lucide-plus class="w-4 h-4 mr-2" />
                                    Tambah Bus
                                </x-ui.button>
                            </a>
                        </div>

                        <!-- Search Bar & Table Controls -->
                        <div class="flex flex-col gap-2">
                            <div class="flex gap-2 items-center w-full">
                                <form method="GET" action="{{ route('admin/bus.index') }}" class="flex gap-2 flex-1 min-w-0">
                                    <div class="flex flex-row gap-2">
                                        <div class="relative flex-1 min-w-0">
                                            {{-- <x-lucide-search class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" /> --}}
                                            <x-ui.input
                                                type="text"
                                                name="search"
                                                placeholder="Cari nama bus atau plat nomor..."
                                                value="{{ $search ?? '' }}"
                                                class=" h-10 max-w-md"
                                            />
                                        </div>
                                        <x-ui.button size="icon" type="submit" variant="outline" class="h-9 w-9 shrink-0">
                                            <x-lucide-search class="w-4 h-4" />
                                        </x-ui.button>
                                    </div>
                                    @if($search)
                                        <a href="{{ route('admin/bus.index') }}" class="shrink-0">
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
                    <a href="{{ route('admin/bus.create') }}" class="sm:hidden w-full">
                        <x-ui.button class="w-full">
                            <x-lucide-plus class="w-4 h-4 mr-2" />
                            Tambah Bus
                        </x-ui.button>
                    </a>
                </div>
            </x-ui.card.header>
            <x-ui.card.content class="p-0 sm:p-6">
                @if($bus->count() > 0)
                    <div class="overflow-x-auto -mx-4 sm:mx-0">
                        <x-ui.table>
                            <x-ui.table.header class="hidden sm:table-header-group">
                                <x-ui.table.row>
                                    <x-ui.table.head class="w-12 sm:w-16">No</x-ui.table.head>
                                    <x-table.sortable-header name="nama">Nama Bus</x-table.sortable-header>
                                    <x-table.sortable-header name="plat_nomor" class="hidden md:table-cell">Plat Nomor</x-table.sortable-header>
                                    <x-table.sortable-header name="kapasitas" class="hidden sm:table-cell text-center">Kapasitas</x-table.sortable-header>
                                    <x-ui.table.head class="hidden lg:table-cell">Fasilitas</x-ui.table.head>
                                    <x-ui.table.head class="hidden sm:table-cell">Foto</x-ui.table.head>
                                    <x-ui.table.head class="text-right">Aksi</x-ui.table.head>
                                </x-ui.table.row>
                            </x-ui.table.header>
                            <x-ui.table.body>
                                @foreach($bus as $index => $item)
                                    <!-- Desktop View -->
                                    <x-ui.table.row class="hidden sm:table-row">
                                        <x-ui.table.cell class="font-medium text-xs sm:text-sm">{{ $bus->firstItem() + $index }}</x-ui.table.cell>
                                        <x-ui.table.cell>
                                            <div class="flex items-center gap-2 sm:gap-3">
                                                <div class="h-8 w-8 sm:h-10 sm:w-10 rounded-lg bg-primary/10 flex items-center justify-center shrink-0">
                                                    <x-lucide-bus class="h-4 w-4 sm:h-5 sm:w-5 text-primary" />
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="font-medium text-sm sm:text-base truncate">{{ $item->nama }}</p>
                                                    <p class="text-xs sm:text-sm text-muted-foreground truncate">{{ $item->jenis ?? 'Bus Standar' }}</p>
                                                </div>
                                            </div>
                                        </x-ui.table.cell>
                                        <x-ui.table.cell class="hidden md:table-cell">
                                            <x-ui.badge variant="outline">{{ $item->plat_nomor }}</x-ui.badge>
                                        </x-ui.table.cell>
                                        <x-ui.table.cell class="hidden sm:table-cell text-center">
                                            <div class="inline-flex items-center gap-1 px-2 py-1 bg-muted rounded-md">
                                                <x-lucide-users class="h-4 w-4 text-muted-foreground" />
                                                <span class="text-sm font-medium">{{ $item->kapasitas }}</span>
                                            </div>
                                        </x-ui.table.cell>
                                        <x-ui.table.cell class="hidden lg:table-cell">
                                            @if($item->fasilitas->count() > 0)
                                                <div class="flex flex-wrap gap-1 items-center">
                                                    @foreach($item->fasilitas->take(5) as $fasilitas)
                                                        <x-ui.badge variant="secondary" class="text-xs whitespace-nowrap">
                                                            {{ $fasilitas->nama }}
                                                        </x-ui.badge>
                                                    @endforeach

                                                    @if($item->fasilitas->count() > 5)
                                                        <div x-data="{ open: false }" class="relative">
                                                            <button
                                                                @click="open = !open"
                                                                @click.away="open = false"
                                                                type="button"
                                                                class="inline-flex items-center justify-center rounded-full bg-primary/10 text-primary hover:bg-primary/20 h-6 w-6 text-xs font-medium transition-colors">
                                                                +{{ $item->fasilitas->count() - 5 }}
                                                            </button>

                                                            <!-- Popover -->
                                                            <div
                                                                x-show="open"
                                                                x-cloak
                                                                x-transition:enter="transition ease-out duration-100"
                                                                x-transition:enter-start="opacity-0 scale-95"
                                                                x-transition:enter-end="opacity-100 scale-100"
                                                                x-transition:leave="transition ease-in duration-75"
                                                                x-transition:leave-start="opacity-100 scale-100"
                                                                x-transition:leave-end="opacity-0 scale-95"
                                                                class="absolute z-50 left-0 mt-2 w-64 rounded-md border bg-popover p-3 text-popover-foreground shadow-md outline-none"
                                                                style="display: none;">
                                                                <div class="space-y-1">
                                                                    <p class="text-xs font-medium mb-2">Semua Fasilitas:</p>
                                                                    <div class="flex flex-wrap gap-1">
                                                                        @foreach($item->fasilitas as $fasilitas)
                                                                            <x-ui.badge variant="secondary" class="text-xs">
                                                                                {{ $fasilitas->nama }}
                                                                            </x-ui.badge>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-sm text-muted-foreground">-</span>
                                            @endif
                                        </x-ui.table.cell>
                                        <x-ui.table.cell class="hidden sm:table-cell">
                                            @if($item->photos->count() > 0)
                                                <x-photo-gallery :photos="$item->photos" :title="$item->nama">
                                                    <div class="flex items-center gap-2">
                                                        <button type="button" @click="photoOpen = true" class="cursor-pointer hover:opacity-80 transition-opacity">
                                                            <img src="{{ asset('storage/' . $item->photos->first()->path) }}" alt="{{ $item->nama }}" class="h-10 w-16 object-cover rounded border border-border" />
                                                        </button>
                                                        @if($item->photos->count() > 1)
                                                            <span class="text-xs text-muted-foreground">+{{ $item->photos->count() - 1 }}</span>
                                                        @endif
                                                    </div>
                                                </x-photo-gallery>
                                            @else
                                                <span class="text-sm text-muted-foreground flex items-center gap-1">
                                                    <x-lucide-image-off class="w-4 h-4" />
                                                </span>
                                            @endif
                                        </x-ui.table.cell>
                                        <x-ui.table.cell class="text-right">
                                            <div class="flex items-center justify-end gap-1">
                                                <a href="{{ route('admin/bus.show', $item) }}" class="hidden sm:inline-block">
                                                    <x-ui.button variant="ghost" size="sm">
                                                        <x-lucide-eye class="w-4 h-4" />
                                                    </x-ui.button>
                                                </a>
                                                <a href="{{ route('admin/bus.edit', $item) }}">
                                                    <x-ui.button variant="ghost" size="sm">
                                                        <x-lucide-pencil class="w-4 h-4" />
                                                    </x-ui.button>
                                                </a>

                                                <!-- Delete Dialog -->
                                                <div x-data="{ open: false }">
                                                    <x-ui.button @click="open = true" variant="ghost" size="sm" class="text-destructive hover:bg-destructive/10">
                                                        <x-lucide-trash-2 class="w-4 h-4" />
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
                                                                            <h3 class="text-lg font-semibold mb-2">Hapus Bus</h3>
                                                                            <p class="text-sm text-muted-foreground mb-4">
                                                                                Apakah Anda yakin ingin menghapus bus <strong>{{ $item->nama }}</strong>?
                                                                                Tindakan ini tidak dapat dibatalkan.
                                                                            </p>

                                                                            <div class="flex flex-col-reverse sm:flex-row gap-2 justify-end">
                                                                                <x-ui.button type="button" variant="outline" @click="open = false" size="sm">
                                                                                    Batal
                                                                                </x-ui.button>
                                                                                <form method="POST" action="{{ route('admin/bus.destroy', $item) }}" class="inline">
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

                                    <!-- Mobile Card View -->
                                    <x-ui.table.row class="sm:hidden border-b">
                                        <x-ui.table.cell colspan="7" class="p-0">
                                            <div class="p-4 bg-card">
                                                <!-- Header dengan No dan Actions -->
                                                <div class="flex items-start justify-between mb-3">
                                                    <div class="flex items-center gap-3">
                                                        <div class="h-8 w-8 rounded-lg bg-primary/10 flex items-center justify-center shrink-0">
                                                            <x-lucide-bus class="h-4 w-4 text-primary" />
                                                        </div>
                                                        <div>
                                                            <p class="font-medium text-sm">{{ $item->nama }}</p>
                                                            <p class="text-xs text-muted-foreground">{{ $item->jenis ?? 'Bus Standar' }}</p>
                                                        </div>
                                                    </div>
                                                    <span class="text-xs font-medium text-muted-foreground">{{ $bus->firstItem() + $index }}</span>
                                                </div>

                                                <!-- Info Cards -->
                                                <div class="space-y-2 mb-3">
                                                    <!-- Plat Nomor -->
                                                    <div class="flex justify-between items-center p-2 bg-muted rounded">
                                                        <span class="text-xs text-muted-foreground">Plat Nomor</span>
                                                        <x-ui.badge variant="outline" class="text-xs">{{ $item->plat_nomor }}</x-ui.badge>
                                                    </div>

                                                    <!-- Kapasitas -->
                                                    <div class="flex justify-between items-center p-2 bg-muted rounded">
                                                        <span class="text-xs text-muted-foreground">Kapasitas</span>
                                                        <div class="inline-flex items-center gap-1 px-2 py-1 bg-background rounded">
                                                            <x-lucide-users class="h-3 w-3 text-muted-foreground" />
                                                            <span class="text-xs font-medium">{{ $item->kapasitas }}</span>
                                                        </div>
                                                    </div>

                                                    <!-- Foto -->
                                                    <div class="p-2">
                                                        @if($item->photos->count() > 0)
                                                            <p class="text-xs text-muted-foreground mb-2">Foto</p>
                                                            <x-photo-gallery :photos="$item->photos" :title="$item->nama">
                                                                <div class="flex items-center gap-2">
                                                                    <button type="button" @click="photoOpen = true" class="cursor-pointer hover:opacity-80 transition-opacity">
                                                                        <img src="{{ asset('storage/' . $item->photos->first()->path) }}" alt="{{ $item->nama }}" class="h-12 w-16 object-cover rounded border border-border" />
                                                                    </button>
                                                                    @if($item->photos->count() > 1)
                                                                        <span class="text-xs text-muted-foreground">+{{ $item->photos->count() - 1 }} lainnya</span>
                                                                    @endif
                                                                </div>
                                                            </x-photo-gallery>
                                                        @else
                                                            <span class="text-xs text-muted-foreground flex items-center gap-1">
                                                                <x-lucide-image-off class="w-3 h-3" /> Tidak ada foto
                                                            </span>
                                                        @endif
                                                    </div>

                                                    <!-- Fasilitas -->
                                                    <div class="p-2">
                                                        @if($item->fasilitas->count() > 0)
                                                            <p class="text-xs text-muted-foreground mb-2">Fasilitas</p>
                                                            <div class="flex flex-wrap gap-1">
                                                                @foreach($item->fasilitas as $fasilitas)
                                                                    <x-ui.badge variant="secondary" class="text-xs">{{ $fasilitas->nama }}</x-ui.badge>
                                                                @endforeach
                                                            </div>
                                                        @else
                                                            <p class="text-xs text-muted-foreground">-</p>
                                                        @endif
                                                    </div>
                                                </div>

                                                <!-- Actions -->
                                                <div class="flex gap-2 pt-3 border-t">
                                                    <a href="{{ route('admin/bus.show', $item) }}" class="flex-1">
                                                        <x-ui.button variant="outline" size="sm" class="w-full text-xs">
                                                            <x-lucide-eye class="w-3 h-3 mr-1" />
                                                            Lihat
                                                        </x-ui.button>
                                                    </a>
                                                    <a href="{{ route('admin/bus.edit', $item) }}" class="flex-1">
                                                        <x-ui.button variant="outline" size="sm" class="w-full text-xs">
                                                            <x-lucide-pencil class="w-3 h-3 mr-1" />
                                                            Edit
                                                        </x-ui.button>
                                                    </a>
                                                    <div x-data="{ open: false }" class="flex-1">
                                                        <x-ui.button @click="open = true" variant="outline" size="sm" class="w-full text-xs text-destructive hover:bg-destructive/10">
                                                            <x-lucide-trash-2 class="w-3 h-3 mr-1" />
                                                            Hapus
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
                                                                                <h3 class="text-lg font-semibold mb-2">Hapus Bus</h3>
                                                                                <p class="text-sm text-muted-foreground mb-4">
                                                                                    Apakah Anda yakin ingin menghapus bus <strong>{{ $item->nama }}</strong>?
                                                                                    Tindakan ini tidak dapat dibatalkan.
                                                                                </p>
                                                                                <div class="flex flex-col-reverse sm:flex-row gap-2 justify-end">
                                                                                    <x-ui.button type="button" variant="outline" @click="open = false" size="sm">
                                                                                        Batal
                                                                                    </x-ui.button>
                                                                                    <form method="POST" action="{{ route('admin/bus.destroy', $item) }}" class="inline">
                                                                                        @csrf
                                                                                        @method('DELETE')
                                                                                        <x-ui.button type="submit" size="sm" class="bg-destructive text-destructive-foreground hover:bg-destructive/90">
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
                                <x-lucide-bus class="h-8 w-8 text-muted-foreground" />
                            @endif
                        </div>
                        @if($search)
                            <h3 class="text-base sm:text-lg font-medium mb-1">Tidak ada hasil ditemukan</h3>
                            <p class="text-sm text-muted-foreground mb-4">Tidak ada bus yang cocok dengan pencarian "<strong>{{ $search }}</strong>"</p>
                            <a href="{{ route('admin/bus.index') }}" class="inline-block">
                                <x-ui.button variant="outline" class="w-full sm:w-auto">
                                    <x-lucide-x class="w-4 h-4 mr-2" />
                                    Reset Pencarian
                                </x-ui.button>
                            </a>
                        @else
                            <h3 class="text-base sm:text-lg font-medium mb-1">Belum ada data bus</h3>
                            <p class="text-sm text-muted-foreground mb-4">Mulai tambahkan bus pertama Anda</p>
                            <a href="{{ route('admin/bus.create') }}" class="inline-block">
                                <x-ui.button class="w-full sm:w-auto">
                                    <x-lucide-plus class="w-4 h-4 mr-2" />
                                    Tambah Bus
                                </x-ui.button>
                            </a>
                        @endif
                    </div>
                @endif
            </x-ui.card.content>
            @if($bus->count() > 0)
                <x-ui.card.footer>
                    <div class="w-full flex flex-col sm:flex-row items-center justify-between gap-3">
                        <p class="text-xs sm:text-sm text-muted-foreground text-center sm:text-left">
                            Menampilkan {{ $bus->firstItem() }} - {{ $bus->lastItem() }} dari {{ $bus->total() }} bus
                        </p>
                        <div class="w-full sm:w-auto flex justify-center">
                            {{ $bus->links('vendor.pagination.shadcn') }}
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
