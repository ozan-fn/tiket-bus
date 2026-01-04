@extends('layouts.app')
@section('content')

@section('title', 'Pembayaran Tiket')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto">
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <h3 class="text-red-800 font-semibold mb-2">Error</h3>
                    <ul class="text-red-700 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Tiket Info -->
            <x-ui.card class="mb-6">
                <x-ui.card.header>
                    <h2 class="text-xl font-bold">Informasi Tiket</h2>
                </x-ui.card.header>
                <x-ui.card.content>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-gray-600 text-sm">Kode Tiket</p>
                            <p class="font-semibold text-lg">{{ $tiket->kode_tiket }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm">Penumpang</p>
                            <p class="font-semibold">{{ $tiket->nama_penumpang }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm">Rute</p>
                            <p class="font-semibold">{{ $tiket->jadwalKelasBus->jadwal->rute->asal }} -
                                {{ $tiket->jadwalKelasBus->jadwal->rute->tujuan }}
                            </p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm">Tanggal Berangkat</p>
                            <p class="font-semibold">
                                {{ \Carbon\Carbon::parse($tiket->jadwalKelasBus->jadwal->tanggal)->format('d M Y') }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm">Kelas</p>
                            <p class="font-semibold">{{ $tiket->jadwalKelasBus->kelasBus->nama }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm">Kursi</p>
                            <p class="font-semibold">{{ $tiket->kursi->nomor }}</p>
                        </div>
                        <div class="col-span-2 pt-2 border-t mt-2">
                            <p class="text-gray-600 text-sm">Total Harga</p>
                            <p class="text-2xl font-bold text-blue-600">Rp {{ number_format($tiket->harga, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </x-ui.card.content>
            </x-ui.card>

            <!-- Payment Method Selection -->
            <x-ui.card>
                <x-ui.card.header>
                    <h2 class="text-xl font-bold">Pilih Metode Pembayaran</h2>
                </x-ui.card.header>
                <x-ui.card.content>
                    <form action="{{ route('pemesanan.pembayaran.store', $tiket->id) }}" method="POST">
                        @csrf

                        <div class="space-y-4">
                            <!-- Xendit -->
                            <label
                                class="flex items-start p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-400 transition"
                                id="metode-xendit-label">
                                <input type="radio" name="metode" value="xendit" class="mt-1 w-4 h-4" required>
                                <div class="ml-3 flex-1">
                                    <p class="font-semibold text-gray-900">Kartu Kredit / E-Wallet / Transfer Bank (Xendit)
                                    </p>
                                    <p class="text-sm text-gray-600 mt-1">Bayar dengan kartu kredit, GCash, Dana, OVO,
                                        GoPay, atau transfer bank instan</p>
                                </div>
                            </label>

                            <!-- Transfer Manual -->
                            <label
                                class="flex items-start p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-400 transition"
                                id="metode-transfer-label">
                                <input type="radio" name="metode" value="transfer" class="mt-1 w-4 h-4" required>
                                <div class="ml-3 flex-1">
                                    <p class="font-semibold text-gray-900">Transfer Bank Manual</p>
                                    <p class="text-sm text-gray-600 mt-1">Lakukan transfer langsung ke rekening perusahaan.
                                        Konfirmasi akan dikirim via email.</p>
                                </div>
                            </label>

                            <!-- Tunai -->
                            <label
                                class="flex items-start p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-400 transition"
                                id="metode-tunai-label">
                                <input type="radio" name="metode" value="tunai" class="mt-1 w-4 h-4" required>
                                <div class="ml-3 flex-1">
                                    <p class="font-semibold text-gray-900">Bayar di Lokasi / Terminal</p>
                                    <p class="text-sm text-gray-600 mt-1">Bayar tunai saat naik bus di terminal
                                        keberangkatan</p>
                                </div>
                            </label>
                        </div>

                        <!-- Info Penting -->
                        <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                            <p class="text-sm text-blue-800">
                                <strong>Catatan:</strong> Tiket Anda akan tetap dalam status <strong>Pending</strong> sampai
                                pembayaran dikonfirmasi.
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
@endsection
