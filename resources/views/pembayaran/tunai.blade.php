@extends('layouts.admin')
@section('content')
    @push('header')
        <div class="flex items-center gap-2">
            <a href="{{ route('tiket.index') }}" class="text-muted-foreground hover:text-foreground">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Pembayaran Tunai</h2>
        </div>
    @endpush

    <div class="p-6 space-y-6">
        <div class="max-w-3xl mx-auto space-y-6">
            <div class="text-center space-y-2">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 mb-2">
                    <i data-lucide="check-circle-2" class="w-10 h-10"></i>
                </div>
                <h1 class="text-3xl font-bold text-foreground">Pemesanan Berhasil!</h1>
                <p class="text-muted-foreground">Tiket Anda telah dipesan. Silakan lakukan pembayaran tunai di terminal.</p>
            </div>

            <div class="grid gap-6 md:grid-cols-5">
                <div class="md:col-span-3 space-y-6">
                    <!-- Terminal Info -->
                    <x-ui.card>
                        <x-ui.card.header>
                            <x-ui.card.title class="flex items-center gap-2">
                                <i data-lucide="map-pin" class="w-5 h-5 text-primary"></i>
                                Lokasi Pembayaran
                            </x-ui.card.title>
                            <x-ui.card.description>Silakan datang ke terminal keberangkatan</x-ui.card.description>
                        </x-ui.card.header>
                        <x-ui.card.content class="space-y-6">
                            <div class="p-4 bg-muted/50 rounded-xl border border-border">
                                <p class="text-[10px] font-bold text-muted-foreground uppercase tracking-widest mb-1">Terminal Keberangkatan</p>
                                <p class="text-xl font-bold text-foreground">{{ $pembayaran->tiket->jadwalKelasBus->jadwal->rute->asalTerminal->nama_terminal ?? 'Terminal' }}</p>
                                <p class="text-sm text-muted-foreground mt-1">{{ $pembayaran->tiket->jadwalKelasBus->jadwal->rute->asalTerminal->alamat ?? '-' }}</p>
                            </div>

                            <div class="space-y-4">
                                <p class="font-bold text-sm">Langkah Pembayaran:</p>
                                <div class="space-y-3">
                                    <div class="flex gap-3">
                                        <div class="flex-none w-6 h-6 rounded-full bg-primary/10 text-primary flex items-center justify-center text-xs font-bold">1</div>
                                        <p class="text-sm text-muted-foreground">Datang ke terminal keberangkatan minimal 30 menit sebelum jadwal.</p>
                                    </div>
                                    <div class="flex gap-3">
                                        <div class="flex-none w-6 h-6 rounded-full bg-primary/10 text-primary flex items-center justify-center text-xs font-bold">2</div>
                                        <p class="text-sm text-muted-foreground">Tunjukkan kode tiket <span class="font-mono font-bold text-foreground">{{ $pembayaran->tiket->kode_tiket }}</span> kepada petugas loket.</p>
                                    </div>
                                    <div class="flex gap-3">
                                        <div class="flex-none w-6 h-6 rounded-full bg-primary/10 text-primary flex items-center justify-center text-xs font-bold">3</div>
                                        <p class="text-sm text-muted-foreground">Lakukan pembayaran tunai sebesar <span class="font-bold text-foreground">Rp {{ number_format($pembayaran->nominal, 0, ',', '.') }}</span>.</p>
                                    </div>
                                    <div class="flex gap-3">
                                        <div class="flex-none w-6 h-6 rounded-full bg-primary/10 text-primary flex items-center justify-center text-xs font-bold">4</div>
                                        <p class="text-sm text-muted-foreground">Petugas akan memberikan tiket fisik atau memvalidasi tiket digital Anda.</p>
                                    </div>
                                </div>
                            </div>
                        </x-ui.card.content>
                    </x-ui.card>

                    <x-ui.card class="bg-blue-50 dark:bg-blue-950/20 border-blue-200 dark:border-blue-900">
                        <x-ui.card.content class="p-6">
                            <div class="flex gap-4">
                                <div class="p-2 bg-blue-100 dark:bg-blue-900/40 rounded-lg h-fit">
                                    <i data-lucide="info" class="w-5 h-5 text-blue-600 dark:text-blue-400"></i>
                                </div>
                                <div class="space-y-1">
                                    <h4 class="font-bold text-blue-900 dark:text-blue-300 text-sm">Informasi Tambahan</h4>
                                    <p class="text-xs text-blue-800 dark:text-blue-400/80 leading-relaxed">
                                        Pemesanan ini akan dibatalkan otomatis jika Anda tidak melakukan pembayaran di terminal sebelum bus berangkat.
                                    </p>
                                </div>
                            </div>
                        </x-ui.card.content>
                    </x-ui.card>
                </div>

                <div class="md:col-span-2 space-y-6">
                    <!-- Tiket Info -->
                    <x-ui.card>
                        <x-ui.card.header>
                            <x-ui.card.title>Ringkasan Tiket</x-ui.card.title>
                        </x-ui.card.header>
                        <x-ui.card.content class="space-y-4">
                            <div class="space-y-1">
                                <p class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider">Kode Tiket</p>
                                <p class="font-mono font-bold text-lg">{{ $pembayaran->tiket->kode_tiket }}</p>
                            </div>
                            
                            <div class="space-y-3 py-4 border-y border-border/50">
                                <div class="flex justify-between text-sm">
                                    <span class="text-muted-foreground">Penumpang</span>
                                    <span class="font-semibold">{{ $pembayaran->tiket->nama_penumpang }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-muted-foreground">Rute</span>
                                    <span class="font-semibold text-right">{{ $pembayaran->tiket->jadwalKelasBus->jadwal->rute->asalTerminal->nama_kota }} → {{ $pembayaran->tiket->jadwalKelasBus->jadwal->rute->tujuanTerminal->nama_kota }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-muted-foreground">Jadwal</span>
                                    <span class="font-semibold">{{ \Carbon\Carbon::parse($pembayaran->tiket->jadwalKelasBus->jadwal->tanggal)->format('d M Y') }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-muted-foreground">Jam</span>
                                    <span class="font-semibold">{{ \Carbon\Carbon::parse($pembayaran->tiket->jadwalKelasBus->jadwal->jam_berangkat)->format('H:i') }} WIB</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-muted-foreground">Kursi</span>
                                    <x-ui.badge variant="outline" class="font-bold text-primary">{{ $pembayaran->tiket->kursi->nomor_kursi }}</x-ui.badge>
                                </div>
                            </div>

                            <div class="pt-2">
                                <p class="text-xs text-muted-foreground font-bold uppercase tracking-wider mb-1">Total Bayar</p>
                                <p class="text-3xl font-bold text-primary">Rp {{ number_format($pembayaran->nominal, 0, ',', '.') }}</p>
                            </div>
                        </x-ui.card.content>
                        <x-ui.card.footer>
                            <x-ui.button variant="outline" class="w-full gap-2" onclick="window.print()">
                                <i data-lucide="printer" class="w-4 h-4"></i>
                                Cetak Bukti Pesan
                            </x-ui.button>
                        </x-ui.card.footer>
                    </x-ui.card>

                    <x-ui.button class="w-full gap-2" as-child>
                        <a href="{{ route('tiket.index') }}">
                            <i data-lucide="ticket" class="w-4 h-4"></i>
                            Lihat Tiket Saya
                        </a>
                    </x-ui.button>
                </div>
            </div>
        </div>
    </div>
@endsection
