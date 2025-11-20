<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Tiket') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-4">
                        <h3 class="text-lg font-medium">Informasi Tiket</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <strong>ID Tiket:</strong> {{ $tiket->id }}
                        </div>
                        <div>
                            <strong>Status:</strong> {{ $tiket->status }}
                        </div>
                        <div>
                            <strong>Nama Penumpang:</strong> {{ $tiket->nama_penumpang }}
                        </div>
                        <div>
                            <strong>Jenis Kelamin:</strong>
                            {{ $tiket->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                        </div>
                        <div>
                            <strong>Nomor Telepon:</strong> {{ $tiket->nomor_telepon }}
                        </div>
                        <div>
                            <strong>Email:</strong> {{ $tiket->email }}
                        </div>
                        <div>
                            <strong>Kursi:</strong> {{ $tiket->kursi }}
                        </div>
                        <div>
                            <strong>Rute:</strong> {{ $tiket->jadwal->rute->asalTerminal->nama_terminal ?? '-' }} →
                            {{ $tiket->jadwal->rute->tujuanTerminal->nama_terminal ?? '-' }}
                        </div>
                        <div>
                            <strong>Bus:</strong> {{ $tiket->jadwal->bus->nama }}
                        </div>
                        <div>
                            <strong>Sopir:</strong> {{ $tiket->jadwal->sopir->user->name }}
                        </div>
                        <div>
                            <strong>Tanggal & Jam:</strong> {{ $tiket->jadwal->tanggal_berangkat->format('d-m-Y') }}
                            {{ $tiket->jadwal->jam_berangkat->format('H:i') }}
                        </div>
                        <div>
                            <strong>Dibuat:</strong> {{ $tiket->created_at->format('d-m-Y H:i') }}
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        @if($tiket->status == 'dipesan')
                            <button class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded mr-2">
                                Bayar Sekarang
                            </button>
                        @endif
                        <a href="{{ route('pemesanan.index') }}" class="text-gray-600 hover:text-gray-900">Kembali</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>