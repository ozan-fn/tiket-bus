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
                    <x-ui.breadcrumb.link href="{{ route('admin/terminal.index') }}">
                        Terminal
                    </x-ui.breadcrumb.link>
                </x-ui.breadcrumb.item>
                <x-ui.breadcrumb.separator>
                    <x-lucide-chevron-right class="w-3.5 h-3.5" />
                </x-ui.breadcrumb.separator>
                <x-ui.breadcrumb.item>
                    <x-ui.breadcrumb.page>
                        Edit Terminal
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
                            <x-ui.card.title>Edit Informasi Terminal</x-ui.card.title>
                            <x-ui.card.description>Perbarui detail informasi terminal {{ $terminal->nama_terminal }}</x-ui.card.description>
                        </div>
                        <a href="{{ route('admin/terminal.index') }}">
                            <x-ui.button variant="outline" size="sm">
                                <x-lucide-arrow-left class="w-4 h-4 mr-2" />
                                Kembali
                            </x-ui.button>
                        </a>
                    </div>
                </x-ui.card.header>
                <x-ui.card.content>
                    <form method="POST" action="{{ route('admin/terminal.update', $terminal) }}" enctype="multipart/form-data" id="terminalForm">
                        @csrf
                        @method('PUT')

                        <div class="space-y-6">
                            <!-- Nama Terminal -->
                            <div class="space-y-2">
                                <x-ui.label for="nama_terminal">
                                    Nama Terminal
                                    <span class="text-red-500">*</span>
                                </x-ui.label>
                                <x-ui.input
                                    type="text"
                                    id="nama_terminal"
                                    name="nama_terminal"
                                    value="{{ old('nama_terminal', $terminal->nama_terminal) }}"
                                    placeholder="Contoh: Terminal Kampung Rambutan"
                                    required
                                />
                                @error('nama_terminal')
                                    <p class="text-sm text-destructive mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Nama Kota -->
                                <div class="space-y-2">
                                    <x-ui.label for="nama_kota">
                                        Nama Kota
                                        <span class="text-red-500">*</span>
                                    </x-ui.label>
                                    <x-ui.input
                                        type="text"
                                        id="nama_kota"
                                        name="nama_kota"
                                        value="{{ old('nama_kota', $terminal->nama_kota) }}"
                                        placeholder="Contoh: Jakarta"
                                        required
                                    />
                                    @error('nama_kota')
                                        <p class="text-sm text-destructive mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- ID Terminal -->
                                <div class="space-y-2">
                                    <x-ui.label for="id">
                                        <div class="flex items-center gap-2">
                                            <x-lucide-fingerprint class="w-4 h-4" />
                                            ID Terminal
                                        </div>
                                    </x-ui.label>
                                    <x-ui.input
                                        type="text"
                                        id="id"
                                        value="#{{ $terminal->id }}"
                                        disabled
                                        class="bg-muted"
                                    />
                                </div>
                            </div>

                            <!-- Alamat -->
                            <div class="space-y-2">
                                <x-ui.label for="alamat">
                                    <div class="flex items-center gap-2">
                                        <x-lucide-map-pin class="w-4 h-4" />
                                        Alamat
                                    </div>
                                </x-ui.label>
                                <textarea
                                    name="alamat"
                                    id="alamat"
                                    rows="3"
                                    placeholder="Masukkan alamat lengkap terminal"
                                    class="flex min-h-[80px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                                >{{ old('alamat', $terminal->alamat) }}</textarea>
                                @error('alamat')
                                    <p class="text-sm text-destructive mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Foto Terminal yang Ada -->
                            @if($terminal->photos && $terminal->photos->count() > 0)
                                <div class="space-y-2">
                                    <x-ui.label>
                                        <div class="flex items-center gap-2">
                                            <x-lucide-images class="w-4 h-4" />
                                            Foto Terminal Saat Ini
                                        </div>
                                    </x-ui.label>
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                        @foreach($terminal->photos as $photo)
                                            <div x-data="{ deleteOpen: false }" class="relative group">
                                                <img src="{{ asset('storage/' . $photo->path) }}"
                                                    alt="Terminal Photo"
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
                            <a href="{{ route('admin/terminal.index') }}" class="w-full sm:w-auto">
                                <x-ui.button type="button" variant="outline" class="w-full sm:w-auto">
                                    <x-lucide-x class="w-4 h-4 mr-2" />
                                    Batal
                                </x-ui.button>
                            </a>
                            <x-ui.button type="submit" class="w-full sm:w-auto">
                                <x-lucide-save class="w-4 h-4 mr-2" />
                                Update Terminal
                            </x-ui.button>
                        </div>
                    </form>
                </x-ui.card.content>
            </x-ui.card>
        </div>
    </div>

    @push('scripts')
    <script>
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
            fetch(`/admin/terminal-photo/${fotoId}`, {
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
