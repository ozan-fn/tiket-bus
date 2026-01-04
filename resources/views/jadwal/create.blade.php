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
                        Tambah Jadwal
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
                            <x-ui.card.title>Informasi Jadwal</x-ui.card.title>
                            <x-ui.card.description>Masukkan detail jadwal keberangkatan yang akan ditambahkan</x-ui.card.description>
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
                    <form method="POST" action="{{ route('admin/jadwal.store') }}" id="jadwalForm">
                        @csrf

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
                                    required
                                    class="w-full rounded-md border border-input bg-background dark:bg-input/30 dark:text-foreground dark:border-border px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring dark:focus:ring-ring/50">
                                    <option value="">Pilih Bus</option>
                                    @foreach($buses as $bus)
                                        <option value="{{ $bus->id }}" {{ old('bus_id') == $bus->id ? 'selected' : '' }}>{{ $bus->nama }}</option>
                                    @endforeach
                                </select>
                                @error('bus_id')
                                    <p class="text-sm text-destructive mt-1 flex items-center gap-1">
                                        <i data-lucide="alert-circle" class="w-4 h-4" ></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Sopir -->
                                <div class="space-y-2">
                                    <x-ui.label for="sopir_id">
                                        <div class="flex items-center gap-2">
                                            <i data-lucide="user" class="w-4 h-4" ></i>
                                            Sopir
                                        </div>
                                        <span class="text-red-500">*</span>
                                    </x-ui.label>
                                    <select
                                        name="sopir_id"
                                        id="sopir_id"
                                        required
                                        class="w-full rounded-md border border-input bg-background dark:bg-input/30 dark:text-foreground dark:border-border px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring dark:focus:ring-ring/50">
                                        <option value="">Pilih Sopir</option>
                                        @foreach($sopirs as $sopir)
                                            <option value="{{ $sopir->id }}" {{ old('sopir_id') == $sopir->id ? 'selected' : '' }}>{{ $sopir->user->name }}</option>
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
                                        class="w-full rounded-md border border-input bg-background dark:bg-input/30 dark:text-foreground dark:border-border px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring dark:focus:ring-ring/50">
                                        <option value="">Pilih Kondektur (Opsional)</option>
                                        @foreach($conductors as $conductor)
                                            <option value="{{ $conductor->id }}" {{ old('conductor_id') == $conductor->id ? 'selected' : '' }}>{{ $conductor->user->name }} - {{ $conductor->nomor_sim }}</option>
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
                                            <i data-lucide="map-pin" class="w-4 h-4" ></i>
                                            Rute
                                        </div>
                                        <span class="text-red-500">*</span>
                                    </x-ui.label>
                                    <select
                                        name="rute_id"
                                        id="rute_id"
                                        required
                                        class="w-full rounded-md border border-input bg-background dark:bg-input/30 dark:text-foreground dark:border-border px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring dark:focus:ring-ring/50">
                                        <option value="">Pilih Rute</option>
                                        @foreach($rutes as $rute)
                                            <option value="{{ $rute->id }}" {{ old('rute_id') == $rute->id ? 'selected' : '' }}>
                                                {{ $rute->asalTerminal->nama_terminal ?? '-' }} → {{ $rute->tujuanTerminal->nama_terminal ?? '-' }}
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
                                        placeholder="Pilih tanggal berangkat..."
                                        :value="old('tanggal_berangkat')"
                                        required
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
                                        placeholder="HH:MM"
                                        :value="old('jam_berangkat')"
                                        required
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
                                        <i data-lucide="check-circle" class="w-4 h-4" ></i>
                                        Status
                                    </div>
                                    <span class="text-red-500">*</span>
                                </x-ui.label>
                                <select
                                    name="status"
                                    id="status"
                                    required
                                    class="w-full rounded-md border border-input bg-background dark:bg-input/30 dark:text-foreground dark:border-border px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring dark:focus:ring-ring/50">
                                    <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="tidak_aktif" {{ old('status') == 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                                </select>
                                @error('status')
                                    <p class="text-sm text-destructive mt-1 flex items-center gap-1">
                                        <i data-lucide="alert-circle" class="w-4 h-4" ></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Recurring -->
                            <div class="space-y-2">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input
                                        type="checkbox"
                                        name="is_recurring"
                                        id="is_recurring"
                                        value="1"
                                        class="w-4 h-4 rounded border-input"
                                        {{ old('is_recurring') ? 'checked' : '' }}
                                    />
                                    <span class="text-sm font-medium">Buat Jadwal Berulang</span>
                                </label>
                            </div>

                            <!-- Recurring Fields -->
                            <div id="recurring-fields" class="hidden space-y-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Jenis Berulang -->
                                    <div class="space-y-2">
                                        <x-ui.label for="recurring_type">
                                            <div class="flex items-center gap-2">
                                                <i data-lucide="repeat" class="w-4 h-4" ></i>
                                                Jenis Berulang
                                            </div>
                                        </x-ui.label>
                                        <select
                                            name="recurring_type"
                                            id="recurring_type"
                                            class="w-full rounded-md border border-input bg-background dark:bg-input/30 dark:text-foreground dark:border-border px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring dark:focus:ring-ring/50">
                                            <option value="daily" {{ old('recurring_type') == 'daily' ? 'selected' : '' }}>Harian</option>
                                            <option value="weekly" {{ old('recurring_type') == 'weekly' ? 'selected' : '' }}>Mingguan</option>
                                        </select>
                                        @error('recurring_type')
                                            <p class="text-sm text-destructive mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Jumlah Hari/Minggu -->
                                    <div class="space-y-2">
                                        <x-ui.label for="recurring_count">
                                            <div class="flex items-center gap-2">
                                                <i data-lucide="hash" class="w-4 h-4" ></i>
                                                Jumlah Hari/Minggu
                                            </div>
                                        </x-ui.label>
                                        <x-ui.input
                                            type="number"
                                            name="recurring_count"
                                            id="recurring_count"
                                            placeholder="Contoh: 7"
                                            min="1"
                                            max="90"
                                            :value="old('recurring_count')"
                                        />
                                        @error('recurring_count')
                                            <p class="text-sm text-destructive mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Harga Tiket Section -->
                        <div class="mt-8 pt-8 border-t border-border">
                            <h3 class="font-semibold mb-4 flex items-center gap-2">
                                <i data-lucide="tag" class="w-4 h-4" ></i>
                                Tentukan Harga Tiket per Kelas (Opsional)
                            </h3>

                            <div id="harga-container" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <p class="text-muted-foreground col-span-2">Pilih bus terlebih dahulu untuk melihat kelas bus yang tersedia.</p>
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
                                Tambah Jadwal
                            </x-ui.button>
                        </div>
                    </form>
                </x-ui.card.content>
            </x-ui.card>
        </div>
    </div>

    @push('scripts')
    <script>
        document.getElementById('is_recurring').addEventListener('change', function () {
            const recurringFields = document.getElementById('recurring-fields');
            if (this.checked) {
                recurringFields.classList.remove('hidden');
            } else {
                recurringFields.classList.add('hidden');
            }
        });

        // Load kelas bus when bus is selected
        document.getElementById('bus_id').addEventListener('change', function () {
            const busId = this.value;
            const container = document.getElementById('harga-container');

            if (!busId) {
                container.innerHTML = '<p class="text-muted-foreground col-span-2">Pilih bus terlebih dahulu untuk melihat kelas bus yang tersedia.</p>';
                return;
            }

            fetch(`/admin/jadwal/get-kelas-by-bus/${busId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.length === 0) {
                        container.innerHTML = '<p class="text-muted-foreground col-span-2">Bus ini belum memiliki kelas bus yang dikonfigurasi.</p>';
                        return;
                    }

                    let html = '';
                    data.forEach(kelasBus => {
                        html += `
                            <div class="space-y-2 p-4 border rounded-lg bg-muted/30">
                                <label for="harga_${kelasBus.id}" class="text-sm font-medium">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                        </svg>
                                        ${kelasBus.nama_kelas}
                                    </div>
                                </label>
                                <div class="flex items-center gap-2">
                                    <span class="text-muted-foreground">Rp</span>
                                    <input
                                        type="number"
                                        name="harga[${kelasBus.id}]"
                                        id="harga_${kelasBus.id}"
                                        placeholder="0"
                                        min="0"
                                        step="1000"
                                        value=""
                                        class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50"
                                    />
                                </div>
                                <p class="text-xs text-muted-foreground">Biarkan kosong jika tidak ingin menambah harga sekarang</p>
                            </div>
                        `;
                    });
                    container.innerHTML = html;
                })
                .catch(error => {
                    console.error('Error loading kelas bus:', error);
                    container.innerHTML = '<p class="text-destructive col-span-2">Error memuat kelas bus.</p>';
                });
        });

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
    </script>
    @endpush

@endsection
