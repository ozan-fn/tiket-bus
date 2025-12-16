<x-ui.card>
    <x-ui.card.header>
        <x-ui.card.title>Fasilitas Bus</x-ui.card.title>
        <x-ui.card.description>Pilih fasilitas yang tersedia di bus ini</x-ui.card.description>
    </x-ui.card.header>
    <x-ui.card.content>
        <div class="space-y-2">
            <x-ui.label>
                <div class="flex items-center gap-2">
                    <x-lucide-sparkles class="w-4 h-4" />
                    Pilih Fasilitas
                </div>
            </x-ui.label>
            <select
                name="fasilitas_ids[]"
                id="fasilitas_ids"
                multiple
                class="w-full rounded-md border border-input bg-input text-foreground px-3 py-1 text-base shadow-xs transition-[color,box-shadow] outline-none focus:border-primary focus:outline-none focus:ring-2 focus:ring-ring disabled:pointer-events-none disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
            >
                @foreach($fasilitas as $fasilitasItem)
                    <option value="{{ $fasilitasItem->id }}" {{ in_array($fasilitasItem->id, old('fasilitas_ids', [])) ? 'selected' : '' }}>
                        {{ $fasilitasItem->nama }}
                    </option>
                @endforeach
            </select>
            <p class="text-xs text-muted-foreground flex items-center gap-1">
                <x-lucide-info class="w-3 h-3" />
                Tekan Ctrl (Windows) atau Cmd (Mac) untuk memilih lebih dari satu
            </p>
        </div>
        <!-- Fasilitas Preview -->
        <div class="mt-6 space-y-3">
            <h4 class="text-sm font-semibold">Fasilitas yang Dipilih:</h4>
            <div id="fasilitas-preview" class="flex flex-wrap gap-2">
                @foreach($fasilitas as $fasilitasItem)
                    @if(in_array($fasilitasItem->id, old('fasilitas_ids', [])))
                        <x-ui.badge variant="secondary" class="flex items-center gap-1">
                            <x-lucide-check class="w-3 h-3" />
                            {{ $fasilitasItem->nama }}
                        </x-ui.badge>
                    @endif
                @endforeach
            </div>
        </div>
    </x-ui.card.content>
</x-ui.card>
