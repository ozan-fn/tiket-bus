<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold">Edit Kelas Bus</h2>
                <p class="text-sm text-muted-foreground mt-1">Perbarui informasi kelas bus</p>
            </div>
            <a href="{{ route('admin/kelas-bus.index') }}">
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
                    <x-ui.card.title>Informasi Kelas Bus</x-ui.card.title>
                    <x-ui.card.description>Perbarui detail kelas bus</x-ui.card.description>
                </x-ui.card.header>
                <x-ui.card.content>
                    <form method="POST" action="{{ route('admin/kelas-bus.update', $kelasBus) }}">
                        @csrf
                        @method('PUT')

                        <div class="space-y-6">
                            <!-- Nama Kelas -->
                            <div class="space-y-2">
                                <x-ui.label for="nama_kelas">
                                    Nama Kelas
                                    <span class="text-red-500">*</span>
                                </x-ui.label>
                                <x-ui.input
                                    type="text"
                                    id="nama_kelas"
                                    name="nama_kelas"
                                    value="{{ old('nama_kelas', $kelasBus->nama_kelas) }}"
                                    placeholder="Contoh: Ekonomi, VIP, Premium"
                                    required
                                />
                                @error('nama_kelas')
                                    <p class="text-sm text-destructive mt-1">{{ $message }}</p>
                                @enderror
                                <p class="text-sm text-muted-foreground">Masukkan nama kelas bus yang akan ditawarkan</p>
                            </div>



                            <!-- Deskripsi -->
                            <div class="space-y-2">
                                <x-ui.label for="deskripsi">
                                    Deskripsi
                                </x-ui.label>
                                <textarea
                                    id="deskripsi"
                                    name="deskripsi"
                                    placeholder="Masukkan deskripsi kelas bus (opsional)"
                                    class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring"
                                    rows="4"
                                >{{ old('deskripsi', $kelasBus->deskripsi) }}</textarea>
                                @error('deskripsi')
                                    <p class="text-sm text-destructive mt-1">{{ $message }}</p>
                                @enderror
                                <p class="text-sm text-muted-foreground">Deskripsi tambahan tentang kelas ini</p>
                            </div>
                        </div>

                        <div class="flex items-center justify-between mt-8 pt-6 border-t border-border">
                            <a href="{{ route('admin/kelas-bus.index') }}">
                                <x-ui.button type="button" variant="outline">
                                    Batal
                                </x-ui.button>
                            </a>
                            <x-ui.button type="submit">
                                <x-lucide-check class="w-4 h-4" />
                                Update Kelas
                            </x-ui.button>
                        </div>
                    </form>
                </x-ui.card.content>
            </x-ui.card>
        </div>
    </div>
</x-admin-layout>
