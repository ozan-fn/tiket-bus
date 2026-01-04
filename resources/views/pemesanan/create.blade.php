@extends('layouts.admin')
@section('content')
    @push('header')
        <div class="flex items-center gap-2">
            <a href="{{ route('pemesanan.index') }}" class="text-muted-foreground hover:text-foreground">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Pesan Tiket</h2>
        </div>
    @endpush

    <div class="p-6 space-y-6">
        <!-- Perjalanan Summary -->
        <div class="bg-primary text-primary-foreground rounded-xl shadow-lg overflow-hidden">
            <div class="p-6 md:p-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-center">
                    <div class="space-y-1">
                        <p class="text-primary-foreground/70 text-xs font-bold uppercase tracking-wider">Asal</p>
                        <p class="text-2xl md:text-3xl font-bold">{{ $jadwal->rute->asalTerminal->nama_kota }}</p>
                        <p class="text-sm text-primary-foreground/80">{{ $jadwal->rute->asalTerminal->nama_terminal }}</p>
                    </div>
                    <div class="flex flex-col items-center justify-center gap-2">
                        <div class="h-px w-full bg-primary-foreground/20 relative">
                            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-primary px-2">
                                <i data-lucide="bus" class="w-6 h-6"></i>
                            </div>
                        </div>
                        <p class="text-xs font-medium">{{ $jadwal->tanggal_berangkat->format('d M Y') }}</p>
                    </div>
                    <div class="space-y-1 md:text-right">
                        <p class="text-primary-foreground/70 text-xs font-bold uppercase tracking-wider">Tujuan</p>
                        <p class="text-2xl md:text-3xl font-bold">{{ $jadwal->rute->tujuanTerminal->nama_kota }}</p>
                        <p class="text-sm text-primary-foreground/80">{{ $jadwal->rute->tujuanTerminal->nama_terminal }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-black/10 px-6 py-4 grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                <div class="flex items-center gap-2">
                    <i data-lucide="info" class="w-4 h-4 opacity-70"></i>
                    <span>{{ $jadwal->bus->nama }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <i data-lucide="clock" class="w-4 h-4 opacity-70"></i>
                    <span>{{ $jadwal->jam_berangkat->format('H:i') }} WIB</span>
                </div>
                <div class="flex items-center gap-2">
                    <i data-lucide="user" class="w-4 h-4 opacity-70"></i>
                    <span>{{ $jadwal->sopir->user->name }}</span>
                </div>
                @if($jadwal->conductor)
                    <div class="flex items-center gap-2">
                        <i data-lucide="users" class="w-4 h-4 opacity-70"></i>
                        <span>{{ $jadwal->conductor->user->name }}</span>
                    </div>
                @endif
            </div>
        </div>

        <form method="POST" action="{{ route('pemesanan.store', $jadwal) }}" class="space-y-8">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-8">
                    <!-- Pilih Kelas -->
                    <x-ui.card>
                        <x-ui.card.header>
                            <x-ui.card.title class="flex items-center gap-2">
                                <i data-lucide="layers" class="w-5 h-5 text-primary"></i>
                                Pilih Kelas Bus
                            </x-ui.card.title>
                            <x-ui.card.description>Pilih kelas bus yang sesuai dengan kenyamanan Anda</x-ui.card.description>
                        </x-ui.card.header>
                        <x-ui.card.content>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @forelse($jadwal->jadwalKelasBus as $jkb)
                                    <label class="relative cursor-pointer group">
                                        <input type="radio" name="jadwal_kelas_bus_id" value="{{ $jkb->id }}"
                                            {{ old('jadwal_kelas_bus_id') == $jkb->id ? 'checked' : '' }}
                                            class="sr-only peer" onchange="updateSeatGrid(this)">

                                        <div class="p-4 border-2 rounded-xl transition-all peer-checked:border-primary peer-checked:bg-primary/5 hover:border-primary/50 border-border bg-card">
                                            <div class="flex justify-between items-start mb-2">
                                                <h4 class="font-bold text-foreground">{{ $jkb->kelasBus->nama_kelas }}</h4>
                                                <div class="h-4 w-4 rounded-full border-2 border-muted peer-checked:border-primary peer-checked:bg-primary flex items-center justify-center">
                                                    <div class="h-1.5 w-1.5 rounded-full bg-white opacity-0 peer-checked:opacity-100"></div>
                                                </div>
                                            </div>
                                            <p class="text-lg font-bold text-primary">Rp {{ number_format($jkb->harga, 0, ',', '.') }}</p>
                                            <div class="flex items-center gap-2 mt-2 text-xs text-muted-foreground">
                                                <i data-lucide="armchair" class="w-3 h-3"></i>
                                                <span>{{ $jkb->busKelasBus->kursi->count() }} Kursi Tersedia</span>
                                            </div>
                                        </div>
                                    </label>
                                @empty
                                    <div class="col-span-full p-8 text-center border-2 border-dashed rounded-xl">
                                        <p class="text-muted-foreground">Tidak ada kelas bus tersedia</p>
                                    </div>
                                @endforelse
                            </div>

                            @error('jadwal_kelas_bus_id')
                                <p class="text-sm font-medium text-destructive mt-4">{{ $message }}</p>
                            @enderror
                        </x-ui.card.content>
                    </x-ui.card>

                    <!-- Pilih Kursi -->
                    <x-ui.card>
                        <x-ui.card.header>
                            <x-ui.card.title class="flex items-center gap-2">
                                <i data-lucide="armchair" class="w-5 h-5 text-primary"></i>
                                Pilih Kursi
                            </x-ui.card.title>
                            <x-ui.card.description>Klik pada kursi yang tersedia untuk memilih</x-ui.card.description>
                        </x-ui.card.header>
                        <x-ui.card.content class="space-y-6">
                            <div class="flex flex-wrap gap-6 p-4 bg-muted/50 rounded-xl border border-border/50">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 bg-primary rounded border border-primary"></div>
                                    <span class="text-xs font-medium">Terpilih</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 bg-card rounded border border-border"></div>
                                    <span class="text-xs font-medium">Tersedia</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 bg-muted rounded border border-muted-foreground/20 flex items-center justify-center">
                                        <i data-lucide="x" class="w-3 h-3 text-muted-foreground/50"></i>
                                    </div>
                                    <span class="text-xs font-medium">Terisi</span>
                                </div>
                            </div>

                            <div id="seat-grid" class="p-8 border-2 border-dashed rounded-xl text-center">
                                <p class="text-muted-foreground">Silakan pilih kelas bus terlebih dahulu untuk melihat denah kursi</p>
                            </div>

                            @error('kursi_id')
                                <p class="text-sm font-medium text-destructive">{{ $message }}</p>
                            @enderror

                            <div id="selected-seat-info" class="p-4 bg-primary/10 border border-primary/20 rounded-xl hidden">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="h-10 w-10 rounded-lg bg-primary flex items-center justify-center text-white">
                                            <i data-lucide="armchair" class="w-5 h-5"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs text-muted-foreground font-medium uppercase tracking-wider">Kursi Terpilih</p>
                                            <p class="text-lg font-bold text-primary" id="selected-seat-name">-</p>
                                        </div>
                                    </div>
                                    <i data-lucide="check-circle-2" class="w-6 h-6 text-primary"></i>
                                </div>
                            </div>
                        </x-ui.card.content>
                    </x-ui.card>

                    <!-- Data Penumpang -->
                    <x-ui.card>
                        <x-ui.card.header>
                            <x-ui.card.title class="flex items-center gap-2">
                                <i data-lucide="user-check" class="w-5 h-5 text-primary"></i>
                                Data Penumpang
                            </x-ui.card.title>
                            <x-ui.card.description>Lengkapi data penumpang sesuai dengan identitas resmi</x-ui.card.description>
                        </x-ui.card.header>
                        <x-ui.card.content class="grid md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <x-ui.label for="nama_penumpang">Nama Lengkap <span class="text-destructive">*</span></x-ui.label>
                                <x-ui.input type="text" name="nama_penumpang" id="nama_penumpang" value="{{ old('nama_penumpang', $user?->name) }}" required />
                                @error('nama_penumpang') <p class="text-xs text-destructive">{{ $message }}</p> @enderror
                            </div>

                            <div class="space-y-2">
                                <x-ui.label for="nik">NIK <span class="text-destructive">*</span></x-ui.label>
                                <x-ui.input type="text" name="nik" id="nik" value="{{ old('nik', $user?->nik) }}" placeholder="Nomor Identitas (KTP)" required />
                                @error('nik') <p class="text-xs text-destructive">{{ $message }}</p> @enderror
                            </div>

                            <div class="space-y-2">
                                <x-ui.label for="jenis_kelamin">Jenis Kelamin <span class="text-destructive">*</span></x-ui.label>
                                <select name="jenis_kelamin" id="jenis_kelamin" required class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="L" {{ old('jenis_kelamin', $user?->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="P" {{ old('jenis_kelamin', $user?->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                @error('jenis_kelamin') <p class="text-xs text-destructive">{{ $message }}</p> @enderror
                            </div>

                            <div class="space-y-2">
                                <x-ui.label for="tanggal_lahir">Tanggal Lahir <span class="text-destructive">*</span></x-ui.label>
                                <x-datepicker name="tanggal_lahir" id="tanggal_lahir" :value="old('tanggal_lahir', $user?->tanggal_lahir?->format('Y-m-d'))" required />
                                @error('tanggal_lahir') <p class="text-xs text-destructive">{{ $message }}</p> @enderror
                            </div>

                            <div class="space-y-2">
                                <x-ui.label for="nomor_telepon">Nomor Telepon <span class="text-destructive">*</span></x-ui.label>
                                <x-ui.input type="tel" name="nomor_telepon" id="nomor_telepon" value="{{ old('nomor_telepon', $user?->nomor_telepon) }}" placeholder="08..." required />
                                @error('nomor_telepon') <p class="text-xs text-destructive">{{ $message }}</p> @enderror
                            </div>

                            <div class="space-y-2">
                                <x-ui.label for="email">Email <span class="text-destructive">*</span></x-ui.label>
                                <x-ui.input type="email" name="email" id="email" value="{{ old('email', $user?->email) }}" required />
                                @error('email') <p class="text-xs text-destructive">{{ $message }}</p> @enderror
                            </div>
                        </x-ui.card.content>
                    </x-ui.card>
                </div>

                <div class="lg:col-span-1">
                    <div class="sticky top-6 space-y-6">
                        <x-ui.card>
                            <x-ui.card.header>
                                <x-ui.card.title>Ringkasan Pesanan</x-ui.card.title>
                            </x-ui.card.header>
                            <x-ui.card.content class="space-y-4">
                                <div class="space-y-3 pb-4 border-b border-border/50">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-muted-foreground">Kelas</span>
                                        <span class="font-bold" id="summary-class">-</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-muted-foreground">Nomor Kursi</span>
                                        <span class="font-bold text-primary" id="summary-seat">-</span>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-xs text-muted-foreground font-bold uppercase tracking-wider mb-1">Total Pembayaran</p>
                                    <p class="text-3xl font-bold text-primary" id="summary-price">Rp 0</p>
                                </div>
                            </x-ui.card.content>
                            <x-ui.card.footer class="flex flex-col gap-3">
                                <x-ui.button type="submit" class="w-full gap-2" id="btn-submit" disabled>
                                    <i data-lucide="credit-card" class="w-4 h-4"></i>
                                    Lanjut ke Pembayaran
                                </x-ui.button>
                                <p class="text-[10px] text-center text-muted-foreground">
                                    Dengan mengklik tombol di atas, Anda menyetujui Syarat & Ketentuan yang berlaku.
                                </p>
                            </x-ui.card.footer>
                        </x-ui.card>

                        <x-ui.card class="bg-muted/30 border-dashed">
                            <x-ui.card.content class="p-4 flex gap-3">
                                <i data-lucide="shield-check" class="w-5 h-5 text-primary shrink-0"></i>
                                <p class="text-xs text-muted-foreground">
                                    Pembayaran Anda aman dan terenkripsi. Tiket akan langsung dikirim setelah pembayaran dikonfirmasi.
                                </p>
                            </x-ui.card.content>
                        </x-ui.card>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        // Data jadwal dengan kursi
        const seatsData = @json($seatsData);
        
        console.log('Seats Data:', seatsData);

        const bookedIds = @json($bookedSeatIds);

        function sortSeats(kursis) {
            return [...kursis].sort((a, b) => a.nomor.localeCompare(b.nomor, undefined, {numeric: true, sensitivity: 'base'}));
        }

        function updateSeatGrid(element) {
            const jkbId = element.value;
            const data = seatsData[jkbId];
            console.log('Selected JKB ID:', jkbId);
            console.log('Selected Data:', data);
            
            const grid = document.getElementById('seat-grid');
            
            if (!data) {
                console.error('No data found for JKB ID:', jkbId);
                grid.innerHTML = '<div class="p-8 border-2 border-dashed rounded-xl text-center"><p class="text-muted-foreground">Data kelas tidak ditemukan</p></div>';
                return;
            }

            // Update summary class & price
            document.getElementById('summary-class').textContent = data.class;
            document.getElementById('summary-price').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(data.harga);

            // Sort seats
            const sortedKursis = sortSeats(data.kursis);
            console.log('Sorted Kursis:', sortedKursis);

            // Render seat grid
            let html = '<div class="max-w-xs mx-auto space-y-4">';
            
            // Driver area
            html += `
                <div class="flex justify-between items-center mb-8 pb-4 border-b border-border/50">
                    <div class="w-10 h-10 rounded-full bg-muted flex items-center justify-center">
                        <i data-lucide="circle-dot" class="w-6 h-6 text-muted-foreground"></i>
                    </div>
                    <div class="text-xs font-bold text-muted-foreground uppercase tracking-widest">Area Pengemudi</div>
                </div>
            `;

            if (sortedKursis.length === 0) {
                html += '<p class="text-center text-muted-foreground py-4">Tidak ada kursi tersedia untuk kelas ini.</p>';
            } else {
                for (let i = 0; i < sortedKursis.length; i += 4) {
                    const row = sortedKursis.slice(i, i + 4);
                    html += '<div class="flex justify-between gap-8">';
                    
                    // Left side (2 seats)
                    html += '<div class="flex gap-2">';
                    [row[0], row[1]].forEach(k => {
                        if (k) html += renderSeat(k);
                    });
                    html += '</div>';
                    
                    // Right side (2 seats)
                    html += '<div class="flex gap-2">';
                    [row[2], row[3]].forEach(k => {
                        if (k) html += renderSeat(k);
                    });
                    html += '</div>';
                    
                    html += '</div>';
                }
            }
            html += '</div>';
            
            grid.innerHTML = html;
            grid.classList.remove('p-8', 'border-2', 'border-dashed', 'text-center');
            
            if (window.lucide) {
                window.lucide.createIcons();
            }
            
            // Reset selection
            document.getElementById('summary-seat').textContent = '-'; 
            document.getElementById('btn-submit').disabled = true;
            document.getElementById('selected-seat-info').classList.add('hidden');
        }

        function renderSeat(k) {
            const isBooked = bookedIds.includes(k.id);
            const oldKursiId = @json(old('kursi_id'));
            const isSelected = oldKursiId == k.id;
            
            return `
                <label class="relative group">
                    <input type="radio" name="kursi_id" value="${k.id}" 
                        ${isSelected ? 'checked' : ''} 
                        ${isBooked ? 'disabled' : ''}
                        class="sr-only peer" onchange="updateSeatInfo('${k.nomor}')">
                    
                    <div class="w-10 h-10 rounded-lg border-2 transition-all flex items-center justify-center text-[10px] font-bold
                        ${isBooked ? 'bg-muted border-muted-foreground/10 text-muted-foreground/30 cursor-not-allowed' : 'bg-card border-border text-foreground hover:border-primary/50 cursor-pointer'}
                        peer-checked:bg-primary peer-checked:border-primary peer-checked:text-primary-foreground">
                        ${isBooked ? '<i data-lucide="x" class="w-3 h-3"></i>' : k.nomor}
                    </div>
                </label>
            `;
        }

        function updateSeatInfo(nomor) {
            console.log('Selected Seat Number:', nomor);
            document.getElementById('summary-seat').textContent = nomor;
            document.getElementById('selected-seat-name').textContent = nomor;
            document.getElementById('selected-seat-info').classList.remove('hidden');
            document.getElementById('btn-submit').disabled = false;
        }

        // Initialize if old value exists
        window.addEventListener('DOMContentLoaded', () => {
            const checkedRadio = document.querySelector('input[name="jadwal_kelas_bus_id"]:checked');
            if (checkedRadio) {
                updateSeatGrid(checkedRadio);
                const oldKursiId = @json(old('kursi_id'));
                if (oldKursiId) {
                    const kursiRadio = document.querySelector(`input[name="kursi_id"][value="${oldKursiId}"]`);
                    if (kursiRadio) {
                        kursiRadio.checked = true;
                        // Find the seat number from the label text or data
                        const label = kursiRadio.closest('label');
                        const nomor = label.innerText.trim() || label.querySelector('div').innerText.trim();
                        updateSeatInfo(nomor);
                    }
                }
            }
        });
    </script>
    @endpush
@endsection
