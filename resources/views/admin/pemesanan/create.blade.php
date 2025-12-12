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
        <div class="max-w-4xl mx-auto">
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

                    <form method="POST" action="{{ route('admin/pemesanan.store', $jadwal) }}">
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
                                    <x-lucide-chair class="w-4 h-4" />
                                    Pilih Kursi & Kelas
                                </h3>

                                <div class="space-y-4">
                                    @forelse($jadwal->jadwalKelasBus as $jkb)
                                        <div class="p-4 border rounded-lg">
                                            <p class="font-semibold mb-3">{{ $jkb->kelasBus->nama_kelas }}</p>
                                            <div class="grid grid-cols-auto gap-3">
                                                @foreach($jkb->kelasBus->kursi as $kursi)
                                                    <label class="flex items-center gap-2 cursor-pointer p-2 rounded-md hover:bg-accent">
                                                        <input
                                                            type="radio"
                                                            name="kursi_id"
                                                            value="{{ $kursi->id }}"
                                                            data-jadwal-kelas="{{ $jkb->id }}"
                                                            data-harga="{{ $jkb->harga }}"
                                                            @if(in_array($kursi->id, $kursiTerpakai)) disabled @endif
                                                            {{ old('kursi_id') == $kursi->id ? 'checked' : '' }}
                                                            required
                                                        />
                                                        <span class="text-sm font-medium {{ in_array($kursi->id, $kursiTerpakai) ? 'text-muted-foreground line-through' : '' }}">
                                                            {{ $kursi->nomor_kursi }}
                                                        </span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-muted-foreground">Tidak ada kelas bus yang tersedia</p>
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
                            <x-ui.button type="submit" class="w-full sm:w-auto">
                                <x-lucide-save class="w-4 h-4 mr-2" />
                                Pesan Tiket
                            </x-ui.button>
                        </div>
                    </form>
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

                document.getElementById('jadwal_kelas_bus_id').value = jadwalKelas;
                document.getElementById('total-harga').textContent = 'Rp ' + harga.toLocaleString('id-ID');
            });
        });

        // Set initial harga jika ada yang di-select
        const checkedRadio = document.querySelector('input[name="kursi_id"]:checked');
        if (checkedRadio) {
            const harga = parseInt(checkedRadio.getAttribute('data-harga'));
            document.getElementById('jadwal_kelas_bus_id').value = checkedRadio.getAttribute('data-jadwal-kelas');
            document.getElementById('total-harga').textContent = 'Rp ' + harga.toLocaleString('id-ID');
        }
    </script>
    @endpush
</x-admin-layout>
