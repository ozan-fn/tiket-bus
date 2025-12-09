<x-admin-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="text-xl sm:text-2xl font-bold">Manajemen Kelas Bus</h2>
                <p class="text-sm text-muted-foreground mt-1">Kelola kelas dan harga bus</p>
            </div>
            <a href="{{ route('admin/kelas-bus.create') }}" class="w-full sm:w-auto">
                <button class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-9 px-4 py-2 w-full sm:w-auto">
                    <x-lucide-plus class="w-4 h-4 mr-2" />
                    Tambah Kelas Bus
                </button>
            </a>
        </div>
    </x-slot>

    <div class="p-4 sm:p-6">
        @if(session('success'))
            <div class="relative w-full rounded-lg border border-green-200 bg-green-50 p-4 text-green-800 dark:border-green-800 dark:bg-green-900/50 dark:text-green-200 mb-6">
                <div class="flex items-start gap-3">
                    <x-lucide-check-circle class="w-4 h-4 mt-0.5" />
                    <div class="flex-1">
                        <h5 class="font-medium">Berhasil!</h5>
                        <p class="text-sm opacity-90">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
            <div class="flex flex-col space-y-1.5 p-6">
                <h3 class="text-2xl font-semibold leading-none tracking-tight">Daftar Kelas Bus</h3>
                <p class="text-sm text-muted-foreground">Semua kelas bus yang terdaftar dalam sistem</p>
            </div>
            <div class="p-0 sm:p-6">
                @if($kelasBus->count() > 0)
                    <div class="overflow-x-auto -mx-4 sm:mx-0">
                        <table class="w-full caption-bottom text-sm">
                            <thead class="[&_tr]:border-b">
                                <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                                    <th class="h-10 px-2 text-left align-middle font-medium whitespace-nowrap w-16 sm:w-20">ID</th>
                                    <th class="h-10 px-2 text-left align-middle font-medium whitespace-nowrap">Bus</th>
                                    <th class="h-10 px-2 text-left align-middle font-medium whitespace-nowrap">Nama Kelas</th>
                                    <th class="h-10 px-2 text-left align-middle font-medium whitespace-nowrap hidden md:table-cell">Deskripsi</th>
                                    <th class="h-10 px-2 text-left align-middle font-medium whitespace-nowrap hidden sm:table-cell text-center">Jumlah Kursi</th>
                                    <th class="h-10 px-2 text-left align-middle font-medium whitespace-nowrap text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="[&_tr:last-child]:border-0">
                                @foreach($kelasBus as $kelas)
                                    <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                                        <td class="p-2 align-middle whitespace-nowrap font-medium text-xs sm:text-sm">#{{ $kelas->id }}</td>
                                        <td class="p-2 align-middle whitespace-nowrap">
                                            <div class="flex items-center gap-2 sm:gap-3">
                                                <div class="h-8 w-8 sm:h-10 sm:w-10 rounded-lg bg-primary/10 flex items-center justify-center shrink-0">
                                                    <x-lucide-bus class="h-4 w-4 sm:h-5 sm:w-5 text-primary" />
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="font-medium text-sm sm:text-base truncate">{{ $kelas->bus->nama }}</p>
                                                    <p class="text-xs text-muted-foreground truncate">{{ $kelas->bus->plat_nomor }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-2 align-middle whitespace-nowrap">
                                            <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 border-transparent bg-primary/10 text-primary">
                                                {{ $kelas->nama_kelas }}
                                            </span>
                                        </td>
                                        <td class="p-2 align-middle whitespace-nowrap hidden md:table-cell">
                                            <p class="text-sm text-muted-foreground truncate max-w-xs">{{ $kelas->deskripsi ?? '-' }}</p>
                                        </td>
                                        <td class="p-2 align-middle whitespace-nowrap hidden sm:table-cell text-center">
                                            <div class="inline-flex items-center gap-1 px-2 py-1 bg-muted rounded-md">
                                                <x-lucide-armchair class="h-4 w-4 text-muted-foreground" />
                                                <span class="text-sm font-medium">{{ $kelas->jumlah_kursi }}</span>
                                            </div>
                                        </td>
                                        <td class="p-2 align-middle whitespace-nowrap text-right">
                                            <div class="flex items-center justify-end gap-1">
                                                <a href="{{ route('admin/kelas-bus.show', $kelas) }}" class="hidden sm:inline-block">
                                                    <button class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 hover:bg-accent hover:text-accent-foreground h-8 w-8">
                                                        <x-lucide-eye class="w-4 h-4" />
                                                    </button>
                                                </a>
                                                <a href="{{ route('admin/kelas-bus.edit', $kelas) }}">
                                                    <button class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 hover:bg-accent hover:text-accent-foreground h-8 w-8">
                                                        <x-lucide-pencil class="w-4 h-4" />
                                                    </button>
                                                </a>
                                                <form method="POST" action="{{ route('admin/kelas-bus.destroy', $kelas) }}" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus kelas bus ini?')" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 hover:bg-accent hover:text-accent-foreground h-8 w-8 text-destructive">
                                                        <x-lucide-trash-2 class="w-4 h-4" />
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center py-12 text-center">
                        <div class="h-16 w-16 rounded-full bg-muted flex items-center justify-center mb-4">
                            <x-lucide-layers class="w-8 h-8 text-muted-foreground" />
                        </div>
                        <h3 class="text-lg font-semibold mb-2">Belum Ada Kelas Bus</h3>
                        <p class="text-sm text-muted-foreground mb-6">Mulai dengan menambahkan kelas bus pertama Anda.</p>
                        <a href="{{ route('admin/kelas-bus.create') }}">
                            <button class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-9 px-4 py-2">
                                <x-lucide-plus class="w-4 h-4 mr-2" />
                                Tambah Kelas Bus
                            </button>
                        </a>
                    </div>
                @endif
            </div>
            @if($kelasBus->count() > 0)
                <div class="flex items-center justify-between border-t p-6">
                    <div class="w-full flex flex-col sm:flex-row items-center justify-between gap-3">
                        <p class="text-xs sm:text-sm text-muted-foreground text-center sm:text-left">
                            Menampilkan {{ $kelasBus->firstItem() }} - {{ $kelasBus->lastItem() }} dari {{ $kelasBus->total() }} kelas bus
                        </p>
                        <div class="w-full sm:w-auto flex justify-center">
                            {{ $kelasBus->links('vendor.pagination.shadcn') }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>
