<x-admin-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="text-xl sm:text-2xl font-bold">Manajemen Jadwal</h2>
                <p class="text-sm text-muted-foreground mt-1">Kelola jadwal keberangkatan bus</p>
            </div>
            <a href="{{ route('admin/jadwal.create') }}" class="w-full sm:w-auto">
                <x-ui.button class="w-full sm:w-auto">
                    <x-lucide-plus class="w-4 h-4" />
                    Tambah Jadwal
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
                <x-ui.card.title>Daftar Jadwal</x-ui.card.title>
                <x-ui.card.description>Semua jadwal keberangkatan yang terdaftar dalam sistem</x-ui.card.description>
            </x-ui.card.header>
            <x-ui.card.content class="p-0 sm:p-6">
                @if($jadwals->count() > 0)
                    <div class="overflow-x-auto -mx-4 sm:mx-0">
                        <x-ui.table>
                            <x-ui.table.header>
                                <x-ui.table.row>
                                    <x-ui.table.head class="w-16 sm:w-20">ID</x-ui.table.head>
                                    <x-ui.table.head>Bus</x-ui.table.head>
                                    <x-ui.table.head class="hidden md:table-cell">Sopir</x-ui.table.head>
                                    <x-ui.table.head>Rute</x-ui.table.head>
                                    <x-ui.table.head class="hidden lg:table-cell">Tanggal & Jam</x-ui.table.head>
                                    <x-ui.table.head class="hidden sm:table-cell">Status</x-ui.table.head>
                                    <x-ui.table.head class="text-right">Aksi</x-ui.table.head>
                                </x-ui.table.row>
                            </x-ui.table.header>
                            <x-ui.table.body>
                                @foreach($jadwals as $jadwal)
                                    <x-ui.table.row>
                                        <x-ui.table.cell class="font-medium text-xs sm:text-sm">#{{ $jadwal->id }}</x-ui.table.cell>
                                        <x-ui.table.cell>
                                            <div class="flex items-center gap-2 sm:gap-3">
                                                <div class="h-8 w-8 sm:h-10 sm:w-10 rounded-lg bg-primary/10 flex items-center justify-center shrink-0">
                                                    <x-lucide-bus class="h-4 w-4 sm:h-5 sm:w-5 text-primary" />
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="font-medium text-sm sm:text-base truncate">{{ $jadwal->bus->nama ?? '-' }}</p>
                                                    <p class="text-xs text-muted-foreground md:hidden">{{ $jadwal->sopir->user->name ?? '-' }}</p>
                                                </div>
                                            </div>
                                        </x-ui.table.cell>
                                        <x-ui.table.cell class="hidden md:table-cell">
                                            <div class="flex items-center gap-2">
                                                <x-lucide-user-round class="h-4 w-4 text-muted-foreground" />
                                                <span class="text-sm">{{ $jadwal->sopir->user->name ?? '-' }}</span>
                                            </div>
                                        </x-ui.table.cell>
                                        <x-ui.table.cell>
                                            <div class="flex items-center gap-1 text-sm">
                                                <span class="font-medium">{{ $jadwal->rute->asalTerminal->nama_terminal ?? '-' }}</span>
                                                <x-lucide-arrow-right class="h-3 w-3 text-muted-foreground shrink-0" />
                                                <span class="font-medium">{{ $jadwal->rute->tujuanTerminal->nama_terminal ?? '-' }}</span>
                                            </div>
                                            <p class="text-xs text-muted-foreground lg:hidden mt-1">
                                                {{ $jadwal->tanggal_berangkat->format('d M Y') }}, {{ $jadwal->jam_berangkat->format('H:i') }}
                                            </p>
                                        </x-ui.table.cell>
                                        <x-ui.table.cell class="hidden lg:table-cell">
                                            <div class="flex items-center gap-2">
                                                <x-lucide-calendar class="h-4 w-4 text-muted-foreground" />
                                                <div>
                                                    <p class="text-sm font-medium">{{ $jadwal->tanggal_berangkat->format('d M Y') }}</p>
                                                    <p class="text-xs text-muted-foreground">{{ $jadwal->jam_berangkat->format('H:i') }} WIB</p>
                                                </div>
                                            </div>
                                        </x-ui.table.cell>
                                        <x-ui.table.cell class="hidden sm:table-cell">
                                            @if($jadwal->status === 'aktif')
                                                <x-ui.badge class="bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                                                    Aktif
                                                </x-ui.badge>
                                            @elseif($jadwal->status === 'selesai')
                                                <x-ui.badge class="bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">
                                                    Selesai
                                                </x-ui.badge>
                                            @elseif($jadwal->status === 'dibatalkan')
                                                <x-ui.badge class="bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">
                                                    Dibatalkan
                                                </x-ui.badge>
                                            @else
                                                <x-ui.badge variant="outline">
                                                    {{ ucfirst($jadwal->status) }}
                                                </x-ui.badge>
                                            @endif
                                        </x-ui.table.cell>
                                        <x-ui.table.cell class="text-right">
                                            <div class="flex items-center justify-end gap-1">
                                                <a href="{{ route('admin/jadwal.show', $jadwal) }}" class="hidden sm:inline-block">
                                                    <x-ui.button variant="ghost" size="icon">
                                                        <x-lucide-eye class="w-4 h-4" />
                                                    </x-ui.button>
                                                </a>
                                                <a href="{{ route('admin/jadwal.edit', $jadwal) }}">
                                                    <x-ui.button variant="ghost" size="icon">
                                                        <x-lucide-pencil class="w-4 h-4" />
                                                    </x-ui.button>
                                                </a>
                                                <form method="POST" action="{{ route('admin/jadwal.destroy', $jadwal) }}" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <x-ui.button type="submit" variant="ghost" size="icon"
                                                        onclick="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?')"
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
                            <x-lucide-calendar class="w-8 h-8 text-muted-foreground" />
                        </div>
                        <h3 class="text-lg font-semibold mb-2">Belum Ada Jadwal</h3>
                        <p class="text-sm text-muted-foreground mb-6">Mulai dengan menambahkan jadwal keberangkatan pertama Anda.</p>
                        <a href="{{ route('admin/jadwal.create') }}">
                            <x-ui.button>
                                <x-lucide-plus class="w-4 h-4 mr-2" />
                                Tambah Jadwal
                            </x-ui.button>
                        </a>
                    </div>
                @endif
            </x-ui.card.content>
            @if($jadwals->count() > 0)
                <x-ui.card.footer class="border-t pt-4">
                    <div class="w-full flex items-center justify-between">
                        <p class="text-sm text-muted-foreground">
                            Menampilkan {{ $jadwals->firstItem() ?? 0 }} - {{ $jadwals->lastItem() ?? 0 }} dari {{ $jadwals->total() }} jadwal
                        </p>
                        <div>
                            {{ $jadwals->links('vendor.pagination.shadcn') }}
                        </div>
                    </div>
                </x-ui.card.footer>
            @endif
        </x-ui.card>
    </div>
</x-admin-layout>
