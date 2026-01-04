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
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-sm text-muted-foreground mb-1">
                    <span class="font-mono uppercase tracking-wider">{{ $tiket->kode_tiket }}</span>
                    <span>•</span>
                    <span>Dipesan pada {{ $tiket->waktu_pesan ? \Carbon\Carbon::parse($tiket->waktu_pesan)->format('d M Y H:i') : '-' }}</span>
                </div>
                <h1 class="text-2xl font-bold tracking-tight">Informasi Tiket Perjalanan</h1>
            </div>
            <div>
                @if ($tiket->status === 'dipesan')
                    <x-ui.badge variant="outline" class="bg-yellow-500/10 text-yellow-600 border-yellow-500/20 px-3 py-1 text-sm">Menunggu Pembayaran</x-ui.badge>
                @elseif ($tiket->status === 'dibayar')
                    <x-ui.badge variant="outline" class="bg-green-500/10 text-green-600 border-green-500/20 px-3 py-1 text-sm">Terbayar</x-ui.badge>
                @elseif ($tiket->status === 'selesai')
                    <x-ui.badge variant="outline" class="bg-blue-500/10 text-blue-600 border-blue-500/20 px-3 py-1 text-sm">Selesai</x-ui.badge>
                @elseif ($tiket->status === 'batal')
                    <x-ui.badge variant="outline" class="bg-destructive/10 text-destructive border-destructive/20 px-3 py-1 text-sm">Dibatalkan</x-ui.badge>
                @endif
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            <div class="lg:col-span-2 space-y-6">
                <!-- Perjalanan -->
                <x-ui.card>
                    <x-ui.card.header>
                        <x-ui.card.title class="flex items-center gap-2">
                            <i data-lucide="map-pin" class="w-5 h-5 text-primary"></i>
                            Rute Perjalanan
                        </x-ui.card.title>
                    </x-ui.card.header>
                    <x-ui.card.content class="space-y-6">
                        <div class="flex items-center justify-between relative">
                            <div class="flex-1">
                                <p class="text-sm text-muted-foreground mb-1">Asal</p>
                                <p class="text-xl font-bold">{{ $tiket->jadwalKelasBus->jadwal->rute->asalTerminal->nama_kota }}</p>
                                <p class="text-sm text-muted-foreground">{{ $tiket->jadwalKelasBus->jadwal->rute->asalTerminal->nama_terminal }}</p>
                            </div>
                            <div class="flex flex-col items-center px-4">
                                <i data-lucide="move-right" class="w-6 h-6 text-muted-foreground"></i>
                            </div>
                            <div class="flex-1 text-right">
                                <p class="text-sm text-muted-foreground mb-1">Tujuan</p>
                                <p class="text-xl font-bold">{{ $tiket->jadwalKelasBus->jadwal->rute->tujuanTerminal->nama_kota }}</p>
                                <p class="text-sm text-muted-foreground">{{ $tiket->jadwalKelasBus->jadwal->rute->tujuanTerminal->nama_terminal }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 pt-6 border-t border-border/50">
                            <div>
                                <p class="text-[10px] uppercase font-bold text-muted-foreground tracking-wider mb-1">Tanggal</p>
                                <p class="font-semibold">{{ \Carbon\Carbon::parse($tiket->jadwalKelasBus->jadwal->tanggal)->format('d M Y') }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] uppercase font-bold text-muted-foreground tracking-wider mb-1">Waktu</p>
                                <p class="font-semibold">{{ \Carbon\Carbon::parse($tiket->jadwalKelasBus->jadwal->jam_berangkat)->format('H:i') }} WIB</p>
                            </div>
                            <div>
                                <p class="text-[10px] uppercase font-bold text-muted-foreground tracking-wider mb-1">Bus</p>
                                <p class="font-semibold">{{ $tiket->jadwalKelasBus->busKelasBus->bus->nama }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] uppercase font-bold text-muted-foreground tracking-wider mb-1">Sopir</p>
                                <p class="font-semibold">{{ $tiket->jadwalKelasBus->jadwal->sopir->user->name ?? '-' }}</p>
                            </div>
                        </div>
                    </x-ui.card.content>
                </x-ui.card>

                <!-- Penumpang -->
                <x-ui.card>
                    <x-ui.card.header>
                        <x-ui.card.title class="flex items-center gap-2">
                            <i data-lucide="users" class="w-5 h-5 text-primary"></i>
                            Detail Penumpang
                        </x-ui.card.title>
                    </x-ui.card.header>
                    <x-ui.card.content>
                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div>
                                    <p class="text-sm text-muted-foreground mb-1">Nama Lengkap</p>
                                    <p class="font-semibold">{{ $tiket->nama_penumpang }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-muted-foreground mb-1">NIK</p>
                                    <p class="font-semibold">{{ $tiket->nik }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-muted-foreground mb-1">Jenis Kelamin</p>
                                    <p class="font-semibold">{{ $tiket->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <p class="text-sm text-muted-foreground mb-1">Nomor Telepon</p>
                                    <p class="font-semibold">{{ $tiket->nomor_telepon }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-muted-foreground mb-1">Email</p>
                                    <p class="font-semibold">{{ $tiket->email }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-muted-foreground mb-1">Tanggal Lahir</p>
                                    <p class="font-semibold">{{ \Carbon\Carbon::parse($tiket->tanggal_lahir)->format('d M Y') }}</p>
                                </div>
                            </div>
                        </div>
                    </x-ui.card.content>
                </x-ui.card>
            </div>

            <div class="space-y-6">
                <!-- Detail Tiket -->
                <x-ui.card>
                    <x-ui.card.header>
                        <x-ui.card.title>Detail Tiket</x-ui.card.title>
                    </x-ui.card.header>
                    <x-ui.card.content class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-muted-foreground">Kelas</span>
                            <span class="font-semibold">{{ $tiket->jadwalKelasBus->kelasBus->nama_kelas }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-muted-foreground">Nomor Kursi</span>
                            <x-ui.badge variant="outline" class="font-bold text-primary border-primary/20">{{ $tiket->kursi->nomor_kursi }}</x-ui.badge>
                        </div>
                        <div class="flex justify-between items-center pt-4 border-t border-border/50">
                            <span class="text-muted-foreground">Harga Tiket</span>
                            <span class="text-lg font-bold text-primary">Rp {{ number_format($tiket->harga, 0, ',', '.') }}</span>
                        </div>
                    </x-ui.card.content>
                    @if ($tiket->status === 'dipesan')
                        <x-ui.card.footer>
                            <a href="{{ route('pemesanan.pembayaran', $tiket) }}" class="w-full">
                                <x-ui.button class="w-full gap-2">
                                    <i data-lucide="credit-card" class="w-4 h-4"></i>
                                    Bayar Sekarang
                                </x-ui.button>
                            </a>
                        </x-ui.card.footer>
                    @endif
                </x-ui.card>

                @if ($tiket->status === 'dibayar')
                    <x-ui.card class="text-center">
                        <x-ui.card.header>
                            <x-ui.card.title>E-Tiket QR Code</x-ui.card.title>
                        </x-ui.card.header>
                        <x-ui.card.content class="flex flex-col items-center gap-4">
                            <div id="qrcode" class="p-4 bg-white rounded-xl border border-border/50">
                                <!-- QR Code will be rendered here -->
                            </div>
                            <p class="text-xs text-muted-foreground">Tunjukkan QR Code ini kepada petugas saat keberangkatan</p>
                        </x-ui.card.content>
                        <x-ui.card.footer>
                            <x-ui.button variant="outline" class="w-full gap-2" onclick="printQRCode()">
                                <i data-lucide="printer" class="w-4 h-4"></i>
                                Cetak Tiket
                            </x-ui.button>
                        </x-ui.card.footer>
                    </x-ui.card>
                @endif

                @if ($tiket->pembayaran)
                    <x-ui.card>
                        <x-ui.card.header>
                            <x-ui.card.title>Informasi Pembayaran</x-ui.card.title>
                        </x-ui.card.header>
                        <x-ui.card.content class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-muted-foreground">Metode</span>
                                <span class="font-medium uppercase">{{ $tiket->pembayaran->metode_pembayaran }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-muted-foreground">Waktu</span>
                                <span class="font-medium">{{ \Carbon\Carbon::parse($tiket->pembayaran->waktu_pembayaran)->format('d M Y H:i') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-muted-foreground">Status</span>
                                <span class="text-green-600 font-bold">SUKSES</span>
                            </div>
                        </x-ui.card.content>
                    </x-ui.card>
                @endif
            </div>
        </div>
    </div>

    @if ($tiket->status === 'dibayar')
        @push('scripts')
            <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
            <script>
                new QRCode(document.getElementById("qrcode"), {
                    text: "{{ $tiket->kode_tiket }}",
                    width: 180,
                    height: 180,
                    colorDark: "#000000",
                    colorLight: "#ffffff",
                    correctLevel: QRCode.CorrectLevel.H
                });

                function printQRCode() {
                    const qrContent = document.getElementById('qrcode').innerHTML;
                    const printWindow = window.open('', '', 'height=600,width=800');
                    printWindow.document.write('<html><head><title>Cetak Tiket - {{ $tiket->kode_tiket }}</title>');
                    printWindow.document.write('<style>body{font-family:sans-serif;display:flex;flex-direction:column;align-items:center;justify-content:center;height:100vh;margin:0} .ticket{border:2px solid #000;padding:40px;text-align:center;border-radius:20px} h1{margin:0 0 10px 0} p{margin:5px 0}</style>');
                    printWindow.document.write('</head><body>');
                    printWindow.document.write('<div class="ticket">');
                    printWindow.document.write('<h1>TIKET BUS</h1>');
                    printWindow.document.write('<p><strong>{{ $tiket->kode_tiket }}</strong></p>');
                    printWindow.document.write('<div style="margin:20px 0">' + qrContent + '</div>');
                    printWindow.document.write('<p>{{ $tiket->nama_penumpang }}</p>');
                    printWindow.document.write('<p>{{ $tiket->jadwalKelasBus->jadwal->rute->asal }} → {{ $tiket->jadwalKelasBus->jadwal->rute->tujuan }}</p>');
                    printWindow.document.write('<p>{{ \Carbon\Carbon::parse($tiket->jadwalKelasBus->jadwal->tanggal)->format('d M Y') }} | {{ \Carbon\Carbon::parse($tiket->jadwalKelasBus->jadwal->jam_berangkat)->format('H:i') }}</p>');
                    printWindow.document.write('<p>Kursi: {{ $tiket->kursi->nomor_kursi }}</p>');
                    printWindow.document.write('</div>');
                    printWindow.document.write('</body></html>');
                    printWindow.document.close();
                    printWindow.focus();
                    setTimeout(() => {
                        printWindow.print();
                        printWindow.close();
                    }, 250);
                }
            </script>
        @endpush
    @endif
@endsection
                            </div>
                            <div>
                                <p class="text-gray-600 text-sm mb-1">Metode Pembayaran</p>
                                <p class="font-semibold text-lg capitalize">{{ $tiket->pembayaran->metode }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600 text-sm mb-1">Nominal</p>
                                <p class="font-semibold text-lg">
                                    Rp {{ number_format($tiket->pembayaran->nominal, 0, ',', '.') }}
                                </p>
                            </div>
                            <div>
                                <p class="text-gray-600 text-sm mb-1">Status Pembayaran</p>
                                <p class="font-semibold text-lg">
                                    @if ($tiket->pembayaran->status === 'dibayar')
                                        <span class="text-green-600">✓ Dibayar</span>
                                    @elseif ($tiket->pembayaran->status === 'dipesan')
                                        <span class="text-yellow-600">⏳ Menunggu Konfirmasi</span>
                                    @elseif ($tiket->pembayaran->status === 'gagal')
                                        <span class="text-red-600">✗ Gagal</span>
                                    @else
                                        <span class="text-gray-600">{{ ucfirst($tiket->pembayaran->status) }}</span>
                                    @endif
                                </p>
                            </div>
                            @if ($tiket->pembayaran->waktu_bayar)
                                <div class="col-span-1 md:col-span-2">
                                    <p class="text-gray-600 text-sm mb-1">Waktu Pembayaran</p>
                                    <p class="font-semibold text-lg">
                                        {{ \Carbon\Carbon::parse($tiket->pembayaran->waktu_bayar)->format('d M Y H:i') }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    </x-ui.card.content>
                </x-ui.card>
            @endif
        </div>
    </div>

    @if ($tiket->status === 'dibayar')
        {{-- Pastikan library QRCode diload. Contoh menggunakan CDN --}}
        <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.1/build/qrcode.min.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var container = document.getElementById('qrcode');
                
                // Pastikan element container ada sebelum render
                if (container) {
                    QRCode.toCanvas(document.createElement('canvas'), '{{ $tiket->kode_tiket }}', {
                        width: 200,
                        margin: 2,
                        color: {
                            dark: '#000000',
                            light: '#FFFFFF'
                        }
                    }, function(error, canvas) {
                        if (error) console.error(error);
                        container.appendChild(canvas);
                    });
                }
            });

            function printQRCode() {
                // Ambil canvas yang sudah dirender
                const qrCanvas = document.querySelector('#qrcode canvas');
                
                if (!qrCanvas) {
                    alert('QR Code belum dimuat.');
                    return;
                }

                const dataUrl = qrCanvas.toDataURL('image/png');
                const printWindow = window.open('', '', 'width=600,height=700');

                printWindow.document.write(`
                    <html>
                        <head>
                            <title>QR Code - {{ $tiket->kode_tiket }}</title>
                            <style>
                                body { text-align: center; font-family: Arial, sans-serif; padding: 40px; }
                                .ticket-info { border: 1px solid #ccc; padding: 20px; border-radius: 10px; display: inline-block; }
                                img { max-width: 250px; margin: 20px 0; }
                                h2 { margin: 0 0 10px 0; color: #333; }
                                p { margin: 5px 0; color: #555; font-size: 14px; }
                                .code { font-weight: bold; font-size: 18px; margin-top: 10px; color: #000; }
                            </style>
                        </head>
                        <body>
                            <div class="ticket-info">
                                <h2>{{ $tiket->nama_penumpang }}</h2>
                                <p>{{ $tiket->jadwalKelasBus->jadwal->rute->asal }} &rarr; {{ $tiket->jadwalKelasBus->jadwal->rute->tujuan }}</p>
                                <p>{{ \Carbon\Carbon::parse($tiket->jadwalKelasBus->jadwal->tanggal)->format('d M Y') }} - 
                                   {{ is_string($tiket->jadwalKelasBus->jadwal->jam_berangkat) ? \Carbon\Carbon::createFromFormat('H:i:s', $tiket->jadwalKelasBus->jadwal->jam_berangkat)->format('H:i') : $tiket->jadwalKelasBus->jadwal->jam_berangkat->format('H:i') }}
                                </p>
                                <p>Bus: {{ $tiket->jadwalKelasBus->jadwal->bus->nama }} | Kursi: {{ $tiket->kursi->nomor_kursi }}</p>
                                
                                <img src="${dataUrl}" alt="QR Code">
                                
                                <p>Kode Tiket:</p>
                                <div class="code">{{ $tiket->kode_tiket }}</div>
                            </div>
                            <script>
                                window.onload = function() { window.print(); window.close(); }
                            <\/script>
                        </body>
                    </html>
                `);
                printWindow.document.close();
            }
        </script>
    @endif
@endsection
