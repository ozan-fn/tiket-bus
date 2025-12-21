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
                        Banner
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
                                <x-ui.card.title>Daftar Banner</x-ui.card.title>
                                <x-ui.card.description>Semua banner yang terdaftar dalam sistem</x-ui.card.description>
                            </div>
                            <a href="{{ route('admin/banner.create') }}" class="hidden sm:inline-block">
                                <x-ui.button>
                                    <x-lucide-plus class="w-4 h-4 mr-2" />
                                    Tambah Banner
                                </x-ui.button>
                            </a>
                        </div>

                        <!-- Search Bar -->
                        <div class="flex flex-col gap-2">
                            <div class="flex gap-2 items-center w-full">
                                <form method="GET" action="{{ route('admin/banner.index') }}" class="flex gap-2 flex-1 min-w-0">
                                    <div class="flex flex-row gap-2">
                                        <div class="relative flex-1 min-w-0">
                                            <x-ui.input
                                                type="text"
                                                name="search"
                                                placeholder="Cari judul banner..."
                                                value="{{ $search ?? '' }}"
                                                class=" h-10 max-w-md"
                                            />
                                        </div>
                                        <x-ui.button size="icon" type="submit" variant="outline" class="h-9 w-9 shrink-0">
                                            <x-lucide-search class="w-4 h-4" />
                                        </x-ui.button>
                                    </div>
                                    @if($search)
                                        <a href="{{ route('admin/banner.index') }}" class="shrink-0">
                                            <x-ui.button size="icon" type="button" variant="outline" class="h-9! w-9! shrink-0">
                                                <x-lucide-x class="w-4 h-4" />
                                            </x-ui.button>
                                        </a>
                                    @endif
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Mobile Add Button -->
                    <a href="{{ route('admin/banner.create') }}" class="sm:hidden w-full">
                        <x-ui.button class="w-full">
                            <x-lucide-plus class="w-4 h-4 mr-2" />
                            Tambah Banner
                        </x-ui.button>
                    </a>
                </div>
            </x-ui.card.header>
            <x-ui.card.content class="p-0 sm:p-6">
                @if($banners->count() > 0)
                    <div class="overflow-x-auto -mx-4 sm:mx-0">
                        <x-ui.table>
                            <x-ui.table.header class="hidden sm:table-header-group">
                                <x-ui.table.row>
                                    <x-ui.table.head class="w-12 sm:w-16">No</x-ui.table.head>
                                    <x-table.sortable-header name="title">Judul</x-table.sortable-header>
                                    <x-ui.table.head class="hidden md:table-cell">Deskripsi</x-ui.table.head>
                                    <x-ui.table.head class="hidden sm:table-cell">Gambar</x-ui.table.head>
                                    <x-ui.table.head class="hidden lg:table-cell">Owner</x-ui.table.head>
                                    <x-ui.table.head class="hidden md:table-cell text-center">Urutan</x-ui.table.head>
                                    <x-ui.table.head class="text-right">Aksi</x-ui.table.head>
                                </x-ui.table.row>
                            </x-ui.table.header>
                            <x-ui.table.body>
                                @foreach($banners as $index => $banner)
                                    <!-- Desktop View -->
                                    <x-ui.table.row class="hidden sm:table-row">
                                        <x-ui.table.cell class="font-medium text-xs sm:text-sm">{{ $banners->firstItem() + $index }}</x-ui.table.cell>
                                        <x-ui.table.cell>
                                            <div class="flex items-center gap-2 sm:gap-3">
                                                <div class="h-8 w-8 sm:h-10 sm:w-10 rounded-lg bg-primary/10 flex items-center justify-center shrink-0">
                                                    <x-lucide-image class="h-4 w-4 sm:h-5 sm:w-5 text-primary" />
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="font-medium text-sm sm:text-base truncate">{{ $banner->title }}</p>
                                                </div>
                                            </div>
                                        </x-ui.table.cell>
                                        <x-ui.table.cell class="hidden md:table-cell">
                                            <p class="text-sm text-muted-foreground truncate max-w-xs">{{ $banner->description ?? '-' }}</p>
                                        </x-ui.table.cell>
                                        <x-ui.table.cell class="hidden sm:table-cell">
                                            @if($banner->image)
                                                @php $photos = [(object)['path' => $banner->image]]; @endphp
                                                <x-photo-gallery :photos="$photos" :title="$banner->title">
                                                    <button type="button" @click="photoOpen = true" class="cursor-pointer hover:opacity-80 transition-opacity">
                                                        <img src="{{ asset('storage/' . $banner->image) }}" alt="{{ $banner->title }}" class="h-10 w-16 object-cover rounded border border-border" />
                                                    </button>
                                                </x-photo-gallery>
                                            @else
                                                <span class="text-sm text-muted-foreground flex items-center gap-1">
                                                    <x-lucide-image-off class="w-4 h-4" />
                                                </span>
                                            @endif
                                        </x-ui.table.cell>
                                        <x-ui.table.cell class="hidden lg:table-cell">
                                            <x-ui.badge variant="outline">{{ $banner->owner->name }}</x-ui.badge>
                                        </x-ui.table.cell>
                                        <x-ui.table.cell class="hidden md:table-cell text-center">
                                            <x-ui.badge variant="secondary">{{ $banner->order }}</x-ui.badge>
                                        </x-ui.table.cell>
                                        <x-ui.table.cell class="text-right">
                                            <div class="flex items-center justify-end gap-1">
                                                <!-- Order Up -->
                                                <form method="POST" action="{{ route('admin/banner.order', ['banner' => $banner, 'direction' => 'up']) }}" class="inline">
                                                    @csrf
                                                    <x-ui.button type="submit" variant="ghost" size="sm" title="Naikkan Urutan">
                                                        <x-lucide-chevron-up class="w-4 h-4" />
                                                    </x-ui.button>
                                                </form>

                                                <!-- Order Down -->
                                                <form method="POST" action="{{ route('admin/banner.order', ['banner' => $banner, 'direction' => 'down']) }}" class="inline">
                                                    @csrf
                                                    <x-ui.button type="submit" variant="ghost" size="sm" title="Turunkan Urutan">
                                                        <x-lucide-chevron-down class="w-4 h-4" />
                                                    </x-ui.button>
                                                </form>

                                                <a href="{{ route('admin/banner.show', $banner) }}" class="hidden sm:inline-block">
                                                    <x-ui.button variant="ghost" size="sm">
                                                        <x-lucide-eye class="w-4 h-4" />
                                                    </x-ui.button>
                                                </a>
                                                <a href="{{ route('admin/banner.edit', $banner) }}">
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
                                                                            <h3 class="text-lg font-semibold mb-2">Hapus Banner</h3>
                                                                            <p class="text-sm text-muted-foreground mb-4">
                                                                                Apakah Anda yakin ingin menghapus banner <strong>{{ $banner->title }}</strong>?
                                                                                Tindakan ini tidak dapat dibatalkan.
                                                                            </p>

                                                                            <div class="flex flex-col-reverse sm:flex-row gap-2 justify-end">
                                                                                <x-ui.button type="button" variant="outline" @click="open = false" size="sm">
                                                                                    Batal
                                                                                </x-ui.button>
                                                                                <form method="POST" action="{{ route('admin/banner.destroy', $banner) }}" class="inline">
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
                                        <x-ui.table.cell colspan="6" class="p-0">
                                            <div class="p-4 bg-card">
                                                <!-- Header dengan No dan Actions -->
                                                <div class="flex items-start justify-between mb-3">
                                                    <div class="flex items-center gap-3">
                                                        <div class="h-8 w-8 rounded-lg bg-primary/10 flex items-center justify-center shrink-0">
                                                            <x-lucide-image class="h-4 w-4 text-primary" />
                                                        </div>
                                                        <div>
                                                            <p class="font-medium text-sm">{{ $banner->title }}</p>
                                                        </div>
                                                    </div>
                                                    <span class="text-xs font-medium text-muted-foreground">{{ $banners->firstItem() + $index }}</span>
                                                </div>

                                                <!-- Info Cards -->
                                                <div class="space-y-2 mb-3">
                                                    <!-- Deskripsi -->
                                                    <div class="flex justify-between items-start p-2 bg-muted rounded">
                                                        <span class="text-xs text-muted-foreground">Deskripsi</span>
                                                        <p class="text-xs text-right max-w-xs">{{ $banner->description ?? '-' }}</p>
                                                    </div>

                                                    <!-- Gambar -->
                                                    <div class="p-2">
                                                        @if($banner->image)
                                                            <p class="text-xs text-muted-foreground mb-2">Gambar</p>
                                                            @php $photos = [(object)['path' => $banner->image]]; @endphp
                                                            <x-photo-gallery :photos="$photos" :title="$banner->title">
                                                                <button type="button" @click="photoOpen = true" class="cursor-pointer hover:opacity-80 transition-opacity">
                                                                    <img src="{{ asset('storage/' . $banner->image) }}" alt="{{ $banner->title }}" class="h-12 w-16 object-cover rounded border border-border" />
                                                                </button>
                                                            </x-photo-gallery>
                                                        @else
                                                            <span class="text-xs text-muted-foreground flex items-center gap-1">
                                                                <x-lucide-image-off class="w-3 h-3" /> Tidak ada gambar
                                                            </span>
                                                        @endif
                                                    </div>

                                                    <!-- Owner -->
                                                    <div class="flex justify-between items-center p-2 bg-muted rounded">
                                                        <span class="text-xs text-muted-foreground">Owner</span>
                                                        <x-ui.badge variant="outline" class="text-xs">{{ $banner->owner->name }}</x-ui.badge>
                                                    </div>

                                                    <!-- Urutan -->
                                                    <div class="flex justify-between items-center p-2 bg-muted rounded">
                                                        <span class="text-xs text-muted-foreground">Urutan</span>
                                                        <x-ui.badge variant="secondary" class="text-xs">{{ $banner->order }}</x-ui.badge>
                                                    </div>
                                                </div>

                                                <!-- Actions -->
                                                <div class="flex gap-2 pt-3 border-t">
                                                    <!-- Order Up -->
                                                    <form method="POST" action="{{ route('admin/banner.order', ['banner' => $banner, 'direction' => 'up']) }}" class="flex-1">
                                                        @csrf
                                                        <x-ui.button type="submit" variant="outline" size="sm" class="w-full text-xs">
                                                            <x-lucide-chevron-up class="w-3 h-3 mr-1" />
                                                            Naik
                                                        </x-ui.button>
                                                    </form>

                                                    <!-- Order Down -->
                                                    <form method="POST" action="{{ route('admin/banner.order', ['banner' => $banner, 'direction' => 'down']) }}" class="flex-1">
                                                        @csrf
                                                        <x-ui.button type="submit" variant="outline" size="sm" class="w-full text-xs">
                                                            <x-lucide-chevron-down class="w-3 h-3 mr-1" />
                                                            Turun
                                                        </x-ui.button>
                                                    </form>

                                                    <a href="{{ route('admin/banner.show', $banner) }}" class="flex-1">
                                                        <x-ui.button variant="outline" size="sm" class="w-full text-xs">
                                                            <x-lucide-eye class="w-3 h-3 mr-1" />
                                                            Lihat
                                                        </x-ui.button>
                                                    </a>
                                                    <a href="{{ route('admin/banner.edit', $banner) }}" class="flex-1">
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
                                                                                <h3 class="text-lg font-semibold mb-2">Hapus Banner</h3>
                                                                                <p class="text-sm text-muted-foreground mb-4">
                                                                                    Apakah Anda yakin ingin menghapus banner <strong>{{ $banner->title }}</strong>?
                                                                                    Tindakan ini tidak dapat dibatalkan.
                                                                                </p>
                                                                                <div class="flex flex-col-reverse sm:flex-row gap-2 justify-end">
                                                                                    <x-ui.button type="button" variant="outline" @click="open = false" size="sm">
                                                                                        Batal
                                                                                    </x-ui.button>
                                                                                    <form method="POST" action="{{ route('admin/banner.destroy', $banner) }}" class="inline">
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
                                <x-lucide-image class="h-8 w-8 text-muted-foreground" />
                            @endif
                        </div>
                        @if($search)
                            <h3 class="text-base sm:text-lg font-medium mb-1">Tidak ada hasil ditemukan</h3>
                            <p class="text-sm text-muted-foreground mb-4">Tidak ada banner yang cocok dengan pencarian "<strong>{{ $search }}</strong>"</p>
                            <a href="{{ route('admin/banner.index') }}" class="inline-block">
                                <x-ui.button variant="outline" class="w-full sm:w-auto">
                                    <x-lucide-x class="w-4 h-4 mr-2" />
                                    Reset Pencarian
                                </x-ui.button>
                            </a>
                        @else
                            <h3 class="text-base sm:text-lg font-medium mb-1">Belum ada data banner</h3>
                            <p class="text-sm text-muted-foreground mb-4">Mulai tambahkan banner pertama Anda</p>
                            <a href="{{ route('admin/banner.create') }}" class="inline-block">
                                <x-ui.button class="w-full sm:w-auto">
                                    <x-lucide-plus class="w-4 h-4 mr-2" />
                                    Tambah Banner
                                </x-ui.button>
                            </a>
                        @endif
                    </div>
                @endif
            </x-ui.card.content>
            @if($banners->count() > 0)
                <x-ui.card.footer>
                    <div class="w-full flex flex-col sm:flex-row items-center justify-between gap-3">
                        <p class="text-xs sm:text-sm text-muted-foreground text-center sm:text-left">
                            Menampilkan {{ $banners->firstItem() }} - {{ $banners->lastItem() }} dari {{ $banners->total() }} banner
                        </p>
                        <div class="w-full sm:w-auto flex justify-center">
                            {{ $banners->links('vendor.pagination.shadcn') }}
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
