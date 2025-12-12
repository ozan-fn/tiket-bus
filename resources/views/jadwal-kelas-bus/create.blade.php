<x-admin-layout>
    <x-slot name="header">
        <x-ui.breadcrumb.breadcrumb>
            <x-ui.breadcrumb.list class="text-xs">
                <x-ui.breadcrumb.item>
                    <x-ui.breadcrumb.link href="{{ route('dashboard') }}">
                        Home
                    </x-ui.breadcrumb.link>
                </x-ui.breadcrumb.item>
                <x-ui.breadcrumb.separator>
                    <x-lucide-chevron-right class="w-3.5 h-3.5" />
                </x-ui.breadcrumb.separator>
                <x-ui.breadcrumb.item>
                    <x-ui.breadcrumb.link href="{{ route('admin/jadwal-kelas-bus.index') }}">
                        Jadwal Kelas Bus
                    </x-ui.breadcrumb.link>
                </x-ui.breadcrumb.item>
                <x-ui.breadcrumb.separator>
                    <x-lucide-chevron-right class="w-3.5 h-3.5" />
                </x-ui.breadcrumb.separator>
                <x-ui.breadcrumb.item>
                    <x-ui.breadcrumb.page>
                        Tambah Jadwal Kelas Bus
                    </x-ui.breadcrumb.page>
                </x-ui.breadcrumb.item>
            </x-ui.breadcrumb.list>
        </x-ui.breadcrumb.breadcrumb>
    </x-slot>

    <div class="p-4 sm:p-6">
        <div class="max-w-4xl mx-auto">
            <x-ui.card>
                <x-ui.card.header>
                    <div class="flex items-center justify-between">
                        <div>
                            <x-ui.card.title>Informasi Jadwal Kelas Bus</x-ui.card.title>
                            <x-ui.card.description>Masukkan detail jadwal kelas bus yang akan ditambahkan</x-ui.card.description>
                        </div>
                        <a href="{{ route('admin/jadwal-kelas-bus.index') }}">
                            <x-ui.button variant="outline" size="sm">
                                <x-lucide-arrow-left class="w-4 h-4 mr-2" />
                                Kembali
                            </x-ui.button>
                        </a>
                    </div>
                </x-ui.card.header>
                <x-ui.card.content>
                    <form method="POST" action="{{ route('admin/jadwal-kelas-bus.store') }}" id="jadwalKelasBusForm">
                        @csrf

                        <div class="space-y-6">
                            <!-- Jadwal -->
                            <div class="space-y-2">
                                <x-ui.label for="jadwal_id">
                                    <div class="flex items-center gap-2">
                                        <x-lucide-calendar-clock class="w-4 h-4" />
                                        Jadwal
                                    </div>
                                    <span class="text-red-500">*</span>
                                </x-ui.label>
                                <select
                                    name="jadwal_id"
                                    id="jadwal_id"
                                    required
                                    class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                                    <option value="">Pilih Jadwal</option>
                                    @foreach($jadwals as $jadwal)
                                        <option value="{{ $jadwal->id }}" {{ old('jadwal_id') == $jadwal->id ? 'selected' : '' }}>
                                            {{ $jadwal->rute->asalTerminal->nama_terminal ?? '-' }} → {{ $jadwal->rute->tujuanTerminal->nama_terminal ?? '-' }} | {{ $jadwal->bus->nama }} ({{ $jadwal->bus->plat_nomor }}) | {{ \Carbon\Carbon::parse($jadwal->tanggal_berangkat)->format('d/m/Y') }} {{ \Carbon\Carbon::parse($jadwal->jam_berangkat)->format('H:i') }}
                                        </option>
                                    @endforeach
                                </select>
                                <p class="text-xs text-muted-foreground flex items-center gap-1">
                                    <x-lucide-info class="w-3 h-3" />
                                    Pilih jadwal yang akan dikaitkan dengan kelas bus
                                </p>
                                @error('jadwal_id')
                                    <p class="text-sm text-destructive mt-1 flex items-center gap-1">
                                        <x-lucide-alert-circle class="w-4 h-4" />
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Kelas Bus -->
                                <div class="space-y-2">
                                    <x-ui.label for="kelas_bus_id">
                                        <div class="flex items-center gap-2">
                                            <x-lucide-layers class="w-4 h-4" />
                                            Kelas Bus
                                        </div>
                                        <span class="text-red-500">*</span>
                                    </x-ui.label>
                                    <select
                                        name="kelas_bus_id"
                                        id="kelas_bus_id"
                                        required
                                        class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                                        <option value="">Pilih Kelas Bus</option>
                                        @foreach($kelasBuses as $kelas)
                                            <option value="{{ $kelas->id }}" {{ old('kelas_bus_id') == $kelas->id ? 'selected' : '' }}>
                                                {{ $kelas->nama_kelas }} - {{ $kelas->bus->nama }} ({{ $kelas->jumlah_kursi }} kursi)
                                            </option>
                                        @endforeach
                                    </select>
                                    <p class="text-xs text-muted-foreground flex items-center gap-1">
                                        <x-lucide-info class="w-3 h-3" />
                                        Pilih kelas bus yang tersedia
                                    </p>
                                    @error('kelas_bus_id')
                                        <p class="text-sm text-destructive mt-1 flex items-center gap-1">
                                            <x-lucide-alert-circle class="w-4 h-4" />
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- Harga -->
                                <div class="space-y-2">
                                    <x-ui.label for="harga">
                                        <div class="flex items-center gap-2">
                                            <x-lucide-banknote class="w-4 h-4" />
                                            Harga
                                        </div>
                                        <span class="text-red-500">*</span>
                                    </x-ui.label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-muted-foreground">Rp</span>
                                        <x-ui.input
                                            type="number"
                                            id="harga"
                                            name="harga"
                                            value="{{ old('harga') }}"
                                            placeholder="0"
                                            min="0"
                                            step="1000"
                                            required
                                            class="pl-9"
                                        />
                                    </div>
                                    <p class="text-xs text-muted-foreground flex items-center gap-1">
                                        <x-lucide-info class="w-3 h-3" />
                                        Masukkan harga tiket untuk kelas bus ini
                                    </p>
                                    @error('harga')
                                        <p class="text-sm text-destructive mt-1 flex items-center gap-1">
                                            <x-lucide-alert-circle class="w-4 h-4" />
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col-reverse sm:flex-row items-center justify-end gap-3 mt-8 pt-6 border-t border-border">
                            <a href="{{ route('admin/jadwal-kelas-bus.index') }}" class="w-full sm:w-auto">
                                <x-ui.button type="button" variant="outline" class="w-full sm:w-auto">
                                    <x-lucide-x class="w-4 h-4 mr-2" />
                                    Batal
                                </x-ui.button>
                            </a>
                            <x-ui.button type="submit" class="w-full sm:w-auto">
                                <x-lucide-save class="w-4 h-4 mr-2" />
                                Tambah Jadwal Kelas Bus
                            </x-ui.button>
                        </div>
                    </form>
                </x-ui.card.content>
            </x-ui.card>
        </div>
    </div>
</x-admin-layout>
