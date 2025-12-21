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
                        Edit Banner
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
                            <x-ui.card.title>Edit Banner</x-ui.card.title>
                            <x-ui.card.description>Perbarui informasi banner</x-ui.card.description>
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
                    <form method="POST" action="{{ route('admin/banner.update', $banner) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="space-y-6">
                            <!-- Judul Banner -->
                            <div class="space-y-2">
                                <x-ui.label for="title">
                                    <div class="flex items-center gap-2">
                                        <x-lucide-image class="w-4 h-4" />
                                        Judul Banner
                                    </div>
                                    <span class="text-red-500">*</span>
                                </x-ui.label>
                                <x-ui.input
                                    type="text"
                                    id="title"
                                    name="title"
                                    value="{{ old('title', $banner->title) }}"
                                    placeholder="Masukkan judul banner"
                                    required
                                    maxlength="255"
                                />
                                @error('title')
                                    <p class="text-sm text-destructive mt-1 flex items-center gap-1">
                                        <x-lucide-alert-circle class="w-4 h-4" />
                                        {{ $message }}
                                    </p>
                                @enderror
                                <p class="text-xs text-muted-foreground flex items-center gap-1">
                                    <x-lucide-info class="w-3 h-3" />
                                    Judul banner yang akan ditampilkan (maksimal 255 karakter)
                                </p>
                            </div>

                            <!-- Deskripsi -->
                            <div class="space-y-2">
                                <x-ui.label for="description">
                                    <div class="flex items-center gap-2">
                                        <x-lucide-file-text class="w-4 h-4" />
                                        Deskripsi
                                    </div>
                                </x-ui.label>
                                <x-ui.textarea
                                    id="description"
                                    name="description"
                                    placeholder="Masukkan deskripsi banner (opsional)"
                                    rows="4"
                                    maxlength="1000"
                                >{{ old('description', $banner->description) }}</x-ui.textarea>
                                @error('description')
                                    <p class="text-sm text-destructive mt-1 flex items-center gap-1">
                                        <x-lucide-alert-circle class="w-4 h-4" />
                                        {{ $message }}
                                    </p>
                                @enderror
                                <p class="text-xs text-muted-foreground flex items-center gap-1">
                                    <x-lucide-info class="w-3 h-3" />
                                    Deskripsi banner (opsional, maksimal 1000 karakter)
                                </p>
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
                                    value="{{ old('order', $banner->order) }}"
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
                                <x-ui.label for="image">
                                    <div class="flex items-center gap-2">
                                        <x-lucide-camera class="w-4 h-4" />
                                        Gambar Banner
                                    </div>
                                </x-ui.label>
                                @if($banner->image)
                                    <div class="mb-4">
                                        <p class="text-sm font-medium mb-2">Gambar Saat Ini:</p>
                                        <img src="{{ asset('storage/' . $banner->image) }}" alt="{{ $banner->title }}" class="w-full max-w-md h-32 object-cover rounded border border-border" />
                                    </div>
                                @endif
                                <x-ui.input
                                    id="image"
                                    name="image"
                                    type="file"
                                    accept="image/*"
                                />
                                @error('image')
                                    <p class="text-sm text-destructive mt-1 flex items-center gap-1">
                                        <x-lucide-alert-circle class="w-4 h-4" />
                                        {{ $message }}
                                    </p>
                                @enderror
                                <p class="text-xs text-muted-foreground flex items-center gap-1">
                                    <x-lucide-info class="w-3 h-3" />
                                    Upload gambar baru untuk mengganti yang lama (opsional). Format: JPG, PNG, GIF. Maksimal 2MB.
                                </p>

                                <!-- Preview Container -->
                                <div id="preview" class="grid grid-cols-2 md:grid-cols-3 gap-4 mt-4"></div>
                            </div>

                            <!-- Info Box -->
                            <div class="p-4 rounded-lg border border-border bg-muted/50">
                                <div class="flex items-center gap-2 mb-3">
                                    <x-lucide-info class="w-5 h-5 text-primary" />
                                    <p class="text-sm font-medium">Informasi</p>
                                </div>
                                <ul class="space-y-2 text-sm text-muted-foreground">
                                    <li class="flex items-start gap-2">
                                        <x-lucide-check class="w-4 h-4 text-green-600 mt-0.5 shrink-0" />
                                        <span>Judul banner harus jelas dan menarik</span>
                                    </li>
                                    <li class="flex items-start gap-2">
                                        <x-lucide-check class="w-4 h-4 text-green-600 mt-0.5 shrink-0" />
                                        <span>Gambar akan otomatis diganti jika upload gambar baru</span>
                                    </li>
                                    <li class="flex items-start gap-2">
                                        <x-lucide-check class="w-4 h-4 text-green-600 mt-0.5 shrink-0" />
                                        <span>Pastikan format gambar sesuai (JPG, PNG, GIF)</span>
                                    </li>
                                </ul>
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
                                Update Banner
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
