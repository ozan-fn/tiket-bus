@extends('layouts.app')
@section('content')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Pesan Tiket') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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
                    <div class="lg:col-span-2 space-y-8">
                        <x-ui.card.card>
                            <x-ui.card.header>
                                <x-ui.card.title>Pilih Kelas Bus</x-ui.card.title>
                            </x-ui.card.header>
                            <x-ui.card.content>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @forelse($jadwal->jadwalKelasBus as $jkb)
                                        <label class="relative cursor-pointer group">
                                            <input type="radio" name="jadwal_kelas_bus_id" value="{{ $jkb->id }}"
                                                {{ old('jadwal_kelas_bus_id') == $jkb->id ? 'checked' : '' }}
                                                class="sr-only peer" onchange="updateSeatGrid(this)">

                                            <div class="p-4 border-2 rounded-lg transition-all
                                                {{ old('jadwal_kelas_bus_id') == $jkb->id ? 'bg-blue-50 dark:bg-blue-900/20 border-blue-600' : 'bg-gray-50 dark:bg-gray-700 border-gray-300 dark:border-gray-600' }}
                                                hover:border-blue-400">
                                                <h4 class="font-semibold text-gray-900 dark:text-white">
                                                    {{ $jkb->kelasBus->nama_kelas }}
                                                </h4>
                                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                                                    Rp {{ number_format($jkb->harga, 0, ',', '.') }}
                                                </p>
                                                <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                                                    {{ $jkb->busKelasBus->kursi->count() }} kursi tersedia
                                                </p>
                                            </div>
                                        </label>
                                    @empty
                                        <p class="text-gray-500 dark:text-gray-400">Tidak ada kelas bus tersedia</p>
                                    @endforelse
                                </div>

                                @error('jadwal_kelas_bus_id')
                                    <x-input-error :messages="[$message]" class="mt-4" />
                                @enderror
                            </x-ui.card.content>
                        </x-ui.card.card>

                        <x-ui.card.card>
                            <x-ui.card.header>
                                <x-ui.card.title>Pilih Kursi Anda</x-ui.card.title>
                            </x-ui.card.header>
                            <x-ui.card.content class="space-y-6">
                                <div class="flex flex-wrap gap-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 bg-blue-500 rounded border-2 border-blue-600"></div>
                                        <span class="text-sm text-gray-700 dark:text-gray-300">Tersedia</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 bg-red-500 rounded border-2 border-red-600 opacity-50"></div>
                                        <span class="text-sm text-gray-700 dark:text-gray-300">Terpakai</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 bg-green-500 rounded border-2 border-green-600"></div>
                                        <span class="text-sm text-gray-700 dark:text-gray-300">Terpilih</span>
                                    </div>
                                </div>

                                <div id="seat-grid" class="grid gap-4">
                                    <p class="text-gray-500 dark:text-gray-400">Pilih kelas bus terlebih dahulu</p>
                                </div>

                                @error('kursi_id')
                                    <x-input-error :messages="[$message]" />
                                @enderror

                                <div id="selected-seat-info" class="p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg hidden">
                                    <p class="text-sm text-gray-700 dark:text-gray-300">
                                        <span class="font-semibold">Kursi Terpilih:</span>
                                        <span class="text-lg font-bold text-green-600 dark:text-green-400" id="selected-seat-name">-</span>
                                    </p>
                                </div>
                            </x-ui.card.content>
                        </x-ui.card.card>

                        <x-ui.card.card>
                            <x-ui.card.header>
                                <x-ui.card.title>Data Penumpang</x-ui.card.title>
                            </x-ui.card.header>
                            <x-ui.card.content class="space-y-5">
                                <div>
                                    <x-ui.label.label for="nama_penumpang">
                                        Nama Lengkap <span class="text-red-500">*</span>
                                    </x-ui.label.label>
                                    <x-ui.input.input
                                        type="text"
                                        name="nama_penumpang"
                                        id="nama_penumpang"
                                        value="{{ old('nama_penumpang', $user?->name) }}"
                                        required
                                        class="mt-1 w-full"
                                    />
                                    @error('nama_penumpang')
                                        <x-input-error :messages="[$message]" class="mt-1" />
                                    @enderror
                                </div>

                                <div>
                                    <x-ui.label.label for="nik">
                                        NIK <span class="text-red-500">*</span>
                                    </x-ui.label.label>
                                    <x-ui.input.input
                                        type="text"
                                        name="nik"
                                        id="nik"
                                        value="{{ old('nik', $user?->nik) }}"
                                        placeholder="Nomor Identitas (KTP)"
                                        required
                                        class="mt-1 w-full"
                                    />
                                    @error('nik')
                                        <x-input-error :messages="[$message]" class="mt-1" />
                                    @enderror
                                </div>

                                <div>
                                    <x-ui.label.label for="jenis_kelamin">
                                        Jenis Kelamin <span class="text-red-500">*</span>
                                    </x-ui.label.label>
                                    <select
                                        name="jenis_kelamin"
                                        id="jenis_kelamin"
                                        required
                                        class="mt-1 w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500"
                                    >
                                        <option value="">-- Pilih --</option>
                                        <option value="L" {{ old('jenis_kelamin', $user?->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="P" {{ old('jenis_kelamin', $user?->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                    @error('jenis_kelamin')
                                        <x-input-error :messages="[$message]" class="mt-1" />
                                    @enderror
                                </div>

                                <div>
                                    <x-ui.label.label for="tanggal_lahir">
                                        Tanggal Lahir <span class="text-red-500">*</span>
                                    </x-ui.label.label>
                                    <x-ui.input.input
                                        type="date"
                                        name="tanggal_lahir"
                                        id="tanggal_lahir"
                                        value="{{ old('tanggal_lahir', $user?->tanggal_lahir?->format('Y-m-d')) }}"
                                        required
                                        class="mt-1 w-full"
                                    />
                                    @error('tanggal_lahir')
                                        <x-input-error :messages="[$message]" class="mt-1" />
                                    @enderror
                                </div>

                                <div>
                                    <x-ui.label.label for="nomor_telepon">
                                        Nomor Telepon <span class="text-red-500">*</span>
                                    </x-ui.label.label>
                                    <x-ui.input.input
                                        type="tel"
                                        name="nomor_telepon"
                                        id="nomor_telepon"
                                        value="{{ old('nomor_telepon', $user?->nomor_telepon) }}"
                                        placeholder="08..."
                                        required
                                        class="mt-1 w-full"
                                    />
                                    @error('nomor_telepon')
                                        <x-input-error :messages="[$message]" class="mt-1" />
                                    @enderror
                                </div>

                                <div>
                                    <x-ui.label.label for="email">
                                        Email <span class="text-red-500">*</span>
                                    </x-ui.label.label>
                                    <x-ui.input.input
                                        type="email"
                                        name="email"
                                        id="email"
                                        value="{{ old('email', $user?->email) }}"
                                        required
                                        class="mt-1 w-full"
                                    />
                                    @error('email')
                                        <x-input-error :messages="[$message]" class="mt-1" />
                                    @enderror
                                </div>
                            </x-ui.card.content>
                        </x-ui.card.card>
                    </div>

                    <div class="lg:col-span-1">
                        <x-ui.card.card class="sticky top-6">
                            <x-ui.card.header>
                                <x-ui.card.title>Ringkasan Pemesanan</x-ui.card.title>
                            </x-ui.card.header>
                            <x-ui.card.content class="space-y-6">
                                <div class="space-y-4 pb-6 border-b border-gray-200 dark:border-gray-700">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600 dark:text-gray-400">Rute</span>
                                        <span class="font-semibold text-gray-900 dark:text-white text-right">
                                            {{ $jadwal->rute->asalTerminal->nama_kota }} → {{ $jadwal->rute->tujuanTerminal->nama_kota }}
                                        </span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600 dark:text-gray-400">Tanggal</span>
                                        <span class="font-semibold text-gray-900 dark:text-white">
                                            {{ $jadwal->tanggal_berangkat->format('d M Y') }}
                                        </span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600 dark:text-gray-400">Jam</span>
                                        <span class="font-semibold text-gray-900 dark:text-white">
                                            {{ $jadwal->jam_berangkat->format('H:i') }}
                                        </span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600 dark:text-gray-400">Kelas</span>
                                        <span class="font-semibold text-gray-900 dark:text-white" id="summary-class">-</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600 dark:text-gray-400">Kursi</span>
                                        <span class="font-semibold text-gray-900 dark:text-white" id="summary-seat">-</span>
                                    </div>
                                </div>

                                <div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Total Harga</p>
                                    <p class="text-3xl font-bold text-blue-600 dark:text-blue-400" id="summary-price">Rp 0</p>
                                </div>

                                <div class="space-y-3">
                                    <x-ui.button.button type="submit" variant="default" class="w-full" id="btn-submit" disabled>
                                        <i data-lucide="check-circle" class="w-5 h-5 mr-2"></i>
                                        Lanjut Bayar
                                    </x-ui.button.button>
                                    <x-ui.button.button type="button" variant="outline" class="w-full" onclick="window.location.href='{{ route('pemesanan.index') }}'">
                                        <i data-lucide="arrow-left" class="w-5 h-5 mr-2"></i>
                                        Kembali
                                    </x-ui.button.button>
                                </div>
                            </x-ui.card.content>
                        </x-ui.card.card>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <script>
        lucide.createIcons();

        // Data jadwal dengan kursi
        const seatsData = {!! json_encode($jadwal->jadwalKelasBus->mapWithKeys(function($jkb) {
            return [$jkb->id => [
                'class' => $jkb->kelasBus->nama_kelas,
                'harga' => $jkb->harga,
                'kursis' => $jkb->busKelasBus->kursi->map(fn($k) => ['id' => $k->id, 'nomor' => $k->nomor_kursi])
            ]];
        })) !!};
        
        const bookedIds = {{ json_encode($bookedSeatIds) }};

        function sortSeats(kursis) {
            return kursis.sort((a, b) => a.nomor.localeCompare(b.nomor));
        }

        function updateSeatGrid(element) {
            const jkbId = element.value;
            const data = seatsData[jkbId];
            const grid = document.getElementById('seat-grid');
            
            // Update summary class & price
            document.getElementById('summary-class').textContent = data.class;
            document.getElementById('summary-price').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(data.harga);

            // Sort seats by nomor (A-Z)
            const sortedKursis = sortSeats(data.kursis);

            // Render seat grid dengan layout 2-gap-2
            let html = '';
            for (let i = 0; i < sortedKursis.length; i += 4) {
                const kiri = [sortedKursis[i], sortedKursis[i + 1]];
                const kanan = [sortedKursis[i + 2], sortedKursis[i + 3]];
                
                html += '<div class="flex items-center justify-between gap-8 mb-4">';
                
                // Kursi kiri (2)
                html += '<div class="flex gap-3">';
                kiri.forEach(k => {
                    if (!k) return;
                    const isBooked = bookedIds.includes(k.id);
                    const isSelected = {{ json_encode(old('kursi_id')) }} == k.id;
                    
                    html += `
                        <label class="cursor-pointer">
                            <input type="radio" name="kursi_id" value="${k.id}" 
                                ${isSelected ? 'checked' : ''} 
                                ${isBooked ? 'disabled' : ''}
                                class="sr-only peer" onchange="updateSeatInfo('${k.nomor}')">
                            
                            <div class="w-12 h-12 rounded border-2 transition-all flex items-center justify-center text-white font-semibold text-xs
                                ${isBooked ? 'bg-red-500 border-red-600 opacity-50 cursor-not-allowed' : ''}
                                ${!isBooked && !isSelected ? 'bg-blue-500 border-blue-600 hover:bg-blue-600' : ''}
                                ${isSelected ? 'bg-green-500 border-green-600' : ''}">
                                ${k.nomor}
                            </div>
                        </label>
                    `;
                });
                html += '</div>';
                
                // Gap tengah (simulasi pintu/gang)
                html += '<div class="border-l-2 border-gray-400 dark:border-gray-500 h-12"></div>';
                
                // Kursi kanan (2)
                html += '<div class="flex gap-3">';
                kanan.forEach(k => {
                    if (!k) return;
                    const isBooked = bookedIds.includes(k.id);
                    const isSelected = {{ json_encode(old('kursi_id')) }} == k.id;
                    
                    html += `
                        <label class="cursor-pointer">
                            <input type="radio" name="kursi_id" value="${k.id}" 
                                ${isSelected ? 'checked' : ''} 
                                ${isBooked ? 'disabled' : ''}
                                class="sr-only peer" onchange="updateSeatInfo('${k.nomor}')">
                            
                            <div class="w-12 h-12 rounded border-2 transition-all flex items-center justify-center text-white font-semibold text-xs
                                ${isBooked ? 'bg-red-500 border-red-600 opacity-50 cursor-not-allowed' : ''}
                                ${!isBooked && !isSelected ? 'bg-blue-500 border-blue-600 hover:bg-blue-600' : ''}
                                ${isSelected ? 'bg-green-500 border-green-600' : ''}">
                                ${k.nomor}
                            </div>
                        </label>
                    `;
                });
                html += '</div></div>';
            }
            
            grid.innerHTML = html;
            lucide.createIcons();
            // Reset summary seat text
            document.getElementById('summary-seat').textContent = '-'; 
            document.getElementById('btn-submit').disabled = true;
            
            // Re-check old seat if exists
            const oldSeatId = {{ json_encode(old('kursi_id')) }};
            if(oldSeatId) {
                const oldInput = document.querySelector(`input[name="kursi_id"][value="${oldSeatId}"]`);
                if(oldInput) {
                    oldInput.checked = true;
                    // Trigger change manually or update info directly
                     // Find seat number for old id
                     const seat = sortedKursis.find(s => s.id == oldSeatId);
                     if(seat) updateSeatInfo(seat.nomor);
                     document.getElementById('btn-submit').disabled = false;
                }
            }
        }

        function updateSeatInfo(seatNo) {
            document.getElementById('summary-seat').textContent = seatNo;
            document.getElementById('btn-submit').disabled = false;
        }

        // Auto-load if class already selected
        const checked = document.querySelector('input[name="jadwal_kelas_bus_id"]:checked');
        if (checked) updateSeatGrid(checked);

        // Event delegation for dynamically added inputs
        document.getElementById('seat-grid').addEventListener('change', function(e) {
             if (e.target && e.target.name === 'kursi_id') {
                 // Logic handled in updateSeatInfo inline call, but we ensure button state here too if needed
                 document.getElementById('btn-submit').disabled = false;
             }
        });
    </script>
@endsection
