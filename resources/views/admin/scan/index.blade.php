<x-admin-layout>
    <x-slot name="header">
        <x-ui.breadcrumb.breadcrumb>
            <x-ui.breadcrumb.list class="text-xs">
                <x-ui.breadcrumb.item>
                    <x-ui.breadcrumb.link href="{{ route('dashboard') }}">Home</x-ui.breadcrumb.link>
                </x-ui.breadcrumb.item>
                <x-ui.breadcrumb.separator>
                    <x-lucide-chevron-right class="w-3.5 h-3.5" />
                </x-ui.breadcrumb.separator>
                <x-ui.breadcrumb.item>
                    <x-ui.breadcrumb.page>Scan Tiket</x-ui.breadcrumb.page>
                </x-ui.breadcrumb.item>
            </x-ui.breadcrumb.list>
        </x-ui.breadcrumb.breadcrumb>
    </x-slot>

    <div class="p-4 sm:p-6" x-data="scanForm()">
        <div class="max-w-2xl mx-auto space-y-6">

            <x-ui.card>
                <x-ui.card.header>
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-primary/10 rounded-lg">
                            <x-lucide-qr-code class="w-6 h-6 text-primary" />
                        </div>
                        <div>
                            <x-ui.card.title>Scan Tiket Penumpang</x-ui.card.title>
                            <x-ui.card.description>Gunakan kamera atau input kode manual</x-ui.card.description>
                        </div>
                    </div>
                </x-ui.card.header>

                <x-ui.card.content>
                    <div x-data="{ activeTab: 'manual' }" class="space-y-6">
                        <!-- Tabs Navigation -->
                        <div class="flex gap-4 border-b border-border">
                            <button
                                @click="activeTab = 'manual'"
                                :class="activeTab === 'manual' ? 'border-b-2 border-primary text-primary' : 'text-muted-foreground hover:text-foreground'"
                                class="pb-3 px-4 font-medium transition-colors text-sm"
                            >
                                <span class="flex items-center gap-2">
                                    <x-lucide-keyboard class="w-4 h-4" />
                                    Input Manual
                                </span>
                            </button>
                            <button
                                @click="activeTab = 'camera'"
                                :class="activeTab === 'camera' ? 'border-b-2 border-primary text-primary' : 'text-muted-foreground hover:text-foreground'"
                                class="pb-3 px-4 font-medium transition-colors text-sm"
                            >
                                <span class="flex items-center gap-2">
                                    <x-lucide-camera class="w-4 h-4" />
                                    Scan Kamera
                                </span>
                            </button>
                        </div>

                        <!-- Manual Input Tab -->
                        <div x-show="activeTab === 'manual'" x-transition class="space-y-4">
                            <div class="flex gap-2">
                                <input
                                    type="text"
                                    x-model="kodeTiket"
                                    @keydown.enter="verifyTicket()"
                                    placeholder="Masukkan kode tiket..."
                                    class="flex-1 px-4 py-2 border border-border rounded-lg bg-background text-foreground focus:ring-2 focus:ring-primary outline-none"
                                />
                                <button
                                    @click="verifyTicket()"
                                    :disabled="loading || !kodeTiket"
                                    class="px-6 py-2 bg-primary text-primary-foreground rounded-lg hover:bg-primary/90 disabled:opacity-50 transition-colors flex items-center gap-2"
                                >
                                    <x-lucide-search x-show="!loading" class="w-4 h-4" />
                                    <span x-text="loading ? '...' : 'Cek'"></span>
                                </button>
                            </div>
                            <template x-if="error">
                                <p class="text-sm text-destructive mt-2" x-text="error"></p>
                            </template>
                        </div>

                        <!-- Camera Tab -->
                        <div x-show="activeTab === 'camera'" x-transition class="space-y-4">
                            <div x-show="!showScanner" class="text-center">
                                <button
                                    @click="toggleScanner()"
                                    class="w-full flex flex-col items-center justify-center gap-3 py-8 border-2 border-dashed border-muted-foreground/20 rounded-xl hover:bg-accent/50 transition-all group"
                                >
                                    <div class="p-3 bg-primary/10 rounded-full group-hover:scale-110 transition-transform">
                                        <x-lucide-camera class="w-8 h-8 text-primary" />
                                    </div>
                                    <span class="font-medium">Buka Scanner Kamera</span>
                                </button>
                            </div>

                            <div x-show="showScanner" x-transition>
                                <div id="reader" class="overflow-hidden rounded-xl border-2 border-primary/20 bg-black aspect-square"></div>
                                <button @click="toggleScanner()" class="w-full mt-3 py-2 text-sm font-medium text-destructive hover:bg-destructive/10 rounded-lg">
                                    Tutup Kamera
                                </button>
                            </div>
                        </div>

                        <div x-show="result !== null" x-transition
                             :class="result.success ? 'border border-green-200 bg-green-50 dark:bg-green-900/10 rounded-xl p-5' : 'border border-red-200 bg-red-50 dark:bg-red-900/10 rounded-xl p-5'">

                            <div class="space-y-4">
                                <div class="flex items-center gap-3">
                                    <div :class="result.success ? 'text-green-600' : 'text-red-600'">
                                        <x-lucide-check-circle x-show="result.success" class="w-6 h-6" />
                                        <x-lucide-x-circle x-show="!result.success" class="w-6 h-6" />
                                    </div>
                                    <h3 class="font-bold text-foreground" x-text="result?.message"></h3>
                                </div>

                                <template x-if="result?.data">
                                    <div class="space-y-4 pt-2 border-t border-border/50">
                                        <!-- Header Info -->
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <p class="text-[10px] text-muted-foreground uppercase">Kode Tiket</p>
                                                <p class="font-bold text-sm text-primary" x-text="result.data.kode_tiket"></p>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-[10px] text-muted-foreground uppercase">Status</p>
                                                <span :class="result.data.status === 'dibayar' ? 'inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800' : (result.data.status === 'dipesan' ? 'inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800' : (result.data.status === 'selesai' ? 'inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800' : (result.data.status === 'batal' ? 'inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800' : 'inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800')))" x-text="result.data.status || 'N/A'"></span>
                                            </div>
                                        </div>

                                        <!-- Passenger Info -->
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <p class="text-[10px] text-muted-foreground uppercase">Nama Penumpang</p>
                                                <p class="font-bold text-sm" x-text="result.data.nama_penumpang || 'N/A'"></p>
                                            </div>
                                            <div>
                                                <p class="text-[10px] text-muted-foreground uppercase">NIK</p>
                                                <p class="font-bold text-sm" x-text="result.data.nik || 'N/A'"></p>
                                            </div>
                                            <div>
                                                <p class="text-[10px] text-muted-foreground uppercase">Jenis Kelamin</p>
                                                <p class="font-bold text-sm" x-text="result.data.jenis_kelamin === 'L' ? 'Laki-laki' : (result.data.jenis_kelamin === 'P' ? 'Perempuan' : 'N/A')"></p>
                                            </div>
                                            <div>
                                                <p class="text-[10px] text-muted-foreground uppercase">Nomor Telepon</p>
                                                <p class="font-bold text-sm" x-text="result.data.nomor_telepon || 'N/A'"></p>
                                            </div>
                                            <div>
                                                <p class="text-[10px] text-muted-foreground uppercase">Email</p>
                                                <p class="font-bold text-sm" x-text="result.data.email || 'N/A'"></p>
                                            </div>
                                            <div>
                                                <p class="text-[10px] text-muted-foreground uppercase">Kursi</p>
                                                <p class="font-bold text-sm text-primary" x-text="result.data.kursi || 'N/A'"></p>
                                            </div>
                                        </div>

                                        <!-- Schedule Info -->
                                        <div class="p-3 bg-background/50 rounded-lg border">
                                            <p class="text-[10px] text-muted-foreground uppercase mb-2 text-center">Informasi Perjalanan</p>
                                            <div class="space-y-2">
                                                <div class="flex justify-between">
                                                    <span class="text-xs text-muted-foreground">Tanggal:</span>
                                                    <span class="font-bold text-xs" x-text="result.data.jadwal && result.data.jadwal.tanggal_berangkat ? result.data.jadwal.tanggal_berangkat.split('T')[0] : 'N/A'"></span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <span class="text-xs text-muted-foreground">Jam:</span>
                                                    <span class="font-bold text-xs" x-text="result.data.jadwal && result.data.jadwal.jam_berangkat ? result.data.jadwal.jam_berangkat.split('T')[1].split('.')[0] : 'N/A'"></span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <span class="text-xs text-muted-foreground">Kelas:</span>
                                                    <span class="font-bold text-xs" x-text="result.data.kelas || 'N/A'"></span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <span class="text-xs text-muted-foreground">Harga:</span>
                                                    <span class="font-bold text-xs text-green-600" x-text="result.data.harga ? 'Rp ' + result.data.harga.toLocaleString('id-ID') : 'N/A'"></span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Route -->
                                        <div class="p-3 bg-primary/5 rounded-lg border border-primary/20">
                                            <p class="text-[10px] text-muted-foreground uppercase mb-1 text-center">Rute Perjalanan</p>
                                            <div class="flex justify-between items-center px-4">
                                                <span class="font-bold text-sm" x-text="result.data.jadwal && result.data.jadwal.asal ? result.data.jadwal.asal : 'N/A'"></span>
                                                <x-lucide-arrow-right class="w-4 h-4 text-primary" />
                                                <span class="font-bold text-sm" x-text="result.data.jadwal && result.data.jadwal.tujuan ? result.data.jadwal.tujuan : 'N/A'"></span>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </x-ui.card.content>
            </x-ui.card>
        </div>
    </div>

    {{-- Push Scripts --}}
    @push('scripts')
    <script src="https://unpkg.com/html5-qrcode"></script>
    <script>
        function scanForm() {
            return {
                kodeTiket: '',
                loading: false,
                error: '',
                result: null,
                showScanner: false,
                html5QrCode: null,

                async toggleScanner() {
                    this.showScanner = !this.showScanner;
                    if (this.showScanner) {
                        this.$nextTick(() => this.startScan());
                    } else {
                        this.stopScan();
                    }
                },

                async startScan() {
                    console.log('Starting scan...');
                    this.html5QrCode = new Html5Qrcode("reader");
                    const config = {
                        fps: 15,
                        qrbox: { width: 300, height: 300 },
                        aspectRatio: 1.0,
                        formatsToSupport: [ Html5QrcodeSupportedFormats.QR_CODE ],
                        experimentalFeatures: {
                            useBarCodeDetectorIfSupported: true
                        }
                    };

                    try {
                        console.log('Initializing Html5Qrcode...');
                        await this.html5QrCode.start(
                            { facingMode: "environment" },
                            config,
                            (decodedText) => {
                                console.log('QR Code detected:', decodedText);
                                if (navigator.vibrate) navigator.vibrate(100);
                                this.kodeTiket = decodedText;
                                this.toggleScanner(); // Matikan kamera
                                this.verifyTicket(); // Jalankan verifikasi
                            }
                        );
                        console.log('Scan started successfully');
                    } catch (err) {
                        console.error('Error starting scan:', err);
                        this.error = "Gagal mengakses kamera: " + err.message;
                        this.showScanner = false;
                    }
                },

                async stopScan() {
                    if (this.html5QrCode && this.html5QrCode.isScanning) {
                        await this.html5QrCode.stop();
                        this.html5QrCode = null;
                    }
                },

                async verifyTicket() {
                    if (!this.kodeTiket.trim()) return;

                    this.loading = true;
                    this.error = '';
                    this.result = null;

                    try {
                        const response = await fetch('{{ route("admin/scan.verify") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content,
                            },
                            body: JSON.stringify({ kode_tiket: this.kodeTiket.trim() }),
                        });

                        const data = await response.json();
                        this.result = data;
                    } catch (error) {
                        this.error = 'Terjadi kesalahan sistem.';
                    } finally {
                        this.loading = false;
                    }
                }
            };
        }
    </script>
    @endpush
</x-admin-layout>
