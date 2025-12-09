<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold">Tambah Fasilitas Baru</h2>
                <p class="text-sm text-muted-foreground mt-1">Isi formulir untuk menambahkan fasilitas baru</p>
            </div>
            <a href="{{ route('admin/fasilitas.index') }}">
                <x-ui.button variant="outline">
                    <x-lucide-arrow-left class="w-4 h-4" />
                    Kembali
                </x-ui.button>
            </a>
        </div>
    </x-slot>

    <div class="p-6">
        <div class="max-w-2xl">
            <x-ui.card>
                <x-ui.card.header>
                    <x-ui.card.title>Informasi Fasilitas</x-ui.card.title>
                    <x-ui.card.description>Masukkan detail fasilitas yang akan ditambahkan</x-ui.card.description>
                </x-ui.card.header>
                <x-ui.card.content>
                    <form method="POST" action="{{ route('admin/fasilitas.store') }}" id="fasilitasForm">
                        @csrf

                        <div class="space-y-6">
                            <!-- Nama Fasilitas -->
                            <div class="space-y-2">
                                <x-ui.label for="nama">
                                    Nama Fasilitas
                                    <span class="text-red-500">*</span>
                                </x-ui.label>
                                <x-ui.input
                                    type="text"
                                    id="nama"
                                    name="nama"
                                    value="{{ old('nama') }}"
                                    placeholder="Contoh: AC, WiFi, USB Charger"
                                    required
                                />
                                @error('nama')
                                    <p class="text-sm text-destructive mt-1">{{ $message }}</p>
                                @enderror
                                <p class="text-sm text-muted-foreground">Masukkan nama fasilitas yang tersedia di bus</p>
                            </div>
                        </div>

                        <div class="flex items-center justify-between mt-8 pt-6 border-t border-border">
                            <a href="{{ route('admin/fasilitas.index') }}">
                                <x-ui.button type="button" variant="outline">
                                    Batal
                                </x-ui.button>
                            </a>
                            <x-ui.button type="submit">
                                <x-lucide-check class="w-4 h-4" />
                                Simpan Fasilitas
                            </x-ui.button>
                        </div>
                    </form>
                </x-ui.card.content>
            </x-ui.card>
        </div>
    </div>
</x-admin-layout>
