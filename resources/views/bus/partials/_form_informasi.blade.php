<x-ui.card>
    <x-ui.card.header>
        <x-ui.card.title>Informasi Dasar Bus</x-ui.card.title>
        <x-ui.card.description>Masukkan detail identitas bus yang akan ditambahkan</x-ui.card.description>
    </x-ui.card.header>
    <x-ui.card.content>
        <div class="space-y-6">
            <!-- Nama Bus -->
            <div class="space-y-2">
                <x-ui.label for="nama">
                    <div class="flex items-center gap-2">
                        <x-lucide-bus class="w-4 h-4" />
                        Nama Bus
                        <span class="text-red-500">*</span>
                    </div>
                </x-ui.label>
                <x-ui.input
                    type="text"
                    id="nama"
                    name="nama"
                    value="{{ old('nama') }}"
                    placeholder="Contoh: Haryanto Executive, Pahala Kencana"
                    required
                />
                @error('nama')
                    <p class="text-sm text-destructive flex items-center gap-1">
                        <x-lucide-alert-circle class="w-4 h-4" />
                        {{ $message }}
                    </p>
                @enderror
                <p class="text-xs text-muted-foreground">Masukkan nama merek atau nama armada bus</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Kapasitas -->
                <div class="space-y-2">
                    <x-ui.label for="kapasitas">
                        <div class="flex items-center gap-2">
                            <x-lucide-users class="w-4 h-4" />
                            Kapasitas Kursi
                            <span class="text-red-500">*</span>
                        </div>
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
                        <p class="text-sm text-destructive flex items-center gap-1">
                            <x-lucide-alert-circle class="w-4 h-4" />
                            {{ $message }}
                        </p>
                    @enderror
                    <p class="text-xs text-muted-foreground">Total jumlah kursi di bus</p>
                </div>

                <!-- Plat Nomor -->
                <div class="space-y-2">
                    <x-ui.label for="plat_nomor">
                        <div class="flex items-center gap-2">
                            <x-lucide-hash class="w-4 h-4" />
                            Plat Nomor
                            <span class="text-red-500">*</span>
                        </div>
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
                        <p class="text-sm text-destructive flex items-center gap-1">
                            <x-lucide-alert-circle class="w-4 h-4" />
                            {{ $message }}
                        </p>
                    @enderror
                    <p class="text-xs text-muted-foreground">Nomor identitas kendaraan</p>
                </div>
            </div>
        </div>
    </x-ui.card.content>
</x-ui.card>
