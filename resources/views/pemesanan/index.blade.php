@extends('layouts.admin')
@section('content')
    @push('header')
        <h2 class="text-xl font-bold text-gray-900 dark:text-white">Pesan Tiket</h2>
@endpush

    <div class="p-6 bg-secondary/30">
        <!-- Search Section -->
        <div class="mb-8">
            <x-ui.card class="mb-8">
                <x-ui.card.content class="px-6 py-6">
                <x-ui.card.content class="px-6 py-6">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6">
                        Cari Tiket Perjalanan Anda
                    </h3>

                    <form method="GET" action="{{ route('pemesanan.index') }}" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <!-- Terminal Asal -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Terminal Asal
                                </label>
                                <x-ui.input type="text" name="asal" value="{{ request('asal') }}"
                                    placeholder="Cari terminal asal..." />
                            </div>

                            <!-- Terminal Tujuan -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Terminal Tujuan
                                </label>
                                <x-ui.input type="text" name="tujuan" value="{{ request('tujuan') }}"
                                    placeholder="Cari terminal tujuan..." />
                            </div>

                            <!-- Tanggal Berangkat -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Tanggal Berangkat
                                </label>
                                <x-ui.input type="date" name="tanggal" value="{{ request('tanggal') }}"
                                    min="{{ now()->toDateString() }}" />
                            </div>

                            <!-- Search Button -->
                            <div class="flex items-end">
                                <x-ui.button type="submit" class="w-full gap-2">
                                    <i data-lucide="search" class="w-5 h-5"></i>
                                    Cari
                                </x-ui.button>
                            </div>
                        </div>
                    </form>
                </x-ui.card.content>
            </x-ui.card>
        </div>
            </div>

        <!-- Results Section -->
        @if($jadwals->count() > 0)
            <div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">
                    {{ $jadwals->total() }} Perjalanan Tersedia
                </h3>

                <div class="space-y-4">
                    @foreach($jadwals as $jadwal)
                        <x-ui.card class="hover:shadow-lg transition-all duration-300 hover:border-primary/50">
                            <x-ui.card.content class="px-6 py-4">
                                <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
                                    <!-- Route & Time -->
                                    <div>
                                        <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-3">
                                            {{ $jadwal->rute->asalTerminal->nama_kota }} →
                                            {{ $jadwal->rute->tujuanTerminal->nama_kota }}
                                        </h4>
                                        <div class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                                            <p>
                                                <span class="font-semibold">Tanggal:</span><br>
                                                {{ $jadwal->tanggal_berangkat->format('d M Y') }}
                                            </p>
                                            <p>
                                                <span class="font-semibold">Jam:</span><br>
                                                {{ $jadwal->jam_berangkat->format('H:i') }}
                                            </p>
                                            <p>
                                                <span class="font-semibold">Bus:</span><br>
                                                {{ $jadwal->bus->nama }}
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Sopir & Conductor Info -->
                                    <div>
                                        <h5 class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-3">
                                            Kru Bus
                                        </h5>
                                        <div class="space-y-2">
                                            <div class="flex items-start gap-2">
                                                <i data-lucide="user" class="w-4 h-4 text-gray-400 flex-shrink-0 mt-0.5"></i>
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                                                        {{ $jadwal->sopir->user->name }}
                                                    </p>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">Sopir</p>
                                                </div>
                                            </div>
                                            @if($jadwal->conductor)
                                                <div class="flex items-start gap-2">
                                                    <i data-lucide="user" class="w-4 h-4 text-gray-400 flex-shrink-0 mt-0.5"></i>
                                                    <div>
                                                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                                                            {{ $jadwal->conductor->user->name }}
                                                        </p>
                                                        <p class="text-xs text-gray-500 dark:text-gray-400">Kondektur</p>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Class & Price Info -->
                                    <div>
                                        <h5 class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-3">
                                            Kelas & Harga
                                        </h5>
                                        <div class="space-y-2">
                                            @forelse($jadwal->jadwalKelasBus->take(3) as $jkb)
                                                <div class="p-2 bg-gray-50 dark:bg-gray-700 rounded">
                                                    <p class="text-xs text-gray-700 dark:text-gray-300">
                                                        {{ $jkb->kelasBus->nama_kelas }}
                                                    </p>
                                                    <p class="text-sm font-bold text-primary dark:text-primary-foreground">
                                                        Rp {{ number_format($jkb->harga, 0, ',', '.') }}
                                                    </p>
                                                </div>
                                            @empty
                                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                                    Tidak ada kelas
                                                </p>
                                            @endforelse
                                        </div>
                                    </div>

                                    <!-- Kursi Tersedia -->
                                    <div>
                                        <h5 class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-3">
                                            Kursi
                                        </h5>
                                        <div class="space-y-2">
                                            @php
                                                $totalKursi = $jadwal->jadwalKelasBus->sum(function($jkb) { 
                                                    return $jkb->kelasBus->jumlah_kursi ?? 0;
                                                });
                                                $kursiTerjual = 0;
                                            @endphp
                                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                                <span class="font-semibold text-primary">{{ $totalKursi - $kursiTerjual }}</span> / {{ $totalKursi }}
                                            </p>
                                            <x-ui.badge variant="outline" class="w-fit">
                                                Tersedia
                                            </x-ui.badge>
                                        </div>
                                    </div>

                                    <!-- Action Button -->
                                    <div class="flex items-center justify-end md:justify-start">
                                        <a href="{{ route('pemesanan.create', $jadwal) }}" class="w-full">
                                            <x-ui.button class="w-full gap-2">
                                                <i data-lucide="arrow-right" class="w-4 h-4"></i>
                                                Pesan
                                            </x-ui.button>
                                        </a>
                                    </div>
                                </div>
                            </x-ui.card.content>
                        </x-ui.card>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $jadwals->links() }}
                </div>
            </div>
        @else
            <x-ui.card class="text-center py-12">
                <x-ui.card.content>
                    <i data-lucide="search" class="w-16 h-16 text-gray-300 dark:text-gray-600 mx-auto mb-4"></i>
                    <p class="text-gray-600 dark:text-gray-400 mb-2 text-lg font-semibold">
                        Tidak ada perjalanan yang ditemukan
                    </p>
                    <p class="text-sm text-gray-500 dark:text-gray-500 mb-6">
                        Coba ubah pencarian Anda atau lihat semua perjalanan tersedia
                    </p>
                    <a href="{{ route('pemesanan.index') }}" class="inline-block">
                        <x-ui.button variant="outline" class="gap-2">
                            Lihat Semua Perjalanan
                            <i data-lucide="arrow-right" class="w-4 h-4"></i>
                        </x-ui.button>
                    </a>
                </x-ui.card.content>
            </x-ui.card>
        @endif
    </div>
@endsection
