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
                    <x-ui.breadcrumb.page>
                        Scan Tiket
                    </x-ui.breadcrumb.page>
                </x-ui.breadcrumb.item>
            </x-ui.breadcrumb.list>
        </x-ui.breadcrumb.breadcrumb>
    </x-slot>

    <div class="p-4 sm:p-6">
        <div class="max-w-7xl mx-auto space-y-6">
            <!-- Scan Card -->
            <x-ui.card>
                <x-ui.card.header>
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-primary/10 rounded-lg">
                            <x-lucide-qr-code class="w-6 h-6 text-primary" />
                        </div>
                        <div>
                            <x-ui.card.title>Scan Tiket Penumpang</x-ui.card.title>
                            <x-ui.card.description>Masukkan kode tiket atau scan QR code untuk memverifikasi tiket penumpang</x-ui.card.description>
                        </div>
                    </div>
                </x-ui.card.header>
                <x-ui.card.content>
                    <!-- Tab Navigation -->
                    <div x-data="{ activeTab: 'manual' }" class="space-y-6">
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
                        <div x-show="activeTab === 'manual'" x-transition class="space-y-4" x-data="scanForm()">
                            <div>
                                <label for="kode_tiket" class="block text-sm font-medium text-foreground mb-2">
                                    Kode Tiket
                                </label>
                                <input
                                    type="text"
                                    id="kode_tiket"
                                    x-model="kodeTiket"
                                    @keydown.enter="verifyTicket()"
                                    placeholder="Masukkan kode tiket..."
                                    class="w-full px-4 py-2 border border-border rounded-lg bg-background text-foreground placeholder-muted-foreground focus:ring-2 focus:ring-primary focus:border-transparent outline-none"
                                    autocomplete="off"
                                />
                                <template x-if="error">
                                    <p class="text-sm text-destructive mt-2" x-text="error"></p>
                                </template>
                            </div>

                            <button
                                @click="verifyTicket()"
                                :disabled="loading"
                                class="w-full sm:w-auto px-6 py-2 bg-primary text-primary-foreground rounded-lg hover:bg-primary/90 transition-colors disabled:opacity-50 disabled:cursor-not-allowed font-medium flex items-center justify-center gap-2"
                            >
                                <span x-show="!loading" class="flex items-center gap-2">
                                    <x-lucide-search class="w-4 h-4" />
                                    Verifikasi Tiket
                                </span>
                                <span x-show="loading" class="flex items-center gap-2">
                                    <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Memverifikasi...
                                </span>
                            </button>
                        </div>

                        <!-- Camera Tab -->
                        <div x-show="activeTab === 'camera'" x-transition class="space-y-4" x-data="cameraScanner()">
                            <!-- Camera Status -->
                            <div x-show="!cameraActive && !cameraError" class="text-center">
                                <button
                                    @click="startCamera()"
                                    :disabled="loading"
                                    class="w-full sm:w-auto px-6 py-2 bg-primary text-primary-foreground rounded-lg hover:bg-primary/90 transition-colors disabled:opacity-50 disabled:cursor-not-allowed font-medium flex items-center justify-center gap-2"
                                >
                                    <x-lucide-camera class="w-4 h-4" />
                                    Buka Kamera
                                </button>
                            </div>

                            <!-- Camera Error -->
                            <template x-if="cameraError">
                                <div class="p-4 bg-destructive/10 border border-destructive/30 rounded-lg">
                                    <p class="text-sm text-destructive" x-text="cameraError"></p>
                                    <button
                                        @click="resetCamera()"
                                        class="mt-2 px-3 py-1 bg-destructive text-destructive-foreground rounded text-sm hover:bg-destructive/90 transition-colors"
                                    >
                                        Coba Lagi
                                    </button>
                                </div>
                            </template>

                            <!-- Video Stream -->
                            <div x-show="cameraActive" class="relative rounded-lg overflow-hidden border border-border bg-black">
                                <video
                                    x-ref="videoElement"
                                    id="video-stream"
                                    width="400"
                                    height="400"
                                    class="w-full h-auto"
                                    playsinline
                                ></video>

                                <!-- QR Code Scanner Overlay -->
                                <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                    <div class="w-64 h-64 border-2 border-primary rounded-lg opacity-50"></div>
                                </div>

                                <!-- Close Camera Button -->
                                <button
                                    @click="stopCamera()"
                                    class="absolute top-2 right-2 p-2 bg-black/50 hover:bg-black/75 rounded-lg text-white transition-colors"
                                >
                                    <x-lucide-x class="w-5 h-5" />
                                </button>

                                <!-- Scanning Status -->
                                <div class="absolute bottom-4 left-0 right-0 text-center">
                                    <div x-show="scanning" class="inline-block px-3 py-1 bg-yellow-500/80 text-white text-sm rounded">
                                        Memindai QR Code...
                                    </div>
                                    <div x-show="!scanning && cameraActive" class="inline-block px-3 py-1 bg-green-500/80 text-white text-sm rounded">
                                        Kamera Aktif
                                    </div>
                                </div>
                            </div>

                            <!-- Camera Controls -->
                            <div x-show="cameraActive" class="flex gap-2">
                                <button
                                    @click="captureFrame()"
                                    :disabled="scanning"
                                    class="flex-1 px-4 py-2 bg-primary text-primary-foreground rounded-lg hover:bg-primary/90 transition-colors disabled:opacity-50 disabled:cursor-not-allowed font-medium flex items-center justify-center gap-2"
                                >
                                    <x-lucide-camera class="w-4 h-4" />
                                    Ambil Foto
                                </button>
                                <button
                                    @click="stopCamera()"
                                    class="flex-1 px-4 py-2 bg-accent text-foreground rounded-lg hover:bg-accent/80 transition-colors font-medium flex items-center justify-center gap-2"
                                >
                                    <x-lucide-x class="w-4 h-4" />
                                    Tutup
                                </button>
                            </div>
                        </div>
                    </div>
                </x-ui.card.content>
            </x-ui.card>

            <!-- Features Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <x-ui.card>
                    <x-ui.card.content class="pt-6">
                        <div class="flex flex-col items-center text-center space-y-2">
                            <div class="p-2 bg-primary/10 rounded-lg">
                                <x-lucide-check-circle class="w-6 h-6 text-primary" />
                            </div>
                            <h3 class="font-semibold text-foreground">Verifikasi Tiket</h3>
                            <p class="text-xs text-muted-foreground">Scan QR code atau masukkan kode tiket</p>
                        </div>
                    </x-ui.card.content>
                </x-ui.card>

                <x-ui.card>
                    <x-ui.card.content class="pt-6">
                        <div class="flex flex-col items-center text-center space-y-2">
                            <div class="p-2 bg-primary/10 rounded-lg">
                                <x-lucide-users class="w-6 h-6 text-primary" />
                            </div>
                            <h3 class="font-semibold text-foreground">Kelola Penumpang</h3>
                            <p class="text-xs text-muted-foreground">Pantau kehadiran secara real-time</p>
                        </div>
                    </x-ui.card.content>
                </x-ui.card>

                <x-ui.card>
                    <x-ui.card.content class="pt-6">
                        <div class="flex flex-col items-center text-center space-y-2">
                            <div class="p-2 bg-primary/10 rounded-lg">
                                <x-lucide-file-text class="w-6 h-6 text-primary" />
                            </div>
                            <h3 class="font-semibold text-foreground">Laporan Kehadiran</h3>
                            <p class="text-xs text-muted-foreground">Buat laporan untuk setiap perjalanan</p>
                        </div>
                    </x-ui.card.content>
                </x-ui.card>

                <x-ui.card>
                    <x-ui.card.content class="pt-6">
                        <div class="flex flex-col items-center text-center space-y-2">
                            <div class="p-2 bg-primary/10 rounded-lg">
                                <x-lucide-shield-check class="w-6 h-6 text-primary" />
                            </div>
                            <h3 class="font-semibold text-foreground">Keamanan Tinggi</h3>
                            <p class="text-xs text-muted-foreground">Enkripsi data penumpang</p>
                        </div>
                    </x-ui.card.content>
                </x-ui.card>
            </div>
        </div>
    </div>

    <!-- Verification Dialog -->
    <template x-teleport="body">
        <div x-show="dialogState.open" x-cloak class="fixed inset-0 z-50 overflow-y-auto" @keydown.escape.window="closeVerificationDialog()">
            <!-- Overlay -->
            <div x-show="dialogState.open" x-transition.opacity class="fixed inset-0 bg-black/50" @click="closeVerificationDialog()"></div>

            <!-- Dialog -->
            <div class="flex min-h-full items-center justify-center p-4">
                <div x-show="dialogState.open" x-transition class="relative bg-card border border-border rounded-lg shadow-lg w-full max-w-2xl">
                    <!-- Close Button -->
                    <button onclick="closeVerificationDialog()" class="absolute top-4 right-4 text-muted-foreground hover:text-foreground transition-colors z-10">
                        <x-lucide-x class="w-5 h-5" />
                    </button>

                    <!-- Content -->
                    <div class="p-6">
                        <!-- Success State -->
                        <template x-if="dialogState.ticketData && !dialogState.ticketData.error">
                            <div class="space-y-6">
                                <div class="text-center">
                                    <div class="flex justify-center mb-4">
                                        <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-full">
                                            <x-lucide-check-circle class="w-12 h-12 text-green-600 dark:text-green-400" />
                                        </div>
                                    </div>
                                    <h2 class="text-2xl font-bold text-foreground mb-2">Tiket Valid</h2>
                                    <p class="text-muted-foreground">Tiket penumpang telah terverifikasi</p>
                                </div>

                                <!-- Ticket Details -->
                                <div class="space-y-4 bg-accent/30 rounded-lg p-4">
                                    <!-- Header Info -->
                                    <div class="flex justify-between items-start border-b border-border pb-4">
                                        <div>
                                            <p class="text-xs text-muted-foreground uppercase tracking-wider">Kode Tiket</p>
                                            <p class="text-lg font-bold text-foreground" x-text="dialogState.ticketData?.kode_tiket"></p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-xs text-muted-foreground uppercase tracking-wider">Status</p>
                                            <p class="text-lg font-bold" :class="dialogState.ticketData?.status === 'aktif' ? 'text-green-600 dark:text-green-400' : 'text-yellow-600 dark:text-yellow-400'" x-text="dialogState.ticketData?.status?.toUpperCase()"></p>
                                        </div>
                                    </div>

                                    <!-- Passenger Info -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-xs text-muted-foreground uppercase tracking-wider">Nama Penumpang</p>
                                            <p class="text-sm font-semibold text-foreground" x-text="dialogState.ticketData?.nama_penumpang"></p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-muted-foreground uppercase tracking-wider">NIK</p>
                                            <p class="text-sm font-semibold text-foreground" x-text="dialogState.ticketData?.nik"></p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-muted-foreground uppercase tracking-wider">Jenis Kelamin</p>
                                            <p class="text-sm font-semibold text-foreground" x-text="dialogState.ticketData?.jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan'"></p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-muted-foreground uppercase tracking-wider">Nomor Telepon</p>
                                            <p class="text-sm font-semibold text-foreground" x-text="dialogState.ticketData?.nomor_telepon"></p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-muted-foreground uppercase tracking-wider">Email</p>
                                            <p class="text-sm font-semibold text-foreground" x-text="dialogState.ticketData?.email"></p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex gap-3">
                                    <button
                                        @click="scanAnother()"
                                        class="flex-1 px-4 py-2 bg-primary text-primary-foreground rounded-lg hover:bg-primary/90 transition-colors font-medium"
                                    >
                                        Scan Lagi
                                    </button>
                                    <button
                                        @click="closeVerificationDialog()"
                                        class="flex-1 px-4 py-2 bg-accent text-foreground rounded-lg hover:bg-accent/80 transition-colors font-medium"
                                    >
                                        Tutup
                                    </button>
                                </div>
                            </div>
                        </template>

                        <!-- Error State -->
                        <template x-if="dialogState.ticketData && dialogState.ticketData.error">
                            <div class="space-y-6">
                                <div class="text-center">
                                    <div class="flex justify-center mb-4">
                                        <div class="p-3 bg-red-100 dark:bg-red-900/30 rounded-full">
                                            <x-lucide-x-circle class="w-12 h-12 text-red-600 dark:text-red-400" />
                                        </div>
                                    </div>
                                    <h2 class="text-2xl font-bold text-foreground mb-2">Tiket Tidak Valid</h2>
                                    <p class="text-destructive" x-text="dialogState.ticketData?.message"></p>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex gap-3">
                                    <button
                                        @click="scanAnother()"
                                        class="flex-1 px-4 py-2 bg-primary text-primary-foreground rounded-lg hover:bg-primary/90 transition-colors font-medium"
                                    >
                                        Coba Lagi
                                    </button>
                                    <button
                                        @click="closeVerificationDialog()"
                                        class="flex-1 px-4 py-2 bg-accent text-foreground rounded-lg hover:bg-accent/80 transition-colors font-medium"
                                    >
                                        Tutup
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <!-- Load jsQR library -->
    <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.js"></script>

    <script>
        let dialogState = {
            open: false,
            ticketData: null,
        };

        function scanForm() {
            return {
                kodeTiket: '',
                loading: false,
                error: '',

                async verifyTicket() {
                    if (!this.kodeTiket.trim()) {
                        this.error = 'Masukkan kode tiket terlebih dahulu';
                        return;
                    }

                    this.loading = true;
                    this.error = '';

                    try {
                        const response = await fetch('{{ route("admin/scan.verify") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content,
                            },
                            body: JSON.stringify({
                                kode_tiket: this.kodeTiket.trim(),
                            }),
                        });

                        const data = await response.json();

                        if (data.success) {
                            showVerificationDialog(data.data);
                        } else {
                            showVerificationDialog({
                                error: true,
                                message: data.message,
                            });
                        }
                    } catch (error) {
                        this.error = 'Terjadi kesalahan: ' + error.message;
                        console.error('Error:', error);
                    } finally {
                        this.loading = false;
                    }
                },
            };
        }

        function cameraScanner() {
            return {
                cameraActive: false,
                scanning: false,
                cameraError: '',
                loading: false,
                stream: null,

                async startCamera() {
                    this.loading = true;
                    this.cameraError = '';

                    try {
                        const stream = await navigator.mediaDevices.getUserMedia({
                            video: { facingMode: 'environment' },
                        });

                        this.stream = stream;
                        this.cameraActive = true;
                        this.loading = false;

                        this.$nextTick(() => {
                            const video = this.$refs.videoElement;
                            if (video) {
                                video.srcObject = stream;
                                video.play().catch(err => {
                                    console.error('Error playing video:', err);
                                    this.cameraError = 'Gagal memutar video kamera';
                                });
                                this.startScanning();
                            }
                        });
                    } catch (error) {
                        this.loading = false;
                        if (error.name === 'NotAllowedError') {
                            this.cameraError = 'Akses kamera ditolak. Silakan berikan izin akses kamera di pengaturan browser.';
                        } else if (error.name === 'NotFoundError') {
                            this.cameraError = 'Kamera tidak ditemukan di perangkat Anda.';
                        } else {
                            this.cameraError = 'Terjadi kesalahan saat mengakses kamera: ' + error.message;
                        }
                        console.error('Error accessing camera:', error);
                    }
                },

                stopCamera() {
                    if (this.stream) {
                        this.stream.getTracks().forEach(track => track.stop());
                        this.stream = null;
                    }
                    this.cameraActive = false;
                    this.scanning = false;
                    this.cameraError = '';
                },

                resetCamera() {
                    this.stopCamera();
                    this.cameraError = '';
                },

                startScanning() {
                    if (!this.cameraActive) return;

                    const video = document.getElementById('video-stream');
                    const canvas = document.createElement('canvas');
                    const ctx = canvas.getContext('2d');

                    const scanInterval = setInterval(() => {
                        if (!this.cameraActive) {
                            clearInterval(scanInterval);
                            return;
                        }

                        if (video && video.readyState === video.HAVE_ENOUGH_DATA) {
                            canvas.width = video.videoWidth;
                            canvas.height = video.videoHeight;
                            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

                            const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
                            const code = jsQR(imageData.data, imageData.width, imageData.height, {
                                inversionAttempts: 'dontInvert',
                            });

                            if (code && code.data) {
                                this.scanning = true;
                                this.stopCamera();
                                clearInterval(scanInterval);
                                this.processQRCode(code.data);
                            }
                        }
                    }, 500);
                },

                captureFrame() {
                    const video = document.getElementById('video-stream');
                    const canvas = document.createElement('canvas');
                    const ctx = canvas.getContext('2d');

                    canvas.width = video.videoWidth;
                    canvas.height = video.videoHeight;
                    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

                    const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
                    const code = jsQR(imageData.data, imageData.width, imageData.height, {
                        inversionAttempts: 'dontInvert',
                    });

                    if (code && code.data) {
                        this.scanning = true;
                        this.stopCamera();
                        this.processQRCode(code.data);
                    } else {
                        this.cameraError = 'QR Code tidak ditemukan di foto. Silakan coba lagi.';
                    }
                },

                async processQRCode(qrData) {
                    try {
                        const response = await fetch('{{ route("admin/scan.verify") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content,
                            },
                            body: JSON.stringify({
                                kode_tiket: qrData.trim(),
                            }),
                        });

                        const data = await response.json();

                        if (data.success) {
                            showVerificationDialog(data.data);
                        } else {
                            showVerificationDialog({
                                error: true,
                                message: data.message,
                            });
                        }
                    } catch (error) {
                        this.cameraError = 'Terjadi kesalahan: ' + error.message;
                        console.error('Error:', error);
                    } finally {
                        this.scanning = false;
                    }
                },
            };
        }

        function showVerificationDialog(data) {
            dialogState.ticketData = data;
            dialogState.open = true;
        }

        function closeVerificationDialog() {
            dialogState.open = false;
            dialogState.ticketData = null;
        }

        function scanAnother() {
            closeVerificationDialog();
            const input = document.getElementById('kode_tiket');
            if (input) {
                input.value = '';
                input.focus();
            }
        }
    </script>
</x-admin-layout>
