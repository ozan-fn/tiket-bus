@extends('layouts.admin')
@section('content')
    <x-slot name="header">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white">Dashboard Pengguna</h2>
    </x-slot>

    <div class="p-6 bg-secondary/30">
        <!-- Welcome Section -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                Selamat datang kembali, {{ Auth::user()->name }}!
            </h1>
            <p class="text-gray-600 dark:text-gray-400">
                Kelola pemesanan tiket dan riwayat perjalanan Anda di sini
            </p>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Total Tiket Aktif -->
            <x-ui.card class="hover:shadow-lg transition-all duration-300 hover:border-primary/50">
                <x-ui.card.content class="px-4">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <p class="text-sm text-muted-foreground">Tiket Aktif</p>
                            <p class="text-3xl font-bold text-foreground">{{ $activeTickets ?? 0 }}</p>
                        </div>
                        <div class="h-12 w-12 rounded-lg bg-blue-500/10 flex items-center justify-center">
                            <i data-lucide="ticket" class="h-6 w-6 text-blue-600 dark:text-blue-400"></i>
                        </div>
                    </div>
                </x-ui.card.content>
            </x-ui.card>

            <!-- Total Perjalanan Selesai -->
            <x-ui.card class="hover:shadow-lg transition-all duration-300 hover:border-primary/50">
                <x-ui.card.content class="px-4">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <p class="text-sm text-muted-foreground">Perjalanan Selesai</p>
                            <p class="text-3xl font-bold text-foreground">{{ $completedTrips ?? 0 }}</p>
                        </div>
                        <div class="h-12 w-12 rounded-lg bg-green-500/10 flex items-center justify-center">
                            <i data-lucide="check-circle" class="h-6 w-6 text-green-600 dark:text-green-400"></i>
                        </div>
                    </div>
                </x-ui.card.content>
            </x-ui.card>

            <!-- Total Pengeluaran -->
            <x-ui.card class="hover:shadow-lg transition-all duration-300 hover:border-primary/50">
                <x-ui.card.content class="px-4">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <p class="text-sm text-muted-foreground">Total Pengeluaran</p>
                            <p class="text-3xl font-bold text-foreground">Rp
                                {{ number_format($totalSpent ?? 0, 0, ',', '.') }}</p>
                        </div>
                        <div class="h-12 w-12 rounded-lg bg-purple-500/10 flex items-center justify-center">
                            <i data-lucide="wallet" class="h-6 w-6 text-purple-600 dark:text-purple-400"></i>
                        </div>
                    </div>
                </x-ui.card.content>
            </x-ui.card>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <!-- Tiket Saya -->
            <x-ui.card
                class="bg-gradient-to-br from-blue-500/20 to-blue-600/20 dark:from-blue-900/30 dark:to-blue-800/30 border-blue-200 dark:border-blue-800/50 hover:shadow-lg transition-all">
                <x-ui.card.content class="px-6 py-6">
                    <i data-lucide="ticket" class="w-12 h-12 text-blue-600 dark:text-blue-400 mb-4"></i>
                    <h3 class="text-xl font-bold text-foreground mb-2">Tiket Saya</h3>
                    <p class="text-muted-foreground mb-4 text-sm">
                        Lihat daftar tiket dan status pembayaran Anda
                    </p>
                    <a href="{{ route('tiket.index') }}" class="inline-block">
                        <x-ui.button variant="default" class="gap-2">
                            Lihat Tiket
                            <i data-lucide="arrow-right" class="w-4 h-4"></i>
                        </x-ui.button>
                    </a>
                </x-ui.card.content>
            </x-ui.card>

            <!-- Pesan Tiket Baru -->
            <x-ui.card
                class="bg-gradient-to-br from-primary/20 to-primary/30 dark:from-primary/20 dark:to-primary/10 border-primary/30 dark:border-primary/20 hover:shadow-lg transition-all">
                <x-ui.card.content class="px-6 py-6">
                    <i data-lucide="plus-circle" class="w-12 h-12 text-primary mb-4"></i>
                    <h3 class="text-xl font-bold text-foreground mb-2">Pesan Tiket Baru</h3>
                    <p class="text-muted-foreground mb-4 text-sm">
                        Cari dan pesan tiket perjalanan Anda sekarang
                    </p>
                    <a href="{{ route('pemesanan.index') }}" class="inline-block">
                        <x-ui.button variant="default" class="gap-2">
                            Pesan Sekarang
                            <i data-lucide="arrow-right" class="w-4 h-4"></i>
                        </x-ui.button>
                    </a>
                </x-ui.card.content>
            </x-ui.card>

            <!-- Profil Saya -->
            <x-ui.card
                class="bg-gradient-to-br from-amber-500/20 to-amber-600/20 dark:from-amber-900/30 dark:to-amber-800/30 border-amber-200 dark:border-amber-800/50 hover:shadow-lg transition-all">
                <x-ui.card.content class="px-6 py-6">
                    <i data-lucide="user" class="w-12 h-12 text-amber-600 dark:text-amber-400 mb-4"></i>
                    <h3 class="text-xl font-bold text-foreground mb-2">Profil Saya</h3>
                    <p class="text-muted-foreground mb-4 text-sm">
                        Kelola data pribadi dan pengaturan akun Anda
                    </p>
                    <a href="{{ route('profile.edit') }}" class="inline-block">
                        <x-ui.button variant="outline" class="gap-2">
                            Kelola Profil
                            <i data-lucide="arrow-right" class="w-4 h-4"></i>
                        </x-ui.button>
                    </a>
                </x-ui.card.content>
            </x-ui.card>
        </div> <!-- Tiket Aktif / Mendatang -->
        <x-ui.card class="mb-8">
            <x-ui.card.header>
                <h3 class="text-lg font-semibold text-foreground flex items-center gap-2">
                    <i data-lucide="calendar" class="w-5 h-5"></i>
                    Tiket Mendatang
                </h3>
            </x-ui.card.header>

            <x-ui.card.content class="px-0">
                @if($upcomingTickets && $upcomingTickets->count() > 0)
                    <div class="divide-y divide-border dark:divide-border/50">
                        @foreach($upcomingTickets as $ticket)
                            <div class="p-6 hover:bg-muted/50 dark:hover:bg-card/50 transition-colors">
                                <div class="flex items-start justify-between mb-3">
                                    <div>
                                        <p class="text-sm text-muted-foreground">
                                            {{ $ticket->jadwalKelasBus->jadwal->tanggal_berangkat->format('d M Y') }}
                                        </p>
                                        <h4 class="text-lg font-bold text-foreground">
                                            {{ $ticket->jadwalKelasBus->jadwal->rute->asalTerminal->nama_kota }} →
                                            {{ $ticket->jadwalKelasBus->jadwal->rute->tujuanTerminal->nama_kota }}
                                        </h4>
                                    </div>
                                    <x-ui.badge class="capitalize">
                                        {{ ucfirst($ticket->status) }}
                                    </x-ui.badge>
                                </div>

                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                                    <div>
                                        <p class="text-xs text-muted-foreground uppercase font-semibold">Jam Berangkat</p>
                                        <p class="font-bold text-foreground">
                                            {{ $ticket->jadwalKelasBus->jadwal->jam_berangkat->format('H:i') }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-muted-foreground uppercase font-semibold">Bus</p>
                                        <p class="font-bold text-foreground">
                                            {{ $ticket->jadwalKelasBus->busKelasBus->bus->nama }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-muted-foreground uppercase font-semibold">Kelas</p>
                                        <p class="font-bold text-foreground">
                                            {{ $ticket->jadwalKelasBus->kelasBus->nama_kelas }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-muted-foreground uppercase font-semibold">Kursi</p>
                                        <p class="font-bold text-foreground">
                                            {{ $ticket->kursi->nomor_kursi }}
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between pt-4 border-t border-border/50">
                                    <div>
                                        <p class="text-xs text-muted-foreground uppercase font-semibold">Kode Tiket</p>
                                        <p class="font-mono font-bold text-foreground">
                                            {{ $ticket->kode_tiket }}
                                        </p>
                                    </div>
                                    <a href="{{ route('pemesanan.show', $ticket) }}">
                                        <x-ui.button variant="outline" size="sm" class="gap-2">
                                            Lihat Detail
                                            <i data-lucide="arrow-right" class="w-4 h-4"></i>
                                        </x-ui.button>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-12 text-center">
                        <i data-lucide="inbox" class="w-16 h-16 text-muted-foreground/30 mx-auto mb-4"></i>
                        <p class="text-muted-foreground mb-4 font-semibold">
                            Belum ada tiket mendatang
                        </p>
                        <a href="{{ route('pemesanan.index') }}">
                            <x-ui.button class="gap-2">
                                Pesan Tiket Sekarang
                                <i data-lucide="arrow-right" class="w-4 h-4"></i>
                            </x-ui.button>
                        </a>
                    </div>
                @endif
            </x-ui.card.content>
        </x-ui.card>

        <!-- Riwayat Perjalanan -->
        <x-ui.card>
            <x-ui.card.header>
                <h3 class="text-lg font-semibold text-foreground flex items-center gap-2">
                    <i data-lucide="history" class="w-5 h-5"></i>
                    Riwayat Perjalanan
                </h3>
            </x-ui.card.header>

            <x-ui.card.content class="px-0">
                @if($completedTickets && $completedTickets->count() > 0)
                    <div class="divide-y divide-border dark:divide-border/50">
                        @foreach($completedTickets as $ticket)
                            <div class="p-6 hover:bg-muted/50 dark:hover:bg-card/50 transition-colors">
                                <div class="flex items-start justify-between mb-3">
                                    <div>
                                        <p class="text-sm text-muted-foreground">
                                            {{ $ticket->jadwalKelasBus->jadwal->tanggal_berangkat->format('d M Y') }}
                                        </p>
                                        <h4 class="text-lg font-bold text-foreground">
                                            {{ $ticket->jadwalKelasBus->jadwal->rute->asalTerminal->nama_kota }} →
                                            {{ $ticket->jadwalKelasBus->jadwal->rute->tujuanTerminal->nama_kota }}
                                        </h4>
                                    </div>
                                    <x-ui.badge variant="outline"
                                        class="bg-green-500/10 border-green-200 dark:border-green-800/50 text-green-700 dark:text-green-400">
                                        Selesai
                                    </x-ui.badge>
                                </div>

                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                    <div>
                                        <p class="text-xs text-muted-foreground uppercase font-semibold">Bus</p>
                                        <p class="font-bold text-foreground">
                                            {{ $ticket->jadwalKelasBus->busKelasBus->bus->nama }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-muted-foreground uppercase font-semibold">Kelas</p>
                                        <p class="font-bold text-foreground">
                                            {{ $ticket->jadwalKelasBus->kelasBus->nama_kelas }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-muted-foreground uppercase font-semibold">Harga</p>
                                        <p class="font-bold text-primary">
                                            Rp {{ number_format($ticket->harga, 0, ',', '.') }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-muted-foreground uppercase font-semibold">Kursi</p>
                                        <p class="font-bold text-foreground">
                                            {{ $ticket->kursi->nomor_kursi }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-12 text-center">
                        <i data-lucide="inbox" class="w-16 h-16 text-muted-foreground/30 mx-auto mb-4"></i>
                        <p class="text-muted-foreground font-semibold">
                            Belum ada riwayat perjalanan
                        </p>
                    </div>
                @endif
            </x-ui.card.content>
        </x-ui.card>
    </div>
@endsection
