<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Pesan Tiket') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Journey Details -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg shadow-lg p-8 mb-8">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    <div>
                        <p class="text-blue-100 text-sm">ASAL</p>
                        <p class="text-2xl font-bold">{{ $jadwal->rute->asalTerminal->nama_kota }}</p>
                    </div>
                    <div class="flex items-center justify-center">
                        <i data-lucide="arrow-right" class="w-8 h-8"></i>
                    </div>
                    <div>
                        <p class="text-blue-100 text-sm">TUJUAN</p>
                        <p class="text-2xl font-bold">{{ $jadwal->rute->tujuanTerminal->nama_kota }}</p>
                    </div>
                    <div>
                        <p class="text-blue-100 text-sm">TANGGAL</p>
                        <p class="text-2xl font-bold">{{ $jadwal->tanggal_berangkat->format('d M Y') }}</p>
                    </div>
                </div>
                <div class="mt-6 pt-6 border-t border-blue-400 grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                    <div>
                        <p class="text-blue-100">Bus</p>
                        <p class="font-semibold">{{ $jadwal->bus->nama }}</p>
                    </div>
                    <div>
                        <p class="text-blue-100">Jam Berangkat</p>
                        <p class="font-semibold">{{ $jadwal->jam_berangkat->format('H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-blue-100">Sopir</p>
                        <p class="font-semibold">{{ $jadwal->sopir->user->name }}</p>
                    </div>
                    @if($jadwal->conductor)
                        <div>
                            <p class="text-blue-100">Kondektur</p>
                            <p class="font-semibold">{{ $jadwal->conductor->user->name }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <form method="POST" action="{{ route('pemesanan.store', $jadwal) }}" class="space-y-8">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Left Column: Seat Selection -->
                    <div class="lg:col-span-2">
                        <!-- Seat Selection -->
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-8 mb-8">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6">
                                Pilih Kursi Anda
                            </h3>

                            <!-- Seat Legend -->
                            <div class="flex flex-wrap gap-6 mb-8 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 bg-blue-500 rounded border-2 border-blue-600"></div>
                                    <span class="text-sm text-gray-700 dark:text-gray-300">Tersedia</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div
                                        class="w-8 h-8 bg-red-500 rounded border-2 border-red-600 cursor-not-allowed opacity-50">
                                    </div>
                                    <span class="text-sm text-gray-700 dark:text-gray-300">Terpakai</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 bg-green-500 rounded border-2 border-green-600"></div>
                                    <span class="text-sm text-gray-700 dark:text-gray-300">Terpilih</span>
                                </div>
                            </div>

                            <!-- Seat Grid -->
                            <div class="grid grid-cols-8 gap-3">
                                @php
                                    $kursiTerpakai = $kursiTerpakai ?? [];
                                    $totalKursi = $jadwal->bus->kapasitas;
                                @endphp

                                @for($i = 1; $i <= $totalKursi; $i++)
                                    @php
                                        $isBooked = in_array($i, $kursiTerpakai);
                                        $isSelected = old('kursi') == $i;
                                    @endphp

                                    <label class="relative cursor-pointer group">
                                        <input type="radio" name="kursi" value="{{ $i }}" {{ $isSelected ? 'checked' : '' }}
                                            {{ $isBooked ? 'disabled' : '' }} class="sr-only peer">

                                        <div class="w-full aspect-square rounded-lg border-2 transition-all
                                                {{ $isBooked ? 'bg-red-500 border-red-600 opacity-50 cursor-not-allowed' : '' }}
                                                {{ !$isBooked && !$isSelected ? 'bg-blue-500 border-blue-600 hover:bg-blue-600' : '' }}
                                                {{ $isSelected ? 'bg-green-500 border-green-600' : '' }}
                                                flex items-center justify-center text-white font-semibold text-sm">
                                            {{ $i }}
                                        </div>

                                        @if(!$isBooked)
                                            <div
                                                class="absolute -top-10 left-1/2 transform -translate-x-1/2 bg-gray-900 text-white text-xs py-1 px-2 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
                                                Kursi {{ $i }}
                                            </div>
                                        @endif
                                    </label>
                                @endfor
                            </div>

                            @error('kursi')
                                <p class="mt-4 text-sm text-red-600">{{ $message }}</p>
                            @enderror

                            <!-- Selected Seat Info -->
                            @if(old('kursi'))
                                <div
                                    class="mt-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                                    <p class="text-sm text-gray-700 dark:text-gray-300">
                                        <span class="font-semibold">Kursi Terpilih:</span>
                                        <span class="text-lg font-bold text-green-600 dark:text-green-400">Kursi
                                            {{ old('kursi') }}</span>
                                    </p>
                                </div>
                            @endif
                        </div>

                        <!-- Passenger Info -->
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-8">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6">
                                Data Penumpang
                            </h3>

                            <div class="space-y-5">
                                <!-- Name -->
                                <div>
                                    <label for="nama_penumpang"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Nama Lengkap <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="nama_penumpang" id="nama_penumpang"
                                        value="{{ old('nama_penumpang', auth()->user()->name) }}"
                                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        required>
                                    @error('nama_penumpang')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Gender -->
                                <div>
                                    <label for="jenis_kelamin"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Jenis Kelamin <span class="text-red-500">*</span>
                                    </label>
                                    <select name="jenis_kelamin" id="jenis_kelamin"
                                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        required>
                                        <option value="">-- Pilih --</option>
                                        <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki
                                        </option>
                                        <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan
                                        </option>
                                    </select>
                                    @error('jenis_kelamin')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Phone -->
                                <div>
                                    <label for="nomor_telepon"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Nomor Telepon <span class="text-red-500">*</span>
                                    </label>
                                    <input type="tel" name="nomor_telepon" id="nomor_telepon"
                                        value="{{ old('nomor_telepon') }}" placeholder="08..."
                                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        required>
                                    @error('nomor_telepon')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div>
                                    <label for="email"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Email <span class="text-red-500">*</span>
                                    </label>
                                    <input type="email" name="email" id="email"
                                        value="{{ old('email', auth()->user()->email) }}"
                                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        required>
                                    @error('email')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Summary & Price -->
                    <div class="lg:col-span-1">
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 sticky top-6">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6">
                                Ringkasan Pemesanan
                            </h3>

                            <div class="space-y-4 mb-6 pb-6 border-b border-gray-200 dark:border-gray-700">
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Rute</span>
                                    <span class="font-semibold text-gray-900 dark:text-white">
                                        {{ $jadwal->rute->asalTerminal->nama_kota }} -
                                        {{ $jadwal->rute->tujuanTerminal->nama_kota }}
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Tanggal</span>
                                    <span class="font-semibold text-gray-900 dark:text-white">
                                        {{ $jadwal->tanggal_berangkat->format('d M Y') }}
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Jam</span>
                                    <span class="font-semibold text-gray-900 dark:text-white">
                                        {{ $jadwal->jam_berangkat->format('H:i') }}
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Kursi</span>
                                    <span class="font-semibold text-gray-900 dark:text-white" id="selected-seat">
                                        {{ old('kursi') ? 'Kursi ' . old('kursi') : '-' }}
                                    </span>
                                </div>
                            </div>

                            <!-- Price Display -->
                            <div class="mb-6">
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Harga per tiket</p>
                                <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">
                                    Rp {{ number_format($jadwal->jadwalKelasBus->first()?->harga ?? 0, 0, ',', '.') }}
                                </p>
                            </div>

                            <!-- Action Buttons -->
                            <div class="space-y-3">
                                <button type="submit"
                                    class="w-full px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-lg font-semibold transition-all transform hover:scale-105 flex items-center justify-center gap-2">
                                    <i data-lucide="check-circle" class="w-5 h-5"></i>
                                    Lanjut ke Pembayaran
                                </button>
                                <a href="{{ route('pemesanan.index') }}"
                                    class="w-full px-6 py-3 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-900 dark:text-white rounded-lg font-semibold transition-colors text-center">
                                    Kembali
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Lucide Icons Script -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <script>
        lucide.createIcons();

        // Update selected seat display
        document.querySelectorAll('input[name="kursi"]').forEach(input => {
            input.addEventListener('change', function () {
                document.getElementById('selected-seat').textContent = 'Kursi ' + this.value;
            });
        });
    </script>
</x-app-layout>

</div>
</div>
</div>
</x-app-layout>