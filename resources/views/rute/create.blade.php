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
                        Tambah Rute
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
                            <x-ui.card.title>Informasi Rute</x-ui.card.title>
                            <x-ui.card.description>Masukkan detail rute perjalanan yang akan ditambahkan</x-ui.card.description>
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
                    <form method="POST" action="{{ route('admin/rute.store') }}" id="ruteForm">
                        @csrf

                        <div class="space-y-6">
                            <div class="relative grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Terminal Asal -->
                                <div class="space-y-2">
                                    <x-ui.label for="asal_terminal_id">
                                        <div class="flex items-center gap-2">
                                            <x-lucide-map-pin class="w-4 h-4" />
                                            Terminal Asal
                                        </div>
                                        <span class="text-red-500">*</span>
                                    </x-ui.label>
                                    <select
                                        name="asal_terminal_id"
                                        id="asal_terminal_id"
                                        required
                                        class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                                        <option value="">Pilih Terminal Asal</option>
                                        @foreach($terminals as $terminal)
                                            <option value="{{ $terminal->id }}" {{ old('asal_terminal_id') == $terminal->id ? 'selected' : '' }}>
                                                {{ $terminal->nama_terminal }} ({{ $terminal->nama_kota }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('asal_terminal_id')
                                        <p class="text-sm text-destructive mt-1 flex items-center gap-1">
                                            <x-lucide-alert-circle class="w-4 h-4" />
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- Arrow -->
                                <div class="hidden md:flex absolute left-1/2 top-7 -translate-x-1/2 z-10">
                                    <div class="h-8 w-8 rounded-full bg-primary flex items-center justify-center shadow-lg">
                                        <x-lucide-arrow-right class="h-4 w-4 text-primary-foreground" />
                                    </div>
                                </div>

                                <!-- Terminal Tujuan -->
                                <div class="space-y-2">
                                    <x-ui.label for="tujuan_terminal_id">
                                        <div class="flex items-center gap-2">
                                            <x-lucide-map-pin-check-inside class="w-4 h-4" />
                                            Terminal Tujuan
                                        </div>
                                        <span class="text-red-500">*</span>
                                    </x-ui.label>
                                    <select
                                        name="tujuan_terminal_id"
                                        id="tujuan_terminal_id"
                                        required
                                        class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                                        <option value="">Pilih Terminal Tujuan</option>
                                        @foreach($terminals as $terminal)
                                            <option value="{{ $terminal->id }}" {{ old('tujuan_terminal_id') == $terminal->id ? 'selected' : '' }}>
                                                {{ $terminal->nama_terminal }} ({{ $terminal->nama_kota }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('tujuan_terminal_id')
                                        <p class="text-sm text-destructive mt-1 flex items-center gap-1">
                                            <x-lucide-alert-circle class="w-4 h-4" />
                                            {{ $message }}
                                        </p>
                                    @enderror
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
                                Tambah Rute
                            </x-ui.button>
                        </div>
                    </form>
                </x-ui.card.content>
            </x-ui.card>
        </div>
    </div>
</x-admin-layout>
