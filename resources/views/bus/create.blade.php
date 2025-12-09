<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold">Tambah Bus Baru</h2>
                <p class="text-sm text-muted-foreground mt-1">Isi formulir untuk menambahkan bus baru</p>
            </div>
            <a href="{{ route('admin/bus.index') }}">
                <x-ui.button variant="outline">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </x-ui.button>
            </a>
        </div>
    </x-slot>

    <div class="p-6">
        <div class="max-w-3xl">
            <x-ui.card>
                <x-ui.card.header>
                    <x-ui.card.title>Informasi Bus</x-ui.card.title>
                    <x-ui.card.description>Masukkan detail informasi bus yang akan ditambahkan</x-ui.card.description>
                </x-ui.card.header>
                <x-ui.card.content>
                    <form method="POST" action="{{ route('admin/bus.store') }}" enctype="multipart/form-data" id="busForm">
                        @csrf

                        <div class="space-y-6">
                            <!-- Nama Bus -->
                            <div class="space-y-2">
                                <x-ui.label for="nama">
                                    Nama Bus
                                    <span class="text-red-500">*</span>
                                </x-ui.label>
                                <x-ui.input
                                    type="text"
                                    id="nama"
                                    name="nama"
                                    value="{{ old('nama') }}"
                                    placeholder="Contoh: Haryanto Executive"
                                    required
                                />
                                @error('nama')
                                    <p class="text-sm text-destructive mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Kapasitas -->
                                <div class="space-y-2">
                                    <x-ui.label for="kapasitas">
                                        Kapasitas Kursi
                                        <span class="text-red-500">*</span>
                                    </x-ui.label>
                                    <x-ui.input
                                        type="number"
                                        id="kapasitas"
                                        name="kapasitas"
                                        value="{{ old('kapasitas') }}"
                                        placeholder="Contoh: 40"
                                        min="1"
                                        required
                                    />
                                    @error('kapasitas')
                                        <p class="text-sm text-destructive mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Plat Nomor -->
                                <div class="space-y-2">
                                    <x-ui.label for="plat_nomor">
                                        Plat Nomor
                                        <span class="text-red-500">*</span>
                                    </x-ui.label>
                                    <x-ui.input
                                        type="text"
                                        id="plat_nomor"
                                        name="plat_nomor"
                                        value="{{ old('plat_nomor') }}"
                                        placeholder="Contoh: B 1234 XYZ"
                                        required
                                    />
                                    @error('plat_nomor')
                                        <p class="text-sm text-destructive mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>


                            <!-- Fasilitas -->
                            <div class="space-y-2">
                                <x-ui.label for="fasilitas_ids">Fasilitas Bus</x-ui.label>
                                <select
                                    name="fasilitas_ids[]"
                                    id="fasilitas_ids"
                                    multiple
                                    class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring"
                                >
                                    @foreach($fasilitas as $fasilitasItem)
                                        <option value="{{ $fasilitasItem->id }}" {{ in_array($fasilitasItem->id, old('fasilitas_ids', [])) ? 'selected' : '' }}>
                                            {{ $fasilitasItem->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                <p class="text-sm text-muted-foreground">Tekan Ctrl (Windows) atau Cmd (Mac) untuk memilih lebih dari satu</p>
                                @error('fasilitas_ids')
                                    <p class="text-sm text-destructive mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Upload Foto -->
                            <div class="space-y-2">
                                <x-ui.label for="foto">Foto Bus</x-ui.label>
                                <div class="border-2 border-dashed border-border rounded-lg p-6 text-center hover:border-primary transition-colors">
                                    <svg class="h-12 w-12 text-muted-foreground mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <label for="foto" class="cursor-pointer">
                                        <span class="text-sm text-primary hover:underline font-medium">Klik untuk upload</span>
                                        <span class="text-sm text-muted-foreground"> atau drag & drop</span>
                                    </label>
                                    <input
                                        type="file"
                                        name="foto[]"
                                        id="foto"
                                        multiple
                                        accept="image/*"
                                        class="hidden"
                                    />
                                    <p class="text-xs text-muted-foreground mt-2">PNG, JPG, JPEG (Max. 2MB per file)</p>
                                </div>
                                @error('foto')
                                    <p class="text-sm text-destructive mt-1">{{ $message }}</p>
                                @enderror
                                <div id="preview" class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4"></div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between mt-8 pt-6 border-t border-border">
                            <a href="{{ route('admin/bus.index') }}">
                                <x-ui.button type="button" variant="outline">
                                    Batal
                                </x-ui.button>
                            </a>
                            <x-ui.button type="submit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Simpan Bus
                            </x-ui.button>
                        </div>
                    </form>
                </x-ui.card.content>
            </x-ui.card>
        </div>
    </div>

    @push('scripts')
    <script>
        // Tom Select for Fasilitas
        new TomSelect('#fasilitas_ids', {
            plugins: ['remove_button'],
            placeholder: 'Pilih fasilitas...',
            maxItems: null
        });

        // Preview Upload Foto
        document.getElementById('foto').addEventListener('change', function(e) {
            const preview = document.getElementById('preview');
            preview.innerHTML = '';

            const files = Array.from(e.target.files);
            files.forEach((file, index) => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.className = 'relative group';
                        div.innerHTML = `
                            <img src="${e.target.result}" class="w-full h-24 object-cover rounded-lg border border-border">
                            <button type="button" onclick="this.parentElement.remove()" class="absolute top-1 right-1 bg-destructive text-destructive-foreground rounded-full p-1 opacity-0 group-hover:opacity-100 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        `;
                        preview.appendChild(div);
                    };
                    reader.readAsDataURL(file);
                }
            });
        });
    </script>
    @endpush
</x-admin-layout>
