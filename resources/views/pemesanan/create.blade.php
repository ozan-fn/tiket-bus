<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pesan Tiket') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium">Detail Jadwal</h3>
                        <p><strong>Rute:</strong> {{ $jadwal->rute->asalTerminal->nama_terminal }} →
                            {{ $jadwal->rute->tujuanTerminal->nama_terminal }}</p>
                        <p><strong>Bus:</strong> {{ $jadwal->bus->nama }}</p>
                        <p><strong>Sopir:</strong> {{ $jadwal->sopir->user->name }}</p>
                        <p><strong>Tanggal & Jam:</strong> {{ $jadwal->tanggal_berangkat->format('d-m-Y') }}
                            {{ $jadwal->jam_berangkat->format('H:i') }}</p>
                        <p><strong>Harga:</strong> Rp 100.000</p>
                    </div>

                    <form method="POST" action="{{ route('pemesanan.store', $jadwal) }}">
                        @csrf

                        <div class="mb-4">
                            <label for="nama_penumpang" class="block text-sm font-medium text-gray-700">Nama
                                Penumpang</label>
                            <input type="text" name="nama_penumpang" id="nama_penumpang"
                                value="{{ old('nama_penumpang', auth()->user()->name) }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                required>
                            @error('nama_penumpang')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700">Jenis
                                Kelamin</label>
                            <select name="jenis_kelamin" id="jenis_kelamin"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                required>
                                <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('jenis_kelamin')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="nomor_telepon" class="block text-sm font-medium text-gray-700">Nomor
                                Telepon</label>
                            <input type="text" name="nomor_telepon" id="nomor_telepon"
                                value="{{ old('nomor_telepon') }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                required>
                            @error('nomor_telepon')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" id="email"
                                value="{{ old('email', auth()->user()->email) }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                required>
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="kursi" class="block text-sm font-medium text-gray-700">Nomor Kursi</label>
                            <select name="kursi" id="kursi"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                required>
                                @for($i = 1; $i <= $jadwal->bus->kapasitas; $i++)
                                    <option value="{{ $i }}" {{ old('kursi') == $i ? 'selected' : '' }} {{ in_array($i, $kursiTerpakai ?? []) ? 'disabled' : '' }}>
                                        Kursi {{ $i }} {{ in_array($i, $kursiTerpakai ?? []) ? '(Terpakai)' : '' }}
                                    </option>
                                @endfor
                            </select>
                            @error('kursi')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-between">
                            <a href="{{ route('pemesanan.index') }}"
                                class="text-gray-600 hover:text-gray-900">Kembali</a>
                            <button type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Pesan Tiket
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>