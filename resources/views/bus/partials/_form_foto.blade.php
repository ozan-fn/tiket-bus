<x-ui.card>
    <x-ui.card.header>
        <x-ui.card.title>Foto Bus</x-ui.card.title>
        <x-ui.card.description>Unggah foto atau gambar bus dari berbagai sudut</x-ui.card.description>
    </x-ui.card.header>
    <x-ui.card.content>
        <div class="space-y-4">
            <!-- Upload Area -->
            <div class="border-2 border-dashed border-border rounded-lg p-8 text-center hover:border-primary hover:bg-primary/5 transition-all cursor-pointer bg-muted/30">
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
                    PNG, JPG, JPEG, GIF (Max. 2MB per file)
                </p>
            </div>

            @error('foto')
                <x-ui.alert variant="destructive" :title="'Error Upload'" :description="$message">
                    <x-lucide-alert-circle class="w-5 h-5" />
                </x-ui.alert>
            @enderror

            <!-- Preview -->
            <div>
                <h4 class="text-sm font-semibold mb-3 flex items-center gap-2">
                    <x-lucide-image class="w-4 h-4" />
                    Preview Foto
                </h4>
                <div id="preview" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                    <!-- Preview images will be injected here by JS, but you can wrap each img in x-ui.card or x-ui.badge if desired -->
                </div>
            </div>
        </div>
    </x-ui.card.content>
</x-ui.card>
