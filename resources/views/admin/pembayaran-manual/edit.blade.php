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
                        Edit Pembayaran Manual
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
                            <x-ui.card.title>Edit Pembayaran Manual</x-ui.card.title>
                            <x-ui.card.description>Edit detail pembayaran manual</x-ui.card.description>
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
                    <form method="POST" action="{{ route('admin/pembayaran-manual.update', $pembayaran) }}">
                        @csrf
                        @method('PUT')

                        <div class="space-y-6">
                            <!-- Tiket (disabled karena tidak boleh diubah) -->
                            <div class="space-y-2">
                                <x-ui.label for="tiket_id">
                                    <div class="flex items-center gap-2">
                                        <x-lucide-ticket class="w-4 h-4" />
                                        Tiket
                                    </div>
                                </x-ui.label>
                                <select
                                    name="tiket_id"
                                    id="tiket_id"
                                    disabled
                                    class="w-full rounded-md border border-input bg-muted px-3 py-2 text-sm">
                                    <option value="{{ $pembayaran->tiket_id }}" selected>
                                        {{ $pembayaran->tiket->kode_tiket }} - {{ $pembayaran->tiket->user->name }} ({{ $pembayaran->tiket->jadwalKelasBus->jadwal->rute->asalTerminal->nama_terminal ?? '-' }} → {{ $pembayaran->tiket->jadwalKelasBus->jadwal->rute->tujuanTerminal->nama_terminal ?? '-' }})
                                    </option>
                                </select>
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
                                        disabled
                                        class="w-full rounded-md border border-input bg-muted px-3 py-2 text-sm cursor-not-allowed">
                                        <option value="tunai" {{ $pembayaran->metode == 'tunai' ? 'selected' : '' }}>Tunai</option>
                                        <option value="transfer" {{ $pembayaran->metode == 'transfer' ? 'selected' : '' }}>Transfer</option>
                                        <option value="xendit" {{ $pembayaran->metode == 'xendit' ? 'selected' : '' }}>Xendit</option>
                                    </select>
                                    <input type="hidden" name="metode" value="{{ $pembayaran->metode }}" />
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
                                            value="{{ $pembayaran->nominal }}"
                                            placeholder="0"
                                            min="0"
                                            step="1000"
                                            required
                                            readonly
                                            class="pl-9 bg-muted cursor-not-allowed"
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
                                        <option value="dipesan" {{ $pembayaran->status == 'dipesan' ? 'selected' : '' }}>Dipesan</option>
                                        <option value="dibayar" {{ $pembayaran->status == 'dibayar' ? 'selected' : '' }}>Dibayar</option>
                                        <option value="batal" {{ $pembayaran->status == 'batal' ? 'selected' : '' }}>Batal</option>
                                        <option value="selesai" {{ $pembayaran->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
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
                                    <x-ui.input
                                        type="text"
                                        id="waktu_bayar_display"
                                        value="{{ $pembayaran->waktu_bayar ? $pembayaran->waktu_bayar->format('d/m/Y H:i') : 'Belum dibayar' }}"
                                        readonly
                                        class="w-full rounded-md border border-input bg-muted px-3 py-2 text-sm cursor-not-allowed"
                                    />
                                    <input type="hidden" name="waktu_bayar" value="{{ $pembayaran->waktu_bayar ? $pembayaran->waktu_bayar->format('Y-m-d H:i') : '' }}" />
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
                                Update Pembayaran
                            </x-ui.button>
                        </div>
                    </form>
                </x-ui.card.content>
            </x-ui.card>
        </div>
    </div>
</x-admin-layout>
