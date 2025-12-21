<x-admin-layout>
    <x-slot name="header">
        <x-ui.breadcrumb.breadcrumb>
            <x-ui.breadcrumb.list class="text-xs">
                <x-ui.breadcrumb.item>
                    <x-ui.breadcrumb.link href="{{ route('dashboard') }}">
                        Home
                    </x-ui.breadcrumb.link>
                </x-ui.breadcrumb.item>
                <x-ui.breadcrumb.separator>
                    <x-lucide-chevron-right class="w-3.5 h-3.5" />
                </x-ui.breadcrumb.separator>
                <x-ui.breadcrumb.item>
                    <x-ui.breadcrumb.link href="{{ route('admin/pemesanan.index') }}">
                        Pesan Tiket
                    </x-ui.breadcrumb.link>
                </x-ui.breadcrumb.item>
                <x-ui.breadcrumb.separator>
                    <x-lucide-chevron-right class="w-3.5 h-3.5" />
                </x-ui.breadcrumb.separator>
                <x-ui.breadcrumb.item>
                    <x-ui.breadcrumb.page>
                        Detail Tiket
                    </x-ui.breadcrumb.page>
                </x-ui.breadcrumb.item>
            </x-ui.breadcrumb.list>
        </x-ui.breadcrumb.breadcrumb>
    </x-slot>

    {{-- =====================================================================
         TAMPILAN LAYAR WEB (SCREEN ONLY)
         Kelas 'screen-area' akan disembunyikan saat print lewat CSS di bawah
         ===================================================================== --}}
    <div class="p-4 sm:p-6 screen-area">
        <div class="max-w-4xl mx-auto" x-data="{ }">
            <x-ui.card>
                <x-ui.card.header>
                    <div class="flex items-center justify-between">
                        <div>
                            <x-ui.card.title>Detail Tiket</x-ui.card.title>
                            <x-ui.card.description>Kode Tiket: {{ $tiket->kode_tiket }}</x-ui.card.description>
                        </div>
                        <div class="flex gap-2">
                            <x-ui.button @click="window.print()" size="sm">
                                <x-lucide-printer class="w-4 h-4 mr-2" />
                                Print Struk (58mm)
                            </x-ui.button>
                            <a href="{{ route('admin/pemesanan.index') }}">
                                <x-ui.button variant="outline" size="sm">
                                    <x-lucide-arrow-left class="w-4 h-4 mr-2" />
                                    Kembali
                                </x-ui.button>
                            </a>
                        </div>
                    </div>
                </x-ui.card.header>
                <x-ui.card.content>
                    <div class="space-y-6">
                        <div class="flex items-center gap-2">
                            <x-ui.badge :class="$tiket->status === 'dibayar' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : ($tiket->status === 'dipesan' ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400' : ($tiket->status === 'selesai' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400'))">
                                {{ ucfirst($tiket->status) }}
                            </x-ui.badge>
                            @if($tiket->status === 'dipesan')
                                <span class="text-sm text-muted-foreground">Tiket belum dibayar</span>
                            @elseif($tiket->status === 'dibayar')
                                <span class="text-sm text-green-600">Tiket sudah dibayar</span>
                            @elseif($tiket->status === 'selesai')
                                <span class="text-sm text-blue-600">Tiket sudah digunakan</span>
                            @else
                                <span class="text-sm text-red-600">Tiket dibatalkan</span>
                            @endif
                        </div>

                        <div class="border-2 border-primary/20 rounded-xl p-6 bg-gradient-to-br from-primary/5 to-background">
                            <div class="text-center mb-6">
                                <h2 class="text-2xl font-bold text-primary mb-2">TIKET BUS</h2>
                                <p class="text-sm text-muted-foreground">{{ config('app.name') }}</p>
                            </div>

                            <div class="text-center mb-6">
                                <div class="inline-block bg-primary text-primary-foreground px-4 py-2 rounded-lg">
                                    <p class="text-sm font-mono font-bold tracking-wider">{{ $tiket->kode_tiket }}</p>
                                </div>
                                <img src="{{ $qrCodeDataUri }}" alt="QR Code" class="mx-auto mt-4 w-32 h-32 border border-border rounded-lg">
                                <p class="text-center text-xs text-muted-foreground mt-2">Scan QR code ini di terminal untuk verifikasi tiket.</p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div class="space-y-3">
                                    <h3 class="font-semibold text-sm uppercase tracking-wider text-muted-foreground">Data Penumpang</h3>
                                    <div class="space-y-2">
                                        <div class="flex justify-between">
                                            <span class="text-sm text-muted-foreground">Nama:</span>
                                            <span class="font-semibold">{{ $tiket->nama_penumpang }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-sm text-muted-foreground">NIK:</span>
                                            <span class="font-semibold">{{ $tiket->nik }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-sm text-muted-foreground">Jenis Kelamin:</span>
                                            <span class="font-semibold">{{ $tiket->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-sm text-muted-foreground">Telepon:</span>
                                            <span class="font-semibold">{{ $tiket->nomor_telepon }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-sm text-muted-foreground">Email:</span>
                                            <span class="font-semibold">{{ $tiket->email }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="space-y-3">
                                    <h3 class="font-semibold text-sm uppercase tracking-wider text-muted-foreground">Detail Perjalanan</h3>
                                    <div class="space-y-2">
                                        <div class="flex justify-between">
                                            <span class="text-sm text-muted-foreground">Bus:</span>
                                            <span class="font-semibold">{{ $tiket->jadwalKelasBus->jadwal->bus->nama ?? 'N/A' }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-sm text-muted-foreground">Plat Nomor:</span>
                                            <span class="font-semibold">{{ $tiket->jadwalKelasBus->jadwal->bus->plat_nomor ?? 'N/A' }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-sm text-muted-foreground">Kelas:</span>
                                            <span class="font-semibold">{{ $tiket->jadwalKelasBus->kelasBus->nama_kelas ?? 'N/A' }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-sm text-muted-foreground">Kursi:</span>
                                            <span class="font-semibold text-primary">{{ $tiket->kursi->nomor_kursi ?? 'N/A' }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-sm text-muted-foreground">Harga:</span>
                                            <span class="font-semibold text-green-600">Rp {{ number_format($tiket->harga, 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-muted/50 rounded-lg p-4 mb-6">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-center">
                                    <div>
                                        <p class="text-xs text-muted-foreground uppercase">Asal</p>
                                        <p class="font-bold text-lg">{{ $tiket->jadwalKelasBus->jadwal->rute->asalTerminal->nama_terminal ?? 'N/A' }}</p>
                                        <p class="text-xs text-muted-foreground">{{ $tiket->jadwalKelasBus->jadwal->rute->asalTerminal->nama_kota ?? '' }}</p>
                                    </div>
                                    <div class="flex items-center justify-center">
                                        <x-lucide-arrow-right class="w-6 h-6 text-primary" />
                                    </div>
                                    <div>
                                        <p class="text-xs text-muted-foreground uppercase">Tujuan</p>
                                        <p class="font-bold text-lg">{{ $tiket->jadwalKelasBus->jadwal->rute->tujuanTerminal->nama_terminal ?? 'N/A' }}</p>
                                        <p class="text-xs text-muted-foreground">{{ $tiket->jadwalKelasBus->jadwal->rute->tujuanTerminal->nama_kota ?? '' }}</p>
                                    </div>
                                </div>
                                <div class="mt-4 pt-4 border-t border-border text-center">
                                    <p class="text-xs text-muted-foreground uppercase mb-1">Tanggal & Waktu Keberangkatan</p>
                                    <p class="font-bold text-lg">{{ $tiket->jadwalKelasBus->jadwal->tanggal_berangkat->format('d M Y') }}</p>
                                    <p class="text-sm text-muted-foreground">{{ $tiket->jadwalKelasBus->jadwal->jam_berangkat->format('H:i') }} WIB</p>
                                </div>
                            </div>

                            <div class="text-center text-xs text-muted-foreground">
                                <p>Dipesan pada: {{ $tiket->waktu_pesan->format('d M Y H:i') }}</p>
                                <p class="mt-1">Harap tiba di terminal 30 menit sebelum keberangkatan</p>
                            </div>
                        </div>

                        <div class="flex flex-col-reverse sm:flex-row items-center justify-end gap-3 pt-6 border-t border-border">
                            <a href="{{ route('admin/pemesanan.index') }}">
                                <x-ui.button variant="outline">
                                    <x-lucide-arrow-left class="w-4 h-4 mr-2" />
                                    Kembali ke Daftar
                                </x-ui.button>
                            </a>
                            <x-ui.button @click="window.print()">
                                <x-lucide-printer class="w-4 h-4 mr-2" />
                                Print Tiket
                            </x-ui.button>
                        </div>
                    </div>
                </x-ui.card.content>
            </x-ui.card>
        </div>
    </div>

    {{-- =====================================================================
         TAMPILAN STRUK 58mm (PRINT ONLY)
         Tampilan ini disembunyikan di layar biasa (display:none)
         Dan akan muncul saat @media print aktif
         ===================================================================== --}}
    <div id="ticket-print-area">
        <div class="receipt">
            <div class="header">
                <h2 class="title">TIKET BUS</h2>
            </div>

            <div class="dashed-line"></div>

            <div class="details">
                <div class="row">
                    <span class="label">Kode:</span>
                    <span class="value bold">{{ $tiket->kode_tiket }}</span>
                </div>
                <div class="row">
                    <span class="label">Nama:</span>
                    <span class="value">{{ substr($tiket->nama_penumpang, 0, 15) }}</span>
                </div>
                <div class="row">
                    <span class="label">Tgl:</span>
                    <span class="value">{{ $tiket->jadwalKelasBus->jadwal->tanggal_berangkat->format('d/m/y') }}</span>
                </div>
                <div class="row">
                    <span class="label">Jam:</span>
                    <span class="value">{{ $tiket->jadwalKelasBus->jadwal->jam_berangkat->format('H:i') }}</span>
                </div>
            </div>

            <div class="dashed-line"></div>

            <div class="route center">
                <p class="bold">{{ $tiket->jadwalKelasBus->jadwal->rute->asalTerminal->nama_kota }}</p>
                <p class="arrow">▼</p>
                <p class="bold">{{ $tiket->jadwalKelasBus->jadwal->rute->tujuanTerminal->nama_kota }}</p>
            </div>

            <div class="dashed-line"></div>

            <div class="details">
                <div class="row">
                    <span class="label">Bus:</span>
                    <span class="value">{{ $tiket->jadwalKelasBus->jadwal->bus->nama }}</span>
                </div>
                <div class="row">
                    <span class="label">Kelas:</span>
                    <span class="value">{{ $tiket->jadwalKelasBus->kelasBus->nama_kelas }}</span>
                </div>
                <div class="row highlight-row">
                    <span class="label">Kursi:</span>
                    <span class="value box">{{ $tiket->kursi->nomor_kursi }}</span>
                </div>
            </div>

            <div class="dashed-line"></div>

            <div class="total center">
                <p class="label">TOTAL BAYAR</p>
                <p class="amount">Rp {{ number_format($tiket->harga, 0, ',', '.') }}</p>
            </div>

            <div class="qr-area center">
                <img src="{{ $qrCodeDataUri }}" class="qr-image">
            </div>

            <div class="footer center">
                <p>Simpan struk ini.</p>
                <p>Terima Kasih.</p>
            </div>
        </div>
    </div>

    {{-- CSS UNTUK LOGIKA PRINT --}}
    <style>
        /* 1. SEMBUNYIKAN AREA PRINT DI LAYAR BIASA */
        #ticket-print-area {
            display: none;
        }

        /* 2. KONFIGURASI SAAT PRINT - UKURAN THERMAL 58MM */
        @media print {
            /* Biarkan ukuran halaman default (A4) untuk preview */
            @page {
                size: auto;
                margin: 5mm;
            }

            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            html, body {
                margin: 0 !important;
                padding: 0 !important;
                background: white !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            /* Sembunyikan semua elemen web biasa */
            .screen-area,
            nav,
            header,
            footer,
            .sidebar,
            x-admin-layout > *:not(#ticket-print-area) {
                display: none !important;
            }

            /* Tampilkan area print */
            #ticket-print-area {
                display: block !important;
                width: 58mm;
                background: white;
                visibility: visible !important;
            }

            #ticket-print-area * {
                visibility: visible !important;
            }

            /* STYLING STRUK 58mm */
            .receipt {
                width: 58mm;
                max-width: 58mm;
                margin: 0;
                padding: 3mm 2mm;
                font-family: 'Courier New', Courier, monospace;
                font-size: 9pt;
                line-height: 1.3;
                color: #000;
                background: white;
                page-break-inside: avoid;
            }

            .center {
                text-align: center;
            }

            .bold {
                font-weight: bold;
            }

            .header {
                margin-bottom: 3mm;
                text-align: center;
            }

            .title {
                font-size: 13pt;
                font-weight: bold;
                margin: 0 0 1mm 0;
                letter-spacing: 1px;
            }

            .company {
                font-size: 8pt;
                text-transform: uppercase;
                margin: 0;
            }

            .dashed-line {
                border-bottom: 1px dashed #000;
                margin: 2mm 0;
                width: 100%;
            }

            .details {
                margin: 2mm 0;
            }

            .row {
                display: flex;
                justify-content: space-between;
                margin-bottom: 1mm;
                line-height: 1.2;
            }

            .label {
                font-size: 8pt;
                flex-shrink: 0;
            }

            .value {
                text-align: right;
                max-width: 60%;
                word-wrap: break-word;
                font-size: 8pt;
            }

            .route {
                margin: 3mm 0;
            }

            .route p {
                margin: 1mm 0;
                font-size: 10pt;
            }

            .arrow {
                font-size: 10pt;
                margin: 0.5mm 0;
            }

            .highlight-row {
                margin-top: 2mm;
                align-items: center;
            }

            .box {
                border: 1.5px solid #000;
                padding: 1mm 3mm;
                font-weight: bold;
                font-size: 11pt;
                display: inline-block;
            }

            .total {
                margin: 3mm 0;
                text-align: center;
            }

            .total .label {
                font-size: 8pt;
                margin-bottom: 1mm;
                display: block;
            }

            .amount {
                font-size: 12pt;
                font-weight: bold;
                margin: 0;
            }

            .qr-area {
                margin: 3mm 0;
                text-align: center;
            }

            .qr-image {
                width: 30mm;
                height: 30mm;
                display: block;
                margin: 0 auto;
            }

            .footer {
                font-size: 7pt;
                margin-top: 3mm;
                padding-bottom: 3mm;
                text-align: center;
            }

            .footer p {
                margin: 0.5mm 0;
            }

            /* Hindari page break di tengah konten */
            .receipt, .header, .details, .route, .total, .qr-area, .footer {
                page-break-inside: avoid;
            }
        }
    </style>
</x-admin-layout>
