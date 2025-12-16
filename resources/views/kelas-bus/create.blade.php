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
                            <x-ui.card.description class="mt-1">Masukkan detail kelas bus yang akan ditambahkan</x-ui.card.description>
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
                                >{{ old('deskripsi') }}</textarea>
                                @error('deskripsi')
                                    <p class="text-sm text-destructive mt-1">{{ $message }}</p>
                                @enderror
                                <p class="text-sm text-muted-foreground">Deskripsi tambahan tentang kelas ini</p>
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
                                Simpan Kelas
                            </x-ui.button>
                        </div>
                    </form>
                </x-ui.card.content>
            </x-ui.card>
        </div>
    </div>
</x-admin-layout>
