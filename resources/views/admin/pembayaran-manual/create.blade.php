tiket-bus\resources\views\admin\pembayaran-manual\create.blade.php
Create pembayaran-manual create view
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
                    <x-ui.breadcrumb.link href="{{ route('admin/pembayaran-manual.index') }}">
                        Pembayaran Manual
                    </x-ui.breadcrumb.link>
                </x-ui.breadcrumb.item>
                <x-ui.breadcrumb.separator>
                    <x-lucide-chevron-right class="w-3.5 h-3.5" />
                </x-ui.breadcrumb.separator>
                <x-ui.breadcrumb.item>
                    <x-ui.breadcrumb.page>
                        Tambah Pembayaran Manual
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
                            <x-ui.card.title>Tambah Pembayaran Manual</x-ui.card.title>
                            <x-ui.card.description>Masukkan detail pembayaran manual</x-ui.card.description>
                        </div>
                        <a href="{{ route('admin/pembayaran-manual.index') }}">
                            <x-ui.button variant="outline" size="sm">
                                <x-lucide-arrow-left class="w-4 h-4 mr-2" />
                                Kembali
                            </x-ui.button>
                        </a>
                    </div>
                </x-ui.card.header>
                <x-ui.card.content>
                    <form method="POST" action="{{ route('admin/pembayaran-manual.store') }}">
                        @csrf

                        <div class="space-y-6">
                            <!-- Tiket -->
                            <div class="space-y-2">
                                <x-ui.label for="tiket_id">
                                    <div class="flex items-center gap-2">
                                        <x-lucide-ticket class="w-4 h-4" />
                                        Tiket
                                    </div>
                                    <span class="text-red-500">*</span>
                                </x-ui.label>
                                <select
                                    name="tiket_id"
                                    id="tiket_id"
                                    required
                                    class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                                    <option value="">Pilih Tiket</option>
                                    @foreach($tikets as $tiket)
                                        <option value="{{ $tiket->id }}" {{ old('tiket_id') == $tiket->id ? 'selected' : '' }}>
                                            {{ $tiket->kode_tiket }} - {{ $tiket->user->name }} ({{ $tiket->jadwalKelasBus->jadwal->rute->asalTerminal->nama_terminal ?? '-' }} → {{ $tiket->jadwalKelasBus->jadwal->rute->tujuanTerminal->nama_terminal ?? '-' }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('tiket_id')
                                    <p class="text-sm text-destructive mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Metode -->
                                <div class="space-y-2">
                                    <x-ui.label for="metode">
                                        <div class="flex items-center gap-2">
                                            <x-lucide-credit-card class="w-4 h-4" />
                                            Metode Pembayaran
                                        </div>
                                        <span class="text-red-500">*</span>
                                    </x-ui.label>
                                    <select
                                        name="metode"
                                        id="metode"
                                        required
                                        class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                                        <option value="tunai" {{ old('metode') == 'tunai' ? 'selected' : '' }}>Tunai</option>
                                        <option value="transfer" {{ old('metode') == 'transfer' ? 'selected' : '' }}>Transfer</option>
                                        <option value="xendit" {{ old('metode') == 'xendit' ? 'selected' : '' }}>Xendit</option>
                                    </select>
                                    @error('metode')
                                        <p class="text-sm text-destructive mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Nominal -->
                                <div class="space-y-2">
                                    <x-ui.label for="nominal">
                                        <div class="flex items-center gap-2">
                                            <x-lucide-banknote class="w-4 h-4" />
                                            Nominal
                                        </div>
                                        <span class="text-red-500">*</span>
                                    </x-ui.label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-muted-foreground">Rp</span>
                                        <x-ui.input
                                            type="number"
                                            id="nominal"
                                            name="nominal"
                                            value="{{ old('nominal') }}"
                                            placeholder="0"
                                            min="0"
                                            step="1000"
                                            required
                                            class="pl-9"
                                        />
                                    </div>
                                    @error('nominal')
                                        <p class="text-sm text-destructive mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Status -->
                                <div class="space-y-2">
                                    <x-ui.label for="status">
                                        <div class="flex items-center gap-2">
                                            <x-lucide-check-circle class="w-4 h-4" />
                                            Status
                                        </div>
                                        <span class="text-red-500">*</span>
                                    </x-ui.label>
                                    <select
                                        name="status"
                                        id="status"
                                        required
                                        class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                                        <option value="dipesan" {{ old('status') == 'dipesan' ? 'selected' : '' }}>Dipesan</option>
                                        <option value="dibayar" {{ old('status') == 'dibayar' ? 'selected' : '' }}>Dibayar</option>
                                        <option value="batal" {{ old('status') == 'batal' ? 'selected' : '' }}>Batal</option>
                                        <option value="selesai" {{ old('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                    </select>
                                    @error('status')
                                        <p class="text-sm text-destructive mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Waktu Bayar -->
                                <div class="space-y-2">
                                    <x-ui.label for="waktu_bayar">
                                        <div class="flex items-center gap-2">
                                            <x-lucide-calendar class="w-4 h-4" />
                                            Waktu Bayar
                                        </div>
                                    </x-ui.label>
                                    <x-datepicker
                                        name="waktu_bayar"
                                        id="waktu_bayar"
                                        value="{{ old('waktu_bayar') }}"
                                        placeholder="Pilih waktu bayar"
                                    />
                                    @error('waktu_bayar')
                                        <p class="text-sm text-destructive mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col-reverse sm:flex-row items-center justify-end gap-3 mt-8 pt-6 border-t border-border">
                            <a href="{{ route('admin/pembayaran-manual.index') }}" class="w-full sm:w-auto">
                                <x-ui.button type="button" variant="outline" class="w-full sm:w-auto">
                                    <x-lucide-x class="w-4 h-4 mr-2" />
                                    Batal
                                </x-ui.button>
                            </a>
                            <x-ui.button type="submit" class="w-full sm:w-auto">
                                <x-lucide-save class="w-4 h-4 mr-2" />
                                Tambah Pembayaran
                            </x-ui.button>
                        </div>
                    </form>
                </x-ui.card.content>
            </x-ui.card>
        </div>
    </div>
</x-admin-layout>
