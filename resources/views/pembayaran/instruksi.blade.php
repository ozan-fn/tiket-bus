@extends('layouts.app')
@section('content')

@section('title', 'Instruksi Transfer')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Instruksi Transfer Bank</h1>
                <p class="text-gray-600 mt-2">Silakan transfer sesuai data rekening di bawah</p>
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
                            <p class="font-semibold">{{ $pembayaran->tiket->kode_tiket }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm">Kode Referensi</p>
                            <p class="font-semibold">{{ $pembayaran->kode_transaksi }}</p>
                        </div>
                        <div class="col-span-2">
                            <p class="text-gray-600 text-sm">Rute</p>
                            <p class="font-semibold">{{ $pembayaran->tiket->jadwalKelasBus->jadwal->rute->asal }} -
                                {{ $pembayaran->tiket->jadwalKelasBus->jadwal->rute->tujuan }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm">Nominal</p>
                            <p class="font-bold text-lg text-blue-600">Rp
                                {{ number_format($pembayaran->nominal, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </x-ui.card.content>
            </x-ui.card>

            <!-- Bank Account Info -->
            <x-ui.card class="mb-6">
                <x-ui.card.header>
                    <h2 class="text-xl font-bold">Data Rekening Tujuan Transfer</h2>
                </x-ui.card.header>
                <x-ui.card.content>
                    <div class="space-y-6">
                        <!-- BCA -->
                        <div class="border-b pb-4 last:border-b-0">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <span class="font-bold text-blue-600">BCA</span>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">Bank BCA</p>
                                    <p class="text-sm text-gray-600">Transfer instan tersedia</p>
                                </div>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="mb-3">
                                    <p class="text-xs text-gray-600 uppercase">Nomor Rekening</p>
                                    <p class="font-bold text-lg">{{ env('BANK_BCA_NUMBER', '1234567890') }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-600 uppercase">Nama Pemilik</p>
                                    <p class="font-semibold">{{ env('BANK_BCA_NAME', 'PT TIKET BUS') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Mandiri -->
                        <div class="border-b pb-4 last:border-b-0">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                                    <span class="font-bold text-red-600">MDR</span>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">Bank Mandiri</p>
                                    <p class="text-sm text-gray-600">Transfer instan tersedia</p>
                                </div>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="mb-3">
                                    <p class="text-xs text-gray-600 uppercase">Nomor Rekening</p>
                                    <p class="font-bold text-lg">{{ env('BANK_MANDIRI_NUMBER', '0987654321') }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-600 uppercase">Nama Pemilik</p>
                                    <p class="font-semibold">{{ env('BANK_MANDIRI_NAME', 'PT TIKET BUS') }}</p>
                                </div>
                            </div>
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
@endsection
