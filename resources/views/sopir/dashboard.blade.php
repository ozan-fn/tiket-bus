@extends('layouts.admin')
@section('content')
    @push('header')
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-800">Dashboard Sopir</h2>
            <p class="text-gray-600">Selamat datang, {{ Auth::user()->name }}</p>
        </div>
@endpush

    <div class="p-4 sm:p-6">
        <div class="max-w-7xl mx-auto space-y-6">
            <!-- Statistik Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Total Jadwal -->
                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-medium">Total Jadwal</p>
                            <p class="text-3xl font-bold text-gray-800 mt-2">{{ $totalJadwal }}</p>
                        </div>
                        <i data-lucide="calendar" class="w-12 h-12 text-blue-500 opacity-20"></i>
                    </div>
                </div>

                <!-- Jadwal Selesai -->
                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-medium">Jadwal Selesai</p>
                            <p class="text-3xl font-bold text-gray-800 mt-2">{{ $jadwalSelesai }}</p>
                        </div>
                        <i data-lucide="check-circle" class="w-12 h-12 text-green-500 opacity-20"></i>
                    </div>
                </div>

                <!-- Jadwal Aktif Hari Ini -->
                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-orange-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-medium">Jadwal Aktif</p>
                            <p class="text-3xl font-bold text-gray-800 mt-2">{{ $jadwalAktif ? 1 : 0 }}</p>
                        </div>
                        <i data-lucide="zap" class="w-12 h-12 text-orange-500 opacity-20"></i>
                    </div>
                </div>

                <!-- Perjalanan Mendatang -->
                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-medium">Perjalanan Mendatang</p>
                            <p class="text-3xl font-bold text-gray-800 mt-2">{{ $jadwalMendatang->count() }}</p>
                        </div>
                        <i data-lucide="arrow-right" class="w-12 h-12 text-purple-500 opacity-20"></i>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Jadwal Aktif -->
                <div class="lg:col-span-2 bg-white rounded-lg shadow">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800">Jadwal Aktif</h3>
                    </div>
                    <div class="p-6">
                        @if ($jadwalAktif)
                            <div class="bg-blue-50 rounded-lg p-6 border border-blue-200">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <p class="text-sm text-gray-600 font-medium">Bus</p>
                                        <p class="text-xl font-bold text-gray-800">{{ $jadwalAktif->bus->nama }}</p>
                                        <p class="text-sm text-gray-600 mt-2">{{ $jadwalAktif->bus->plat_nomor }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600 font-medium">Rute</p>
                                        <p class="text-xl font-bold text-gray-800">{{ $jadwalAktif->rute->asal }} →
                                            {{ $jadwalAktif->rute->tujuan }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600 font-medium">Tanggal Berangkat</p>
                                        <p class="text-lg font-semibold text-gray-800">
                                            {{ $jadwalAktif->tanggal_berangkat->format('d M Y') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600 font-medium">Jam Berangkat</p>
                                        <p class="text-lg font-semibold text-gray-800">
                                            {{ $jadwalAktif->jam_berangkat->format('H:i') }}</p>
                                    </div>
                                </div>
                                <div class="mt-6 flex gap-3">
                                    <a href="{{ route('sopir.jadwal.show', $jadwalAktif) }}"
                                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition text-center">
                                        <i data-lucide="eye" class="w-4 h-4 inline-block mr-2"></i>
                                        Lihat Detail
                                    </a>
                                    <a href="{{ route('sopir.jadwal.show', $jadwalAktif) }}#scan"
                                        class="flex-1 bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg transition text-center">
                                        <i data-lucide="barcode" class="w-4 h-4 inline-block mr-2"></i>
                                        Scan Tiket
                                    </a>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-12">
                                <i data-lucide="inbox" class="w-16 h-16 text-gray-300 mx-auto mb-4"></i>
                                <p class="text-gray-500 font-medium">Tidak ada jadwal aktif saat ini</p>
                                <p class="text-gray-400 text-sm mt-2">Jadwal Anda akan muncul di sini ketika ada rute yang
                                    sedang berlangsung</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Jadwal Mendatang -->
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800">Jadwal Mendatang</h3>
                    </div>
                    <div class="p-6 space-y-4 max-h-96 overflow-y-auto">
                        @forelse ($jadwalMendatang as $jadwal)
                            <div class="pb-4 border-b border-gray-200 last:border-0">
                                <p class="font-semibold text-gray-800 text-sm">
                                    {{ $jadwal->tanggal_berangkat->format('d M Y') }}</p>
                                <p class="text-xs text-gray-600 mt-1">{{ $jadwal->jam_berangkat->format('H:i') }}</p>
                                <p class="text-sm font-medium text-gray-700 mt-2">{{ $jadwal->rute->asal }} →
                                    {{ $jadwal->rute->tujuan }}</p>
                                <p class="text-xs text-gray-500 mt-1">Bus: {{ $jadwal->bus->nama }}</p>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <i data-lucide="calendar-x" class="w-10 h-10 text-gray-300 mx-auto mb-2"></i>
                                <p class="text-gray-500 text-sm">Tidak ada jadwal mendatang</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Info Sopir -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">Informasi Profil</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <p class="text-sm text-gray-600 font-medium">Nama</p>
                            <p class="text-gray-800 font-semibold">{{ Auth::user()->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 font-medium">Email</p>
                            <p class="text-gray-800 font-semibold">{{ Auth::user()->email }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 font-medium">No. SIM</p>
                            <p class="text-gray-800 font-semibold">{{ $sopir->nomor_sim }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 font-medium">NIK</p>
                            <p class="text-gray-800 font-semibold">{{ $sopir->nik }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 font-medium">Telepon</p>
                            <p class="text-gray-800 font-semibold">{{ $sopir->telepon ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 font-medium">Status</p>
                            <div class="mt-1">
                                <span
                                    class="inline-block px-3 py-1 rounded-full text-xs font-semibold {{ $sopir->status === 'aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($sopir->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
