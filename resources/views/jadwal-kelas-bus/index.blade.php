<x-admin-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="text-xl sm:text-2xl font-bold">Manajemen Jadwal Kelas Bus</h2>
                <p class="text-sm text-muted-foreground mt-1">Kelola jadwal dan harga per kelas bus</p>
            </div>
            <a href="{{ route('admin/jadwal-kelas-bus.create') }}" class="w-full sm:w-auto">
                <button class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-9 px-4 py-2 w-full sm:w-auto">
                    <x-lucide-plus class="w-4 h-4 mr-2" />
                    Tambah Jadwal Kelas Bus
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

        @if(session('error'))
            <div class="relative w-full rounded-lg border border-red-200 bg-red-50 p-4 text-red-800 dark:border-red-800 dark:bg-red-900/50 dark:text-red-200 mb-6">
                <div class="flex items-start gap-3">
                    <x-lucide-alert-circle class="w-4 h-4 mt-0.5" />
                    <div class="flex-1">
                        <h5 class="font-medium">Error!</h5>
                        <p class="text-sm opacity-90">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
            <div class="flex flex-col space-y-1.5 p-6">
                <h3 class="text-2xl font-semibold leading-none tracking-tight">Daftar Jadwal Kelas Bus</h3>
                <p class="text-sm text-muted-foreground">Semua jadwal kelas bus yang terdaftar dalam sistem</p>
            </div>
            <div class="p-0 sm:p-6">
                @if($jadwalKelasBus->count() > 0)
                    <div class="overflow-x-auto -mx-4 sm:mx-0">
                        <table class="w-full caption-bottom text-sm">
                            <thead class="[&_tr]:border-b">
                                <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                                    <th class="h-10 px-2 text-left align-middle font-medium whitespace-nowrap w-16 sm:w-20">ID</th>
                                    <th class="h-10 px-2 text-left align-middle font-medium whitespace-nowrap">Rute</th>
                                    <th class="h-10 px-2 text-left align-middle font-medium whitespace-nowrap hidden md:table-cell">Bus</th>
                                    <th class="h-10 px-2 text-left align-middle font-medium whitespace-nowrap">Kelas</th>
                                    <th class="h-10 px-2 text-left align-middle font-medium whitespace-nowrap hidden lg:table-cell">Jadwal</th>
                                    <th class="h-10 px-2 text-left align-middle font-medium whitespace-nowrap text-right">Harga</th>
                                    <th class="h-10 px-2 text-left align-middle font-medium whitespace-nowrap text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="[&_tr:last-child]:border-0">
                                @foreach($jadwalKelasBus as $jkb)
                                    <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                                        <td class="p-2 align-middle whitespace-nowrap font-medium text-xs sm:text-sm">#{{ $jkb->id }}</td>
                                        <td class="p-2 align-middle">
                                            <div class="flex items-center gap-2">
                                                <div class="h-8 w-8 sm:h-10 sm:w-10 rounded-lg bg-primary/10 flex items-center justify-center shrink-0">
                                                    <x-lucide-route class="h-4 w-4 sm:h-5 sm:w-5 text-primary" />
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="font-medium text-sm truncate">{{ $jkb->jadwal->rute->asalTerminal->nama }}</p>
                                                    <div class="flex items-center gap-1 text-xs text-muted-foreground">
                                                        <x-lucide-arrow-right class="h-3 w-3" />
                                                        <span class="truncate">{{ $jkb->jadwal->rute->tujuanTerminal->nama }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-2 align-middle whitespace-nowrap hidden md:table-cell">
                                            <div class="flex items-center gap-2">
                                                <x-lucide-bus class="h-4 w-4 text-muted-foreground" />
                                                <div>
                                                    <p class="text-sm font-medium">{{ $jkb->jadwal->bus->nama }}</p>
                                                    <p class="text-xs text-muted-foreground">{{ $jkb->jadwal->bus->plat_nomor }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-2 align-middle whitespace-nowrap">
                                            <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 border-transparent bg-primary/10 text-primary">
                                                {{ $jkb->kelasBus->nama_kelas }}
                                            </span>
                                        </td>
                                        <td class="p-2 align-middle whitespace-nowrap hidden lg:table-cell">
                                            <div class="text-sm">
                                                <div class="flex items-center gap-1 mb-1">
                                                    <x-lucide-calendar class="h-3 w-3 text-muted-foreground" />
                                                    <span class="font-medium">{{ \Carbon\Carbon::parse($jkb->jadwal->tanggal_berangkat)->format('d M Y') }}</span>
                                                </div>
                                                <div class="flex items-center gap-1 text-muted-foreground">
                                                    <x-lucide-clock class="h-3 w-3" />
                                                    <span class="text-xs">{{ \Carbon\Carbon::parse($jkb->jadwal->waktu_berangkat)->format('H:i') }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-2 align-middle whitespace-nowrap text-right">
                                            <div class="font-semibold text-sm text-primary">
                                                Rp {{ number_format($jkb->harga, 0, ',', '.') }}
                                            </div>
                                        </td>
                                        <td class="p-2 align-middle whitespace-nowrap text-right">
                                            <div class="flex items-center justify-end gap-1">
                                                <a href="{{ route('admin/jadwal-kelas-bus.show', $jkb) }}" class="hidden sm:inline-block">
                                                    <button class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 hover:bg-accent hover:text-accent-foreground h-8 w-8">
                                                        <x-lucide-eye class="w-4 h-4" />
                                                    </button>
                                                </a>
                                                <a href="{{ route('admin/jadwal-kelas-bus.edit', $jkb) }}">
                                                    <button class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 hover:bg-accent hover:text-accent-foreground h-8 w-8">
                                                        <x-lucide-pencil class="w-4 h-4" />
                                                    </button>
                                                </a>
                                                <form method="POST" action="{{ route('admin/jadwal-kelas-bus.destroy', $jkb) }}" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus jadwal kelas bus ini?')" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 hover:bg-accent hover:text-accent-foreground h-8 w-8 text-destructive">
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
                            <x-lucide-calendar-range class="w-8 h-8 text-muted-foreground" />
                        </div>
                        <h3 class="text-lg font-semibold mb-2">Belum Ada Jadwal Kelas Bus</h3>
                        <p class="text-sm text-muted-foreground mb-6">Mulai dengan menambahkan jadwal kelas bus pertama Anda.</p>
                        <a href="{{ route('admin/jadwal-kelas-bus.create') }}">
                            <button class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-9 px-4 py-2">
                                <x-lucide-plus class="w-4 h-4 mr-2" />
                                Tambah Jadwal Kelas Bus
                            </button>
                        </a>
                    </div>
                @endif
            </div>
            @if($jadwalKelasBus->count() > 0)
                <div class="flex items-center justify-between border-t pt-4 px-6 pb-6">
                    <div class="w-full flex flex-col sm:flex-row items-center justify-between gap-3">
                        <p class="text-xs sm:text-sm text-muted-foreground text-center sm:text-left">
                            Menampilkan {{ $jadwalKelasBus->firstItem() }} - {{ $jadwalKelasBus->lastItem() }} dari {{ $jadwalKelasBus->total() }} jadwal kelas bus
                        </p>
                        <div class="w-full sm:w-auto flex justify-center">
                            {{ $jadwalKelasBus->links('vendor.pagination.shadcn') }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>
