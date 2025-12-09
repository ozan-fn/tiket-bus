<x-admin-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="text-xl sm:text-2xl font-bold">Detail Jadwal Kelas Bus</h2>
                <p class="text-sm text-muted-foreground mt-1">Informasi lengkap jadwal kelas bus</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                <a href="{{ route('admin/jadwal-kelas-bus.edit', $jadwalKelasBu) }}" class="w-full sm:w-auto">
                    <button class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-9 px-4 py-2 w-full sm:w-auto">
                        <x-lucide-pencil class="w-4 h-4 mr-2" />
                        Edit
                    </button>
                </a>
                <a href="{{ route('admin/jadwal-kelas-bus.index') }}" class="w-full sm:w-auto">
                    <button class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2 w-full sm:w-auto">
                        <x-lucide-arrow-left class="w-4 h-4 mr-2" />
                        Kembali
                    </button>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="p-4 sm:p-6 space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                    <div class="flex flex-col space-y-1.5 p-6">
                        <h3 class="text-2xl font-semibold leading-none tracking-tight">Informasi Jadwal</h3>
                        <p class="text-sm text-muted-foreground">Detail informasi jadwal keberangkatan</p>
                    </div>
                    <div class="p-6 pt-0 space-y-4">
                        <div class="flex items-start gap-4">
                            <div class="h-12 w-12 rounded-lg bg-primary/10 flex items-center justify-center shrink-0">
                                <x-lucide-route class="h-6 w-6 text-primary" />
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-muted-foreground mb-1">Rute Perjalanan</p>
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="font-semibold text-lg">{{ $jadwalKelasBu->jadwal->rute->asalTerminal->nama }}</span>
                                    <x-lucide-arrow-right class="h-5 w-5 text-muted-foreground" />
                                    <span class="font-semibold text-lg">{{ $jadwalKelasBu->jadwal->rute->tujuanTerminal->nama }}</span>
                                </div>
                                <div class="flex items-center gap-4 mt-2 text-sm text-muted-foreground">
                                    <div class="flex items-center gap-1">
                                        <x-lucide-map-pin class="h-4 w-4" />
                                        <span>{{ $jadwalKelasBu->jadwal->rute->asalTerminal->kota }}</span>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <x-lucide-map-pin class="h-4 w-4" />
                                        <span>{{ $jadwalKelasBu->jadwal->rute->tujuanTerminal->kota }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="border-t pt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="flex items-start gap-3">
                                <div class="h-10 w-10 rounded-lg bg-blue-500/10 flex items-center justify-center shrink-0">
                                    <x-lucide-calendar class="h-5 w-5 text-blue-600 dark:text-blue-400" />
                                </div>
                                <div>
                                    <p class="text-sm text-muted-foreground">Tanggal Berangkat</p>
                                    <p class="font-semibold">{{ \Carbon\Carbon::parse($jadwalKelasBu->jadwal->tanggal_berangkat)->format('d F Y') }}</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3">
                                <div class="h-10 w-10 rounded-lg bg-purple-500/10 flex items-center justify-center shrink-0">
                                    <x-lucide-clock class="h-5 w-5 text-purple-600 dark:text-purple-400" />
                                </div>
                                <div>
                                    <p class="text-sm text-muted-foreground">Waktu Berangkat</p>
                                    <p class="font-semibold">{{ \Carbon\Carbon::parse($jadwalKelasBu->jadwal->waktu_berangkat)->format('H:i') }} WIB</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3">
                                <div class="h-10 w-10 rounded-lg bg-green-500/10 flex items-center justify-center shrink-0">
                                    <x-lucide-clock class="h-5 w-5 text-green-600 dark:text-green-400" />
                                </div>
                                <div>
                                    <p class="text-sm text-muted-foreground">Estimasi Tiba</p>
                                    <p class="font-semibold">{{ \Carbon\Carbon::parse($jadwalKelasBu->jadwal->estimasi_waktu_tiba)->format('H:i') }} WIB</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3">
                                <div class="h-10 w-10 rounded-lg bg-orange-500/10 flex items-center justify-center shrink-0">
                                    <x-lucide-hourglass class="h-5 w-5 text-orange-600 dark:text-orange-400" />
                                </div>
                                <div>
                                    <p class="text-sm text-muted-foreground">Durasi Perjalanan</p>
                                    <p class="font-semibold">{{ $jadwalKelasBu->jadwal->rute->durasi_perjalanan }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                    <div class="flex flex-col space-y-1.5 p-6">
                        <h3 class="text-2xl font-semibold leading-none tracking-tight">Informasi Bus & Kelas</h3>
                        <p class="text-sm text-muted-foreground">Detail informasi bus dan kelas yang digunakan</p>
                    </div>
                    <div class="p-6 pt-0 space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="p-4 rounded-lg border bg-muted/50">
                                <div class="flex items-center gap-3 mb-3">
                                    <div class="h-10 w-10 rounded-lg bg-primary/10 flex items-center justify-center">
                                        <x-lucide-bus class="h-5 w-5 text-primary" />
                                    </div>
                                    <h4 class="font-semibold">Bus Jadwal</h4>
                                </div>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-muted-foreground">Nama:</span>
                                        <span class="font-medium">{{ $jadwalKelasBu->jadwal->bus->nama }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-muted-foreground">Plat Nomor:</span>
                                        <span class="font-medium">{{ $jadwalKelasBu->jadwal->bus->plat_nomor }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-muted-foreground">Kapasitas:</span>
                                        <span class="font-medium">{{ $jadwalKelasBu->jadwal->bus->kapasitas }} kursi</span>
                                    </div>
                                </div>
                            </div>

                            <div class="p-4 rounded-lg border bg-muted/50">
                                <div class="flex items-center gap-3 mb-3">
                                    <div class="h-10 w-10 rounded-lg bg-primary/10 flex items-center justify-center">
                                        <x-lucide-layers class="h-5 w-5 text-primary" />
                                    </div>
                                    <h4 class="font-semibold">Kelas Bus</h4>
                                </div>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-muted-foreground">Nama Kelas:</span>
                                        <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 border-transparent bg-primary/10 text-primary">
                                            {{ $jadwalKelasBu->kelasBus->nama_kelas }}
                                        </span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-muted-foreground">Bus:</span>
                                        <span class="font-medium">{{ $jadwalKelasBu->kelasBus->bus->nama }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-muted-foreground">Jumlah Kursi:</span>
                                        <span class="font-medium">{{ $jadwalKelasBu->kelasBus->jumlah_kursi }} kursi</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($jadwalKelasBu->kelasBus->deskripsi)
                            <div class="p-4 rounded-lg border bg-muted/50">
                                <p class="text-sm font-medium mb-2">Deskripsi Kelas:</p>
                                <p class="text-sm text-muted-foreground">{{ $jadwalKelasBu->kelasBus->deskripsi }}</p>
                            </div>
                        @endif

                        @if($jadwalKelasBu->jadwal->sopir)
                            <div class="p-4 rounded-lg border bg-muted/50">
                                <div class="flex items-center gap-3">
                                    <div class="h-10 w-10 rounded-lg bg-primary/10 flex items-center justify-center">
                                        <x-lucide-user class="h-5 w-5 text-primary" />
                                    </div>
                                    <div>
                                        <p class="text-sm text-muted-foreground">Sopir</p>
                                        <p class="font-semibold">{{ $jadwalKelasBu->jadwal->sopir->user->name }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                @if($jadwalKelasBu->tikets->count() > 0)
                    <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                        <div class="flex flex-col space-y-1.5 p-6">
                            <h3 class="text-2xl font-semibold leading-none tracking-tight">Daftar Tiket</h3>
                            <p class="text-sm text-muted-foreground">Tiket yang sudah dipesan untuk jadwal kelas bus ini</p>
                        </div>
                        <div class="p-6 pt-0">
                            <div class="overflow-x-auto -mx-4 sm:mx-0">
                                <table class="w-full caption-bottom text-sm">
                                    <thead class="[&_tr]:border-b">
                                        <tr class="border-b transition-colors hover:bg-muted/50">
                                            <th class="h-10 px-2 text-left align-middle font-medium whitespace-nowrap">Kode Booking</th>
                                            <th class="h-10 px-2 text-left align-middle font-medium whitespace-nowrap">Penumpang</th>
                                            <th class="h-10 px-2 text-left align-middle font-medium whitespace-nowrap">No. Kursi</th>
                                            <th class="h-10 px-2 text-left align-middle font-medium whitespace-nowrap">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="[&_tr:last-child]:border-0">
                                        @foreach($jadwalKelasBu->tikets as $tiket)
                                            <tr class="border-b transition-colors hover:bg-muted/50">
                                                <td class="p-2 align-middle whitespace-nowrap font-mono text-xs">{{ $tiket->kode_booking }}</td>
                                                <td class="p-2 align-middle whitespace-nowrap">{{ $tiket->penumpang->user->name }}</td>
                                                <td class="p-2 align-middle whitespace-nowrap">
                                                    <span class="inline-flex items-center justify-center rounded-md bg-primary/10 px-2 py-1 text-xs font-medium text-primary">
                                                        {{ $tiket->nomor_kursi }}
                                                    </span>
                                                </td>
                                                <td class="p-2 align-middle whitespace-nowrap">
                                                    @if($tiket->status_tiket == 'booked')
                                                        <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors border-transparent bg-blue-500/10 text-blue-700 dark:text-blue-400">
                                                            Dipesan
                                                        </span>
                                                    @elseif($tiket->status_tiket == 'paid')
                                                        <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors border-transparent bg-green-500/10 text-green-700 dark:text-green-400">
                                                            Dibayar
                                                        </span>
                                                    @elseif($tiket->status_tiket == 'cancelled')
                                                        <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors border-transparent bg-red-500/10 text-red-700 dark:text-red-400">
                                                            Dibatalkan
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors border-transparent bg-gray-500/10 text-gray-700 dark:text-gray-400">
                                                            {{ ucfirst($tiket->status_tiket) }}
                                                        </span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="space-y-6">
                <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                    <div class="flex flex-col space-y-1.5 p-6">
                        <h3 class="text-lg font-semibold leading-none tracking-tight">Harga Tiket</h3>
                    </div>
                    <div class="p-6 pt-0">
                        <div class="text-center py-4">
                            <p class="text-sm text-muted-foreground mb-2">Harga per Tiket</p>
                            <p class="text-3xl font-bold text-primary">Rp {{ number_format($jadwalKelasBu->harga, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                    <div class="flex flex-col space-y-1.5 p-6">
                        <h3 class="text-lg font-semibold leading-none tracking-tight">Statistik</h3>
                    </div>
                    <div class="p-6 pt-0 space-y-4">
                        <div class="flex items-center justify-between p-3 rounded-lg bg-blue-500/10">
                            <div class="flex items-center gap-2">
                                <x-lucide-ticket class="h-5 w-5 text-blue-600 dark:text-blue-400" />
                                <span class="text-sm font-medium">Total Tiket</span>
                            </div>
                            <span class="text-lg font-bold text-blue-600 dark:text-blue-400">{{ $jadwalKelasBu->tikets->count() }}</span>
                        </div>

                        <div class="flex items-center justify-between p-3 rounded-lg bg-green-500/10">
                            <div class="flex items-center gap-2">
                                <x-lucide-check-circle class="h-5 w-5 text-green-600 dark:text-green-400" />
                                <span class="text-sm font-medium">Dibayar</span>
                            </div>
                            <span class="text-lg font-bold text-green-600 dark:text-green-400">{{ $jadwalKelasBu->tikets->where('status_tiket', 'paid')->count() }}</span>
                        </div>

                        <div class="flex items-center justify-between p-3 rounded-lg bg-orange-500/10">
                            <div class="flex items-center gap-2">
                                <x-lucide-clock class="h-5 w-5 text-orange-600 dark:text-orange-400" />
                                <span class="text-sm font-medium">Dipesan</span>
                            </div>
                            <span class="text-lg font-bold text-orange-600 dark:text-orange-400">{{ $jadwalKelasBu->tikets->where('status_tiket', 'booked')->count() }}</span>
                        </div>

                        <div class="border-t pt-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium">Total Pendapatan</span>
                                <span class="text-lg font-bold text-primary">
                                    Rp {{ number_format($jadwalKelasBu->tikets->where('status_tiket', 'paid')->count() * $jadwalKelasBu->harga, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                    <div class="flex flex-col space-y-1.5 p-6">
                        <h3 class="text-lg font-semibold leading-none tracking-tight">Aksi</h3>
                    </div>
                    <div class="p-6 pt-0 space-y-2">
                        <a href="{{ route('admin/jadwal-kelas-bus.edit', $jadwalKelasBu) }}" class="block">
                            <button class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 py-2 w-full">
                                <x-lucide-pencil class="w-4 h-4 mr-2" />
                                Edit Jadwal Kelas Bus
                            </button>
                        </a>

                        @if($jadwalKelasBu->tikets->count() == 0)
                            <form method="POST" action="{{ route('admin/jadwal-kelas-bus.destroy', $jadwalKelasBu) }}" class="block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus jadwal kelas bus ini?')" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-destructive text-destructive-foreground hover:bg-destructive/90 h-10 px-4 py-2 w-full">
                                    <x-lucide-trash-2 class="w-4 h-4 mr-2" />
                                    Hapus Jadwal Kelas Bus
                                </button>
                            </form>
                        @else
                            <button disabled class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2 w-full cursor-not-allowed">
                                <x-lucide-trash-2 class="w-4 h-4 mr-2" />
                                Tidak Dapat Dihapus (Ada Tiket)
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
