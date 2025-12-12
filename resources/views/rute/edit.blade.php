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
                    <x-ui.breadcrumb.link href="{{ route('admin/rute.index') }}">
                        Rute
                    </x-ui.breadcrumb.link>
                </x-ui.breadcrumb.item>
                <x-ui.breadcrumb.separator>
                    <x-lucide-chevron-right class="w-3.5 h-3.5" />
                </x-ui.breadcrumb.separator>
                <x-ui.breadcrumb.item>
                    <x-ui.breadcrumb.page>
                        Edit Rute
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
                            <x-ui.card.title>Edit Rute</x-ui.card.title>
                            <x-ui.card.description>Perbarui informasi rute perjalanan</x-ui.card.description>
                        </div>
                        <a href="{{ route('admin/rute.index') }}">
                            <x-ui.button variant="outline" size="sm">
                                <x-lucide-arrow-left class="w-4 h-4 mr-2" />
                                Kembali
                            </x-ui.button>
                        </a>
                    </div>
                </x-ui.card.header>
                <x-ui.card.content>
                    <form method="POST" action="{{ route('admin/rute.update', $rute) }}">
                        @csrf
                        @method('PUT')

                        <div class="space-y-6">
                            <!-- Terminal Asal -->
                            <div class="space-y-2">
                                <x-ui.label for="asal_terminal_id">
                                    <div class="flex items-center gap-2">
                                        <x-lucide-map-pin class="w-4 h-4" />
                                        Terminal Asal (Keberangkatan)
                                    </div>
                                    <span class="text-red-500">*</span>
                                </x-ui.label>
                                <select
                                    name="asal_terminal_id"
                                    id="asal_terminal_id"
                                    class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring @error('asal_terminal_id') border-red-500 @enderror"
                                    required
                                >
                                    <option value="">Pilih Terminal Asal</option>
                                    @foreach($terminals as $terminal)
                                        <option value="{{ $terminal->id }}" {{ old('asal_terminal_id', $rute->asal_terminal_id) == $terminal->id ? 'selected' : '' }}>
                                            {{ $terminal->nama_terminal }} - {{ $terminal->nama_kota }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('asal_terminal_id')
                                    <p class="text-sm text-destructive mt-1 flex items-center gap-1">
                                        <x-lucide-alert-circle class="w-4 h-4" />
                                        {{ $message }}
                                    </p>
                                @enderror
                                <p class="text-xs text-muted-foreground flex items-center gap-1">
                                    <x-lucide-info class="w-3 h-3" />
                                    Pilih terminal keberangkatan
                                </p>
                            </div>

                            <!-- Arrow Indicator -->
                            <div class="flex items-center justify-center">
                                <div class="h-12 w-12 rounded-full bg-primary/10 flex items-center justify-center">
                                    <x-lucide-arrow-down class="h-6 w-6 text-primary" />
                                </div>
                            </div>

                            <!-- Terminal Tujuan -->
                            <div class="space-y-2">
                                <x-ui.label for="tujuan_terminal_id">
                                    <div class="flex items-center gap-2">
                                        <x-lucide-map-pin-check-inside class="w-4 h-4" />
                                        Terminal Tujuan (Kedatangan)
                                    </div>
                                    <span class="text-red-500">*</span>
                                </x-ui.label>
                                <select
                                    name="tujuan_terminal_id"
                                    id="tujuan_terminal_id"
                                    class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring @error('tujuan_terminal_id') border-red-500 @enderror"
                                    required
                                >
                                    <option value="">Pilih Terminal Tujuan</option>
                                    @foreach($terminals as $terminal)
                                        <option value="{{ $terminal->id }}" {{ old('tujuan_terminal_id', $rute->tujuan_terminal_id) == $terminal->id ? 'selected' : '' }}>
                                            {{ $terminal->nama_terminal }} - {{ $terminal->nama_kota }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('tujuan_terminal_id')
                                    <p class="text-sm text-destructive mt-1 flex items-center gap-1">
                                        <x-lucide-alert-circle class="w-4 h-4" />
                                        {{ $message }}
                                    </p>
                                @enderror
                                <p class="text-xs text-muted-foreground flex items-center gap-1">
                                    <x-lucide-info class="w-3 h-3" />
                                    Pilih terminal tujuan (harus berbeda dengan terminal asal)
                                </p>
                            </div>

                            <!-- Preview Rute -->
                            <div class="p-4 rounded-lg border border-border bg-muted/50" id="route-preview" style="display: none;">
                                <div class="flex items-center gap-2 mb-3">
                                    <x-lucide-route class="w-5 h-5 text-primary" />
                                    <p class="text-sm font-medium">Preview Rute</p>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="flex-1">
                                        <p class="text-xs text-muted-foreground">Dari</p>
                                        <p class="text-sm font-medium" id="preview-asal">-</p>
                                    </div>
                                    <x-lucide-arrow-right class="w-5 h-5 text-muted-foreground" />
                                    <div class="flex-1">
                                        <p class="text-xs text-muted-foreground">Ke</p>
                                        <p class="text-sm font-medium" id="preview-tujuan">-</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col-reverse sm:flex-row items-center justify-end gap-3 mt-8 pt-6 border-t border-border">
                            <a href="{{ route('admin/rute.index') }}" class="w-full sm:w-auto">
                                <x-ui.button type="button" variant="outline" class="w-full sm:w-auto">
                                    <x-lucide-x class="w-4 h-4 mr-2" />
                                    Batal
                                </x-ui.button>
                            </a>
                            <x-ui.button type="submit" class="w-full sm:w-auto">
                                <x-lucide-save class="w-4 h-4 mr-2" />
                                Update Rute
                            </x-ui.button>
                        </div>
                    </form>
                </x-ui.card.content>
            </x-ui.card>
        </div>
    </div>

    @push('scripts')
    <script>
        // Tom Select for Terminal selection
        const asalSelect = new TomSelect('#asal_terminal_id', {
            placeholder: 'Pilih Terminal Asal',
            allowEmptyOption: true,
            create: false
        });

        const tujuanSelect = new TomSelect('#tujuan_terminal_id', {
            placeholder: 'Pilih Terminal Tujuan',
            allowEmptyOption: true,
            create: false
        });

        // Preview Rute
        function updatePreview() {
            const asalId = asalSelect.getValue();
            const tujuanId = tujuanSelect.getValue();
            const preview = document.getElementById('route-preview');
            const previewAsal = document.getElementById('preview-asal');
            const previewTujuan = document.getElementById('preview-tujuan');

            if (asalId && tujuanId) {
                const asalText = asalSelect.options[asalId]?.text || '-';
                const tujuanText = tujuanSelect.options[tujuanId]?.text || '-';

                previewAsal.textContent = asalText;
                previewTujuan.textContent = tujuanText;
                preview.style.display = 'block';
            } else {
                preview.style.display = 'none';
            }
        }

        asalSelect.on('change', updatePreview);
        tujuanSelect.on('change', updatePreview);

        // Initial preview
        updatePreview();
    </script>
    @endpush
</x-admin-layout>
