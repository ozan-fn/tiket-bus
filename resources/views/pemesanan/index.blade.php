@extends('layouts.admin')
@section('content')
    @push('header')
        <h2 class="text-xl font-bold text-gray-900 dark:text-white">Pesan Tiket</h2>
    @endpush

    <div class="p-6 space-y-6">
        <!-- Search Section -->
        <x-ui.card>
            <x-ui.card.header>
                <x-ui.card.title class="flex items-center gap-2">
                    <i data-lucide="search" class="w-5 h-5 text-primary"></i>
                    Cari Tiket Perjalanan
                </x-ui.card.title>
                <x-ui.card.description>
                    Temukan jadwal keberangkatan bus terbaik untuk perjalanan Anda
                </x-ui.card.description>
            </x-ui.card.header>
            <x-ui.card.content>
                <form method="GET" action="{{ route('pemesanan.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Terminal Asal -->
                    <div class="space-y-2">
                        <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                            Terminal Asal
                        </label>
                        <x-ui.input type="text" name="asal" value="{{ request('asal') }}"
                            placeholder="Cari terminal asal..." class="h-10" />
                    </div>

                    <!-- Terminal Tujuan -->
                    <div class="space-y-2">
                        <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                            Terminal Tujuan
                        </label>
                        <x-ui.input type="text" name="tujuan" value="{{ request('tujuan') }}"
                            placeholder="Cari terminal tujuan..." class="h-10" />
                    </div>

                    <!-- Tanggal Berangkat -->
                    <div class="space-y-2">
                        <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                            Tanggal Berangkat
                        </label>
                        <x-datepicker name="tanggal" id="tanggal" value="{{ request('tanggal') }}"
                            placeholder="Pilih tanggal..." class="h-10" />
                    </div>

                    <!-- Search Button -->
                    <div class="flex items-end">
                        <x-ui.button type="submit" class="w-full h-10 gap-2">
                            <i data-lucide="search" class="w-4 h-4"></i>
                            Cari Tiket
                        </x-ui.button>
                    </div>
                </form>
            </x-ui.card.content>
        </x-ui.card>

        <!-- Results Section -->
        @if($jadwals->count() > 0)
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold tracking-tight">
                        {{ $jadwals->count() }} Perjalanan Tersedia
                    </h3>
                </div>

                <div class="grid gap-4">
                    @foreach($jadwals as $jadwal)
                        <x-ui.card class="overflow-hidden hover:border-primary/50 transition-colors">
                            <x-ui.card.content class="p-0">
                                <div class="flex flex-col md:flex-row">
                                    <!-- Main Info -->
                                    <div class="flex-1 p-6">
                                        <div class="flex items-start justify-between mb-4">
                                            <div>
                                                <div class="flex items-center gap-2 text-sm text-muted-foreground mb-1">
                                                    <i data-lucide="calendar" class="w-4 h-4"></i>
                                                    {{ $jadwal->tanggal_berangkat->format('d M Y') }}
                                                </div>
                                                <h4 class="text-xl font-bold tracking-tight">
                                                    {{ $jadwal->rute->asalTerminal->nama_kota }} 
                                                    <span class="text-muted-foreground mx-2">→</span>
                                                    {{ $jadwal->rute->tujuanTerminal->nama_kota }}
                                                </h4>
                                            </div>
                                            <div class="text-right">
                                                <div class="text-sm text-muted-foreground mb-1">Mulai dari</div>
                                                <div class="text-xl font-bold text-primary">
                                                    Rp {{ number_format($jadwal->jadwalKelasBus->min('harga'), 0, ',', '.') }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 py-4 border-y border-border/50">
                                            <div>
                                                <p class="text-[10px] uppercase font-bold text-muted-foreground tracking-wider mb-1">Jam Berangkat</p>
                                                <p class="font-semibold flex items-center gap-1.5">
                                                    <i data-lucide="clock" class="w-4 h-4 text-muted-foreground"></i>
                                                    {{ $jadwal->jam_berangkat->format('H:i') }}
                                                </p>
                                            </div>
                                            <div>
                                                <p class="text-[10px] uppercase font-bold text-muted-foreground tracking-wider mb-1">Bus</p>
                                                <p class="font-semibold flex items-center gap-1.5">
                                                    <i data-lucide="bus" class="w-4 h-4 text-muted-foreground"></i>
                                                    {{ $jadwal->bus->nama }}
                                                </p>
                                            </div>
                                            <div>
                                                <p class="text-[10px] uppercase font-bold text-muted-foreground tracking-wider mb-1">Sopir</p>
                                                <p class="font-semibold flex items-center gap-1.5">
                                                    <i data-lucide="user" class="w-4 h-4 text-muted-foreground"></i>
                                                    {{ $jadwal->sopir?->user?->name ?? '-' }}
                                                </p>
                                            </div>
                                            <div>
                                                <p class="text-[10px] uppercase font-bold text-muted-foreground tracking-wider mb-1">Kondektur</p>
                                                <p class="font-semibold flex items-center gap-1.5">
                                                    <i data-lucide="user-check" class="w-4 h-4 text-muted-foreground"></i>
                                                    {{ $jadwal->conductor?->user?->name ?? '-' }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="mt-4 flex flex-wrap gap-2">
                                            @foreach($jadwal->bus->fasilitas as $fasilitas)
                                                <x-ui.badge variant="secondary" class="rounded-sm font-normal">
                                                    {{ $fasilitas->nama }}
                                                </x-ui.badge>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Action Area -->
                                    <div class="bg-muted/30 md:w-64 p-6 flex flex-col justify-center border-t md:border-t-0 md:border-l border-border/50">
                                        <div class="space-y-3">
                                            <div class="text-sm text-center text-muted-foreground">
                                                Tersedia {{ $jadwal->jadwalKelasBus->count() }} Kelas Bus
                                            </div>
                                            <a href="{{ route('pemesanan.create', $jadwal) }}" class="block">
                                                <x-ui.button class="w-full gap-2">
                                                    Pilih Kursi
                                                    <i data-lucide="chevron-right" class="w-4 h-4"></i>
                                                </x-ui.button>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </x-ui.card.content>
                        </x-ui.card>
                    @endforeach
                </div>

                </div>
            </div>
        @else
            <x-ui.card class="p-12 text-center">
                <div class="flex flex-col items-center gap-4">
                    <div class="h-20 w-20 rounded-full bg-muted flex items-center justify-center">
                        <i data-lucide="bus" class="w-10 h-10 text-muted-foreground"></i>
                    </div>
                    <div class="space-y-1">
                        <h3 class="text-xl font-bold tracking-tight">Tidak ada perjalanan ditemukan</h3>
                        <p class="text-muted-foreground">Coba sesuaikan pencarian Anda untuk menemukan jadwal lain.</p>
                    </div>
                    <x-ui.button variant="outline" onclick="window.location.href='{{ route('pemesanan.index') }}'">
                        Reset Pencarian
                    </x-ui.button>
                </div>
            </x-ui.card>
        @endif
    </div>
@endsection

