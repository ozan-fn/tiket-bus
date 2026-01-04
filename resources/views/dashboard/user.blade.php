<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Section -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                    Selamat datang kembali, {{ Auth::user()->name }}!
                </h1>
                <p class="text-gray-600 dark:text-gray-400">
                    Kelola pemesanan tiket dan riwayat perjalanan Anda di sini
                </p>
            </div>

            <!-- Stats Overview -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Total Tiket Aktif -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Tiket Aktif</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">
                                {{ $activeTickets ?? 0 }}
                            </p>
                        </div>
                        <div class="bg-blue-500/10 p-3 rounded-lg">
                            <i data-lucide="ticket" class="w-8 h-8 text-blue-600 dark:text-blue-400"></i>
                        </div>
                    </div>
                </div>

                <!-- Total Perjalanan Selesai -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Perjalanan Selesai</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">
                                {{ $completedTrips ?? 0 }}
                            </p>
                        </div>
                        <div class="bg-green-500/10 p-3 rounded-lg">
                            <i data-lucide="check-circle" class="w-8 h-8 text-green-600 dark:text-green-400"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Tiket Saya -->
                <div
                    class="bg-gradient-to-br from-green-500 to-green-600 dark:from-green-600 dark:to-green-700 rounded-lg shadow-lg p-8 text-white">
                    <i data-lucide="ticket" class="w-12 h-12 mb-4"></i>
                    <h3 class="text-2xl font-bold mb-2">Tiket Saya</h3>
                    <p class="text-green-100 mb-4">
                        Lihat daftar tiket dan status pembayaran Anda
                    </p>
                    <a href="{{ route('tiket.index') }}"
                        class="inline-flex items-center gap-2 bg-white text-green-600 hover:bg-green-50 px-4 py-2 rounded-lg font-semibold transition-colors">
                        Lihat Tiket
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>
                </div>

                <!-- Pesan Tiket Baru -->
                <div
                    class="bg-gradient-to-br from-blue-500 to-blue-600 dark:from-blue-600 dark:to-blue-700 rounded-lg shadow-lg p-8 text-white">
                    <i data-lucide="plus-circle" class="w-12 h-12 mb-4"></i>
                    <h3 class="text-2xl font-bold mb-2">Pesan Tiket Baru</h3>
                    <p class="text-blue-100 mb-4">
                        Cari dan pesan tiket perjalanan Anda sekarang
                    </p>
                    <a href="{{ route('pemesanan.index') }}"
                        class="inline-flex items-center gap-2 bg-white text-blue-600 hover:bg-blue-50 px-4 py-2 rounded-lg font-semibold transition-colors">
                        Pesan Sekarang
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>
                </div>

                <!-- Panduan Pemesanan -->
                <div
                    class="bg-gradient-to-br from-amber-500 to-amber-600 dark:from-amber-600 dark:to-amber-700 rounded-lg shadow-lg p-8 text-white">
                    <i data-lucide="help-circle" class="w-12 h-12 mb-4"></i>
                    <h3 class="text-2xl font-bold mb-2">Bantuan</h3>
                    <p class="text-amber-100 mb-4">
                        Pelajari cara memesan tiket dengan mudah
                    </p>
                    <a href="#"
                        class="inline-flex items-center gap-2 bg-white text-amber-600 hover:bg-amber-50 px-4 py-2 rounded-lg font-semibold transition-colors">
                        Pelajari Selengkapnya
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>
                </div>
            </div>

            <!-- Tiket Aktif / Mendatang -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden mb-8">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                        <i data-lucide="calendar" class="w-5 h-5"></i>
                        Tiket Mendatang
                    </h3>
                </div>

                @if($upcomingTickets && $upcomingTickets->count() > 0)
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($upcomingTickets as $ticket)
                            <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <div class="flex items-start justify-between mb-3">
                                    <div>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $ticket->jadwalKelasBus->jadwal->tanggal_berangkat->format('d M Y') }}
                                        </p>
                                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white">
                                            {{ $ticket->jadwalKelasBus->jadwal->rute->asalTerminal->nama_kota }} →
                                            {{ $ticket->jadwalKelasBus->jadwal->rute->tujuanTerminal->nama_kota }}
                                        </h4>
                                    </div>
                                    <span
                                        class="px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 rounded-full text-sm font-medium">
                                        {{ ucfirst($ticket->status) }}
                                    </span>
                                </div>

                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase">Jam Berangkat</p>
                                        <p class="font-semibold text-gray-900 dark:text-white">
                                            {{ $ticket->jadwalKelasBus->jadwal->jam_berangkat->format('H:i') }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase">Bus</p>
                                        <p class="font-semibold text-gray-900 dark:text-white">
                                            {{ $ticket->jadwalKelasBus->busKelasBus->bus->nama }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase">Kelas</p>
                                        <p class="font-semibold text-gray-900 dark:text-white">
                                            {{ $ticket->jadwalKelasBus->kelasBus->nama_kelas }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase">Kursi</p>
                                        <p class="font-semibold text-gray-900 dark:text-white">
                                            {{ $ticket->kursi->nomor_kursi }}
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Kode Tiket</p>
                                        <p class="font-mono font-bold text-gray-900 dark:text-white">
                                            {{ $ticket->kode_tiket }}
                                        </p>
                                    </div>
                                    <a href="{{ route('pemesanan.show', $ticket) }}"
                                        class="inline-flex items-center gap-2 text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 font-semibold transition-colors">
                                        Lihat Detail
                                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-12 text-center">
                        <i data-lucide="inbox" class="w-16 h-16 text-gray-300 dark:text-gray-600 mx-auto mb-4"></i>
                        <p class="text-gray-500 dark:text-gray-400 mb-4">
                            Belum ada tiket mendatang
                        </p>
                        <a href="{{ route('pemesanan.index') }}"
                            class="inline-flex items-center gap-2 text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 font-semibold transition-colors">
                            Pesan Tiket Sekarang
                            <i data-lucide="arrow-right" class="w-4 h-4"></i>
                        </a>
                    </div>
                @endif
            </div>

            <!-- Riwayat Perjalanan -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                        <i data-lucide="history" class="w-5 h-5"></i>
                        Riwayat Perjalanan
                    </h3>
                </div>

                @if($completedTickets && $completedTickets->count() > 0)
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($completedTickets as $ticket)
                            <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <div class="flex items-start justify-between mb-3">
                                    <div>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $ticket->jadwalKelasBus->jadwal->tanggal_berangkat->format('d M Y') }}
                                        </p>
                                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white">
                                            {{ $ticket->jadwalKelasBus->jadwal->rute->asalTerminal->nama_kota }} →
                                            {{ $ticket->jadwalKelasBus->jadwal->rute->tujuanTerminal->nama_kota }}
                                        </h4>
                                    </div>
                                    <span
                                        class="px-3 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 rounded-full text-sm font-medium">
                                        Selesai
                                    </span>
                                </div>

                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase">Bus</p>
                                        <p class="font-semibold text-gray-900 dark:text-white">
                                            {{ $ticket->jadwalKelasBus->busKelasBus->bus->nama }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase">Kelas</p>
                                        <p class="font-semibold text-gray-900 dark:text-white">
                                            {{ $ticket->jadwalKelasBus->kelasBus->nama_kelas }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase">Harga</p>
                                        <p class="font-semibold text-gray-900 dark:text-white">
                                            Rp {{ number_format($ticket->harga, 0, ',', '.') }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase">Kursi</p>
                                        <p class="font-semibold text-gray-900 dark:text-white">
                                            {{ $ticket->kursi->nomor_kursi }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-12 text-center">
                        <i data-lucide="inbox" class="w-16 h-16 text-gray-300 dark:text-gray-600 mx-auto mb-4"></i>
                        <p class="text-gray-500 dark:text-gray-400">
                            Belum ada riwayat perjalanan
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Lucide Icons Script -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <script>
        lucide.createIcons();
    </script>
</x-app-layout>