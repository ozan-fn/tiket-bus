@extends('layouts.app')

@section('title', 'Pembayaran Tunai')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Pembayaran Tunai di Terminal</h1>
                <p class="text-gray-600 mt-2">Tiket Anda siap untuk dibayar saat naik bus</p>
            </div>

            <!-- Success Alert -->
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg flex gap-3">
                <div class="text-green-600 text-2xl">✓</div>
                <div>
                    <p class="font-semibold text-green-900">Tiket Anda berhasil dipesan!</p>
                    <p class="text-sm text-green-800 mt-1">Silakan bayar tunai saat naik di terminal keberangkatan</p>
                </div>
            </div>

            <!-- Tiket Info Card -->
            <x-ui.card class="mb-6">
                <x-ui.card.header>
                    <h2 class="text-xl font-bold">Informasi Tiket</h2>
                </x-ui.card.header>
                <x-ui.card.content>
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <p class="text-gray-600 text-sm">Kode Tiket</p>
                            <p class="font-semibold text-lg">{{ $pembayaran->tiket->kode_tiket }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm">Status Pembayaran</p>
                            <span
                                class="inline-block px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm font-semibold">Menunggu
                                Pembayaran Tunai</span>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm">Penumpang</p>
                            <p class="font-semibold">{{ $pembayaran->tiket->nama_penumpang }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm">Rute</p>
                            <p class="font-semibold">{{ $pembayaran->tiket->jadwalKelasBus->jadwal->rute->asal }} →
                                {{ $pembayaran->tiket->jadwalKelasBus->jadwal->rute->tujuan }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm">Tanggal Berangkat</p>
                            <p class="font-semibold">
                                {{ \Carbon\Carbon::parse($pembayaran->tiket->jadwalKelasBus->jadwal->tanggal)->format('d M Y') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm">Jam Berangkat</p>
                            <p class="font-semibold">{{ $pembayaran->tiket->jadwalKelasBus->jadwal->jam_berangkat }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm">Kursi</p>
                            <p class="font-semibold text-blue-600">{{ $pembayaran->tiket->kursi->nomor }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm">Nominal Pembayaran</p>
                            <p class="font-bold text-lg text-green-600">Rp
                                {{ number_format($pembayaran->nominal, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </x-ui.card.content>
            </x-ui.card>

            <!-- Terminal & Payment Info -->
            <x-ui.card class="mb-6">
                <x-ui.card.header>
                    <h2 class="text-xl font-bold">Lokasi Pembayaran</h2>
                </x-ui.card.header>
                <x-ui.card.content>
                    <div class="space-y-4">
                        <div>
                            <p class="text-gray-600 text-sm mb-1">Terminal Keberangkatan</p>
                            <p class="font-semibold text-lg">
                                {{ $pembayaran->tiket->jadwalKelasBus->jadwal->rute->asalTerminal->nama_terminal ?? 'Terminal' }}
                            </p>
                            <p class="text-sm text-gray-600 mt-1">
                                {{ $pembayaran->tiket->jadwalKelasBus->jadwal->rute->asalTerminal->alamat ?? '-' }}</p>
                        </div>
                        <div class="border-t pt-4">
                            <p class="text-gray-600 text-sm mb-2">Cara Pembayaran</p>
                            <ul class="space-y-2 text-sm">
                                <li class="flex items-start gap-2">
                                    <span class="text-blue-600 font-bold">1.</span>
                                    <span>Datang ke terminal keberangkatan sebelum jadwal keberangkatan</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-blue-600 font-bold">2.</span>
                                    <span>Tunjukkan kode tiket <strong>{{ $pembayaran->tiket->kode_tiket }}</strong> ke
                                        petugas</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-blue-600 font-bold">3.</span>
                                    <span>Lakukan pembayaran tunai sebesar <strong>Rp
                                            {{ number_format($pembayaran->nominal, 0, ',', '.') }}</strong></span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-blue-600 font-bold">4.</span>
                                    <span>Terima kuitansi dan boarding pass dari petugas</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </x-ui.card.content>
            </x-ui.card>

            <!-- Important Notes -->
            <x-ui.card class="mb-6 border-amber-200 bg-amber-50">
                <x-ui.card.content class="pt-6">
                    <div class="flex gap-3">
                        <div class="text-amber-600 text-2xl">⚠️</div>
                        <div>
                            <p class="font-semibold text-amber-900 mb-2">Penting:</p>
                            <ul class="text-sm text-amber-800 space-y-1">
                                <li>• Tiket akan dianggap pembatalan otomatis jika tidak dibayar dalam 24 jam</li>
                                <li>• Harap datang ke terminal minimal 30 menit sebelum waktu keberangkatan</li>
                                <li>• Siapkan uang tunai sesuai dengan nominal tiket</li>
                                <li>• Tunjukkan kode tiket saat check-in di terminal</li>
                                <li>• Jika ada kendala, hubungi customer service kami</li>
                            </ul>
                        </div>
                    </div>
                </x-ui.card.content>
            </x-ui.card>

            <!-- Contact Info -->
            <x-ui.card class="mb-6">
                <x-ui.card.header>
                    <h2 class="text-xl font-bold">Hubungi Kami</h2>
                </x-ui.card.header>
                <x-ui.card.content>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-center gap-3">
                            <div class="text-2xl">📞</div>
                            <div>
                                <p class="text-sm text-gray-600">Nomor Telepon</p>
                                <p class="font-semibold">{{ env('CUSTOMER_SERVICE_PHONE', '1234567890') }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="text-2xl">📧</div>
                            <div>
                                <p class="text-sm text-gray-600">Email</p>
                                <p class="font-semibold">{{ env('CUSTOMER_SERVICE_EMAIL', 'support@tiketbus.com') }}</p>
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