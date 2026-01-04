@extends('layouts.admin')
@section('content')
    @push('header')
        <div class="flex items-center gap-2">
            <a href="{{ route('tiket.index') }}" class="text-muted-foreground hover:text-foreground">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Pembayaran Tiket</h2>
        </div>
    @endpush

    <div class="p-6 space-y-6">
        <div class="max-w-3xl mx-auto space-y-6">
            @if ($errors->any())
                <x-ui.alert variant="destructive">
                    <i data-lucide="alert-circle" class="h-4 w-4"></i>
                    <x-ui.alert.title>Terjadi Kesalahan</x-ui.alert.title>
                    <x-ui.alert.description>
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </x-ui.alert.description>
                </x-ui.alert>
            @endif

            <div class="grid gap-6 md:grid-cols-5">
                <div class="md:col-span-3 space-y-6">
                    <!-- Payment Method Selection -->
                    <x-ui.card>
                        <x-ui.card.header>
                            <x-ui.card.title class="flex items-center gap-2">
                                <i data-lucide="credit-card" class="w-5 h-5 text-primary"></i>
                                Pilih Metode Pembayaran
                            </x-ui.card.title>
                            <x-ui.card.description>Silakan pilih metode pembayaran yang Anda
                                inginkan</x-ui.card.description>
                        </x-ui.card.header>
                        <x-ui.card.content>
                            <form action="{{ route('pemesanan.pembayaran.store', $tiket->id) }}" method="POST"
                                id="payment-form">
                                @csrf
                                <div class="space-y-4">
                                    <!-- Xendit -->
                                    <label
                                        class="relative flex items-start p-4 border-2 rounded-xl cursor-pointer transition-all hover:border-primary/50 border-border bg-card group">
                                        <input type="radio" name="metode" value="xendit" class="sr-only peer" required>
                                        <div
                                            class="absolute top-4 right-4 h-5 w-5 rounded-full border-2 border-muted peer-checked:border-primary peer-checked:bg-primary flex items-center justify-center">
                                            <div class="h-2 w-2 rounded-full bg-white opacity-0 peer-checked:opacity-100">
                                            </div>
                                        </div>
                                        <div class="flex-1 pr-8">
                                            <p class="font-bold text-foreground group-hover:text-primary transition-colors">
                                                Pembayaran Instan (Xendit)</p>
                                            <p class="text-xs text-muted-foreground mt-1">E-Wallet (OVO, Dana, GoPay), QRIS,
                                                Virtual Account, atau Kartu Kredit.</p>
                                            <div
                                                class="flex gap-2 mt-3 opacity-70 grayscale group-hover:grayscale-0 transition-all">
                                                <img src="https://upload.wikimedia.org/wikipedia/commons/e/eb/Logo_ovo_purple.svg"
                                                    class="h-4" alt="OVO">
                                                <img src="https://upload.wikimedia.org/wikipedia/commons/7/72/Logo_dana_blue.svg"
                                                    class="h-4" alt="DANA">
                                                <img src="https://upload.wikimedia.org/wikipedia/commons/8/86/Gopay_logo.svg"
                                                    class="h-4" alt="GOPAY">
                                            </div>
                                        </div>
                                        <div
                                            class="absolute inset-0 border-2 border-primary rounded-xl opacity-0 peer-checked:opacity-100 pointer-events-none">
                                        </div>
                                    </label>

                                    <!-- Transfer Manual -->
                                    <label
                                        class="relative flex items-start p-4 border-2 rounded-xl cursor-pointer transition-all hover:border-primary/50 border-border bg-card group">
                                        <input type="radio" name="metode" value="transfer" class="sr-only peer" required>
                                        <div
                                            class="absolute top-4 right-4 h-5 w-5 rounded-full border-2 border-muted peer-checked:border-primary peer-checked:bg-primary flex items-center justify-center">
                                            <div class="h-2 w-2 rounded-full bg-white opacity-0 peer-checked:opacity-100">
                                            </div>
                                        </div>
                                        <div class="flex-1 pr-8">
                                            <p class="font-bold text-foreground group-hover:text-primary transition-colors">
                                                Transfer Bank Manual</p>
                                            <p class="text-xs text-muted-foreground mt-1">Transfer langsung ke rekening
                                                kami. Perlu verifikasi manual oleh admin.</p>
                                        </div>
                                        <div
                                            class="absolute inset-0 border-2 border-primary rounded-xl opacity-0 peer-checked:opacity-100 pointer-events-none">
                                        </div>
                                    </label>

                                    <!-- Tunai -->
                                    <label
                                        class="relative flex items-start p-4 border-2 rounded-xl cursor-pointer transition-all hover:border-primary/50 border-border bg-card group">
                                        <input type="radio" name="metode" value="tunai" class="sr-only peer" required>
                                        <div
                                            class="absolute top-4 right-4 h-5 w-5 rounded-full border-2 border-muted peer-checked:border-primary peer-checked:bg-primary flex items-center justify-center">
                                            <div class="h-2 w-2 rounded-full bg-white opacity-0 peer-checked:opacity-100">
                                            </div>
                                        </div>
                                        <div class="flex-1 pr-8">
                                            <p class="font-bold text-foreground group-hover:text-primary transition-colors">
                                                Bayar di Terminal (Tunai)</p>
                                            <p class="text-xs text-muted-foreground mt-1">Bayar langsung di loket terminal
                                                sebelum keberangkatan.</p>
                                        </div>
                                        <div
                                            class="absolute inset-0 border-2 border-primary rounded-xl opacity-0 peer-checked:opacity-100 pointer-events-none">
                                        </div>
                                    </label>
                                </div>
                            </form>
                        </x-ui.card.content>
                        <x-ui.card.footer>
                            <x-ui.button type="submit" form="payment-form" class="w-full gap-2">
                                <i data-lucide="shield-check" class="w-4 h-4"></i>
                                Konfirmasi Pembayaran
                            </x-ui.button>
                        </x-ui.card.footer>
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
                                <p class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider">Kode Tiket
                                </p>
                                <p class="font-mono font-bold text-lg">{{ $tiket->kode_tiket }}</p>
                            </div>

                            <div class="space-y-3 py-4 border-y border-border/50">
                                <div class="flex justify-between text-sm">
                                    <span class="text-muted-foreground">Penumpang</span>
                                    <span class="font-semibold">{{ $tiket->nama_penumpang }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-muted-foreground">Rute</span>
                                    <span
                                        class="font-semibold text-right">{{ $tiket->jadwalKelasBus->jadwal->rute->asalTerminal->nama_kota }}
                                        → {{ $tiket->jadwalKelasBus->jadwal->rute->tujuanTerminal->nama_kota }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-muted-foreground">Tanggal</span>
                                    <span
                                        class="font-semibold">{{ \Carbon\Carbon::parse($tiket->jadwalKelasBus->jadwal->tanggal)->format('d M Y') }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-muted-foreground">Kelas</span>
                                    <span class="font-semibold">{{ $tiket->jadwalKelasBus->kelasBus->nama_kelas }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-muted-foreground">Kursi</span>
                                    <x-ui.badge variant="outline"
                                        class="font-bold text-primary">{{ $tiket->kursi->nomor_kursi }}</x-ui.badge>
                                </div>
                            </div>

                            <div class="pt-2">
                                <p class="text-xs text-muted-foreground font-bold uppercase tracking-wider mb-1">Total Bayar
                                </p>
                                <p class="text-2xl font-bold text-primary">Rp
                                    {{ number_format($tiket->harga, 0, ',', '.') }}</p>
                            </div>
                        </x-ui.card.content>
                    </x-ui.card>

                    <x-ui.card class="bg-muted/30 border-dashed">
                        <x-ui.card.content class="p-4 flex gap-3">
                            <i data-lucide="info" class="w-5 h-5 text-primary shrink-0"></i>
                            <p class="text-[11px] text-muted-foreground">
                                Pastikan Anda melakukan pembayaran sebelum batas waktu yang ditentukan untuk menghindari
                                pembatalan otomatis.
                            </p>
                        </x-ui.card.content>
                    </x-ui.card>
                </div>
            </div>
        </div>
    </div>
@endsection
        Kursi akan dikunci selama 24 jam. Pastikan untuk menyelesaikan pembayaran sebelum waktu yang
        ditentukan.
    </p>
</div>

<!-- Buttons -->
<div class="flex gap-3 mt-6">
    <a href="{{ route('pemesanan.index') }}"
        class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 font-semibold hover:bg-gray-50 transition">
        Kembali
    </a>
    <button type="submit"
        class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition">
        Lanjut Pembayaran
    </button>
</div>
</form>
</x-ui.card.content>
</x-ui.card>

<!-- Info Timeout -->
<div class="mt-6 text-center text-sm text-gray-500">
    <p>Tiket akan dibatalkan otomatis jika pembayaran tidak selesai dalam 24 jam</p>
</div>
</div>
</div>

<style>
    input[type="radio"]:checked+div {
        color: #2563eb;
    }

    #metode-xendit-label:has(input[type="radio"]:checked),
    #metode-transfer-label:has(input[type="radio"]:checked),
    #metode-tunai-label:has(input[type="radio"]:checked) {
        @apply border-blue-500 bg-blue-50;
    }
</style>
@endsection