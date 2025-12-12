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
                        Pesan Tiket
                    </x-ui.breadcrumb.page>
                </x-ui.breadcrumb.item>
            </x-ui.breadcrumb.list>
        </x-ui.breadcrumb.breadcrumb>
    </x-slot>

    <div class="p-4 sm:p-6">
        <x-ui.card>
            <x-ui.card.header>
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div class="flex-1">
                        <x-ui.card.title>Pesan Tiket</x-ui.card.title>
                        <x-ui.card.description>Cari dan pesan tiket untuk penumpang</x-ui.card.description>

                        <!-- Search Bar -->
                        <form method="GET" action="{{ route('admin/pemesanan.index') }}" class="flex gap-2 mt-4 flex-wrap">
                            <div class="relative flex-1 sm:flex-initial sm:w-80">
                                <x-lucide-search class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
                                <x-ui.input
                                    type="text"
                                    name="asal"
                                    placeholder="Asal terminal..."
                                    value="{{ request('asal') }}"
                                    class="pl-9 h-10"
                                />
                            </div>
                            <div class="relative flex-1 sm:flex-initial sm:w-80">
                                <x-lucide-map-pin class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
                                <x-ui.input
                                    type="text"
                                    name="tujuan"
                                    placeholder="Tujuan terminal..."
                                    value="{{ request('tujuan') }}"
                                    class="pl-9 h-10"
                                />
                            </div>
                            <div class="flex-1 sm:flex-initial sm:w-56">
                                <x-datepicker
                                    name="tanggal"
                                    placeholder="Pilih tanggal..."
                                    :value="request('tanggal')"
                                />
                            </div>
                            <x-ui.button type="submit" variant="outline" size="sm" class="h-9 w-9 shrink-0">
                                <x-lucide-search class="w-4 h-4" />
                            </x-ui.button>
                            @if(request('asal') || request('tujuan') || request('tanggal'))
                                <a href="{{ route('admin/pemesanan.index') }}" class="shrink-0">
                                    <x-ui.button type="button" variant="outline" size="icon" class="h-10 w-10">
                                        <x-lucide-x class="w-4 h-4" />
                                    </x-ui.button>
                                </a>
                            @endif
                        </form>
                    </div>
                </div>
            </x-ui.card.header>
            <x-ui.card.content class="p-0 sm:p-6">
                @if($jadwals->count() > 0)
                    <div class="space-y-4">
                        @foreach($jadwals as $jadwal)
                            <!-- Desktop View -->
                            <div class="hidden sm:block bg-accent/50 border border-border rounded-lg p-4">
                                <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-center">
                                    <!-- Bus Info -->
                                    <div>
                                        <p class="text-xs text-muted-foreground mb-1">Bus</p>
                                        <div class="flex items-center gap-2">
                                            <div class="h-8 w-8 rounded-lg bg-primary/10 flex items-center justify-center shrink-0">
                                                <x-lucide-bus class="h-4 w-4 text-primary" />
                                            </div>
                                            <div>
                                                <p class="font-semibold text-sm">{{ $jadwal->bus->nama }}</p>
                                                <p class="text-xs text-muted-foreground">{{ $jadwal->bus->plat_nomor }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Rute Info -->
                                    <div>
                                        <p class="text-xs text-muted-foreground mb-1">Rute</p>
                                        <div class="flex items-center gap-1 text-sm">
                                            <span class="font-semibold">{{ $jadwal->rute->asalTerminal->nama_kota }}</span>
                                            <x-lucide-arrow-right class="h-3 w-3 text-muted-foreground" />
                                            <span class="font-semibold">{{ $jadwal->rute->tujuanTerminal->nama_kota }}</span>
                                        </div>
                                    </div>

                                    <!-- Jadwal Info -->
                                    <div>
                                        <p class="text-xs text-muted-foreground mb-1">Jadwal</p>
                                        <p class="font-semibold text-sm">{{ $jadwal->tanggal_berangkat->format('d M Y') }}</p>
                                        <p class="text-xs text-muted-foreground">{{ $jadwal->jam_berangkat->format('H:i') }} WIB</p>
                                    </div>

                                    <!-- Harga Info -->
                                    <div>
                                        <p class="text-xs text-muted-foreground mb-1">Harga</p>
                                        <p class="font-semibold text-primary text-sm">
                                            Rp {{ number_format($jadwal->jadwalKelasBus->first()?->harga ?? 0, 0, ',', '.') }}
                                        </p>
                                    </div>

                                    <!-- Action -->
                                    <div class="flex justify-end">
                                        <a href="{{ route('admin/pemesanan.create', $jadwal) }}">
                                            <x-ui.button size="sm" class="flex items-center gap-2">
                                                <x-lucide-plus class="w-4 h-4" />
                                                Pesan
                                            </x-ui.button>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Mobile View -->
                            <div class="sm:hidden bg-card border border-border rounded-lg p-4">
                                <div class="space-y-3">
                                    <!-- Header -->
                                    <div class="flex items-start justify-between gap-2">
                                        <div class="flex items-center gap-2 flex-1">
                                            <div class="h-8 w-8 rounded-lg bg-primary/10 flex items-center justify-center shrink-0">
                                                <x-lucide-bus class="h-4 w-4 text-primary" />
                                            </div>
                                            <div>
                                                <p class="font-semibold text-sm">{{ $jadwal->bus->nama }}</p>
                                                <p class="text-xs text-muted-foreground">{{ $jadwal->bus->plat_nomor }}</p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-xs text-muted-foreground">Harga</p>
                                            <p class="font-semibold text-primary text-sm">
                                                Rp {{ number_format($jadwal->jadwalKelasBus->first()?->harga ?? 0, 0, ',', '.') }}
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Route -->
                                    <div class="space-y-1">
                                        <p class="text-xs text-muted-foreground">Rute</p>
                                        <div class="flex items-center gap-2 text-sm">
                                            <x-lucide-map-pin class="h-4 w-4 text-muted-foreground" />
                                            <span class="font-semibold">{{ $jadwal->rute->asalTerminal->nama_kota }} → {{ $jadwal->rute->tujuanTerminal->nama_kota }}</span>
                                        </div>
                                    </div>

                                    <!-- Jadwal -->
                                    <div class="space-y-1">
                                        <p class="text-xs text-muted-foreground">Jadwal</p>
                                        <div class="flex items-center gap-2 text-sm">
                                            <x-lucide-calendar class="h-4 w-4 text-muted-foreground" />
                                            <div>
                                                <p class="font-semibold">{{ $jadwal->tanggal_berangkat->format('d M Y') }}</p>
                                                <p class="text-xs text-muted-foreground">{{ $jadwal->jam_berangkat->format('H:i') }} WIB</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Button -->
                                    <a href="{{ route('admin/pemesanan.create', $jadwal) }}" class="w-full">
                                        <x-ui.button class="w-full flex items-center justify-center gap-2" size="sm">
                                            <x-lucide-plus class="w-4 h-4" />
                                            Pesan Tiket
                                        </x-ui.button>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $jadwals->links('vendor.pagination.shadcn') }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="h-16 w-16 rounded-full bg-muted flex items-center justify-center mx-auto mb-4">
                            <x-lucide-inbox class="h-8 w-8 text-muted-foreground" />
                        </div>
                        <h3 class="text-lg font-semibold text-foreground mb-2">Tidak ada jadwal tersedia</h3>
                        <p class="text-muted-foreground">Coba ubah filter pencarian Anda</p>
                    </div>
                @endif
            </x-ui.card.content>
        </x-ui.card>
    </div>
</x-admin-layout>
