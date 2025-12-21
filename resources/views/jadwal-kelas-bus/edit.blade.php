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
                        Edit Jadwal Kelas Bus
                    </x-ui.breadcrumb.page>
                </x-ui.breadcrumb.item>
            </x-ui.breadcrumb.list>
        </x-ui.breadcrumb.breadcrumb>
    </x-slot>

    <div class="p-4 sm:p-6">
        <div class="max-w-4xl mx-auto">
            @if(session('error'))
                <x-ui.alert variant="destructive" class="mb-6">
                    <x-slot:icon>
                        <x-lucide-alert-circle class="w-4 h-4" />
                    </x-slot:icon>
                    <x-slot:title>Error!</x-slot:title>
                    <x-slot:description>
                        {{ session('error') }}
                    </x-slot:description>
                </x-ui.alert>
            @endif

            <x-ui.card>
                <x-ui.card.header>
                    <div class="flex items-center justify-between">
                        <div>
                            <x-ui.card.title>Edit Jadwal Kelas Bus</x-ui.card.title>
                            <x-ui.card.description>Perbarui informasi jadwal kelas bus</x-ui.card.description>
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
                    <form method="POST" action="{{ route('admin/jadwal-kelas-bus.update', $jadwalKelasBu) }}">
                        @csrf
                        @method('PUT')

                        <div class="space-y-6">
                            <!-- Jadwal -->
                            <div class="space-y-2">
                                <x-ui.label for="jadwal_id">
                                    <div class="flex items-center gap-2">
                                        <x-lucide-calendar class="w-4 h-4" />
                                        Jadwal
                                    </div>
                                    <span class="text-red-500">*</span>
                                </x-ui.label>
                                <select
                                    name="jadwal_id"
                                    id="jadwal_id"
                                    class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring @error('jadwal_id') border-red-500 @enderror"
                                    required
                                >
                                    <option value="">Pilih Jadwal</option>
                                    @foreach($jadwals as $jadwal)
                                        <option value="{{ $jadwal->id }}" {{ old('jadwal_id', $jadwalKelasBu->jadwal_id) == $jadwal->id ? 'selected' : '' }}>
                                            {{ $jadwal->rute->asalTerminal->nama_kota ?? '-' }} → {{ $jadwal->rute->tujuanTerminal->nama_kota ?? '-' }} |
                                            {{ $jadwal->bus->nama }} ({{ $jadwal->bus->plat_nomor }}) |
                                            {{ $jadwal->tanggal_berangkat->format('d/m/Y') }} {{ $jadwal->jam_berangkat->format('H:i') }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('jadwal_id')
                                    <p class="text-sm text-destructive mt-1 flex items-center gap-1">
                                        <x-lucide-alert-circle class="w-4 h-4" />
                                        {{ $message }}
                                    </p>
                                @enderror
                                <p class="text-xs text-muted-foreground flex items-center gap-1">
                                    <x-lucide-info class="w-3 h-3" />
                                    Pilih jadwal perjalanan yang akan digunakan
                                </p>
                            </div>

                            <!-- Bus Kelas Bus -->
                            <div class="space-y-2">
                                <x-ui.label for="bus_kelas_bus_id">
                                    <div class="flex items-center gap-2">
                                        <x-lucide-armchair class="w-4 h-4" />
                                        Kelas Bus
                                    </div>
                                    <span class="text-red-500">*</span>
                                </x-ui.label>
                                <select
                                    name="bus_kelas_bus_id"
                                    id="bus_kelas_bus_id"
                                    class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring @error('bus_kelas_bus_id') border-red-500 @enderror"
                                    required
                                >
                                    <option value="">Pilih Kelas Bus</option>
                                    @foreach($busKelasBus as $item)
                                        <option value="{{ $item->id }}" {{ old('bus_kelas_bus_id', $jadwalKelasBu->bus_kelas_bus_id) == $item->id ? 'selected' : '' }}>
                                            {{ $item->bus->nama }} - {{ $item->kelasBus->nama_kelas }} ({{ $item->kelasBus->jumlah_kursi }} kursi)
                                        </option>
                                    @endforeach
                                </select>
                                @error('bus_kelas_bus_id')
                                    <p class="text-sm text-destructive mt-1 flex items-center gap-1">
                                        <x-lucide-alert-circle class="w-4 h-4" />
                                        {{ $message }}
                                    </p>
                                @enderror
                                <p class="text-xs text-muted-foreground flex items-center gap-1">
                                    <x-lucide-info class="w-3 h-3" />
                                    Pilih kelas bus yang tersedia
                                </p>
                            </div>

                            <!-- Harga -->
                            <div class="space-y-2">
                                <x-ui.label for="harga">
                                    <div class="flex items-center gap-2">
                                        <x-lucide-banknote class="w-4 h-4" />
                                        Harga Tiket
                                    </div>
                                    <span class="text-red-500">*</span>
                                </x-ui.label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-muted-foreground">Rp</span>
                                    <x-ui.input
                                        type="number"
                                        id="harga"
                                        name="harga"
                                        value="{{ old('harga', $jadwalKelasBu->harga) }}"
                                        placeholder="0"
                                        min="0"
                                        step="1000"
                                        required
                                        class="pl-10"
                                    />
                                </div>
                                @error('harga')
                                    <p class="text-sm text-destructive mt-1 flex items-center gap-1">
                                        <x-lucide-alert-circle class="w-4 h-4" />
                                        {{ $message }}
                                    </p>
                                @enderror
                                <p class="text-xs text-muted-foreground flex items-center gap-1">
                                    <x-lucide-info class="w-3 h-3" />
                                    Harga tiket untuk kelas bus ini
                                </p>
                            </div>

                            <!-- Info Box -->
                            <div class="p-4 rounded-lg border border-border bg-muted/50">
                                <div class="flex items-center gap-2 mb-3">
                                    <x-lucide-info class="w-5 h-5 text-primary" />
                                    <p class="text-sm font-medium">Informasi Penting</p>
                                </div>
                                <ul class="space-y-2 text-sm text-muted-foreground">
                                    <li class="flex items-start gap-2">
                                        <x-lucide-check class="w-4 h-4 text-green-600 mt-0.5 shrink-0" />
                                        <span>Pastikan kombinasi jadwal dan kelas bus belum ada</span>
                                    </li>
                                    <li class="flex items-start gap-2">
                                        <x-lucide-check class="w-4 h-4 text-green-600 mt-0.5 shrink-0" />
                                        <span>Harga tiket dapat disesuaikan dengan kelas bus</span>
                                    </li>
                                    <li class="flex items-start gap-2">
                                        <x-lucide-alert-triangle class="w-4 h-4 text-orange-600 mt-0.5 shrink-0" />
                                        <span>Tidak dapat mengubah jika sudah ada tiket terjual</span>
                                    </li>
                                </ul>
                            </div>

                            <!-- Preview -->
                            <div class="p-4 rounded-lg border border-primary/20 bg-primary/5" id="preview-box" style="display: none;">
                                <div class="flex items-center gap-2 mb-3">
                                    <x-lucide-eye class="w-5 h-5 text-primary" />
                                    <p class="text-sm font-medium">Preview</p>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-sm">
                                    <div>
                                        <p class="text-muted-foreground text-xs">Jadwal</p>
                                        <p class="font-medium" id="preview-jadwal">-</p>
                                    </div>
                                    <div>
                                        <p class="text-muted-foreground text-xs">Kelas</p>
                                        <p class="font-medium" id="preview-kelas">-</p>
                                    </div>
                                    <div>
                                        <p class="text-muted-foreground text-xs">Harga</p>
                                        <p class="font-medium text-primary" id="preview-harga">Rp 0</p>
                                    </div>
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
                                Update Jadwal Kelas Bus
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
        const jadwalSelect = new TomSelect('#jadwal_id', {
            placeholder: 'Pilih Jadwal',
            allowEmptyOption: true,
            create: false
        });

        const kelasSelect = new TomSelect('#bus_kelas_bus_id', {
            placeholder: 'Pilih Kelas Bus',
            allowEmptyOption: true,
            create: false
        });

        // Preview functionality
        const previewBox = document.getElementById('preview-box');
        const previewJadwal = document.getElementById('preview-jadwal');
        const previewKelas = document.getElementById('preview-kelas');
        const previewHarga = document.getElementById('preview-harga');
        const hargaInput = document.getElementById('harga');

        function updatePreview() {
            const jadwalValue = jadwalSelect.getValue();
            const kelasValue = kelasSelect.getValue();
            const hargaValue = hargaInput.value;

            if (jadwalValue || kelasValue || hargaValue) {
                previewBox.style.display = 'block';

                // Update jadwal preview
                if (jadwalValue) {
                    const jadwalText = jadwalSelect.options[jadwalValue]?.text || '-';
                    previewJadwal.textContent = jadwalText.substring(0, 40) + '...';
                } else {
                    previewJadwal.textContent = '-';
                }

                // Update kelas preview
                if (kelasValue) {
                    const kelasText = kelasSelect.options[kelasValue]?.text || '-';
                    previewKelas.textContent = kelasText;
                } else {
                    previewKelas.textContent = '-';
                }

                // Update harga preview
                if (hargaValue) {
                    const formatted = new Intl.NumberFormat('id-ID').format(hargaValue);
                    previewHarga.textContent = 'Rp ' + formatted;
                } else {
                    previewHarga.textContent = 'Rp 0';
                }
            } else {
                previewBox.style.display = 'none';
            }
        }

        // Event listeners
        jadwalSelect.on('change', updatePreview);
        kelasSelect.on('change', updatePreview);
        hargaInput.addEventListener('input', updatePreview);

        // Initial preview
        updatePreview();
    </script>
    @endpush
</x-admin-layout>
