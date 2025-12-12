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
                    <x-ui.breadcrumb.link href="{{ route('admin/fasilitas.index') }}">
                        Fasilitas
                    </x-ui.breadcrumb.link>
                </x-ui.breadcrumb.item>
                <x-ui.breadcrumb.separator>
                    <x-lucide-chevron-right class="w-3.5 h-3.5" />
                </x-ui.breadcrumb.separator>
                <x-ui.breadcrumb.item>
                    <x-ui.breadcrumb.page>
                        Edit Fasilitas
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
                            <x-ui.card.title>Edit Fasilitas</x-ui.card.title>
                            <x-ui.card.description>Perbarui informasi fasilitas bus</x-ui.card.description>
                        </div>
                        <a href="{{ route('admin/fasilitas.index') }}">
                            <x-ui.button variant="outline" size="sm">
                                <x-lucide-arrow-left class="w-4 h-4 mr-2" />
                                Kembali
                            </x-ui.button>
                        </a>
                    </div>
                </x-ui.card.header>
                <x-ui.card.content>
                    <form method="POST" action="{{ route('admin/fasilitas.update', $fasilitas) }}">
                        @csrf
                        @method('PUT')

                        <div class="space-y-6">
                            <!-- Nama Fasilitas -->
                            <div class="space-y-2">
                                <x-ui.label for="nama">
                                    <div class="flex items-center gap-2">
                                        <x-lucide-sparkles class="w-4 h-4" />
                                        Nama Fasilitas
                                    </div>
                                    <span class="text-red-500">*</span>
                                </x-ui.label>
                                <x-ui.input
                                    type="text"
                                    id="nama"
                                    name="nama"
                                    value="{{ old('nama', $fasilitas->nama) }}"
                                    placeholder="Contoh: AC, WiFi, TV, Toilet"
                                    required
                                    maxlength="100"
                                />
                                @error('nama')
                                    <p class="text-sm text-destructive mt-1 flex items-center gap-1">
                                        <x-lucide-alert-circle class="w-4 h-4" />
                                        {{ $message }}
                                    </p>
                                @enderror
                                <p class="text-xs text-muted-foreground flex items-center gap-1">
                                    <x-lucide-info class="w-3 h-3" />
                                    Nama fasilitas yang tersedia di bus (maksimal 100 karakter)
                                </p>
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
                                        <span>Nama fasilitas harus jelas dan mudah dipahami</span>
                                    </li>
                                    <li class="flex items-start gap-2">
                                        <x-lucide-check class="w-4 h-4 text-green-600 mt-0.5 shrink-0" />
                                        <span>Fasilitas yang diupdate akan otomatis diterapkan ke semua bus yang menggunakannya</span>
                                    </li>
                                    <li class="flex items-start gap-2">
                                        <x-lucide-check class="w-4 h-4 text-green-600 mt-0.5 shrink-0" />
                                        <span>Pastikan nama tidak duplikat dengan fasilitas lain</span>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="flex flex-col-reverse sm:flex-row items-center justify-end gap-3 mt-8 pt-6 border-t border-border">
                            <a href="{{ route('admin/fasilitas.index') }}" class="w-full sm:w-auto">
                                <x-ui.button type="button" variant="outline" class="w-full sm:w-auto">
                                    <x-lucide-x class="w-4 h-4 mr-2" />
                                    Batal
                                </x-ui.button>
                            </a>
                            <x-ui.button type="submit" class="w-full sm:w-auto">
                                <x-lucide-save class="w-4 h-4 mr-2" />
                                Update Fasilitas
                            </x-ui.button>
                        </div>
                    </form>
                </x-ui.card.content>
            </x-ui.card>
        </div>
    </div>
</x-admin-layout>
