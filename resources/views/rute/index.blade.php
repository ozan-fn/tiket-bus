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
                        Rute
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
                                <x-ui.card.title>Daftar Rute</x-ui.card.title>
                                <x-ui.card.description>Semua rute perjalanan yang terdaftar dalam sistem</x-ui.card.description>
                            </div>
                            <a href="{{ route('admin/rute.create') }}" class="hidden sm:inline-block">
                                <x-ui.button>
                                    <x-lucide-plus class="w-4 h-4 mr-2" />
                                    Tambah Rute
                                </x-ui.button>
                            </a>
                        </div>

                        <!-- Search Bar & Table Controls -->
                        <div class="flex flex-col gap-2">
                            <div class="flex gap-2 items-center w-full">
                                <form method="GET" action="{{ route('admin/rute.index') }}" class="flex gap-2 flex-1 min-w-0">
                                    <div class="flex flex-row gap-2">
                                        <div class="relative flex-1 min-w-0">
                                            <x-lucide-search class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
                                            <x-ui.input
                                                type="text"
                                                name="search"
                                                placeholder="Cari terminal asal atau tujuan..."
                                                value="{{ $search ?? '' }}"
                                                class="pl-9 h-10 max-w-md"
                                            />
                                        </div>
                                        <x-ui.button size="icon" type="submit" variant="outline" class="h-9 w-9 shrink-0">
                                            <x-lucide-search class="w-4 h-4" />
                                        </x-ui.button>
                                    </div>
                                    @if($search)
                                        <a href="{{ route('admin/rute.index') }}" class="shrink-0">
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
                    <a href="{{ route('admin/rute.create') }}" class="sm:hidden w-full">
                        <x-ui.button class="w-full">
                            <x-lucide-plus class="w-4 h-4 mr-2" />
                            Tambah Rute
                        </x-ui.button>
                    </a>
                </div>
            </x-ui.card.header>
            <x-ui.card.content class="p-0 sm:p-6">
                @if($rutes->count() > 0)
                    <div class="overflow-x-auto -mx-4 sm:mx-0">
                        <x-ui.table>
                            <x-ui.table.header>
                                <x-ui.table.row>
                                    <x-ui.table.head class="w-12 sm:w-16">No</x-ui.table.head>
                                    <x-ui.table.head>Terminal Asal</x-ui.table.head>
                                    <x-ui.table.head>Terminal Tujuan</x-ui.table.head>
                                    <x-ui.table.head class="text-right">Aksi</x-ui.table.head>
                                </x-ui.table.row>
                            </x-ui.table.header>
                            <x-ui.table.body>
                                @foreach($rutes as $index => $rute)
                                    <x-ui.table.row>
                                        <x-ui.table.cell class="font-medium text-xs sm:text-sm">{{ $rutes->firstItem() + $index }}</x-ui.table.cell>
                                        <x-ui.table.cell>
                                            <div class="flex items-center gap-2 sm:gap-3">
                                                <div class="h-8 w-8 sm:h-10 sm:w-10 rounded-lg bg-primary/10 flex items-center justify-center shrink-0">
                                                    <x-lucide-map-pin class="h-4 w-4 sm:h-5 sm:w-5 text-primary" />
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="font-medium text-sm sm:text-base truncate">{{ $rute->asalTerminal->nama_terminal ?? '-' }}</p>
                                                    <p class="text-xs sm:text-sm text-muted-foreground truncate">{{ $rute->asalTerminal->nama_kota ?? '-' }}</p>
                                                </div>
                                            </div>
                                        </x-ui.table.cell>
                                        <x-ui.table.cell>
                                            <div class="flex items-center gap-2 sm:gap-3">
                                                <div class="h-8 w-8 sm:h-10 sm:w-10 rounded-lg bg-green-500/10 flex items-center justify-center shrink-0">
                                                    <x-lucide-map-pin-check-inside class="h-4 w-4 sm:h-5 sm:w-5 text-green-600" />
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="font-medium text-sm sm:text-base truncate">{{ $rute->tujuanTerminal->nama_terminal ?? '-' }}</p>
                                                    <p class="text-xs sm:text-sm text-muted-foreground truncate">{{ $rute->tujuanTerminal->nama_kota ?? '-' }}</p>
                                                </div>
                                            </div>
                                        </x-ui.table.cell>
                                        <x-ui.table.cell class="text-right">
                                            <div class="flex items-center justify-end gap-1">
                                                <a href="{{ route('admin/rute.show', $rute) }}" class="hidden sm:inline-block">
                                                    <x-ui.button variant="ghost" size="icon">
                                                        <x-lucide-eye class="w-4 h-4" />
                                                    </x-ui.button>
                                                </a>
                                                <a href="{{ route('admin/rute.edit', $rute) }}">
                                                    <x-ui.button variant="ghost" size="icon">
                                                        <x-lucide-pencil class="w-4 h-4" />
                                                    </x-ui.button>
                                                </a>
                                                <!-- Delete Dialog -->
                                                <div x-data="{ open: false }">
                                                    <x-ui.button @click="open = true" variant="ghost" size="icon" class="text-destructive hover:text-destructive hover:bg-destructive/10">
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
                                                                            <h3 class="text-lg font-semibold mb-2">Hapus Rute</h3>
                                                                            <p class="text-sm text-muted-foreground mb-4">
                                                                                Apakah Anda yakin ingin menghapus rute dari <strong>{{ $rute->asalTerminal->nama_kota ?? '-' }}</strong> ke <strong>{{ $rute->tujuanTerminal->nama_kota ?? '-' }}</strong>?
                                                                                Tindakan ini tidak dapat dibatalkan.
                                                                            </p>

                                                                            <div class="flex flex-col-reverse sm:flex-row gap-2 justify-end">
                                                                                <x-ui.button type="button" variant="outline" @click="open = false" size="sm">
                                                                                    Batal
                                                                                </x-ui.button>
                                                                                <form method="POST" action="{{ route('admin/rute.destroy', $rute) }}" class="inline">
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
                                @endforeach
                            </x-ui.table.body>
                        </x-ui.table>
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center py-12 text-center">
                        <div class="h-16 w-16 rounded-full bg-muted flex items-center justify-center mb-4">
                            <x-lucide-route class="w-8 h-8 text-muted-foreground" />
                        </div>
                        <h3 class="text-lg font-semibold mb-2">Belum Ada Rute</h3>
                        <p class="text-sm text-muted-foreground mb-6">Mulai dengan menambahkan rute perjalanan pertama Anda.</p>
                        <a href="{{ route('admin/rute.create') }}">
                            <x-ui.button>
                                <x-lucide-plus class="w-4 h-4 mr-2" />
                                Tambah Rute
                            </x-ui.button>
                        </a>
                    </div>
                @endif
            </x-ui.card.content>
            @if($rutes->count() > 0)
                <x-ui.card.footer class="border-t pt-4">
                    <div class="w-full flex items-center justify-between">
                        <p class="text-sm text-muted-foreground">
                            Menampilkan {{ $rutes->firstItem() ?? 0 }} - {{ $rutes->lastItem() ?? 0 }} dari {{ $rutes->total() }} rute
                        </p>
                        <div>
                            {{ $rutes->links('vendor.pagination.shadcn') }}
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
