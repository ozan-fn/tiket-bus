<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Pesan Tiket') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Search Section -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8 mb-8">
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">
                    Cari Tiket Perjalanan Anda
                </h3>

                <form method="GET" action="{{ route('pemesanan.index') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <!-- Terminal Asal -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Terminal Asal
                            </label>
                            <input type="text" name="asal" value="{{ request('asal') }}"
                                placeholder="Cari terminal asal..."
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <!-- Terminal Tujuan -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Terminal Tujuan
                            </label>
                            <input type="text" name="tujuan" value="{{ request('tujuan') }}"
                                placeholder="Cari terminal tujuan..."
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <!-- Tanggal Berangkat -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Tanggal Berangkat
                            </label>
                            <input type="date" name="tanggal" value="{{ request('tanggal') }}"
                                min="{{ now()->toDateString() }}"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <!-- Search Button -->
                        <div class="flex items-end">
                            <button type="submit"
                                class="w-full px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition-colors flex items-center justify-center gap-2">
                                <i data-lucide="search" class="w-5 h-5"></i>
                                Cari
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Results Section -->
            @if($jadwals->count() > 0)
                <div class="space-y-4">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">
                        {{ $jadwals->total() }} Perjalanan Tersedia
                    </h3>

                    @foreach($jadwals as $jadwal)
                        <div
                            class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                            <div class="p-6">
                                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                                    <!-- Route & Time -->
                                    <div class="flex-1">
                                        <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-2">
                                            {{ $jadwal->rute->asalTerminal->nama_kota }} →
                                            {{ $jadwal->rute->tujuanTerminal->nama_kota }}
                                        </h4>
                                        <div class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                                            <p>
                                                <span class="font-semibold">Tanggal:</span>
                                                {{ $jadwal->tanggal_berangkat->format('d M Y') }}
                                            </p>
                                            <p>
                                                <span class="font-semibold">Jam Berangkat:</span>
                                                {{ $jadwal->jam_berangkat->format('H:i') }}
                                            </p>
                                            <p>
                                                <span class="font-semibold">Bus:</span>
                                                {{ $jadwal->bus->nama }}
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Sopir & Conductor Info -->
                                    <div class="flex-1">
                                        <h5 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                                            Kru Bus
                                        </h5>
                                        <div class="space-y-2">
                                            <div class="flex items-start gap-3">
                                                <i data-lucide="user" class="w-5 h-5 text-gray-400 flex-shrink-0 mt-1"></i>
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                                                        {{ $jadwal->sopir->user->name }}
                                                    </p>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">Sopir</p>
                                                </div>
                                            </div>
                                            @if($jadwal->conductor)
                                                <div class="flex items-start gap-3">
                                                    <i data-lucide="user" class="w-5 h-5 text-gray-400 flex-shrink-0 mt-1"></i>
                                                    <div>
                                                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                                                            {{ $jadwal->conductor->user->name }}
                                                        </p>
                                                        <p class="text-xs text-gray-500 dark:text-gray-400">Kondektur</p>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Class & Price Info -->
                                    <div class="flex-1">
                                        <h5 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                                            Kelas & Harga
                                        </h5>
                                        <div class="space-y-2">
                                            @forelse($jadwal->jadwalKelasBus->take(3) as $jkb)
                                                <div
                                                    class="flex justify-between items-center p-2 bg-gray-50 dark:bg-gray-700 rounded">
                                                    <span class="text-sm text-gray-700 dark:text-gray-300">
                                                        {{ $jkb->kelasBus->nama_kelas }}
                                                    </span>
                                                    <span class="text-sm font-bold text-blue-600 dark:text-blue-400">
                                                        Rp {{ number_format($jkb->harga, 0, ',', '.') }}
                                                    </span>
                                                </div>
                                            @empty
                                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                                    Tidak ada kelas tersedia
                                                </p>
                                            @endforelse
                                        </div>
                                    </div>

                                    <!-- Action Button -->
                                    <div class="flex items-center">
                                        <a href="{{ route('pemesanan.create', $jadwal) }}"
                                            class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-lg font-semibold transition-all transform hover:scale-105 flex items-center gap-2">
                                            <i data-lucide="arrow-right" class="w-5 h-5"></i>
                                            Pesan
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <!-- Pagination -->
                    <div class="mt-8">
                        {{ $jadwals->links() }}
                    </div>
                </div>
            @else
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-12 text-center">
                    <i data-lucide="search" class="w-16 h-16 text-gray-300 dark:text-gray-600 mx-auto mb-4"></i>
                    <p class="text-gray-600 dark:text-gray-400 mb-2">
                        Tidak ada perjalanan yang ditemukan
                    </p>
                    <p class="text-sm text-gray-500 dark:text-gray-500 mb-6">
                        Coba ubah pencarian Anda atau lihat semua perjalanan tersedia
                    </p>
                    <a href="{{ route('pemesanan.index') }}"
                        class="inline-flex items-center gap-2 text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 font-semibold transition-colors">
                        Lihat Semua Perjalanan
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Lucide Icons Script -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <script>
        lucide.createIcons();
    </script>
</x-app-layout>