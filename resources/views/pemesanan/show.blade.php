@extends('layouts.admin')
@section('content')
    @push('header')
        <div class="flex items-center gap-2">
            <a href="{{ route('tiket.index') }}" class="text-muted-foreground hover:text-foreground">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Detail Tiket</h2>
        </div>
    @endpush

    <div class="p-6 space-y-6">
        <div class="max-w-4xl mx-auto">
            <x-ui.card>
                <x-ui.card.header class="flex flex-row items-center justify-between space-y-0 pb-6">
                    <div>
                        <x-ui.card.title class="text-2xl">Informasi Tiket</x-ui.card.title>
                        <x-ui.card.description>Detail lengkap pemesanan tiket Anda</x-ui.card.description>
                    </div>
                    <x-ui.badge
                        variant="{{ $tiket->status == 'terbayar' ? 'default' : ($tiket->status == 'dipesan' ? 'outline' : 'destructive') }}"
                        class="px-4 py-1 text-sm font-bold uppercase tracking-wider">
                        {{ $tiket->status }}
                    </x-ui.badge>
                </x-ui.card.header>
                <x-ui.card.content>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-6">
                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-1">
                                    <p class="text-xs font-bold text-muted-foreground uppercase tracking-widest">ID Tiket
                                    </p>
                                    <p class="font-mono font-bold text-lg">#{{ $tiket->id }}</p>
                                </div>
                                <div class="space-y-1">
                                    <p class="text-xs font-bold text-muted-foreground uppercase tracking-widest">Kode Tiket
                                    </p>
                                    <p class="font-mono font-bold text-lg text-primary">{{ $tiket->kode_tiket ?? '-' }}</p>
                                </div>
                            </div>

                            <div class="space-y-4 pt-4 border-t border-border/50">
                                <div class="flex items-start gap-3">
                                    <div class="p-2 bg-muted rounded-lg">
                                        <i data-lucide="user" class="w-4 h-4 text-muted-foreground"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold text-muted-foreground uppercase tracking-widest">
                                            Penumpang</p>
                                        <p class="font-bold text-foreground">{{ $tiket->nama_penumpang }}</p>
                                        <p class="text-xs text-muted-foreground">
                                            {{ $tiket->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }} •
                                            {{ $tiket->nomor_telepon }}</p>
                                    </div>
                                </div>

                                <div class="flex items-start gap-3">
                                    <div class="p-2 bg-muted rounded-lg">
                                        <i data-lucide="mail" class="w-4 h-4 text-muted-foreground"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold text-muted-foreground uppercase tracking-widest">Email
                                        </p>
                                        <p class="font-medium text-foreground">{{ $tiket->email }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-6">
                            @php
                                $jadwalObj = $tiket->jadwal ?? ($tiket->jadwalKelasBus->jadwal ?? null);
                            @endphp
                            <div class="space-y-4">
                                <div class="flex items-start gap-3">
                                    <div class="p-2 bg-primary/10 rounded-lg">
                                        <i data-lucide="bus" class="w-4 h-4 text-primary"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold text-muted-foreground uppercase tracking-widest">
                                            Perjalanan</p>
                                        <p class="font-bold text-foreground">
                                            {{ $jadwalObj?->bus?->nama_bus ?? $jadwalObj?->bus?->nama ?? '-' }}</p>
                                        <p class="text-sm font-medium text-primary">
                                            {{ $jadwalObj?->rute?->asalTerminal?->nama_terminal ?? '-' }} →
                                            {{ $jadwalObj?->rute?->tujuanTerminal?->nama_terminal ?? '-' }}</p>
                                    </div>
                                </div>

                                <div class="flex items-start gap-3">
                                    <div class="p-2 bg-muted rounded-lg">
                                        <i data-lucide="calendar" class="w-4 h-4 text-muted-foreground"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold text-muted-foreground uppercase tracking-widest">Waktu
                                            Keberangkatan</p>
                                        @if($jadwalObj?->tanggal_berangkat)
                                            <p class="font-bold text-foreground">
                                                {{ \Carbon\Carbon::parse($jadwalObj->tanggal_berangkat)->format('d F Y') }}</p>
                                            <p class="text-sm font-medium text-muted-foreground">
                                                {{ isset($jadwalObj->jam_berangkat) ? \Carbon\Carbon::parse($jadwalObj->jam_berangkat)->format('H:i') : '' }}
                                                WIB</p>
                                        @else
                                            <p class="font-bold text-foreground">-</p>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex items-start gap-3">
                                    <div class="p-2 bg-muted rounded-lg">
                                        <i data-lucide="armchair" class="w-4 h-4 text-muted-foreground"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold text-muted-foreground uppercase tracking-widest">Nomor
                                            Kursi</p>
                                        <x-ui.badge variant="outline"
                                            class="font-bold text-primary">{{ $tiket->kursi->nomor_kursi ?? $tiket->kursi ?? '-' }}</x-ui.badge>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div
                        class="mt-8 pt-6 border-t border-border flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div class="text-center sm:text-left">
                            <p class="text-xs text-muted-foreground">Dipesan pada:
                                {{ $tiket->created_at->format('d M Y, H:i') }}</p>
                        </div>
                        <div class="flex items-center gap-3 w-full sm:w-auto">
                            <x-ui.button variant="outline" class="flex-1 sm:flex-none" as-child>
                                <a href="{{ route('tiket.index') }}">Kembali</a>
                            </x-ui.button>
                            @if($tiket->status == 'dipesan')
                                <x-ui.button class="flex-1 sm:flex-none bg-green-600 hover:bg-green-700" as-child>
                                    <a href="{{ route('pemesanan.pembayaran', $tiket->id) }}">Bayar Sekarang</a>
                                </x-ui.button>
                            @endif
                        </div>
                    </div>
                </x-ui.card.content>
            </x-ui.card>
        </div>
    </div>
@endsection