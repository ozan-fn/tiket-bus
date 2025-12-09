<x-admin-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="text-xl sm:text-2xl font-bold">Tambah Jadwal Kelas Bus</h2>
                <p class="text-sm text-muted-foreground mt-1">Buat jadwal kelas bus baru dengan harga</p>
            </div>
            <a href="{{ route('admin/jadwal-kelas-bus.index') }}" class="w-full sm:w-auto">
                <button class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2 w-full sm:w-auto">
                    <x-lucide-arrow-left class="w-4 h-4 mr-2" />
                    Kembali
                </button>
            </a>
        </div>
    </x-slot>

    <div class="p-4 sm:p-6">
        @if(session('error'))
            <div class="relative w-full rounded-lg border border-red-200 bg-red-50 p-4 text-red-800 dark:border-red-800 dark:bg-red-900/50 dark:text-red-200 mb-6">
                <div class="flex items-start gap-3">
                    <x-lucide-alert-circle class="w-4 h-4 mt-0.5" />
                    <div class="flex-1">
                        <h5 class="font-medium">Error!</h5>
                        <p class="text-sm opacity-90">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
            <div class="flex flex-col space-y-1.5 p-6">
                <h3 class="text-2xl font-semibold leading-none tracking-tight">Form Jadwal Kelas Bus</h3>
                <p class="text-sm text-muted-foreground">Isi formulir di bawah untuk menambah jadwal kelas bus baru</p>
            </div>
            <div class="p-6 pt-0">
                <form method="POST" action="{{ route('admin/jadwal-kelas-bus.store') }}" class="space-y-6">
                    @csrf

                    <div class="space-y-2">
                        <label for="jadwal_id" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                            Jadwal <span class="text-destructive">*</span>
                        </label>
                        <select name="jadwal_id" id="jadwal_id" required class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                            <option value="">Pilih Jadwal</option>
                            @foreach($jadwals as $jadwal)
                                <option value="{{ $jadwal->id }}" {{ old('jadwal_id') == $jadwal->id ? 'selected' : '' }}>
                                    {{ $jadwal->rute->asalTerminal->nama }} → {{ $jadwal->rute->tujuanTerminal->nama }} |
                                    {{ $jadwal->bus->nama }} ({{ $jadwal->bus->plat_nomor }}) |
                                    {{ \Carbon\Carbon::parse($jadwal->tanggal_berangkat)->format('d/m/Y') }} {{ \Carbon\Carbon::parse($jadwal->waktu_berangkat)->format('H:i') }}
                                </option>
                            @endforeach
                        </select>
                        @error('jadwal_id')
                            <p class="text-sm text-destructive">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-muted-foreground">Pilih jadwal yang akan dikaitkan dengan kelas bus</p>
                    </div>

                    <div class="space-y-2">
                        <label for="kelas_bus_id" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                            Kelas Bus <span class="text-destructive">*</span>
                        </label>
                        <select name="kelas_bus_id" id="kelas_bus_id" required class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                            <option value="">Pilih Kelas Bus</option>
                            @foreach($kelasBuses as $kelas)
                                <option value="{{ $kelas->id }}" {{ old('kelas_bus_id') == $kelas->id ? 'selected' : '' }}>
                                    {{ $kelas->nama_kelas }} - {{ $kelas->bus->nama }} ({{ $kelas->jumlah_kursi }} kursi)
                                </option>
                            @endforeach
                        </select>
                        @error('kelas_bus_id')
                            <p class="text-sm text-destructive">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-muted-foreground">Pilih kelas bus yang tersedia</p>
                    </div>

                    <div class="space-y-2">
                        <label for="harga" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                            Harga <span class="text-destructive">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-muted-foreground">Rp</span>
                            <input type="number" name="harga" id="harga" value="{{ old('harga') }}" required min="0" step="1000" class="flex h-10 w-full rounded-md border border-input bg-background pl-10 pr-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50" placeholder="0">
                        </div>
                        @error('harga')
                            <p class="text-sm text-destructive">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-muted-foreground">Masukkan harga tiket untuk kelas bus ini</p>
                    </div>

                    <div class="flex flex-col-reverse sm:flex-row gap-3 pt-4">
                        <a href="{{ route('admin/jadwal-kelas-bus.index') }}" class="w-full sm:w-auto">
                            <button type="button" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2 w-full sm:w-auto">
                                <x-lucide-x class="w-4 h-4 mr-2" />
                                Batal
                            </button>
                        </a>
                        <button type="submit" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 py-2 w-full sm:w-auto">
                            <x-lucide-save class="w-4 h-4 mr-2" />
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>
