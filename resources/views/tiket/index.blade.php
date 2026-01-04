@extends('layouts.app')
@section('content')

@section('title', 'Tiket Saya')

    <div class="max-w-4xl mx-auto">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Tiket Saya</h1>
            <p class="text-gray-600 mt-2">Riwayat pemesanan tiket Anda</p>
        </div>

        @if ($tikets->isEmpty())
            <x-ui.card class="text-center py-12">
                <p class="text-gray-600 text-lg">Anda belum memiliki tiket</p>
                <a href="{{ route('pemesanan.index') }}" class="inline-block mt-4 px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Pesan Tiket Sekarang
                </a>
            </x-ui.card>
        @else
            <!-- Filter Tabs -->
            <div class="mb-6 flex gap-2 border-b">
                <a href="{{ route('tiket.index') }}" class="px-4 py-2 font-semibold {{ !request('status') ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-600' }}">
                    Semua
                </a>
                <a href="{{ route('tiket.index', ['status' => 'dipesan']) }}" class="px-4 py-2 font-semibold {{ request('status') === 'dipesan' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-600' }}">
                    Pending
                </a>
                <a href="{{ route('tiket.index', ['status' => 'dibayar']) }}" class="px-4 py-2 font-semibold {{ request('status') === 'dibayar' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-600' }}">
                    Terbayar
                </a>
                <a href="{{ route('tiket.index', ['status' => 'selesai']) }}" class="px-4 py-2 font-semibold {{ request('status') === 'selesai' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-600' }}">
                    Selesai
                </a>
                <a href="{{ route('tiket.index', ['status' => 'batal']) }}" class="px-4 py-2 font-semibold {{ request('status') === 'batal' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-600' }}">
                    Batal
                </a>
            </div>

            <!-- Tiket List -->
            <div class="space-y-4">
                @forelse ($tikets as $tiket)
                    <x-ui.card>
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900">
                                        {{ $tiket->jadwalKelasBus->jadwal->rute->asal }} → {{ $tiket->jadwalKelasBus->jadwal->rute->tujuan }}
                                    </h3>
                                    <p class="text-sm text-gray-600 mt-1">Kode: {{ $tiket->kode_tiket }}</p>
                                </div>
                                <div class="text-right">
                                    @if ($tiket->status === 'dipesan')
                                        <span class="inline-block px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-semibold">Menunggu Pembayaran</span>
                                    @elseif ($tiket->status === 'dibayar')
                                        <span class="inline-block px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">Terbayar</span>
                                    @elseif ($tiket->status === 'selesai')
                                        <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">Selesai</span>
                                    @elseif ($tiket->status === 'batal')
                                        <span class="inline-block px-3 py-1 bg-red-100 text-red-800 rounded-full text-xs font-semibold">Dibatalkan</span>
                                    @endif
                                </div>
                            </div>

                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                                <div>
                                    <p class="text-xs text-gray-600">Tanggal Berangkat</p>
                                    <p class="font-semibold">{{ \Carbon\Carbon::parse($tiket->jadwalKelasBus->jadwal->tanggal)->format('d M Y') }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-600">Jam Berangkat</p>
                                    <p class="font-semibold">{{ $tiket->jadwalKelasBus->jadwal->jam_berangkat }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-600">Kursi</p>
                                    <p class="font-semibold">{{ $tiket->kursi->nomor }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-600">Harga</p>
                                    <p class="font-semibold text-blue-600">Rp {{ number_format($tiket->harga, 0, ',', '.') }}</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-4 pt-4 border-t">
                                <div>
                                    <p class="text-xs text-gray-600">Penumpang</p>
                                    <p class="font-semibold">{{ $tiket->nama_penumpang }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-600">NIK</p>
                                    <p class="font-semibold text-sm">{{ $tiket->nik }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-600">Telepon</p>
                                    <p class="font-semibold text-sm">{{ $tiket->nomor_telepon }}</p>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex gap-2 pt-4 border-t">
                                @if ($tiket->status === 'dipesan')
                                    <a href="{{ route('pemesanan.pembayaran', $tiket->id) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-semibold hover:bg-blue-700 transition">
                                        Bayar Sekarang
                                    </a>
                                @elseif ($tiket->status === 'dibayar')
                                    <a href="{{ route('tiket.show', $tiket->id) }}" class="px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-semibold hover:bg-green-700 transition">
                                        Lihat Detail & QR
                                    </a>
                                @endif
                                <a href="{{ route('tiket.show', $tiket->id) }}" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg text-sm font-semibold hover:bg-gray-50 transition">
                                    Detail
                                </a>
                            </div>
                        </div>
                    </x-ui.card>
                @empty
                    <x-ui.card class="text-center py-12">
                        <p class="text-gray-600">Tidak ada tiket dengan status ini</p>
                    </x-ui.card>
                @endforelse
            </div>

            <!-- Pagination -->
            @if ($tikets->hasPages())
                <div class="mt-8">
                    {{ $tikets->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
@endsection
