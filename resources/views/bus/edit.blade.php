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
                    <x-ui.breadcrumb.link href="{{ route('admin/bus.index') }}">
                        Bus
                    </x-ui.breadcrumb.link>
                </x-ui.breadcrumb.item>
                <x-ui.breadcrumb.separator>
                    <x-lucide-chevron-right class="w-3.5 h-3.5" />
                </x-ui.breadcrumb.separator>
                <x-ui.breadcrumb.item>
                    <x-ui.breadcrumb.page>
                        Edit Bus
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
                            <x-ui.card.title>Edit Informasi Bus</x-ui.card.title>
                            <x-ui.card.description>Perbarui detail informasi bus {{ $bus->nama }}</x-ui.card.description>
                        </div>
                        <a href="{{ route('admin/bus.index') }}">
                            <x-ui.button variant="outline" size="sm">
                                <x-lucide-arrow-left class="w-4 h-4 mr-2" />
                                Kembali
                            </x-ui.button>
                        </a>
                    </div>
                </x-ui.card.header>
                <x-ui.card.content>
                    @if ($errors->any())
                        <x-ui.alert variant="destructive" class="mb-6">
                            <x-lucide-alert-circle class="w-5 h-5" />
                            <div>
                                <h4 class="font-semibold mb-2">Terjadi Kesalahan</h4>
                                <ul class="text-sm space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li class="flex items-center gap-2">
                                            <span class="h-1 w-1 rounded-full bg-current"></span>
                                            {{ $error }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </x-ui.alert>
                    @endif

                    <form method="POST" action="{{ route('admin/bus.update', $bus) }}" enctype="multipart/form-data" id="busForm">
                        @csrf
                        @method('PUT')

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
                                    value="{{ old('nama', $bus->nama) }}"
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
                                        value="{{ old('kapasitas', $bus->kapasitas) }}"
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
                                        value="{{ old('plat_nomor', $bus->plat_nomor) }}"
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
                                <x-ui.label for="fasilitas_ids">
                                    <div class="flex items-center gap-2">
                                        <x-lucide-sparkles class="w-4 h-4" />
                                        Fasilitas Bus
                                    </div>
                                </x-ui.label>
                                <select
                                    name="fasilitas_ids[]"
                                    id="fasilitas_ids"
                                    multiple
                                    class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring"
                                >
                                    @foreach($fasilitas as $fasilitasItem)
                                        <option value="{{ $fasilitasItem->id }}"
                                            {{ in_array($fasilitasItem->id, old('fasilitas_ids', $bus->fasilitas->pluck('id')->toArray())) ? 'selected' : '' }}>
                                            {{ $fasilitasItem->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                <p class="text-xs text-muted-foreground flex items-center gap-1">
                                    <x-lucide-info class="w-3 h-3" />
                                    Tekan Ctrl (Windows) atau Cmd (Mac) untuk memilih lebih dari satu
                                </p>
                                @error('fasilitas_ids')
                                    <p class="text-sm text-destructive mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Foto Bus yang Ada -->
                            @if($bus->photos && $bus->photos->count() > 0)
                                <div class="space-y-2">
                                    <x-ui.label>
                                        <div class="flex items-center gap-2">
                                            <x-lucide-images class="w-4 h-4" />
                                            Foto Bus Saat Ini
                                        </div>
                                    </x-ui.label>
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                        @foreach($bus->photos as $photo)
                                            <div x-data="{ deleteOpen: false }" class="relative group">
                                                <img src="{{ asset('storage/' . $photo->path) }}"
                                                    alt="Bus Photo"
                                                    class="w-full h-24 object-cover rounded-lg border border-border cursor-pointer">
                                                <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity rounded-lg flex items-center justify-center"
                                                    @click="deleteOpen = true">
                                                    <x-lucide-trash-2 class="w-4 h-4 text-destructive-foreground" />
                                                </div>

                                                <template x-teleport="body">
                                                    <div x-show="deleteOpen" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center">
                                                        <div @click="deleteOpen = false" class="absolute inset-0 bg-black/50"></div>
                                                        <div class="relative bg-background rounded-lg border shadow-lg p-6 max-w-md w-full mx-4">
                                                            <button @click="deleteOpen = false" class="absolute top-4 right-4 text-muted-foreground hover:text-foreground">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                                </svg>
                                                            </button>
                                                            <h2 class="text-lg font-semibold mb-2">Hapus Foto</h2>
                                                            <p class="text-sm text-muted-foreground mb-6">Apakah Anda yakin ingin menghapus foto ini? Tindakan ini tidak dapat dibatalkan.</p>
                                                            <div class="flex gap-3 justify-end">
                                                                <button @click="deleteOpen = false" type="button" class="px-4 py-2 rounded-md border border-input hover:bg-muted transition-colors">
                                                                    Batal
                                                                </button>
                                                                <button @click="deleteFoto({{ $photo->id }}, () => deleteOpen = false)" type="button" class="px-4 py-2 rounded-md bg-destructive text-destructive-foreground hover:bg-destructive/90 transition-colors">
                                                                    Hapus
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Upload Foto Baru -->
                            <div class="space-y-2">
                                <x-ui.label for="foto">
                                    <div class="flex items-center gap-2">
                                        <x-lucide-image class="w-4 h-4" />
                                        Tambah Foto Baru
                                    </div>
                                </x-ui.label>
                                <div class="border-2 border-dashed border-border rounded-lg p-8 text-center hover:border-primary transition-colors cursor-pointer bg-muted/30">
                                    <x-lucide-upload class="h-12 w-12 text-muted-foreground mx-auto mb-3" />
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
                                    <p class="text-xs text-muted-foreground mt-2 flex items-center justify-center gap-1">
                                        <x-lucide-file-image class="w-3 h-3" />
                                        PNG, JPG, JPEG (Max. 2MB per file)
                                    </p>
                                </div>
                                @error('foto')
                                    <p class="text-sm text-destructive mt-1 flex items-center gap-1">
                                        <x-lucide-alert-circle class="w-4 h-4" />
                                        {{ $message }}
                                    </p>
                                @enderror
                                <div id="preview" class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4"></div>
                            </div>
                        </div>

                        <div class="flex flex-col-reverse sm:flex-row items-center justify-end gap-3 mt-8 pt-6 border-t border-border">
                            <a href="{{ route('admin/bus.index') }}" class="w-full sm:w-auto">
                                <x-ui.button type="button" variant="outline" class="w-full sm:w-auto">
                                    <x-lucide-x class="w-4 h-4 mr-2" />
                                    Batal
                                </x-ui.button>
                            </a>
                            <x-ui.button type="submit" class="w-full sm:w-auto">
                                <x-lucide-save class="w-4 h-4 mr-2" />
                                Update Bus
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

        // Store selected files
        let selectedFiles = new DataTransfer();

        // Preview Upload Foto
        document.getElementById('foto').addEventListener('change', function(e) {
            const preview = document.getElementById('preview');
            const input = e.target;

            // Add new files to DataTransfer
            const files = Array.from(input.files);
            files.forEach((file) => {
                if (file.type.startsWith('image/')) {
                    selectedFiles.items.add(file);

                    const reader = new FileReader();
                    reader.onload = function(readerEvent) {
                        const div = document.createElement('div');
                        div.className = 'relative group';
                        div.innerHTML = `
                            <img src="${readerEvent.target.result}" class="w-full h-24 object-cover rounded-lg border border-border">
                            <button type="button" class="removeBtn absolute top-1 right-1 bg-destructive text-destructive-foreground rounded-full p-1 opacity-0 group-hover:opacity-100 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        `;

                        // Add remove functionality
                        div.querySelector('.removeBtn').addEventListener('click', function() {
                            div.remove();
                            // Remove file from DataTransfer
                            const newDataTransfer = new DataTransfer();
                            Array.from(selectedFiles.files).forEach((f, idx) => {
                                if (f !== file) {
                                    newDataTransfer.items.add(f);
                                }
                            });
                            selectedFiles = newDataTransfer;
                            input.files = selectedFiles.files;
                        });

                        preview.appendChild(div);
                    };
                    reader.readAsDataURL(file);
                }
            });

            // Update input files with accumulated selection
            input.files = selectedFiles.files;
        });

        // Delete foto
        function deleteFoto(fotoId, closeDialog) {
            fetch(`/admin/bus-photo/${fotoId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Gagal menghapus foto: ' + (data.message || 'Kesalahan tidak diketahui'));
                    if (closeDialog) closeDialog();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menghapus foto: ' + error.message);
                if (closeDialog) closeDialog();
            });
        }
    </script>
    @endpush
</x-admin-layout>
