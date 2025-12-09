<x-admin-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="text-xl sm:text-2xl font-bold">Manajemen Sopir</h2>
                <p class="text-sm text-muted-foreground mt-1">Kelola data sopir bus</p>
            </div>
            <a href="{{ route('admin/sopir.create') }}" class="w-full sm:w-auto">
                <x-ui.button class="w-full sm:w-auto">
                    <x-lucide-plus class="w-4 h-4" />
                    Tambah Sopir
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
                <x-ui.card.title>Daftar Sopir</x-ui.card.title>
                <x-ui.card.description>Semua data sopir yang terdaftar dalam sistem</x-ui.card.description>
            </x-ui.card.header>
            <x-ui.card.content class="p-0 sm:p-6">
                @if($sopir->count() > 0)
                    <div class="overflow-x-auto -mx-4 sm:mx-0">
                        <x-ui.table>
                            <x-ui.table.header>
                                <x-ui.table.row>
                                    <x-ui.table.head class="w-16 sm:w-20">ID</x-ui.table.head>
                                    <x-ui.table.head>Nama Sopir</x-ui.table.head>
                                    <x-ui.table.head class="hidden md:table-cell">NIK</x-ui.table.head>
                                    <x-ui.table.head class="hidden lg:table-cell">Nomor SIM</x-ui.table.head>
                                    <x-ui.table.head class="hidden sm:table-cell">Status</x-ui.table.head>
                                    <x-ui.table.head class="text-right">Aksi</x-ui.table.head>
                                </x-ui.table.row>
                            </x-ui.table.header>
                            <x-ui.table.body>
                                @foreach($sopir as $item)
                                    <x-ui.table.row>
                                        <x-ui.table.cell class="font-medium text-xs sm:text-sm">#{{ $item->id }}</x-ui.table.cell>
                                        <x-ui.table.cell>
                                            <div class="flex items-center gap-2 sm:gap-3">
                                                <div class="h-8 w-8 sm:h-10 sm:w-10 rounded-full bg-primary/10 flex items-center justify-center shrink-0">
                                                    <x-lucide-user-round class="h-4 w-4 sm:h-5 sm:w-5 text-primary" />
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="font-medium text-sm sm:text-base truncate">{{ $item->user->name ?? 'N/A' }}</p>
                                                    <p class="text-xs text-muted-foreground md:hidden">{{ $item->nik }}</p>
                                                </div>
                                            </div>
                                        </x-ui.table.cell>
                                        <x-ui.table.cell class="hidden md:table-cell">
                                            <x-ui.badge variant="outline">{{ $item->nik }}</x-ui.badge>
                                        </x-ui.table.cell>
                                        <x-ui.table.cell class="hidden lg:table-cell">
                                            <div class="flex items-center gap-2">
                                                <x-lucide-id-card class="h-4 w-4 text-muted-foreground" />
                                                <span class="text-sm">{{ $item->nomor_sim }}</span>
                                            </div>
                                        </x-ui.table.cell>
                                        <x-ui.table.cell class="hidden sm:table-cell">
                                            @if($item->status === 'aktif')
                                                <x-ui.badge class="bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                                                    <x-lucide-circle-check class="h-3 w-3" />
                                                    Aktif
                                                </x-ui.badge>
                                            @else
                                                <x-ui.badge variant="secondary">
                                                    <x-lucide-circle-x class="h-3 w-3" />
                                                    {{ ucfirst($item->status) }}
                                                </x-ui.badge>
                                            @endif
                                        </x-ui.table.cell>
                                        <x-ui.table.cell class="text-right">
                                            <div class="flex items-center justify-end gap-1">
                                                <a href="{{ route('admin/sopir.show', $item) }}" class="hidden sm:inline-block">
                                                    <x-ui.button variant="ghost" size="sm">
                                                        <x-lucide-eye class="w-4 h-4" />
                                                    </x-ui.button>
                                                </a>
                                                <a href="{{ route('admin/sopir.edit', $item) }}">
                                                    <x-ui.button variant="ghost" size="sm">
                                                        <x-lucide-pencil class="w-4 h-4" />
                                                    </x-ui.button>
                                                </a>
                                                <form method="POST" action="{{ route('admin/sopir.destroy', $item) }}" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus sopir ini?')">
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
                            <x-lucide-user-round class="h-8 w-8 text-muted-foreground" />
                        </div>
                        <h3 class="text-base sm:text-lg font-medium mb-1">Belum ada data sopir</h3>
                        <p class="text-sm text-muted-foreground mb-4">Mulai tambahkan sopir pertama Anda</p>
                        <a href="{{ route('admin/sopir.create') }}" class="inline-block">
                            <x-ui.button class="w-full sm:w-auto">
                                <x-lucide-plus class="w-4 h-4" />
                                Tambah Sopir
                            </x-ui.button>
                        </a>
                    </div>
                @endif
            </x-ui.card.content>
            @if($sopir->count() > 0)
                <x-ui.card.footer>
                    <div class="w-full flex flex-col sm:flex-row items-center justify-between gap-3">
                        <p class="text-xs sm:text-sm text-muted-foreground text-center sm:text-left">
                            Menampilkan {{ $sopir->firstItem() }} - {{ $sopir->lastItem() }} dari {{ $sopir->total() }} sopir
                        </p>
                        <div class="w-full sm:w-auto flex justify-center">
                            {{ $sopir->links('vendor.pagination.shadcn') }}
                        </div>
                    </div>
                </x-ui.card.footer>
            @endif
        </x-ui.card>
    </div>
</x-admin-layout>
