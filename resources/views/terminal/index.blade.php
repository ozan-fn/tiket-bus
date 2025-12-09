<x-admin-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3
">
            <div>
                <h2 class="text-xl sm:text-2xl font-bold">Manajemen Terminal</h2>
                <p class="text-sm text-muted-foreground mt-1">Kelola data terminal bus</p>
            </div>
            <a href="{{ route('admin/terminal.create') }}" class="w-full sm:w-auto">
                <x-ui.button class="w-full sm:w-auto">
                    <x-lucide-plus class="w-4 h-4" />
                    Tambah Terminal
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
                <x-ui.card.title>Daftar Terminal</x-ui.card.title>
                <x-ui.card.description>Semua data terminal yang terdaftar dalam sistem</x-ui.card.description>
            </x-ui.card.header>
            <x-ui.card.content class="p-0 sm:p-6">
                @if($terminals->count() > 0)
                    <div class="overflow-x-auto -mx-4 sm:mx-0">
                        <x-ui.table>
                            <x-ui.table.header>
                                <x-ui.table.row>
                                    <x-ui.table.head class="w-16 sm:w-20">ID</x-ui.table.head>
                                    <x-ui.table.head>Nama Terminal</x-ui.table.head>
                                    <x-ui.table.head class="hidden md:table-cell">Kota</x-ui.table.head>
                                    <x-ui.table.head class="hidden lg:table-cell">Alamat</x-ui.table.head>
                                    <x-ui.table.head class="text-right">Aksi</x-ui.table.head>
                                </x-ui.table.row>
                            </x-ui.table.header>
                            <x-ui.table.body>
                                @foreach($terminals as $terminal)
                                    <x-ui.table.row>
                                        <x-ui.table.cell class="font-medium text-xs sm:text-sm">#{{ $terminal->id }}</x-ui.table.cell>
                                        <x-ui.table.cell>
                                            <div class="flex items-center gap-2 sm:gap-3">
                                                <div class="h-8 w-8 sm:h-10 sm:w-10 rounded-lg bg-primary/10 flex items-center justify-center shrink-0">
                                                    <x-lucide-building-2 class="h-4 w-4 sm:h-5 sm:w-5 text-primary" />
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="font-medium text-sm sm:text-base truncate">{{ $terminal->nama_terminal }}</p>
                                                    <p class="text-xs text-muted-foreground md:hidden">{{ $terminal->nama_kota }}</p>
                                                </div>
                                            </div>
                                        </x-ui.table.cell>
                                        <x-ui.table.cell class="hidden md:table-cell">
                                            <div class="flex items-center gap-2">
                                                <x-lucide-map-pin class="h-4 w-4 text-muted-foreground" />
                                                <span class="text-sm">{{ $terminal->nama_kota }}</span>
                                            </div>
                                        </x-ui.table.cell>
                                        <x-ui.table.cell class="hidden lg:table-cell">

                                            <p class="text-sm text-muted-foreground truncate max-w-xs">{{ $terminal->alamat ?? '-' }}</p>
                                        </x-ui.table.cell>
                                        <x-ui.table.cell class="text-right">
                                            <div class="flex items-center justify-end gap-1">
                                                <a href="{{ route('admin/terminal.show', $terminal) }}" class="hidden sm:inline-block">
                                                    <x-ui.button variant="ghost" size="icon">
                                                        <x-lucide-eye class="w-4 h-4" />
                                                    </x-ui.button>
                                                </a>
                                                <a href="{{ route('admin/terminal.edit', $terminal) }}">
                                                    <x-ui.button variant="ghost" size="icon">
                                                        <x-lucide-pencil class="w-4 h-4" />
                                                    </x-ui.button>
                                                </a>
                                                <form method="POST" action="{{ route('admin/terminal.destroy', $terminal) }}" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <x-ui.button type="submit" variant="ghost" size="icon"
                                                        onclick="return confirm('Apakah Anda yakin ingin menghapus terminal ini?')"
                                                        class="text-destructive hover:text-destructive hover:bg-destructive/10">
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
                    <div class="flex flex-col items-center justify-center py-12 text-center">
                        <div class="h-16 w-16 rounded-full bg-muted flex items-center justify-center mb-4">
                            <x-lucide-building-2 class="w-8 h-8 text-muted-foreground" />
                        </div>
                        <h3 class="text-lg font-semibold mb-2">Belum Ada Terminal</h3>
                        <p class="text-sm text-muted-foreground mb-6">Mulai dengan menambahkan terminal pertama Anda.</p>
                        <a href="{{ route('admin/terminal.create') }}">
                            <x-ui.button>
                                <x-lucide-plus class="w-4 h-4 mr-2" />
                                Tambah Terminal
                            </x-ui.button>
                        </a>
                    </div>
                @endif
            </x-ui.card.content>
            @if($terminals->count() > 0)
                <x-ui.card.footer class="border-t pt-4">
                    <div class="w-full flex items-center justify-between">
                        <p class="text-sm text-muted-foreground">
                            Menampilkan {{ $terminals->firstItem() ?? 0 }} - {{ $terminals->lastItem() ?? 0 }} dari {{ $terminals->total() }} terminal
                        </p>
                        <div>
                            {{ $terminals->links('vendor.pagination.shadcn') }}
                        </div>
                    </div>
                </x-ui.card.footer>
            @endif
        </x-ui.card>
    </div>
</x-admin-layout>
