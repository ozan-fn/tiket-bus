@extends('layouts.admin')
@section('content')
    @push('header')
        <div class="flex items-center gap-2">
            <a href="{{ route('tiket.index') }}" class="text-muted-foreground hover:text-foreground">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Instruksi Transfer</h2>
        </div>
    @endpush

    <div class="p-6 space-y-6">
        <div class="max-w-3xl mx-auto space-y-6">
            <div class="text-center space-y-2">
                <h1 class="text-3xl font-bold text-foreground">Instruksi Transfer Bank</h1>
                <p class="text-muted-foreground">Silakan transfer sesuai data rekening di bawah untuk memverifikasi tiket Anda</p>
            </div>

            <div class="grid gap-6 md:grid-cols-5">
                <div class="md:col-span-3 space-y-6">
                    <!-- Bank Account Info -->
                    <x-ui.card>
                        <x-ui.card.header>
                            <x-ui.card.title class="flex items-center gap-2">
                                <i data-lucide="landmark" class="w-5 h-5 text-primary"></i>
                                Rekening Tujuan
                            </x-ui.card.title>
                            <x-ui.card.description>Pilih salah satu rekening di bawah ini</x-ui.card.description>
                        </x-ui.card.header>
                        <x-ui.card.content class="space-y-6">
                            <!-- BCA -->
                            <div class="group">
                                <div class="flex items-center gap-4 mb-4">
                                    <div class="w-14 h-14 bg-blue-50 dark:bg-blue-900/20 rounded-xl flex items-center justify-center border border-blue-100 dark:border-blue-800">
                                        <span class="font-black text-blue-600 dark:text-blue-400 text-lg">BCA</span>
                                    </div>
                                    <div>
                                        <p class="font-bold text-foreground">Bank Central Asia (BCA)</p>
                                        <p class="text-xs text-muted-foreground">Transfer instan / Mobile Banking</p>
                                    </div>
                                </div>
                                <div class="bg-muted/50 p-5 rounded-xl border border-border relative overflow-hidden">
                                    <div class="relative z-10 flex justify-between items-center">
                                        <div>
                                            <p class="text-[10px] font-bold text-muted-foreground uppercase tracking-widest mb-1">Nomor Rekening</p>
                                            <p class="font-mono text-xl font-bold text-foreground tracking-wider">{{ env('BANK_BCA_NUMBER', '1234567890') }}</p>
                                            <p class="text-sm font-medium text-muted-foreground mt-2">a.n {{ env('BANK_BCA_NAME', 'PT TIKET BUS') }}</p>
                                        </div>
                                        <button onclick="copyToClipboard('{{ env('BANK_BCA_NUMBER', '1234567890') }}')" class="p-2 hover:bg-background rounded-lg transition-colors text-muted-foreground hover:text-primary">
                                            <i data-lucide="copy" class="w-5 h-5"></i>
                                        </button>
                                    </div>
                                    <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-blue-500/5 rounded-full blur-2xl"></div>
                                </div>
                            </div>

                            <!-- Mandiri -->
                            <div class="group">
                                <div class="flex items-center gap-4 mb-4">
                                    <div class="w-14 h-14 bg-yellow-50 dark:bg-yellow-900/20 rounded-xl flex items-center justify-center border border-yellow-100 dark:border-yellow-800">
                                        <span class="font-black text-yellow-600 dark:text-yellow-400 text-lg">MDR</span>
                                    </div>
                                    <div>
                                        <p class="font-bold text-foreground">Bank Mandiri</p>
                                        <p class="text-xs text-muted-foreground">Transfer instan / Livin' by Mandiri</p>
                                    </div>
                                </div>
                                <div class="bg-muted/50 p-5 rounded-xl border border-border relative overflow-hidden">
                                    <div class="relative z-10 flex justify-between items-center">
                                        <div>
                                            <p class="text-[10px] font-bold text-muted-foreground uppercase tracking-widest mb-1">Nomor Rekening</p>
                                            <p class="font-mono text-xl font-bold text-foreground tracking-wider">{{ env('BANK_MANDIRI_NUMBER', '0987654321') }}</p>
                                            <p class="text-sm font-medium text-muted-foreground mt-2">a.n {{ env('BANK_MANDIRI_NAME', 'PT TIKET BUS') }}</p>
                                        </div>
                                        <button onclick="copyToClipboard('{{ env('BANK_MANDIRI_NUMBER', '0987654321') }}')" class="p-2 hover:bg-background rounded-lg transition-colors text-muted-foreground hover:text-primary">
                                            <i data-lucide="copy" class="w-5 h-5"></i>
                                        </button>
                                    </div>
                                    <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-yellow-500/5 rounded-full blur-2xl"></div>
                                </div>
                            </div>
                        </x-ui.card.content>
                    </x-ui.card>

                    <!-- Important Notes -->
                    <x-ui.card class="bg-amber-50 dark:bg-amber-950/20 border-amber-200 dark:border-amber-900">
                        <x-ui.card.content class="p-6">
                            <div class="flex gap-4">
                                <div class="p-2 bg-amber-100 dark:bg-amber-900/40 rounded-lg h-fit">
                                    <i data-lucide="alert-triangle" class="w-5 h-5 text-amber-600 dark:text-amber-400"></i>
                                </div>
                                <div class="space-y-3">
                                    <h4 class="font-bold text-amber-900 dark:text-amber-300">Penting untuk diperhatikan:</h4>
                                    <ul class="space-y-2 text-sm text-amber-800 dark:text-amber-400/80">
                                        <li class="flex items-start gap-2">
                                            <span class="mt-1.5 w-1.5 h-1.5 rounded-full bg-amber-500 shrink-0"></span>
                                            <span>Transfer tepat sampai 3 digit terakhir (jika ada) untuk mempercepat verifikasi.</span>
                                        </li>
                                        <li class="flex items-start gap-2">
                                            <span class="mt-1.5 w-1.5 h-1.5 rounded-full bg-amber-500 shrink-0"></span>
                                            <span>Simpan bukti transfer Anda untuk diunggah jika diperlukan.</span>
                                        </li>
                                        <li class="flex items-start gap-2">
                                            <span class="mt-1.5 w-1.5 h-1.5 rounded-full bg-amber-500 shrink-0"></span>
                                            <span>Verifikasi manual membutuhkan waktu 15-60 menit pada jam kerja.</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </x-ui.card.content>
                    </x-ui.card>
                </div>

                <div class="md:col-span-2 space-y-6">
                    <!-- Tiket Info -->
                    <x-ui.card>
                        <x-ui.card.header>
                            <x-ui.card.title>Ringkasan Pembayaran</x-ui.card.title>
                        </x-ui.card.header>
                        <x-ui.card.content class="space-y-4">
                            <div class="space-y-1">
                                <p class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider">Kode Transaksi</p>
                                <p class="font-mono font-bold text-lg">{{ $pembayaran->kode_transaksi }}</p>
                            </div>
                            
                            <div class="space-y-3 py-4 border-y border-border/50">
                                <div class="flex justify-between text-sm">
                                    <span class="text-muted-foreground">Kode Tiket</span>
                                    <span class="font-semibold">{{ $pembayaran->tiket->kode_tiket }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-muted-foreground">Rute</span>
                                    <span class="font-semibold text-right">{{ $pembayaran->tiket->jadwalKelasBus->jadwal->rute->asalTerminal->nama_kota }} → {{ $pembayaran->tiket->jadwalKelasBus->jadwal->rute->tujuanTerminal->nama_kota }}</span>
                                </div>
                            </div>

                            <div class="pt-2">
                                <p class="text-xs text-muted-foreground font-bold uppercase tracking-wider mb-1">Total Transfer</p>
                                <p class="text-3xl font-bold text-primary">Rp {{ number_format($pembayaran->nominal, 0, ',', '.') }}</p>
                            </div>
                        </x-ui.card.content>
                        <x-ui.card.footer>
                            <x-ui.button variant="outline" class="w-full gap-2" onclick="window.print()">
                                <i data-lucide="printer" class="w-4 h-4"></i>
                                Cetak Instruksi
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

    @push('scripts')
    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                alert('Nomor rekening berhasil disalin!');
            });
        }
    </script>
    @endpush
@endsection
                        <div class="text-amber-600 text-2xl">⚠️</div>
                        <div>
                            <p class="font-semibold text-amber-900 mb-2">Penting:</p>
                            <ul class="text-sm text-amber-800 space-y-1">
                                <li>• Transfer ke salah satu rekening di atas</li>
                                <li>• Gunakan kode referensi <strong>{{ $pembayaran->kode_transaksi }}</strong> sebagai
                                    berita/deskripsi transfer</li>
                                <li>• Pembayaran akan dikonfirmasi secara otomatis dalam waktu 1x24 jam</li>
                                <li>• Jika belum dikonfirmasi, hubungi customer service kami</li>
                                <li>• Tiket akan dibatalkan otomatis jika tidak dibayar dalam 24 jam</li>
                            </ul>
                        </div>
                    </div>
                </x-ui.card.content>
            </x-ui.card>

            <!-- Instructions Steps -->
            <x-ui.card class="mb-6">
                <x-ui.card.header>
                    <h2 class="text-xl font-bold">Langkah-Langkah Transfer</h2>
                </x-ui.card.header>
                <x-ui.card.content>
                    <div class="space-y-4">
                        <div class="flex gap-4">
                            <div
                                class="flex-shrink-0 w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold">
                                1</div>
                            <div>
                                <p class="font-semibold text-gray-900">Buka aplikasi atau website bank Anda</p>
                                <p class="text-sm text-gray-600 mt-1">Pilih menu transfer antar bank atau ke bank yang sama
                                </p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div
                                class="flex-shrink-0 w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold">
                                2</div>
                            <div>
                                <p class="font-semibold text-gray-900">Masukkan data rekening tujuan</p>
                                <p class="text-sm text-gray-600 mt-1">Nomor rekening yang tertera di atas beserta nama
                                    pemilik</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div
                                class="flex-shrink-0 w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold">
                                3</div>
                            <div>
                                <p class="font-semibold text-gray-900">Masukkan jumlah transfer</p>
                                <p class="text-sm text-gray-600 mt-1">Rp
                                    {{ number_format($pembayaran->nominal, 0, ',', '.') }} (sesuai nominal tiket)</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div
                                class="flex-shrink-0 w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold">
                                4</div>
                            <div>
                                <p class="font-semibold text-gray-900">Masukkan kode referensi di berita transfer</p>
                                <p class="text-sm text-gray-600 mt-1 font-mono bg-gray-100 p-2 rounded">
                                    {{ $pembayaran->kode_transaksi }}</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div
                                class="flex-shrink-0 w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold">
                                5</div>
                            <div>
                                <p class="font-semibold text-gray-900">Konfirmasi dan selesaikan transfer</p>
                                <p class="text-sm text-gray-600 mt-1">Pembayaran akan diverifikasi secara otomatis</p>
                            </div>
                        </div>
                    </div>
                </x-ui.card.content>
            </x-ui.card>

            <!-- Action Buttons -->
            <div class="flex gap-3 justify-center">
                <a href="{{ route('tiket.index') }}"
                    class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg font-semibold hover:bg-gray-50 transition">
                    Lihat Tiket Saya
                </a>
                <a href="{{ route('dashboard') }}"
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition">
                    Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>
@endsection
