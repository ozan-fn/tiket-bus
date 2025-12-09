<x-admin-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="text-xl sm:text-2xl font-bold">Manajemen Bus</h2>
                <p class="text-sm text-muted-foreground mt-1">Kelola data bus dan armada</p>
            </div>
            <a href="{{ route('admin/bus.create') }}" class="w-full sm:w-auto">
                <x-ui::button class="w-full sm:w-auto">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Bus
                </x-ui::button>
            </a>
        </div>
    </x-slot>

    <div class="p-4 sm:p-6">
        @if(session('success'))
            <x-ui::alert class="mb-6">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <x-ui::alert.title>Berhasil!</x-ui::alert.title>
                <x-ui::alert.description>
                    {{ session('success') }}
                </x-ui::alert.description>
            </x-ui::alert>
        @endif

        <x-ui::card>
            <x-ui::card.header>
                <x-ui::card.title>Daftar Bus</x-ui::card.title>
                <x-ui::card.description>Semua data bus yang terdaftar dalam sistem</x-ui::card.description>
            </x-ui::card.header>
            <x-ui::card.content class="p-0 sm:p-6">
                @if($bus->count() > 0)
                    <div class="overflow-x-auto -mx-4 sm:mx-0">
                        <x-ui::table>
                            <x-ui::table.header>
                                <x-ui::table.row>
                                    <x-ui::table.head class="w-16 sm:w-20">ID</x-ui::table.head>
                                    <x-ui::table.head>Nama Bus</x-ui::table.head>
                                    <x-ui::table.head class="hidden md:table-cell">Plat Nomor</x-ui::table.head>
                                    <x-ui::table.head class="hidden sm:table-cell text-center">Kapasitas</x-ui::table.head>
                                    <x-ui::table.head class="hidden lg:table-cell">Fasilitas</x-ui::table.head>
                                    <x-ui::table.head class="text-right">Aksi</x-ui::table.head>
                                </x-ui::table.row>
                            </x-ui::table.header>
                            <x-ui::table.body>
                                @foreach($bus as $item)
                                    <x-ui::table.row>
                                        <x-ui::table.cell class="font-medium text-xs sm:text-sm">#{{ $item->id }}</x-ui::table.cell>
                                        <x-ui::table.cell>
                                            <div class="flex items-center gap-2 sm:gap-3">
                                                <div class="h-8 w-8 sm:h-10 sm:w-10 rounded-lg bg-primary/10 flex items-center justify-center shrink-0">
                                                    <svg class="h-4 w-4 sm:h-5 sm:w-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                                    </svg>
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="font-medium text-sm sm:text-base truncate">{{ $item->nama }}</p>
                                                    <p class="text-xs sm:text-sm text-muted-foreground truncate">{{ $item->jenis ?? 'Bus Standar' }}</p>
                                                    <p class="text-xs text-muted-foreground md:hidden mt-1">{{ $item->plat_nomor }}</p>
                                                </div>
                                            </div>
                                        </x-ui::table.cell>
                                        <x-ui::table.cell class="hidden md:table-cell">
                                            <x-ui::badge variant="outline">{{ $item->plat_nomor }}</x-ui::badge>
                                        </x-ui::table.cell>
                                        <x-ui::table.cell class="hidden sm:table-cell text-center">
                                            <div class="inline-flex items-center gap-1 px-2 py-1 bg-muted rounded-md">
                                                <svg class="h-4 w-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                                </svg>
                                                <span class="text-sm font-medium">{{ $item->kapasitas }}</span>
                                            </div>
                                        </x-ui::table.cell>
                                        <x-ui::table.cell class="hidden lg:table-cell">
                                            <div class="flex flex-wrap gap-1">
                                                @forelse($item->fasilitas as $fasilitas)
                                                    <x-ui::badge variant="secondary" class="text-xs">
                                                        {{ $fasilitas->nama }}
                                                    </x-ui::badge>

                                                @empty
                                                    <span class="text-sm text-muted-foreground">-</span>
                                                @endforelse
                                            </div>
                                        </x-ui::table.cell>
                                        <x-ui::table.cell class="text-right">
                                            <div class="flex items-center justify-end gap-1">
                                                <a href="{{ route('admin/bus.show', $item) }}" class="hidden sm:inline-block">
                                                    <x-ui::button variant="ghost" size="sm">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        </svg>
                                                    </x-ui::button>
                                                </a>
                                                <a href="{{ route('admin/bus.edit', $item) }}">
                                                    <x-ui::button variant="ghost" size="sm">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                    </x-ui::button>
                                                </a>
                                                <form method="POST" action="{{ route('admin/bus.destroy', $item) }}" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus bus ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <x-ui::button type="submit" variant="ghost" size="sm" class="text-destructive hover:bg-destructive/10">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </x-ui::button>
                                                </form>
                                            </div>
                                        </x-ui::table.cell>
                                    </x-ui::table.row>
                                @endforeach
                            </x-ui::table.body>
                        </x-ui::table>
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="h-16 w-16 rounded-full bg-muted flex items-center justify-center mx-auto mb-4">
                            <svg class="h-8 w-8 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                            </svg>

                        </div>
                        <h3 class="text-base sm:text-lg font-medium mb-1">Belum ada data bus</h3>
                        <p class="text-sm text-muted-foreground mb-4">Mulai tambahkan bus pertama Anda</p>
                        <a href="{{ route('admin/bus.create') }}" class="inline-block">
                            <x-ui::button class="w-full sm:w-auto">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Tambah Bus
                            </x-ui::button>
                        </a>
                    </div>
                @endif
            </x-ui::card.content>
            @if($bus->count() > 0)
                <x-ui::card.footer>
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
                        <p class="text-xs sm:text-sm text-muted-foreground text-center sm:text-left">
                            Menampilkan {{ $bus->firstItem() }} - {{ $bus->lastItem() }} dari {{ $bus->total() }} bus
                        </p>
                        <div class="w-full sm:w-auto flex justify-center">
                            {{ $bus->links() }}
                        </div>
                    </div>
                </x-ui::card.footer>
            @endif
        </x-ui::card>
    </div>
</x-admin-layout>
