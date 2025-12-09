<x-admin-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="text-xl sm:text-2xl font-bold">Manajemen Fasilitas</h2>
                <p class="text-sm text-muted-foreground mt-1">Kelola fasilitas bus</p>
            </div>
            <a href="{{ route('admin/fasilitas.create') }}" class="w-full sm:w-auto">
                <x-ui.button class="w-full sm:w-auto">
                    <x-lucide-plus class="w-4 h-4" />
                    Tambah Fasilitas
                </x-ui.button>
            </a>
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

        <x-ui.card>
            <x-ui.card.header>
                <x-ui.card.title>Daftar Fasilitas</x-ui.card.title>
                <x-ui.card.description>Semua fasilitas yang tersedia untuk bus</x-ui.card.description>
            </x-ui.card.header>
            <x-ui.card.content class="p-0 sm:p-6">
                @if($fasilitas->count() > 0)
                    <div class="overflow-x-auto -mx-4 sm:mx-0">
                        <x-ui.table>
                            <x-ui.table.header>
                                <x-ui.table.row>
                                    <x-ui.table.head class="w-16 sm:w-20">ID</x-ui.table.head>
                                    <x-ui.table.head>Nama Fasilitas</x-ui.table.head>
                                    <x-ui.table.head class="text-right">Aksi</x-ui.table.head>
                                </x-ui.table.row>
                            </x-ui.table.header>
                            <x-ui.table.body>
                                @foreach($fasilitas as $item)
                                    <x-ui.table.row>
                                        <x-ui.table.cell class="font-medium text-xs sm:text-sm">#{{ $item->id }}</x-ui.table.cell>
                                        <x-ui.table.cell>
                                            <div class="flex items-center gap-2 sm:gap-3">
                                                <div class="h-8 w-8 sm:h-10 sm:w-10 rounded-lg bg-primary/10 flex items-center justify-center shrink-0">
                                                    <x-lucide-sparkles class="h-4 w-4 sm:h-5 sm:w-5 text-primary" />
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="font-medium text-sm sm:text-base truncate">{{ $item->nama }}</p>
                                                </div>
                                            </div>
                                        </x-ui.table.cell>
                                        <x-ui.table.cell class="text-right">
                                            <div class="flex items-center justify-end gap-1">
                                                <a href="{{ route('admin/fasilitas.show', $item) }}" class="hidden sm:inline-block">
                                                    <x-ui.button variant="ghost" size="sm">
                                                        <x-lucide-eye class="w-4 h-4" />
                                                    </x-ui.button>
                                                </a>
                                                <a href="{{ route('admin/fasilitas.edit', $item) }}">
                                                    <x-ui.button variant="ghost" size="sm">
                                                        <x-lucide-pencil class="w-4 h-4" />
                                                    </x-ui.button>
                                                </a>
                                                <form method="POST" action="{{ route('admin/fasilitas.destroy', $item) }}" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus fasilitas ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <x-ui.button type="submit" variant="ghost" size="sm" class="text-destructive hover:bg-destructive/10">
                                                        <x-lucide-trash-2 class="w-4 h-4" />
                                                    </x-ui.button>
                                                </form>
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
                            <x-lucide-sparkles class="h-8 w-8 text-muted-foreground" />
                        </div>
                        <h3 class="text-base sm:text-lg font-medium mb-1">Belum ada fasilitas</h3>
                        <p class="text-sm text-muted-foreground mb-4">Mulai tambahkan fasilitas pertama</p>
                        <a href="{{ route('admin/fasilitas.create') }}" class="inline-block">
                            <x-ui.button class="w-full sm:w-auto">
                                <x-lucide-plus class="w-4 h-4" />
                                Tambah Fasilitas
                            </x-ui.button>
                        </a>
                    </div>
                @endif
            </x-ui.card.content>
            @if($fasilitas->count() > 0)
                <x-ui.card.footer>
                    <div class="w-full flex flex-col sm:flex-row items-center justify-between gap-3">
                        <p class="text-xs sm:text-sm text-muted-foreground text-center sm:text-left">
                            Menampilkan {{ $fasilitas->firstItem() }} - {{ $fasilitas->lastItem() }} dari {{ $fasilitas->total() }} fasilitas
                        </p>
                        <div class="w-full sm:w-auto flex justify-center">
                            {{ $fasilitas->links('vendor.pagination.shadcn') }}
                        </div>
                    </div>
                </x-ui.card.footer>
            @endif
        </x-ui.card>
    </div>
</x-admin-layout>
