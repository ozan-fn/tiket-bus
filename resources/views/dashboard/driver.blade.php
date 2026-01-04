<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-4">
                <!-- <x-ui.avatar.avatar size="lg">
                    <x-ui.avatar.fallback class="bg-primary text-primary-foreground">
                        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                    </x-ui.avatar.fallback>
                </x-ui.avatar.avatar>
                <div>
                    <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Dashboard Sopir</h2>
                    <p class="text-gray-600 dark:text-gray-400">Selamat datang, {{ Auth::user()->name }}</p>
                </div> -->
            </div>
        </div>
    </x-slot>

    <div class="p-4 sm:p-6">
        <div class="max-w-7xl mx-auto space-y-6">
            <!-- Statistik Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Total Jadwal -->
                <x-ui.card.card>
                    <div class="flex items-center justify-between border-l-4 border-blue-500 pl-4">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Jadwal</p>
                            <p class="text-3xl font-bold text-gray-800 dark:text-white mt-2">{{ $totalJadwal }}</p>
                        </div>
                        <i data-lucide="calendar" class="w-12 h-12 text-blue-500 opacity-20"></i>
                    </div>
                </x-ui.card.card>

                <!-- Jadwal Selesai -->
                <x-ui.card.card>
                    <div class="flex items-center justify-between border-l-4 border-green-500 pl-4">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Jadwal Selesai</p>
                            <p class="text-3xl font-bold text-gray-800 dark:text-white mt-2">{{ $jadwalSelesai }}</p>
                        </div>
                        <i data-lucide="check-circle" class="w-12 h-12 text-green-500 opacity-20"></i>
                    </div>
                </x-ui.card.card>

                <!-- Jadwal Aktif Hari Ini -->
                <x-ui.card.card>
                    <div class="flex items-center justify-between border-l-4 border-orange-500 pl-4">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Jadwal Aktif</p>
                            <p class="text-3xl font-bold text-gray-800 dark:text-white mt-2">{{ $jadwalAktif ? 1 : 0 }}</p>
                        </div>
                        <i data-lucide="zap" class="w-12 h-12 text-orange-500 opacity-20"></i>
                    </div>
                </x-ui.card.card>

                <!-- Perjalanan Mendatang -->
                <x-ui.card.card>
                    <div class="flex items-center justify-between border-l-4 border-purple-500 pl-4">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Perjalanan Mendatang</p>
                            <p class="text-3xl font-bold text-gray-800 dark:text-white mt-2">{{ $jadwalMendatang->count() }}</p>
                        </div>
                        <i data-lucide="arrow-right" class="w-12 h-12 text-purple-500 opacity-20"></i>
                    </div>
                </x-ui.card.card>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Jadwal Aktif -->
                <div class="lg:col-span-2">
                    <x-ui.card.card>
                        <x-ui.card.header>
                            <x-ui.card.title>Jadwal Aktif</x-ui.card.title>
                        </x-ui.card.header>
                        <x-ui.card.content>
                            @if ($jadwalAktif)
                                <x-ui.alert variant="default" class="mb-4">
                                    <i data-lucide="info" class=""></i>
                                    <div data-slot="alert-description">
                                        <p class="font-semibold">{{ $jadwalAktif->bus->nama }} ({{ $jadwalAktif->bus->plat_nomor }})</p>
                                        <p class="text-sm mt-1">{{ $jadwalAktif->rute->asalTerminal->nama_terminal ?? 'N/A' }} → {{ $jadwalAktif->rute->tujuanTerminal->nama_terminal ?? 'N/A' }}</p>
                                    </div>
                                </x-ui.alert>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                                    <div class="space-y-1">
                                        <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">Tanggal Berangkat</p>
                                        <p class="text-lg font-semibold text-gray-800 dark:text-white">{{ $jadwalAktif->tanggal_berangkat->format('d M Y') }}</p>
                                    </div>
                                    <div class="space-y-1">
                                        <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">Jam Berangkat</p>
                                        <p class="text-lg font-semibold text-gray-800 dark:text-white">{{ $jadwalAktif->jam_berangkat->format('H:i') }}</p>
                                    </div>
                                </div>

                                <div class="flex gap-3">
                                    <a 
                                        href="{{ route('sopir.jadwal.show', $jadwalAktif->id) }}"
                                        class="flex-1 flex items-center justify-center gap-2 h-9 px-4 py-2 rounded-md bg-primary text-primary-foreground hover:bg-primary/90 shadow-xs font-medium transition-all">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                        Lihat Detail
                                    </a>
                                    <a 
                                        href="{{ route('sopir.scan.index') }}"
                                        class="flex-1 flex items-center justify-center gap-2 h-9 px-4 py-2 rounded-md bg-secondary text-secondary-foreground hover:bg-secondary/80 shadow-xs font-medium transition-all">
                                        <i data-lucide="barcode" class="w-4 h-4"></i>
                                        Scan Tiket
                                    </a>
                                </div>
                            @else
                                <x-ui.alert variant="default">
                                    <i data-lucide="inbox" class=""></i>
                                    <div data-slot="alert-description">
                                        <p class="font-semibold">Tidak ada jadwal aktif saat ini</p>
                                        <p class="text-sm mt-1">Jadwal Anda akan muncul di sini ketika ada rute yang sedang berlangsung</p>
                                    </div>
                                </x-ui.alert>
                            @endif
                        </x-ui.card.content>
                    </x-ui.card.card>
                </div>

                <!-- Daftar Penumpang Dipesan (Jadwal Aktif) -->
                <x-ui.card.card>
                    <x-ui.card.header>
                        <x-ui.card.title>Penumpang Dipesan</x-ui.card.title>
                    </x-ui.card.header>
                    <x-ui.card.content>
                        @if ($jadwalAktif)
                            <div class="space-y-2 max-h-96 overflow-y-auto">
                                @php
                                    $penumpangDipesan = [];
                                    foreach ($jadwalAktif->jadwalKelasBus as $jkb) {
                                        foreach ($jkb->tikets as $tiket) {
                                            $penumpangDipesan[] = [
                                                'nama' => $tiket->nama_penumpang,
                                                'kode' => $tiket->kode_tiket,
                                                'kelas' => $jkb->kelasBus->nama,
                                                'is_hadir' => $tiket->is_hadir,
                                            ];
                                        }
                                    }
                                @endphp
                                
                                @forelse ($penumpangDipesan as $penumpang)
                                    <div class="flex items-center justify-between p-3 rounded-lg border {{ $penumpang['is_hadir'] ? 'border-green-200 dark:border-green-800 bg-green-50 dark:bg-green-900/10' : 'border-yellow-200 dark:border-yellow-800 bg-yellow-50 dark:bg-yellow-900/10' }}">
                                        <div class="flex-1">
                                            <p class="font-semibold text-gray-800 dark:text-white text-sm">{{ $penumpang['nama'] }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $penumpang['kode'] }}</p>
                                            <p class="text-xs text-gray-600 dark:text-gray-300 mt-1">Kelas: {{ $penumpang['kelas'] }}</p>
                                        </div>
                                        <div>
                                            @if ($penumpang['is_hadir'])
                                                <x-ui.badge variant="secondary">Hadir</x-ui.badge>
                                            @else
                                                <x-ui.badge variant="default">Dipesan</x-ui.badge>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <x-ui.alert variant="default">
                                        <i data-lucide="users" class=""></i>
                                        <div data-slot="alert-description">
                                            <p class="text-sm">Tidak ada penumpang dipesan</p>
                                        </div>
                                    </x-ui.alert>
                                @endforelse
                            </div>
                        @else
                            <x-ui.alert variant="default">
                                <i data-lucide="inbox" class=""></i>
                                <div data-slot="alert-description">
                                    <p class="text-sm">Tidak ada jadwal aktif</p>
                                </div>
                            </x-ui.alert>
                        @endif
                    </x-ui.card.content>
                </x-ui.card.card>
            </div>

            <!-- Info Sopir -->
            <x-ui.card.card>
                <x-ui.card.header>
                    <x-ui.card.title>Informasi Profil</x-ui.card.title>
                </x-ui.card.header>
                <x-ui.card.content>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 font-medium mb-2">Nama</p>
                            <p class="text-gray-800 dark:text-white font-semibold">{{ Auth::user()->name }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 font-medium mb-2">Email</p>
                            <p class="text-gray-800 dark:text-white font-semibold text-sm break-all">{{ Auth::user()->email }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 font-medium mb-2">No. SIM</p>
                            <p class="text-gray-800 dark:text-white font-semibold">{{ $sopir->nomor_sim }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 font-medium mb-2">NIK</p>
                            <p class="text-gray-800 dark:text-white font-semibold">{{ $sopir->nik }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 font-medium mb-2">Telepon</p>
                            <p class="text-gray-800 dark:text-white font-semibold">{{ $sopir->telepon ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 font-medium mb-2">Status</p>
                            <x-ui.badge :variant="$sopir->status === 'aktif' ? 'secondary' : 'destructive'">
                                {{ ucfirst($sopir->status) }}
                            </x-ui.badge>
                        </div>
                    </div>
                </x-ui.card.content>
            </x-ui.card.card>
        </div>
    </div>
</x-admin-layout>
