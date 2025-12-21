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
                    <x-ui.breadcrumb.link href="{{ route('admin/banner.index') }}">
                        Banner
                    </x-ui.breadcrumb.link>
                </x-ui.breadcrumb.item>
                <x-ui.breadcrumb.separator>
                    <x-lucide-chevron-right class="w-3.5 h-3.5" />
                </x-ui.breadcrumb.separator>
                <x-ui.breadcrumb.item>
                    <x-ui.breadcrumb.page>
                        Tambah Banner
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
                            <x-ui.card.title>Informasi Banner</x-ui.card.title>
                            <x-ui.card.description class="mt-1">Masukkan detail banner yang akan ditambahkan</x-ui.card.description>
                        </div>
                        <a href="{{ route('admin/banner.index') }}">
                            <x-ui.button variant="outline" size="sm">
                                <x-lucide-arrow-left class="w-4 h-4 mr-2" />
                                Kembali
                            </x-ui.button>
                        </a>
                    </div>
                </x-ui.card.header>
                <x-ui.card.content>
                    <form method="POST" action="{{ route('admin/banner.store') }}" enctype="multipart/form-data" id="bannerForm">
                        @csrf

                        <div class="space-y-6">
                            <!-- Judul Banner -->
                            <div class="space-y-2">
                                <x-ui.label for="title">
                                    Judul Banner
                                    <span class="text-red-500">*</span>
                                </x-ui.label>
                                <x-ui.input
                                    type="text"
                                    id="title"
                                    name="title"
                                    value="{{ old('title') }}"
                                    placeholder="Masukkan judul banner"
                                    required
                                />
                                @error('title')
                                    <p class="text-sm text-destructive mt-1">{{ $message }}</p>
                                @enderror
                                <p class="text-sm text-muted-foreground">Masukkan judul banner yang akan ditampilkan</p>
                            </div>

                            <!-- Deskripsi -->
                            <div class="space-y-2">
                                <x-ui.label for="description">Deskripsi</x-ui.label>
                                <x-ui.textarea
                                    id="description"
                                    name="description"
                                    placeholder="Masukkan deskripsi banner (opsional)"
                                    rows="4"
                                >{{ old('description') }}</x-ui.textarea>
                                @error('description')
                                    <p class="text-sm text-destructive mt-1">{{ $message }}</p>
                                @enderror
                                <p class="text-sm text-muted-foreground">Deskripsi banner (opsional)</p>
                            </div>

                            <!-- Urutan -->
                            <div class="space-y-2">
                                <x-ui.label for="order">
                                    <div class="flex items-center gap-2">
                                        <x-lucide-list-ordered class="w-4 h-4" />
                                        Urutan
                                    </div>
                                </x-ui.label>
                                <x-ui.input
                                    type="number"
                                    id="order"
                                    name="order"
                                    value="{{ old('order', (App\Models\Banner::where('owner_id', auth()->id())->max('order') ?? 0) + 1) }}"
                                    placeholder="Masukkan urutan banner"
                                    min="1"
                                />
                                @error('order')
                                    <p class="text-sm text-destructive mt-1 flex items-center gap-1">
                                        <x-lucide-alert-circle class="w-4 h-4" />
                                        {{ $message }}
                                    </p>
                                @enderror
                                <p class="text-xs text-muted-foreground flex items-center gap-1">
                                    <x-lucide-info class="w-3 h-3" />
                                    Urutan tampilan banner (angka lebih kecil akan tampil lebih dulu)
                                </p>
                            </div>

                            <!-- Gambar -->
                            <div class="space-y-2">
                                <x-ui.label for="image">Gambar Banner</x-ui.label>
                                <x-ui.input
                                    id="image"
                                    name="image"
                                    type="file"
                                    accept="image/*"
                                />
                                @error('image')
                                    <p class="text-sm text-destructive mt-1">{{ $message }}</p>
                                @enderror
                                <p class="text-sm text-muted-foreground">Upload gambar banner (opsional). Format: JPG, PNG, GIF. Maksimal 2MB.</p>

                                <!-- Preview Container -->
                                <div id="preview" class="grid grid-cols-2 md:grid-cols-3 gap-4 mt-4"></div>
                            </div>
                        </div>

                        <div class="flex flex-col-reverse sm:flex-row items-center justify-end gap-3 mt-8 pt-6 border-t border-border">
                            <a href="{{ route('admin/banner.index') }}" class="w-full sm:w-auto">
                                <x-ui.button type="button" variant="outline" class="w-full sm:w-auto">
                                    <x-lucide-x class="w-4 h-4 mr-2" />
                                    Batal
                                </x-ui.button>
                            </a>
                            <x-ui.button type="submit" class="w-full sm:w-auto">
                                <x-lucide-save class="w-4 h-4 mr-2" />
                                Simpan Banner
                            </x-ui.button>
                        </div>
                    </form>
                </x-ui.card.content>
            </x-ui.card>
        </div>
    </div>

    @push('scripts')
    <script>
        // Preview Upload Gambar
        document.getElementById('image').addEventListener('change', function(e) {
            const preview = document.getElementById('preview');
            const input = e.target;
            const files = Array.from(input.files);

            preview.innerHTML = ''; // Clear previous previews

            files.forEach((file) => {
                if (file.type.startsWith('image/')) {
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

                        div.querySelector('.removeBtn').addEventListener('click', function() {
                            div.remove();
                            const newDataTransfer = new DataTransfer();
                            Array.from(input.files).forEach((f) => {
                                if (f !== file) {
                                    newDataTransfer.items.add(f);
                                }
                            });
                            input.files = newDataTransfer.files;
                        });

                        preview.appendChild(div);
                    };
                    reader.readAsDataURL(file);
                }
            });
        });
    </script>
    @endpush
</x-admin-layout>
