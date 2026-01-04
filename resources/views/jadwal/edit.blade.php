@extends('layouts.admin')
@section('content')
    @push('header')
        <x-ui.breadcrumb.breadcrumb>
            <x-ui.breadcrumb.list class="text-xs">
                <x-ui.breadcrumb.item>
                    <x-ui.breadcrumb.link href="{{ route('dashboard') }}">
                        Home
                    </x-ui.breadcrumb.link>
                </x-ui.breadcrumb.item>
                <x-ui.breadcrumb.separator>
                    <i data-lucide="chevron-right" class="w-3.5 h-3.5" ></i>
                </x-ui.breadcrumb.separator>
                <x-ui.breadcrumb.item>
                    <x-ui.breadcrumb.link href="{{ route('admin/jadwal.index') }}">
                        Jadwal
                    </x-ui.breadcrumb.link>
                </x-ui.breadcrumb.item>
                <x-ui.breadcrumb.separator>
                    <i data-lucide="chevron-right" class="w-3.5 h-3.5" ></i>
                </x-ui.breadcrumb.separator>
                <x-ui.breadcrumb.item>
                    <x-ui.breadcrumb.page>
                        Edit Jadwal
                    </x-ui.breadcrumb.page>
                </x-ui.breadcrumb.item>
            </x-ui.breadcrumb.list>
        </x-ui.breadcrumb.breadcrumb>
@endpush

    <div class="p-4 sm:p-6">
        <div class="max-w-4xl mx-auto">
            <x-ui.card>
                <x-ui.card.header>
                    <div class="flex items-center justify-between">
                        <div>
                            <x-ui.card.title>Edit Jadwal</x-ui.card.title>
                            <x-ui.card.description>Perbarui informasi jadwal perjalanan</x-ui.card.description>
                        </div>
                        <a href="{{ route('admin/jadwal.index') }}">
                            <x-ui.button variant="outline" size="sm">
                                <i data-lucide="arrow-left" class="w-4 h-4 mr-2" ></i>
                                Kembali
                            </x-ui.button>
                        </a>
                    </div>
                </x-ui.card.header>
                <x-ui.card.content>
                    <form method="POST" action="{{ route('admin/jadwal.update', $jadwal) }}">
                        @csrf
                        @method('PUT')

                        <div class="space-y-6">
                            <!-- Bus -->
                            <div class="space-y-2">
                                <x-ui.label for="bus_id">
                                    <div class="flex items-center gap-2">
                                        <i data-lucide="bus" class="w-4 h-4" ></i>
                                        Bus
                                    </div>
                                    <span class="text-red-500">*</span>
                                </x-ui.label>
                                <select
                                    name="bus_id"
                                    id="bus_id"
                                    class="w-full rounded-md border border-input bg-background dark:bg-input/30 dark:text-foreground dark:border-border px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring dark:focus:ring-ring/50 @error('bus_id') border-red-500 @enderror"
                                    required
                                >
                                    <option value="">Pilih Bus</option>
                                    @foreach($buses as $bus)
                                        <option value="{{ $bus->id }}" {{ old('bus_id', $jadwal->bus_id) == $bus->id ? 'selected' : '' }}>
                                            {{ $bus->nama }} - {{ $bus->plat_nomor }} (Kapasitas: {{ $bus->kapasitas }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('bus_id')
                                    <p class="text-sm text-destructive mt-1 flex items-center gap-1">
                                        <i data-lucide="alert-circle" class="w-4 h-4" ></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Sopir -->
                            <div class="space-y-2">
                                <x-ui.label for="sopir_id">
                                    <div class="flex items-center gap-2">
                                        <i data-lucide="user-round" class="w-4 h-4" ></i>
                                        Sopir
                                    </div>
                                    <span class="text-red-500">*</span>
                                </x-ui.label>
                                <select
                                    name="sopir_id"
                                    id="sopir_id"
                                    class="w-full rounded-md border border-input bg-background dark:bg-input/30 dark:text-foreground dark:border-border px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring dark:focus:ring-ring/50 @error('sopir_id') border-red-500 @enderror"
                                    required
                                >
                                    <option value="">Pilih Sopir</option>
                                    @foreach($sopirs as $sopir)
                                        <option value="{{ $sopir->id }}" {{ old('sopir_id', $jadwal->sopir_id) == $sopir->id ? 'selected' : '' }}>
                                            {{ $sopir->user->name ?? 'N/A' }} - {{ $sopir->nomor_sim }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('sopir_id')
                                    <p class="text-sm text-destructive mt-1 flex items-center gap-1">
                                        <i data-lucide="alert-circle" class="w-4 h-4" ></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Kondektur -->
                            <div class="space-y-2">
                                <x-ui.label for="conductor_id">
                                    <div class="flex items-center gap-2">
                                        <i data-lucide="user-round" class="w-4 h-4" ></i>
                                        Kondektur (Opsional)
                                    </div>
                                </x-ui.label>
                                <select
                                    name="conductor_id"
                                    id="conductor_id"
                                    class="w-full rounded-md border border-input bg-background dark:bg-input/30 dark:text-foreground dark:border-border px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring dark:focus:ring-ring/50 @error('conductor_id') border-red-500 @enderror"
                                >
                                    <option value="">Pilih Kondektur (Opsional)</option>
                                    @foreach($conductors as $conductor)
                                        <option value="{{ $conductor->id }}" {{ old('conductor_id', $jadwal->conductor_id) == $conductor->id ? 'selected' : '' }}>
                                            {{ $conductor->user->name ?? 'N/A' }} - {{ $conductor->nomor_sim }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('conductor_id')
                                    <p class="text-sm text-destructive mt-1 flex items-center gap-1">
                                        <i data-lucide="alert-circle" class="w-4 h-4" ></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Rute -->
                            <div class="space-y-2">
                                <x-ui.label for="rute_id">
                                    <div class="flex items-center gap-2">
                                        <i data-lucide="route" class="w-4 h-4" ></i>
                                        Rute Perjalanan
                                    </div>
                                    <span class="text-red-500">*</span>
                                </x-ui.label>
                                <select
                                    name="rute_id"
                                    id="rute_id"
                                    class="w-full rounded-md border border-input bg-background dark:bg-input/30 dark:text-foreground dark:border-border px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring dark:focus:ring-ring/50 @error('rute_id') border-red-500 @enderror"
                                    required
                                >
                                    <option value="">Pilih Rute</option>
                                    @foreach($rutes as $rute)
                                        <option value="{{ $rute->id }}" {{ old('rute_id', $jadwal->rute_id) == $rute->id ? 'selected' : '' }}>
                                            {{ $rute->asalTerminal->nama_kota ?? '-' }} → {{ $rute->tujuanTerminal->nama_kota ?? '-' }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('rute_id')
                                    <p class="text-sm text-destructive mt-1 flex items-center gap-1">
                                        <i data-lucide="alert-circle" class="w-4 h-4" ></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Tanggal Berangkat -->
                                <div class="space-y-2">
                                    <x-ui.label for="tanggal_berangkat">
                                        <div class="flex items-center gap-2">
                                            <i data-lucide="calendar" class="w-4 h-4" ></i>
                                            Tanggal Berangkat
                                        </div>
                                        <span class="text-red-500">*</span>
                                    </x-ui.label>
                                    <x-datepicker
                                        name="tanggal_berangkat"
                                        id="tanggal_berangkat"
                                        value="{{ old('tanggal_berangkat', $jadwal->tanggal_berangkat->format('Y-m-d')) }}"
                                        placeholder="Pilih tanggal berangkat..."
                                        required
                                        class="mt-1"
                                    />
                                    @error('tanggal_berangkat')
                                        <p class="text-sm text-destructive mt-1 flex items-center gap-1">
                                            <i data-lucide="alert-circle" class="w-4 h-4" ></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- Jam Berangkat -->
                                <div class="space-y-2">
                                    <x-ui.label for="jam_berangkat">
                                        <div class="flex items-center gap-2">
                                            <i data-lucide="clock" class="w-4 h-4" ></i>
                                            Jam Berangkat
                                        </div>
                                        <span class="text-red-500">*</span>
                                    </x-ui.label>
                                    <x-timepicker
                                        name="jam_berangkat"
                                        id="jam_berangkat"
                                        value="{{ old('jam_berangkat', $jadwal->jam_berangkat->format('H:i')) }}"
                                        placeholder="HH:MM"
                                        required
                                        class="mt-1"
                                    />
                                    @error('jam_berangkat')
                                        <p class="text-sm text-destructive mt-1 flex items-center gap-1">
                                            <i data-lucide="alert-circle" class="w-4 h-4" ></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="space-y-2">
                                <x-ui.label for="status">
                                    <div class="flex items-center gap-2">
                                        <i data-lucide="power" class="w-4 h-4" ></i>
                                        Status
                                    </div>
                                    <span class="text-red-500">*</span>
                                </x-ui.label>
                                <select
                                    name="status"
                                    id="status"
                                    class="w-full rounded-md border border-input bg-background dark:bg-input/30 dark:text-foreground dark:border-border px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring dark:focus:ring-ring/50 @error('status') border-red-500 @enderror"
                                    required
                                >
                                    <option value="aktif" {{ old('status', $jadwal->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="tidak_aktif" {{ old('status', $jadwal->status) == 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                                </select>
                                @error('status')
                                    <p class="text-sm text-destructive mt-1 flex items-center gap-1">
                                        <i data-lucide="alert-circle" class="w-4 h-4" ></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                                <p class="text-xs text-muted-foreground flex items-center gap-1">
                                    <i data-lucide="info" class="w-3 h-3" ></i>
                                    Status aktif akan menampilkan jadwal ini pada sistem pemesanan
                                </p>
                            </div>
                        </div>

                        <!-- Harga Tiket Section -->
                        <div class="mt-8 pt-8 border-t border-border">
                            <h3 class="font-semibold mb-4 flex items-center gap-2">
                                <i data-lucide="tag" class="w-4 h-4" ></i>
                                Kelola Harga Tiket per Kelas
                            </h3>

                            <div id="harga-container-edit" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <p class="text-muted-foreground col-span-2">Memuat kelas bus...</p>
                            </div>
                        </div>

                        <div class="flex flex-col-reverse sm:flex-row items-center justify-end gap-3 mt-8 pt-6 border-t border-border">
                            <a href="{{ route('admin/jadwal.index') }}" class="w-full sm:w-auto">
                                <x-ui.button type="button" variant="outline" class="w-full sm:w-auto">
                                    <i data-lucide="x" class="w-4 h-4 mr-2" ></i>
                                    Batal
                                </x-ui.button>
                            </a>
                            <x-ui.button type="submit" class="w-full sm:w-auto">
                                <i data-lucide="save" class="w-4 h-4 mr-2" ></i>
                                Update Jadwal
                            </x-ui.button>
                        </div>
                    </form>
                </x-ui.card.content>
            </x-ui.card>
        </div>
    </div>

    @push('scripts')
    <script>
        // Tom Select for dropdowns
        new TomSelect('#bus_id', {
            placeholder: 'Pilih Bus',
            allowEmptyOption: true,
            create: false
        });

        new TomSelect('#sopir_id', {
            placeholder: 'Pilih Sopir',
            allowEmptyOption: true,
            create: false
        });

        new TomSelect('#conductor_id', {
            placeholder: 'Pilih Kondektur',
            allowEmptyOption: true,
            create: false
        });

        new TomSelect('#rute_id', {
            placeholder: 'Pilih Rute',
            allowEmptyOption: true,
            create: false
        });

        new TomSelect('#status', {
            placeholder: 'Pilih Status',
            allowEmptyOption: false,
            create: false
        });

        // Load kelas bus for edit
        function loadKelasBusForEdit(busId) {
            const container = document.getElementById('harga-container-edit');

            if (!busId) {
                container.innerHTML = '<p class="text-muted-foreground col-span-2">Pilih bus terlebih dahulu untuk melihat kelas bus yang tersedia.</p>';
                return;
            }

            // var jadwalid =  {{ $jadwal->id }}
            fetch(`/admin/jadwal/get-kelas-by-bus/${busId}?jadwal_id=${ {{ $jadwal->id }} }`)
                .then(response => {
                    console.log('Response status:', response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('Fetched data:', data);
                    if (data.length === 0) {
                        container.innerHTML = '<p class="text-muted-foreground col-span-2">Bus ini belum memiliki kelas bus yang dikonfigurasi.</p>';
                        return;
                    }

                    let html = '';
                    data.forEach(kelasBus => {
                        const hasPrice = kelasBus.harga !== null && kelasBus.harga !== '';
                        html += `
                            <div class="space-y-2 p-4 border rounded-lg bg-muted/30">
                                <div class="flex items-center justify-between">
                                    <label for="harga_${kelasBus.id}" class="text-sm font-medium">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                            </svg>
                                            ${kelasBus.nama_kelas}
                                        </div>
                                    </label>
                                    ${hasPrice ? '<span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded">Sudah Ada</span>' : ''}
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-muted-foreground">Rp</span>
                                    <input
                                        type="number"
                                        name="harga[${kelasBus.id}]"
                                        id="harga_${kelasBus.id}"
                                        placeholder="0"
                                        min="0"
                                        step="1000"
                                        value="${kelasBus.harga || ''}"
                                        class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50"
                                    />
                                </div>
                                <p class="text-xs text-muted-foreground">${hasPrice ? `Harga saat ini: Rp ${new Intl.NumberFormat('id-ID').format(kelasBus.harga)}` : 'Belum ada harga untuk kelas ini'}</p>
                            </div>
                        `;
                    });
                    container.innerHTML = html;
                })
                .catch(error => {
                    console.error('Error loading kelas bus:', error);
                    container.innerHTML = '<p class="text-destructive col-span-2">Error memuat kelas bus.</p>';
                });
        }

        // Initial load
        document.addEventListener('DOMContentLoaded', function() {
            const initialBusId = '{{ $jadwal->bus_id }}';
            loadKelasBusForEdit(initialBusId);
        });

        // Load on bus change
        document.getElementById('bus_id').addEventListener('change', function () {
            const busId = this.value;
            loadKelasBusForEdit(busId);
        });
    </script>
    @endpush

@endsection
