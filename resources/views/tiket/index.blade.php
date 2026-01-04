@extends('layouts.admin')
@section('content')
    @push('header')
        <h2 class="text-xl font-bold text-gray-900 dark:text-white">Tiket Saya</h2>
    @endpush

    <div class="p-6 space-y-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight">Riwayat Pemesanan</h1>
                <p class="text-muted-foreground">Kelola dan lihat detail tiket perjalanan Anda</p>
            </div>
        </div>

        <!-- Filter Tabs -->
        <div class="flex flex-wrap gap-2 p-1 bg-muted rounded-lg w-fit">
            <a href="{{ route('tiket.index') }}"
                class="px-4 py-1.5 text-sm font-medium rounded-md transition-all {{ !request('status') ? 'bg-background text-foreground shadow-sm' : 'text-muted-foreground hover:text-foreground' }}">
                Semua
            </a>
            <a href="{{ route('tiket.index', ['status' => 'dipesan']) }}"
                class="px-4 py-1.5 text-sm font-medium rounded-md transition-all {{ request('status') === 'dipesan' ? 'bg-background text-foreground shadow-sm' : 'text-muted-foreground hover:text-foreground' }}">
                Pending
            </a>
            <a href="{{ route('tiket.index', ['status' => 'dibayar']) }}"
                class="px-4 py-1.5 text-sm font-medium rounded-md transition-all {{ request('status') === 'dibayar' ? 'bg-background text-foreground shadow-sm' : 'text-muted-foreground hover:text-foreground' }}">
                Terbayar
            </a>
            <a href="{{ route('tiket.index', ['status' => 'selesai']) }}"
                class="px-4 py-1.5 text-sm font-medium rounded-md transition-all {{ request('status') === 'selesai' ? 'bg-background text-foreground shadow-sm' : 'text-muted-foreground hover:text-foreground' }}">
                Selesai
            </a>
            <a href="{{ route('tiket.index', ['status' => 'batal']) }}"
                class="px-4 py-1.5 text-sm font-medium rounded-md transition-all {{ request('status') === 'batal' ? 'bg-background text-foreground shadow-sm' : 'text-muted-foreground hover:text-foreground' }}">
                Batal
            </a>
        </div>

        @if ($tikets->isEmpty())
            <x-ui.card class="p-12 text-center">
                <div class="flex flex-col items-center gap-4">
                    <div class="h-20 w-20 rounded-full bg-muted flex items-center justify-center">
                        <i data-lucide="ticket" class="w-10 h-10 text-muted-foreground"></i>
                    </div>
                    <div class="space-y-1">
                        <h3 class="text-xl font-bold tracking-tight">Belum ada tiket</h3>
                        <p class="text-muted-foreground">Anda belum memiliki riwayat pemesanan tiket.</p>
                    </div>
                    <a href="{{ route('pemesanan.index') }}">
                        <x-ui.button class="gap-2">
                            Pesan Tiket Sekarang
                            <i data-lucide="arrow-right" class="w-4 h-4"></i>
                        </x-ui.button>
                    </a>
                </div>
            </x-ui.card>
        @else
            <div class="grid gap-4">
                @foreach ($tikets as $tiket)
                    <x-ui.card class="overflow-hidden hover:border-primary/50 transition-colors">
                        <x-ui.card.content class="p-0">
                            <div class="flex flex-col md:flex-row">
                                <div class="flex-1 p-6">
                                    <div class="flex items-start justify-between mb-4">
                                        <div>
                                            <div class="flex items-center gap-2 text-sm text-muted-foreground mb-1">
                                                <span class="font-mono uppercase tracking-wider">{{ $tiket->kode_tiket }}</span>
                                                <span>•</span>
                                                <span>{{ \Carbon\Carbon::parse($tiket->jadwalKelasBus->jadwal->tanggal)->format('d M Y') }}</span>
                                            </div>
                                            <h3 class="text-xl font-bold tracking-tight">
                                                {{ $tiket->jadwalKelasBus->jadwal->rute->asalTerminal->nama_kota }}
                                                <span class="text-muted-foreground mx-2">→</span>
                                                {{ $tiket->jadwalKelasBus->jadwal->rute->tujuanTerminal->nama_kota }}
                                            </h3>
                                        </div>
                                        <div>
                                            @if ($tiket->status === 'dipesan')
                                                <x-ui.badge variant="outline"
                                                    class="bg-yellow-500/10 text-yellow-600 border-yellow-500/20">Menunggu
                                                    Pembayaran</x-ui.badge>
                                            @elseif ($tiket->status === 'dibayar')
                                                <x-ui.badge variant="outline"
                                                    class="bg-green-500/10 text-green-600 border-green-500/20">Terbayar</x-ui.badge>
                                            @elseif ($tiket->status === 'selesai')
                                                <x-ui.badge variant="outline"
                                                    class="bg-blue-500/10 text-blue-600 border-blue-500/20">Selesai</x-ui.badge>
                                            @elseif ($tiket->status === 'batal')
                                                <x-ui.badge variant="outline"
                                                    class="bg-destructive/10 text-destructive border-destructive/20">Dibatalkan</x-ui.badge>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 py-4 border-y border-border/50">
                                        <div>
                                            <p class="text-[10px] uppercase font-bold text-muted-foreground tracking-wider mb-1">Jam
                                            </p>
                                            <p class="font-semibold">{{ $tiket->jadwalKelasBus->jadwal->jam_berangkat }}</p>
                                        </div>
                                        <div>
                                            <p class="text-[10px] uppercase font-bold text-muted-foreground tracking-wider mb-1">
                                                Kursi</p>
                                            <p class="font-semibold">{{ $tiket->kursi->nomor_kursi }}</p>
                                        </div>
                                        <div>
                                            <p class="text-[10px] uppercase font-bold text-muted-foreground tracking-wider mb-1">
                                                Kelas</p>
                                            <p class="font-semibold">{{ $tiket->jadwalKelasBus->kelasBus->nama_kelas }}</p>
                                        </div>
                                        <div>
                                            <p class="text-[10px] uppercase font-bold text-muted-foreground tracking-wider mb-1">
                                                Total Harga</p>
                                            <p class="font-bold text-primary">Rp {{ number_format($tiket->harga, 0, ',', '.') }}</p>
                                        </div>
                                    </div>

                                    <div class="mt-4 flex items-center gap-4 text-sm">
                                        <div class="flex items-center gap-1.5 text-muted-foreground">
                                            <i data-lucide="user" class="w-4 h-4"></i>
                                            <span class="font-medium text-foreground">{{ $tiket->nama_penumpang }}</span>
                                        </div>
                                        <div class="flex items-center gap-1.5 text-muted-foreground">
                                            <i data-lucide="bus" class="w-4 h-4"></i>
                                            <span>{{ $tiket->jadwalKelasBus->busKelasBus->bus->nama }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div
                                    class="bg-muted/30 md:w-48 p-6 flex flex-col justify-center border-t md:border-t-0 md:border-l border-border/50 gap-2">
                                    <a href="{{ route('tiket.show', $tiket) }}" class="block">
                                        <x-ui.button variant="outline" class="w-full gap-2">
                                            <i data-lucide="eye" class="w-4 h-4"></i>
                                            Detail
                                        </x-ui.button>
                                    </a>
                                    @if ($tiket->status === 'dipesan')
                                        <a href="{{ route('pemesanan.pembayaran', $tiket) }}" class="block">
                                            <x-ui.button class="w-full gap-2">
                                                <i data-lucide="credit-card" class="w-4 h-4"></i>
                                                Bayar
                                            </x-ui.button>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </x-ui.card.content>
                    </x-ui.card>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $tikets->links() }}
            </div>
        @endif
    </div>
@endsection