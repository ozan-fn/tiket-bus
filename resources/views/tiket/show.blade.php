@extends('layouts.app')
@section('content')

@section('title', 'Detail Tiket - ' . $tiket->kode_tiket)

        <div class="max-w-3xl mx-auto">
            <div class="mb-6 flex justify-between items-start">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Detail Tiket</h1>
                    <p class="text-gray-600 mt-1">{{ $tiket->kode_tiket }}</p>
                </div>
                @if ($tiket->status === 'dipesan')
                    <span class="px-4 py-2 bg-yellow-100 text-yellow-800 rounded-lg font-semibold text-sm">
                        Menunggu Pembayaran
                    </span>
                @elseif ($tiket->status === 'dibayar')
                    <span class="px-4 py-2 bg-green-100 text-green-800 rounded-lg font-semibold text-sm">
                        Terbayar
                    </span>
                @elseif ($tiket->status === 'selesai')
                    <span class="px-4 py-2 bg-blue-100 text-blue-800 rounded-lg font-semibold text-sm">
                        Selesai
                    </span>
                @elseif ($tiket->status === 'batal')
                    <span class="px-4 py-2 bg-red-100 text-red-800 rounded-lg font-semibold text-sm">
                        Dibatalkan
                    </span>
                @endif
            </div>

            <x-ui.card class="mb-6">
                <x-ui.card.header>
                    <h2 class="text-xl font-bold">Informasi Perjalanan</h2>
                </x-ui.card.header>
                <x-ui.card.content>
                    <div class="grid grid-cols-2 gap-6 mb-6">
                        <div>
                            <p class="text-gray-600 text-sm mb-1">Dari</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $tiket->jadwalKelasBus->jadwal->rute->asal }}
                            </p>
                            <p class="text-sm text-gray-600">
                                {{ $tiket->jadwalKelasBus->jadwal->rute->asalTerminal->nama_terminal ?? 'Terminal' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm mb-1">Tujuan</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $tiket->jadwalKelasBus->jadwal->rute->tujuan }}
                            </p>
                            <p class="text-sm text-gray-600">
                                {{ $tiket->jadwalKelasBus->jadwal->rute->tujuanTerminal->nama_terminal ?? 'Terminal' }}
                            </p>
                        </div>
                    </div>

                    <div class="border-t pt-6">
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div>
                                <p class="text-gray-600 text-xs font-semibold mb-1 uppercase">Tanggal</p>
                                <p class="font-semibold text-lg">
                                    {{ \Carbon\Carbon::parse($tiket->jadwalKelasBus->jadwal->tanggal)->format('d M Y') }}
                                </p>
                            </div>
                            <div>
                                <p class="text-gray-600 text-xs font-semibold mb-1 uppercase">Jam Berangkat</p>
                                <p class="font-semibold text-lg">
                                    @if (is_string($tiket->jadwalKelasBus->jadwal->jam_berangkat))
                                        {{ \Carbon\Carbon::createFromFormat('H:i:s', $tiket->jadwalKelasBus->jadwal->jam_berangkat)->format('H:i') }}
                                    @else
                                        {{ $tiket->jadwalKelasBus->jadwal->jam_berangkat->format('H:i') }}
                                    @endif
                                </p>
                            </div>
                            <div>
                                <p class="text-gray-600 text-xs font-semibold mb-1 uppercase">Bus</p>
                                <p class="font-semibold text-lg">{{ $tiket->jadwalKelasBus->jadwal->bus->nama }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600 text-xs font-semibold mb-1 uppercase">Sopir</p>
                                <p class="font-semibold text-lg">
                                    {{ $tiket->jadwalKelasBus->jadwal->sopir->user->name ?? '-' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </x-ui.card.content>
            </x-ui.card>

            <x-ui.card class="mb-6">
                <x-ui.card.header>
                    <h2 class="text-xl font-bold">Informasi Penumpang</h2>
                </x-ui.card.header>
                <x-ui.card.content>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-gray-600 text-sm mb-1">Nama Penumpang</p>
                            <p class="font-semibold text-lg">{{ $tiket->nama_penumpang }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm mb-1">NIK</p>
                            <p class="font-semibold text-lg">{{ $tiket->nik }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm mb-1">Jenis Kelamin</p>
                            <p class="font-semibold text-lg">
                                {{ $tiket->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm mb-1">Tanggal Lahir</p>
                            <p class="font-semibold text-lg">
                                {{ \Carbon\Carbon::parse($tiket->tanggal_lahir)->format('d M Y') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm mb-1">Nomor Telepon</p>
                            <p class="font-semibold text-lg">{{ $tiket->nomor_telepon }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm mb-1">Email</p>
                            <p class="font-semibold text-lg">{{ $tiket->email }}</p>
                        </div>
                    </div>
                </x-ui.card.content>
            </x-ui.card>

            <x-ui.card class="mb-6">
                <x-ui.card.header>
                    <h2 class="text-xl font-bold">Detail Tiket</h2>
                </x-ui.card.header>
                <x-ui.card.content>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-gray-600 text-sm mb-1">Kode Tiket</p>
                            <p class="font-semibold text-lg">{{ $tiket->kode_tiket }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm mb-1">Kelas</p>
                            <p class="font-semibold text-lg">{{ $tiket->jadwalKelasBus->kelasBus->nama_kelas }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm mb-1">Nomor Kursi</p>
                            <p class="font-semibold text-lg text-blue-600">{{ $tiket->kursi->nomor_kursi }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm mb-1">Harga</p>
                            <p class="font-semibold text-lg text-green-600">
                                Rp {{ number_format($tiket->harga, 0, ',', '.') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm mb-1">Waktu Pemesanan</p>
                            <p class="font-semibold text-lg">
                                {{ $tiket->waktu_pesan ? \Carbon\Carbon::parse($tiket->waktu_pesan)->format('d M Y H:i') : '-' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm mb-1">Status</p>
                            <p class="font-semibold text-lg">
                                @if ($tiket->status === 'dipesan')
                                    <span class="text-yellow-600">Menunggu Pembayaran</span>
                                @elseif ($tiket->status === 'dibayar')
                                    <span class="text-green-600">Terbayar</span>
                                @elseif ($tiket->status === 'selesai')
                                    <span class="text-blue-600">Selesai</span>
                                @else
                                    <span class="text-red-600">Dibatalkan</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </x-ui.card.content>
            </x-ui.card>

            @if ($tiket->status === 'dibayar')
                <x-ui.card class="mb-6 text-center">
                    <x-ui.card.header>
                        <h2 class="text-xl font-bold">QR Code Tiket</h2>
                    </x-ui.card.header>
                    <x-ui.card.content>
                        <div id="qrcode" class="flex justify-center py-6">
                            </div>
                        <p class="text-sm text-gray-600 mb-4">Tunjukkan QR Code ini saat naik di terminal</p>
                        <button type="button" onclick="printQRCode()"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition">
                            Cetak QR Code
                        </button>
                    </x-ui.card.content>
                </x-ui.card>
            @endif

            @if ($tiket->pembayaran)
                <x-ui.card class="mb-6">
                    <x-ui.card.header>
                        <h2 class="text-xl font-bold">Informasi Pembayaran</h2>
                    </x-ui.card.header>
                    <x-ui.card.content>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <p class="text-gray-600 text-sm mb-1">Kode Transaksi</p>
                                <p class="font-semibold text-lg">{{ $tiket->pembayaran->kode_transaksi }}</p>
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
