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
                        Tambah Kelas Bus
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
                            <x-ui.card.title>Informasi Kelas Bus</x-ui.card.title>
                            <x-ui.card.description>Masukkan detail kelas bus yang akan ditambahkan</x-ui.card.description>
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
                    <form method="POST" action="{{ route('admin/kelas-bus.store') }}" id="kelasBusForm">
                        @csrf

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
                                    required
                                    class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                                    <option value="">Pilih Bus</option>
                                    @foreach($buses as $bus)
                                        <option value="{{ $bus->id }}" {{ old('bus_id') == $bus->id ? 'selected' : '' }}>
                                            {{ $bus->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('bus_id')
                                    <p class="text-sm text-destructive mt-1 flex items-center gap-1">
                                        <x-lucide-alert-circle class="w-4 h-4" />
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
                                        value="{{ old('nama_kelas') }}"
                                        placeholder="Contoh: Ekonomi"
                                        required
                                    />
                                    @error('nama_kelas')
                                        <p class="text-sm text-destructive mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Jumlah Kursi -->
                                <div class="space-y-2">
                                    <x-ui.label for="jumlah_kursi">
                                        Jumlah Kursi
                                        <span class="text-red-500">*</span>
                                    </x-ui.label>
                                    <x-ui.input
                                        type="number"
                                        id="jumlah_kursi"
                                        name="jumlah_kursi"
                                        value="{{ old('jumlah_kursi') }}"
                                        placeholder="Contoh: 20"
                                        min="1"
                                        required
                                    />
                                    @error('jumlah_kursi')
                                        <p class="text-sm text-destructive mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
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
                                    name="deskripsi"
                                    id="deskripsi"
                                    rows="3"
                                    placeholder="Masukkan deskripsi kelas bus"
                                    class="flex min-h-[80px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                                >{{ old('deskripsi') }}</textarea>
                                @error('deskripsi')
                                    <p class="text-sm text-destructive mt-1">{{ $message }}</p>
                                @enderror
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
                                Tambah Kelas Bus
                            </x-ui.button>
                        </div>
                    </form>
                </x-ui.card.content>
            </x-ui.card>
        </div>
    </div>
</x-admin-layout>
