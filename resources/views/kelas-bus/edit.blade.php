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
                    <x-ui.breadcrumb.link href="{{ route('admin/kelas-bus.index') }}">
                        Kelas Bus
                    </x-ui.breadcrumb.link>
                </x-ui.breadcrumb.item>
                <x-ui.breadcrumb.separator>
                    <x-lucide-chevron-right class="w-3.5 h-3.5" />
                </x-ui.breadcrumb.separator>
                <x-ui.breadcrumb.item>
                    <x-ui.breadcrumb.page>
                        Edit Kelas Bus
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
                            <x-ui.card.title>Edit Kelas Bus</x-ui.card.title>
                            <x-ui.card.description>Perbarui informasi kelas bus</x-ui.card.description>
                        </div>
                        <a href="{{ route('admin/kelas-bus.index') }}">
                            <x-ui.button variant="outline" size="sm">
                                <x-lucide-arrow-left class="w-4 h-4 mr-2" />
                                Kembali
                            </x-ui.button>
                        </a>
                    </div>
                </x-ui.card.header>
                <x-ui.card.content>
                    <form method="POST" action="{{ route('admin/kelas-bus.update', $kelasBus) }}">
                        @csrf
                        @method('PUT')

                        <div class="space-y-6">
                            <!-- Bus -->
                            <div class="space-y-2">
                                <x-ui.label for="bus_id">
                                    <div class="flex items-center gap-2">
                                        <x-lucide-bus class="w-4 h-4" />
                                        Bus
                                    </div>
                                    <span class="text-red-500">*</span>
                                </x-ui.label>
                                <select
                                    name="bus_id"
                                    id="bus_id"
                                    class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring @error('bus_id') border-red-500 @enderror"
                                    required
                                >
                                    <option value="">Pilih Bus</option>
                                    @foreach($buses as $bus)
                                        <option value="{{ $bus->id }}" {{ old('bus_id', $kelasBus->bus_id) == $bus->id ? 'selected' : '' }}>
                                            {{ $bus->nama }} - {{ $bus->plat_nomor }} (Kapasitas: {{ $bus->kapasitas }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('bus_id')
                                    <p class="text-sm text-destructive mt-1 flex items-center gap-1">
                                        <x-lucide-alert-circle class="w-4 h-4" />
                                        {{ $message }}
                                    </p>
                                @enderror
                                <p class="text-xs text-muted-foreground flex items-center gap-1">
                                    <x-lucide-info class="w-3 h-3" />
                                    Pilih bus yang akan digunakan untuk kelas ini
                                </p>
                            </div>

                            <!-- Nama Kelas -->
                            <div class="space-y-2">
                                <x-ui.label for="nama_kelas">
                                    <div class="flex items-center gap-2">
                                        <x-lucide-tag class="w-4 h-4" />
                                        Nama Kelas
                                    </div>
                                    <span class="text-red-500">*</span>
                                </x-ui.label>
                                <x-ui.input
                                    type="text"
                                    id="nama_kelas"
                                    name="nama_kelas"
                                    value="{{ old('nama_kelas', $kelasBus->nama_kelas) }}"
                                    placeholder="Contoh: Ekonomi, Bisnis, VIP"
                                    required
                                    maxlength="50"
                                />
                                @error('nama_kelas')
                                    <p class="text-sm text-destructive mt-1 flex items-center gap-1">
                                        <x-lucide-alert-circle class="w-4 h-4" />
                                        {{ $message }}
                                    </p>
                                @enderror
                                <p class="text-xs text-muted-foreground flex items-center gap-1">
                                    <x-lucide-info class="w-3 h-3" />
                                    Maksimal 50 karakter
                                </p>
                            </div>

                            <!-- Jumlah Kursi -->
                            <div class="space-y-2">
                                <x-ui.label for="jumlah_kursi">
                                    <div class="flex items-center gap-2">
                                        <x-lucide-armchair class="w-4 h-4" />
                                        Jumlah Kursi
                                    </div>
                                    <span class="text-red-500">*</span>
                                </x-ui.label>
                                <x-ui.input
                                    type="number"
                                    id="jumlah_kursi"
                                    name="jumlah_kursi"
                                    value="{{ old('jumlah_kursi', $kelasBus->jumlah_kursi) }}"
                                    placeholder="Contoh: 20"
                                    min="1"
                                    required
                                />
                                @error('jumlah_kursi')
                                    <p class="text-sm text-destructive mt-1 flex items-center gap-1">
                                        <x-lucide-alert-circle class="w-4 h-4" />
                                        {{ $message }}
                                    </p>
                                @enderror
                                <p class="text-xs text-muted-foreground flex items-center gap-1">
                                    <x-lucide-info class="w-3 h-3" />
                                    Jumlah kursi yang tersedia di kelas ini
                                </p>
                            </div>

                            <!-- Deskripsi -->
                            <div class="space-y-2">
                                <x-ui.label for="deskripsi">
                                    <div class="flex items-center gap-2">
                                        <x-lucide-file-text class="w-4 h-4" />
                                        Deskripsi
                                    </div>
                                </x-ui.label>
                                <textarea
                                    id="deskripsi"
                                    name="deskripsi"
                                    rows="4"
                                    class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring @error('deskripsi') border-red-500 @enderror"
                                    placeholder="Masukkan deskripsi kelas bus (opsional)..."
                                >{{ old('deskripsi', $kelasBus->deskripsi) }}</textarea>
                                @error('deskripsi')
                                    <p class="text-sm text-destructive mt-1 flex items-center gap-1">
                                        <x-lucide-alert-circle class="w-4 h-4" />
                                        {{ $message }}
                                    </p>
                                @enderror
                                <p class="text-xs text-muted-foreground flex items-center gap-1">
                                    <x-lucide-info class="w-3 h-3" />
                                    Deskripsi tambahan tentang kelas bus ini (opsional)
                                </p>
                            </div>

                            <!-- Preview Info -->
                            <div class="p-4 rounded-lg border border-border bg-muted/50">
                                <div class="flex items-center gap-2 mb-3">
                                    <x-lucide-info class="w-5 h-5 text-primary" />
                                    <p class="text-sm font-medium">Informasi</p>
                                </div>
                                <ul class="space-y-2 text-sm text-muted-foreground">
                                    <li class="flex items-start gap-2">
                                        <x-lucide-check class="w-4 h-4 text-green-600 mt-0.5 shrink-0" />
                                        <span>Pastikan nama kelas mencerminkan tipe layanan</span>
                                    </li>
                                    <li class="flex items-start gap-2">
                                        <x-lucide-check class="w-4 h-4 text-green-600 mt-0.5 shrink-0" />
                                        <span>Jumlah kursi harus sesuai dengan kapasitas bus</span>
                                    </li>
                                    <li class="flex items-start gap-2">
                                        <x-lucide-check class="w-4 h-4 text-green-600 mt-0.5 shrink-0" />
                                        <span>Deskripsi membantu penumpang memahami fasilitas kelas</span>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="flex flex-col-reverse sm:flex-row items-center justify-end gap-3 mt-8 pt-6 border-t border-border">
                            <a href="{{ route('admin/kelas-bus.index') }}" class="w-full sm:w-auto">
                                <x-ui.button type="button" variant="outline" class="w-full sm:w-auto">
                                    <x-lucide-x class="w-4 h-4 mr-2" />
                                    Batal
                                </x-ui.button>
                            </a>
                            <x-ui.button type="submit" class="w-full sm:w-auto">
                                <x-lucide-save class="w-4 h-4 mr-2" />
                                Update Kelas Bus
                            </x-ui.button>
                        </div>
                    </form>
                </x-ui.card.content>
            </x-ui.card>
        </div>
    </div>

    @push('scripts')
    <script>
        // Tom Select for Bus selection
        new TomSelect('#bus_id', {
            placeholder: 'Pilih Bus',
            allowEmptyOption: true,
            create: false
        });
    </script>
    @endpush
</x-admin-layout>
