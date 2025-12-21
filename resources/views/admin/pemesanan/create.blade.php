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
                    <x-ui.breadcrumb.link href="{{ route('admin/pemesanan.index') }}">
                        Pesan Tiket
                    </x-ui.breadcrumb.link>
                </x-ui.breadcrumb.item>
                <x-ui.breadcrumb.separator>
                    <x-lucide-chevron-right class="w-3.5 h-3.5" />
                </x-ui.breadcrumb.separator>
                <x-ui.breadcrumb.item>
                    <x-ui.breadcrumb.page>
                        Pesan Tiket Baru
                    </x-ui.breadcrumb.page>
                </x-ui.breadcrumb.item>
            </x-ui.breadcrumb.list>
        </x-ui.breadcrumb.breadcrumb>
    </x-slot>

    <div class="p-4 sm:p-6">
        <div class="max-w-4xl mx-auto" x-data="{ openModal: false }">
            <x-ui.card>
                <x-ui.card.header>
                    <div class="flex items-center justify-between">
                        <div>
                            <x-ui.card.title>Pesan Tiket Baru</x-ui.card.title>
                            <x-ui.card.description>Isi data penumpang untuk melakukan pemesanan tiket</x-ui.card.description>
                        </div>
                        <a href="{{ route('admin/pemesanan.index') }}">
                            <x-ui.button variant="outline" size="sm">
                                <x-lucide-arrow-left class="w-4 h-4 mr-2" />
                                Kembali
                            </x-ui.button>
                        </a>
                    </div>
                </x-ui.card.header>
                <x-ui.card.content>
                    <!-- Jadwal Info -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8 p-4 bg-muted/50 rounded-lg">
                        <div>
                            <p class="text-xs text-muted-foreground">Bus</p>
                            <p class="font-semibold">{{ $jadwal->bus->nama ?? '-' }}</p>
                            <p class="text-xs text-muted-foreground">{{ $jadwal->bus->plat_nomor ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-muted-foreground">Rute</p>
                            <p class="font-semibold">{{ $jadwal->rute->asalTerminal->nama_kota ?? '-' }} → {{ $jadwal->rute->tujuanTerminal->nama_kota ?? '-' }}</p>
                            <p class="text-xs text-muted-foreground">{{ $jadwal->tanggal_berangkat->format('d M Y') }} | {{ $jadwal->jam_berangkat->format('H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-muted-foreground">Sopir</p>
                            <p class="font-semibold">{{ $jadwal->sopir->user->name ?? '-' }}</p>
                            <p class="text-xs text-muted-foreground">{{ $jadwal->sopir->nomor_sim ?? '-' }}</p>
                        </div>
                    </div>

                    <form id="booking-form" method="POST" action="{{ route('admin/pemesanan.store', $jadwal) }}">
                        @csrf

                        <div class="space-y-6">
                            <!-- Data Penumpang -->
                            <div>
                                <h3 class="font-semibold mb-4 flex items-center gap-2">
                                    <x-lucide-user class="w-4 h-4" />
                                    Data Penumpang
                                </h3>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Nama Penumpang -->
                                    <div class="space-y-2">
                                        <x-ui.label for="nama_penumpang">
                                            Nama Lengkap
                                            <span class="text-red-500">*</span>
                                        </x-ui.label>
                                        <x-ui.input
                                            type="text"
                                            name="nama_penumpang"
                                            id="nama_penumpang"
                                            placeholder="Nama lengkap penumpang"
                                            value="{{ old('nama_penumpang') }}"
                                            required
                                        />
                                        @error('nama_penumpang')
                                            <p class="text-sm text-destructive mt-1 flex items-center gap-1">
                                                <x-lucide-alert-circle class="w-4 h-4" />
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>

                                    <!-- NIK -->
                                    <div class="space-y-2">
                                        <x-ui.label for="nik">
                                            NIK
                                            <span class="text-red-500">*</span>
                                        </x-ui.label>
                                        <x-ui.input
                                            type="text"
                                            name="nik"
                                            id="nik"
                                            placeholder="Nomor identitas"
                                            value="{{ old('nik') }}"
                                            required
                                        />
                                        @error('nik')
                                            <p class="text-sm text-destructive mt-1 flex items-center gap-1">
                                                <x-lucide-alert-circle class="w-4 h-4" />
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>

                                    <!-- Tanggal Lahir -->
                                    <div class="space-y-2">
                                        <x-ui.label for="tanggal_lahir">
                                            Tanggal Lahir
                                            <span class="text-red-500">*</span>
                                        </x-ui.label>
                                        <x-ui.input
                                            type="date"
                                            name="tanggal_lahir"
                                            id="tanggal_lahir"
                                            value="{{ old('tanggal_lahir') }}"
                                            required
                                        />
                                        @error('tanggal_lahir')
                                            <p class="text-sm text-destructive mt-1 flex items-center gap-1">
                                                <x-lucide-alert-circle class="w-4 h-4" />
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>

                                    <!-- Jenis Kelamin -->
                                    <div class="space-y-2">
                                        <x-ui.label for="jenis_kelamin">
                                            Jenis Kelamin
                                            <span class="text-red-500">*</span>
                                        </x-ui.label>
                                        <select
                                            name="jenis_kelamin"
                                            id="jenis_kelamin"
                                            class="w-full rounded-md border border-input bg-background dark:bg-input/30 dark:text-foreground dark:border-border px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring dark:focus:ring-ring/50"
                                            required
                                        >
                                            <option value="">Pilih Jenis Kelamin</option>
                                            <option value="L" {{ old('jenis_kelamin') === 'L' ? 'selected' : '' }}>Laki-laki</option>
                                            <option value="P" {{ old('jenis_kelamin') === 'P' ? 'selected' : '' }}>Perempuan</option>
                                        </select>
                                        @error('jenis_kelamin')
                                            <p class="text-sm text-destructive mt-1 flex items-center gap-1">
                                                <x-lucide-alert-circle class="w-4 h-4" />
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>

                                    <!-- Nomor Telepon -->
                                    <div class="space-y-2">
                                        <x-ui.label for="nomor_telepon">
                                            Nomor Telepon
                                            <span class="text-red-500">*</span>
                                        </x-ui.label>
                                        <x-ui.input
                                            type="tel"
                                            name="nomor_telepon"
                                            id="nomor_telepon"
                                            placeholder="08xxxxx"
                                            value="{{ old('nomor_telepon') }}"
                                            required
                                        />
                                        @error('nomor_telepon')
                                            <p class="text-sm text-destructive mt-1 flex items-center gap-1">
                                                <x-lucide-alert-circle class="w-4 h-4" />
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>

                                    <!-- Email -->
                                    <div class="space-y-2">
                                        <x-ui.label for="email">
                                            Email
                                            <span class="text-red-500">*</span>
                                        </x-ui.label>
                                        <x-ui.input
                                            type="email"
                                            name="email"
                                            id="email"
                                            placeholder="email@example.com"
                                            value="{{ old('email') }}"
                                            required
                                        />
                                        @error('email')
                                            <p class="text-sm text-destructive mt-1 flex items-center gap-1">
                                                <x-lucide-alert-circle class="w-4 h-4" />
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Pilihan Kursi & Kelas -->
                            <div>
                                <h3 class="font-semibold mb-4 flex items-center gap-2">
                                    <x-lucide-armchair class="w-4 h-4" />
                                    Pilih Kursi & Kelas
                                </h3>

                                <!-- Legend -->
                                <div class="grid grid-cols-3 gap-4 mb-6 p-4 bg-muted/30 rounded-lg">
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded border-2 border-primary bg-primary/10"></div>
                                        <span class="text-xs font-medium">Tersedia</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded border-2 border-destructive bg-destructive/10 opacity-50"></div>
                                        <span class="text-xs font-medium">Terjual</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded border-2 border-emerald-500 bg-emerald-500/10"></div>
                                        <span class="text-xs font-medium">Dipilih</span>
                                    </div>
                                </div>

                                <div class="space-y-8">
                                    @forelse($jadwal->jadwalKelasBus as $jkb)
                                        <div class="p-6 border-2 rounded-lg hover:border-accent transition-colors">
                                            <div class="flex items-center justify-between mb-4">
                                                <h4 class="font-bold text-lg">{{ $jkb->kelasBus->nama_kelas }}</h4>
                                                <div class="text-right">
                                                    <p class="text-xs text-muted-foreground">Harga per kursi</p>
                                                    <p class="font-bold text-lg text-primary">Rp {{ number_format($jkb->harga, 0, ',', '.') }}</p>
                                                </div>
                                            </div>

                                            <!-- Bus Layout Visualization -->
                                            <div class="bg-gradient-to-b from-muted/50 to-background p-6 rounded-lg mb-4 border border-border/50">
                                                <!-- Depan Bus -->
                                                <div class="text-center mb-4 pb-4 border-b border-dashed border-border/50">
                                                    <div class="inline-block px-3 py-1 bg-secondary text-secondary-foreground text-xs font-semibold rounded">
                                                        <x-lucide-navigation class="w-4 h-4 inline mr-1" /> DEPAN BUS
                                                    </div>
                                                </div>

                                                <!-- Kursi Grid -->
                                                <div class="grid gap-3" style="grid-template-columns: repeat(4, 1fr);">
                                                    @forelse($jkb->kelasBus->kursi as $kursi)
                                                        <label class="cursor-pointer group">
                                                            <input
                                                                type="radio"
                                                                name="kursi_id"
                                                                value="{{ $kursi->id }}"
                                                                data-jadwal-kelas="{{ $jkb->id }}"
                                                                data-harga="{{ $jkb->harga }}"
                                                                data-nomor="{{ $kursi->nomor_kursi }}"
                                                                @if(in_array($kursi->id, $kursiTerpakai)) disabled @endif
                                                                {{ old('kursi_id') == $kursi->id ? 'checked' : '' }}
                                                                class="hidden peer"
                                                                required
                                                            />
                                                            <div class="w-[58px] h-[58px] flex items-center justify-center rounded-lg font-bold text-sm transition-all duration-200
                                                                {{ in_array($kursi->id, $kursiTerpakai)
                                                                    ? 'bg-destructive/20 border-2 border-destructive text-muted-foreground cursor-not-allowed opacity-50'
                                                                    : 'bg-white border-2 border-primary hover:bg-primary/5 group-hover:shadow-md'
                                                                }}
                                                                peer-checked:bg-emerald-500 peer-checked:border-emerald-600 peer-checked:text-white peer-checked:shadow-lg peer-checked:scale-105
                                                            ">
                                                                {{ $kursi->nomor_kursi }}
                                                            </div>
                                                        </label>
                                                    @empty
                                                        <p class="text-muted-foreground col-span-4 text-center py-4">Tidak ada kursi</p>
                                                    @endforelse
                                                </div>

                                                <!-- Belakang Bus -->
                                                <div class="text-center mt-4 pt-4 border-t border-dashed border-border/50">
                                                    <div class="inline-block px-3 py-1 bg-secondary text-secondary-foreground text-xs font-semibold rounded">
                                                        BELAKANG BUS
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Informasi Kursi -->
                                            <div class="text-xs text-muted-foreground">
                                                <p>Total kursi tersedia: <span class="font-semibold">{{ $jkb->kelasBus->kursi->count() - count(array_filter($kursiTerpakai, fn($id) => in_array($id, $jkb->kelasBus->kursi->pluck('id')->toArray()))) }}</span> / <span class="font-semibold">{{ $jkb->kelasBus->kursi->count() }}</span></p>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-center py-12 bg-muted/30 rounded-lg">
                                            <x-lucide-inbox class="w-8 h-8 mx-auto text-muted-foreground mb-2" />
                                            <p class="text-muted-foreground">Tidak ada kelas bus yang tersedia</p>
                                        </div>
                                    @endforelse

                                    <input type="hidden" name="jadwal_kelas_bus_id" id="jadwal_kelas_bus_id" />
                                </div>

                                @error('kursi_id')
                                    <p class="text-sm text-destructive mt-2 flex items-center gap-1">
                                        <x-lucide-alert-circle class="w-4 h-4" />
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Summary -->
                            <div class="p-4 bg-muted/50 rounded-lg">
                                <div class="flex justify-between items-center">
                                    <span class="font-semibold">Total Harga:</span>
                                    <span class="text-2xl font-bold text-primary" id="total-harga">Rp 0</span>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col-reverse sm:flex-row items-center justify-end gap-3 mt-8 pt-6 border-t border-border">
                            <a href="{{ route('admin/pemesanan.index') }}" class="w-full sm:w-auto">
                                <x-ui.button type="button" variant="outline" class="w-full sm:w-auto">
                                    <x-lucide-x class="w-4 h-4 mr-2" />
                                    Batal
                                </x-ui.button>
                            </a>
                            <x-ui.button type="button" @click="openModal = true" class="w-full sm:w-auto">
                                <x-lucide-save class="w-4 h-4 mr-2" />
                                Pesan Tiket
                            </x-ui.button>
                        </div>
                    </form>

                    <!-- Modal Konfirmasi Pembayaran -->
                    <div x-show="openModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" @click.self="openModal = false">
                        <div class="bg-white rounded-lg shadow-lg max-w-md w-full mx-4 p-6">
                            <h3 class="text-lg font-semibold mb-4">Konfirmasi Pembayaran</h3>
                            <p class="text-sm text-gray-600 mb-6">
                                Pastikan pembayaran sudah dilakukan sebelum melanjutkan. Tiket akan dibuat dan pembayaran akan ditandai sebagai berhasil.
                            </p>
                            <div class="flex justify-end gap-3">
                                <x-ui.button type="button" variant="outline" @click="openModal = false">
                                    Batal
                                </x-ui.button>
                                <x-ui.button type="button" @click="openModal = false; document.getElementById('booking-form').submit()">
                                    Konfirmasi Pembayaran
                                </x-ui.button>
                            </div>
                        </div>
                    </div>
                </x-ui.card.content>
            </x-ui.card>
        </div>
    </div>

    @push('scripts')
    <script>
        // Update jadwal_kelas_bus_id dan total harga saat kursi dipilih
        document.querySelectorAll('input[name="kursi_id"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const jadwalKelas = this.getAttribute('data-jadwal-kelas');
                const harga = parseInt(this.getAttribute('data-harga'));
                const nomorKursi = this.getAttribute('data-nomor');

                document.getElementById('jadwal_kelas_bus_id').value = jadwalKelas;
                document.getElementById('total-harga').textContent = 'Rp ' + harga.toLocaleString('id-ID');

                // Show selected seat in summary
                const seatSummary = document.getElementById('selected-seat-summary');
                if (seatSummary) {
                    seatSummary.innerHTML = `<span class="text-emerald-600 font-semibold">Kursi ${nomorKursi} dipilih</span>`;
                }
            });
        });

        // Set initial harga jika ada yang di-select
        const checkedRadio = document.querySelector('input[name="kursi_id"]:checked');
        if (checkedRadio) {
            const harga = parseInt(checkedRadio.getAttribute('data-harga'));
            const nomorKursi = checkedRadio.getAttribute('data-nomor');
            document.getElementById('jadwal_kelas_bus_id').value = checkedRadio.getAttribute('data-jadwal-kelas');
            document.getElementById('total-harga').textContent = 'Rp ' + harga.toLocaleString('id-ID');

            const seatSummary = document.getElementById('selected-seat-summary');
            if (seatSummary) {
                seatSummary.innerHTML = `<span class="text-emerald-600 font-semibold">Kursi ${nomorKursi} dipilih</span>`;
            }
        }
    </script>
    @endpush
</x-admin-layout>
