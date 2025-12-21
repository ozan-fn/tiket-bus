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

    <div x-data="pageData()" class="py-6 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">

            <div class="bg-card border border-border rounded-lg shadow-sm overflow-hidden mb-6">
                <div class="p-8">
                    <div class="text-center">
                        <div class="flex justify-center mb-6">
                            <div class="p-3 bg-primary/10 rounded-full">
                                <x-lucide-qr-code class="w-12 h-12 text-primary" />
                            </div>
                        </div>

                        <h3 class="text-2xl font-bold text-foreground mb-2">Scan Tiket Penumpang</h3>
                        <p class="text-muted-foreground mb-8 max-w-md mx-auto">
                            Masukkan kode tiket atau scan QR code dari kamera untuk memverifikasi tiket penumpang
                        </p>

                        <div class="max-w-md mx-auto mb-6">
                            <div class="flex gap-2 border-b border-border">
                                <button @click="activeTab = 'manual'"
                                    :class="activeTab === 'manual' ? 'border-b-2 border-primary text-primary' : 'text-muted-foreground hover:text-foreground'"
                                    class="pb-2 px-4 font-medium transition-colors">
                                    <span class="flex items-center gap-2 justify-center">
                                        <x-lucide-keyboard class="w-4 h-4" />
                                        Input Manual
                                    </span>
                                </button>
                                <button @click="activeTab = 'camera'"
                                    :class="activeTab === 'camera' ? 'border-b-2 border-primary text-primary' : 'text-muted-foreground hover:text-foreground'"
                                    class="pb-2 px-4 font-medium transition-colors">
                                    <span class="flex items-center gap-2 justify-center">
                                        <x-lucide-camera class="w-4 h-4" />
                                        Scan Kamera
                                    </span>
                                </button>
                            </div>

                            <!-- Manual Input Tab -->
                            <div x-show="activeTab === 'manual'" x-transition class="mt-6">
                                <div class="flex flex-col gap-4">
                                    <div>
                                        <label for="kode_tiket" class="block text-sm font-medium text-foreground mb-2">
                                            Kode Tiket
                                        </label>
                                        <input type="text" id="kode_tiket" x-model="kodeTiket"
                                            @keydown.enter="verifyTicket()" placeholder="Masukkan kode tiket..."
                                            class="w-full px-4 py-2 border border-border rounded-lg bg-background text-foreground placeholder-muted-foreground focus:ring-2 focus:ring-primary focus:border-transparent outline-none"
                                            autocomplete="off" />
                                        <template x-if="error">
                                            <p class="text-sm text-destructive mt-2" x-text="error"></p>
                                        </template>
                                    </div>

                                    <button @click="verifyTicket()" :disabled="loading"
                                        class="w-full px-4 py-2 bg-primary text-primary-foreground rounded-lg hover:bg-primary/90 transition-colors disabled:opacity-50 disabled:cursor-not-allowed font-medium">
                                        <span x-show="!loading" class="flex items-center justify-center gap-2">
                                            <x-lucide-search class="w-4 h-4" />
                                            Verifikasi Tiket
                                        </span>
                                        <span x-show="loading" class="flex items-center justify-center gap-2">
                                            <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                            Memverifikasi...
                                        </span>
                                    </button>
                                </div>
                            </div>

                            <!-- Camera Tab -->
                            <div x-show="activeTab === 'camera'" x-transition class="mt-6">
                                <div class="flex flex-col gap-4">
                                    <div x-show="!cameraActive && !cameraError" class="text-center">
                                        <button @click="startCamera()" :disabled="loading"
                                            class="w-full px-4 py-2 bg-primary text-primary-foreground rounded-lg hover:bg-primary/90 transition-colors disabled:opacity-50 disabled:cursor-not-allowed font-medium">
                                            <span class="flex items-center justify-center gap-2">
                                                <x-lucide-camera class="w-4 h-4" />
                                                Buka Kamera
                                            </span>
                                        </button>
                                    </div>

                                    <template x-if="cameraError">
                                        <div class="p-4 bg-red-100 dark:bg-red-900/30 border border-red-300 dark:border-red-700 rounded-lg">
                                            <p class="text-sm text-red-800 dark:text-red-200" x-text="cameraError"></p>
                                            <button @click="resetCamera()" class="mt-2 px-3 py-1 bg-red-600 text-white rounded text-sm hover:bg-red-700 transition-colors">Coba Lagi</button>
                                        </div>
                                    </template>

                                    <div x-show="cameraActive" class="relative rounded-lg overflow-hidden border border-border bg-black">
                                        <video x-ref="videoElement" id="video-stream" width="400" height="400" class="w-full h-auto" playsinline></video>
                                        <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                            <div class="w-64 h-64 border-2 border-primary rounded-lg opacity-50"></div>
                                        </div>
                                        <button @click="stopCamera()" class="absolute top-2 right-2 p-2 bg-black/50 hover:bg-black/75 rounded-lg text-white transition-colors">
                                            <x-lucide-x class="w-5 h-5" />
                                        </button>
                                        <div class="absolute bottom-4 left-0 right-0 text-center">
                                            <div x-show="scanning" class="inline-block px-3 py-1 bg-yellow-500/80 text-white text-sm rounded">Memindai QR Code...</div>
                                            <div x-show="!scanning && cameraActive" class="inline-block px-3 py-1 bg-green-500/80 text-white text-sm rounded">Kamera Aktif</div>
                                        </div>
                                    </div>

                                    <div x-show="cameraActive" class="flex gap-2">
                                        <button @click="captureFrame()" :disabled="scanning" class="flex-1 px-4 py-2 bg-primary text-primary-foreground rounded-lg hover:bg-primary/90 transition-colors disabled:opacity-50 disabled:cursor-not-allowed font-medium">
                                            <span class="flex items-center justify-center gap-2"><x-lucide-camera class="w-4 h-4" /> Ambil Foto</span>
                                        </button>
                                        <button @click="stopCamera()" class="flex-1 px-4 py-2 bg-accent text-foreground rounded-lg hover:bg-accent/80 transition-colors font-medium">
                                            <span class="flex items-center justify-center gap-2"><x-lucide-x class="w-4 h-4" /> Tutup</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="p-4 bg-accent/50 rounded-lg border border-border">
                    <div class="flex items-center gap-3 mb-2">
                        <x-lucide-check-circle class="w-5 h-5 text-primary" />
                        <h4 class="font-semibold text-foreground">Verifikasi Tiket</h4>
                    </div>
                    <p class="text-sm text-muted-foreground">Scan QR code atau masukkan kode tiket untuk memverifikasi keaslian tiket penumpang</p>
                </div>
                <div class="p-4 bg-accent/50 rounded-lg border border-border">
                    <div class="flex items-center gap-3 mb-2">
                        <x-lucide-shield-check class="w-5 h-5 text-primary" />
                        <h4 class="font-semibold text-foreground">Keamanan Tinggi</h4>
                    </div>
                    <p class="text-sm text-muted-foreground">Validasi real-time dengan database pusat</p>
                </div>
            </div>
        </div>

        <!-- Verification Dialog - Inside Same x-data Scope -->
        <div x-show="dialogOpen"
             x-transition
             class="fixed inset-0 z-50 overflow-y-auto">

            <!-- Overlay -->
            <div x-show="dialogOpen"
                 x-transition.opacity
                 class="fixed inset-0 bg-black/50"
                 @click="closeDialog()"></div>

            <!-- Dialog Panel -->
            <div class="flex min-h-full items-center justify-center p-4">
                <div x-show="dialogOpen"
                     x-transition
                     class="relative bg-card border border-border rounded-lg shadow-lg w-full max-w-2xl"
                     @click.stop>

                    <!-- Close Button -->
                    <button @click="closeDialog()"
                            class="absolute top-4 right-4 text-muted-foreground hover:text-foreground transition-colors z-10">
                        <x-lucide-x class="w-5 h-5" />
                    </button>

                    <!-- Content -->
                    <div class="p-6">
                        <!-- Success State -->
                        <template x-if="ticketData && !ticketData.error">
                            <div class="space-y-6">
                                <div class="text-center mb-6">
                                    <div class="flex justify-center mb-4">
                                        <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-full">
                                            <x-lucide-check-circle class="w-12 h-12 text-green-600 dark:text-green-400" />
                                        </div>
                                    </div>
                                    <h2 class="text-2xl font-bold text-foreground mb-2" x-text="dialogMessage || 'Tiket Valid'"></h2>
                                    <p class="text-muted-foreground">Tiket penumpang telah terverifikasi</p>
                                </div>

                                <!-- Ticket Details -->
                                <div class="space-y-4 bg-accent/30 rounded-lg p-4">
                                    <!-- Header Info -->
                                    <div class="flex justify-between items-start border-b border-border pb-4">
                                        <div>
                                            <p class="text-xs text-muted-foreground uppercase tracking-wider">Kode Tiket</p>
                                            <p class="text-lg font-bold text-foreground" x-text="ticketData?.kode_tiket"></p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-xs text-muted-foreground uppercase tracking-wider">Status</p>
                                            <p class="text-lg font-bold"
                                               :class="ticketData?.status === 'aktif' ? 'text-green-600 dark:text-green-400' : 'text-yellow-600 dark:text-yellow-400'"
                                               x-text="ticketData?.status?.toUpperCase()"></p>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-xs text-muted-foreground uppercase tracking-wider">Nama Penumpang</p>
                                            <p class="text-sm font-semibold text-foreground" x-text="ticketData?.nama_penumpang"></p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-muted-foreground uppercase tracking-wider">NIK</p>
                                            <p class="text-sm font-semibold text-foreground" x-text="ticketData?.nik"></p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-muted-foreground uppercase tracking-wider">Jenis Kelamin</p>
                                            <p class="text-sm font-semibold text-foreground" x-text="ticketData?.jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan'"></p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-muted-foreground uppercase tracking-wider">Nomor Telepon</p>
                                            <p class="text-sm font-semibold text-foreground" x-text="ticketData?.nomor_telepon"></p>
                                        </div>
                                        <div class="md:col-span-2">
                                            <p class="text-xs text-muted-foreground uppercase tracking-wider">Email</p>
                                            <p class="text-sm font-semibold text-foreground" x-text="ticketData?.email"></p>
                                        </div>
                                    </div>

                                    <!-- Journey Info -->
                                    <div class="border-t border-border pt-4">
                                        <p class="text-xs text-muted-foreground uppercase tracking-wider mb-3">Informasi Perjalanan</p>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <p class="text-xs text-muted-foreground">Tanggal Berangkat</p>
                                                <p class="text-sm font-semibold text-foreground" x-text="formatDate(ticketData?.jadwal?.tanggal_berangkat)"></p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-muted-foreground">Jam Berangkat</p>
                                                <p class="text-sm font-semibold text-foreground" x-text="formatTime(ticketData?.jadwal?.jam_berangkat)"></p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-muted-foreground">Asal Terminal</p>
                                                <p class="text-sm font-semibold text-foreground capitalize" x-text="ticketData?.jadwal?.asal"></p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-muted-foreground">Tujuan Terminal</p>
                                                <p class="text-sm font-semibold text-foreground capitalize" x-text="ticketData?.jadwal?.tujuan"></p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-muted-foreground">Kelas</p>
                                                <p class="text-sm font-semibold text-foreground" x-text="ticketData?.kelas"></p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-muted-foreground">Kursi</p>
                                                <p class="text-sm font-semibold text-foreground" x-text="ticketData?.kursi"></p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Price Info -->
                                    <div class="border-t border-border pt-4">
                                        <div class="flex justify-between items-center">
                                            <p class="text-muted-foreground">Harga Tiket</p>
                                            <p class="text-lg font-bold text-primary" x-text="formatPrice(ticketData?.harga)"></p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex gap-3">
                                    <button @click="scanAnother()" class="flex-1 px-4 py-2 bg-primary text-primary-foreground rounded-lg hover:bg-primary/90 transition-colors font-medium">
                                        Scan Lagi
                                    </button>
                                    <button @click="closeDialog()" class="flex-1 px-4 py-2 bg-accent text-foreground rounded-lg hover:bg-accent/80 transition-colors font-medium">
                                        Tutup
                                    </button>
                                </div>
                            </div>
                        </template>

                        <!-- Error State -->
                        <template x-if="ticketData?.error">
                            <div class="space-y-6">
                                <div class="text-center mb-6">
                                    <div class="flex justify-center mb-4">
                                        <div class="p-3 bg-red-100 dark:bg-red-900/30 rounded-full">
                                            <x-lucide-alert-circle class="w-12 h-12 text-red-600 dark:text-red-400" />
                                        </div>
                                    </div>
                                    <h2 class="text-2xl font-bold text-foreground mb-2">Tiket Tidak Valid</h2>
                                    <p class="text-destructive" x-text="dialogMessage"></p>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex gap-3">
                                    <button @click="scanAnother()" class="flex-1 px-4 py-2 bg-primary text-primary-foreground rounded-lg hover:bg-primary/90 transition-colors font-medium">
                                        Coba Lagi
                                    </button>
                                    <button @click="closeDialog()" class="flex-1 px-4 py-2 bg-accent text-foreground rounded-lg hover:bg-accent/80 transition-colors font-medium">
                                        Tutup
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.js"></script>
    <script>
        function pageData() {
            return {
                // Tab state
                activeTab: 'manual',

                // Form state
                kodeTiket: '',
                loading: false,
                error: '',

                // Camera state
                cameraActive: false,
                scanning: false,
                cameraError: '',
                stream: null,

                // Dialog state
                dialogOpen: false,
                ticketData: null,
                dialogMessage: '',

                // Verify ticket method
                async verifyTicket() {
                    if (!this.kodeTiket.trim()) {
                        this.error = 'Masukkan kode tiket terlebih dahulu';
                        return;
                    }

                    this.loading = true;
                    this.error = '';

                    try {
                        console.log('Sending request to verify ticket:', this.kodeTiket.trim());

                        const response = await fetch('{{ route("admin/scan.verify") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content,
                            },
                            body: JSON.stringify({ kode_tiket: this.kodeTiket.trim() }),
                        });

                        const data = await response.json();
                        console.log('Response received:', data);

                        if (data.success) {
                            console.log('Ticket verified successfully, showing dialog');
                            this.showDialog(data.data, data.message);
                        } else {
                            console.log('Ticket verification failed, showing error dialog');
                            this.showDialog({ error: true }, data.message);
                        }
                    } catch (error) {
                        console.error('Error during verification:', error);
                        this.error = 'Terjadi kesalahan sistem: ' + error.message;
                    } finally {
                        this.loading = false;
                    }
                },

                // Show dialog
                showDialog(data, message) {
                    console.log('showDialog called with:', { data, message });
                    this.ticketData = data;
                    this.dialogMessage = message;
                    this.dialogOpen = true;
                    console.log('Dialog state after update:', {
                        dialogOpen: this.dialogOpen,
                        ticketData: this.ticketData,
                        dialogMessage: this.dialogMessage
                    });
                },

                // Close dialog
                closeDialog() {
                    console.log('Closing dialog');
                    this.dialogOpen = false;
                    setTimeout(() => {
                        this.ticketData = null;
                        this.dialogMessage = '';
                    }, 300);
                },

                // Scan another ticket
                scanAnother() {
                    this.closeDialog();
                    this.kodeTiket = '';
                    this.error = '';
                    setTimeout(() => {
                        const input = document.getElementById('kode_tiket');
                        if (input) input.focus();
                    }, 400);
                },

                // Camera methods
                async startCamera() {
                    this.loading = true;
                    this.cameraError = '';
                    try {
                        const stream = await navigator.mediaDevices.getUserMedia({
                            video: { facingMode: 'environment' }
                        });
                        this.stream = stream;
                        this.cameraActive = true;
                        this.loading = false;
                        this.$nextTick(() => {
                            const video = this.$refs.videoElement;
                            if (video) {
                                video.srcObject = stream;
                                video.play().catch(e => console.error(e));
                                this.startScanning();
                            }
                        });
                    } catch (error) {
                        this.loading = false;
                        this.cameraError = 'Gagal akses kamera: ' + error.message;
                    }
                },

                stopCamera() {
                    if (this.stream) {
                        this.stream.getTracks().forEach(t => t.stop());
                    }
                    this.cameraActive = false;
                    this.scanning = false;
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

                    const scanLoop = setInterval(() => {
                        if (!this.cameraActive) {
                            clearInterval(scanLoop);
                            return;
                        }
                        if (video && video.readyState === video.HAVE_ENOUGH_DATA) {
                            canvas.width = video.videoWidth;
                            canvas.height = video.videoHeight;
                            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
                            const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
                            const code = jsQR(imageData.data, imageData.width, imageData.height, {
                                inversionAttempts: 'dontInvert'
                            });
                            if (code && code.data) {
                                this.scanning = true;
                                this.stopCamera();
                                clearInterval(scanLoop);
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
                    const code = jsQR(imageData.data, imageData.width, imageData.height);

                    if (code && code.data) {
                        this.scanning = true;
                        this.stopCamera();
                        this.processQRCode(code.data);
                    } else {
                        this.cameraError = 'Tidak dapat mendeteksi QR code. Coba lagi.';
                        setTimeout(() => this.cameraError = '', 3000);
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
                            body: JSON.stringify({ kode_tiket: qrData.trim() }),
                        });

                        const data = await response.json();

                        if (data.success) {
                            this.showDialog(data.data, data.message);
                        } else {
                            this.showDialog({ error: true }, data.message);
                        }
                    } catch (e) {
                        console.error('QR Process Error:', e);
                        this.cameraError = 'Error verifikasi: ' + e.message;
                    } finally {
                        this.scanning = false;
                    }
                },

                // Format helpers
                formatDate(dateString) {
                    if (!dateString) return '-';
                    try {
                        const date = new Date(dateString);
                        return date.toLocaleDateString('id-ID', {
                            weekday: 'long',
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric'
                        });
                    } catch (e) {
                        return dateString;
                    }
                },

                formatTime(timeString) {
                    if (!timeString) return '-';
                    try {
                        const date = new Date(timeString);
                        return date.toLocaleTimeString('id-ID', {
                            hour: '2-digit',
                            minute: '2-digit'
                        });
                    } catch (e) {
                        return timeString;
                    }
                },

                formatPrice(price) {
                    if (!price) return 'Rp 0';
                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(price);
                }
            };
        }
    </script>
</x-admin-layout>
