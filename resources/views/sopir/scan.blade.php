<x-admin-layout>
    <x-slot name="header">
        <x-ui.breadcrumb.breadcrumb>
            <x-ui.breadcrumb.list class="text-xs">
                <x-ui.breadcrumb.item>
                    <x-ui.breadcrumb.link href="{{ route('dashboard') }}">Home</x-ui.breadcrumb.link>
                </x-ui.breadcrumb.item>
                <x-ui.breadcrumb.separator>
                    <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
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
                            <i data-lucide="qr-code" class="w-6 h-6 text-primary"></i>
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
                            <button @click="activeTab = 'manual'"
                                :class="activeTab === 'manual' ? 'border-b-2 border-primary text-primary' : 'text-muted-foreground hover:text-foreground'"
                                class="pb-3 px-4 font-medium transition-colors text-sm">
                                <span class="flex items-center gap-2">
                                    <i data-lucide="keyboard" class="w-4 h-4"></i>
                                    Input Manual
                                </span>
                            </button>
                            <button @click="activeTab = 'camera'"
                                :class="activeTab === 'camera' ? 'border-b-2 border-primary text-primary' : 'text-muted-foreground hover:text-foreground'"
                                class="pb-3 px-4 font-medium transition-colors text-sm">
                                <span class="flex items-center gap-2">
                                    <i data-lucide="camera" class="w-4 h-4"></i>
                                    Scan Kamera
                                </span>
                            </button>
                        </div>

                        <!-- Manual Input Tab -->
                        <div x-show="activeTab === 'manual'" x-transition class="space-y-4">
                            <div class="flex gap-2">
                                <input type="text" x-model="kodeTiket" @keydown.enter="verifyTicket()"
                                    placeholder="Masukkan kode tiket..."
                                    class="flex-1 px-4 py-2 border border-border rounded-lg bg-background text-foreground focus:ring-2 focus:ring-primary outline-none" />
                                <button @click="verifyTicket()" :disabled="loading || !kodeTiket"
                                    class="px-6 py-2 bg-primary text-primary-foreground rounded-lg hover:bg-primary/90 disabled:opacity-50 transition-colors flex items-center gap-2">
                                    <i data-lucide="search" x-show="!loading" class="w-4 h-4"></i>
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
                                <button @click="toggleScanner()"
                                    class="w-full flex flex-col items-center justify-center gap-3 py-8 border-2 border-dashed border-muted-foreground/20 rounded-xl hover:bg-accent/50 transition-all group">
                                    <div
                                        class="p-3 bg-primary/10 rounded-full group-hover:scale-110 transition-transform">
                                        <i data-lucide="camera" class="w-8 h-8 text-primary"></i>
                                    </div>
                                    <span class="font-medium">Buka Scanner Kamera</span>
                                </button>
                            </div>

                            <div x-show="showScanner" x-transition>
                                <div id="reader"
                                    class="overflow-hidden rounded-xl border-2 border-primary/20 bg-black aspect-square">
                                </div>
                                <button @click="toggleScanner()"
                                    class="w-full mt-3 py-2 text-sm font-medium text-destructive hover:bg-destructive/10 rounded-lg">
                                    Tutup Kamera
                                </button>
                            </div>
                        </div>

                        <div x-show="result !== null" x-transition
                            :class="result.success ? 'border border-green-200 bg-green-50 dark:bg-green-900/10 rounded-xl p-5' : 'border border-red-200 bg-red-50 dark:bg-red-900/10 rounded-xl p-5'">

                            <div class="space-y-4">
                                <div class="flex items-center gap-3">
                                    <div :class="result.success ? 'text-green-600' : 'text-red-600'">
                                        <i data-lucide="check-circle" x-show="result.success" class="w-6 h-6"></i>
                                        <i data-lucide="x-circle" x-show="!result.success" class="w-6 h-6"></i>
                                    </div>
                                    <h3 class="font-bold text-foreground" x-text="result?.message"></h3>
                                </div>

                                <template x-if="result?.tiket">
                                    <div class="space-y-4 pt-2 border-t border-border/50">
                                        <!-- Header Info -->
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <p class="text-[10px] text-muted-foreground uppercase">Kode Tiket</p>
                                                <p class="font-bold text-sm text-primary"
                                                    x-text="result.tiket.kode_tiket"></p>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-[10px] text-muted-foreground uppercase">Status</p>
                                                <span
                                                    :class="result.tiket.is_hadir ? 'inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800' : 'inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800'"
                                                    x-text="result.tiket.is_hadir ? 'Hadir' : 'Dipesan'"></span>
                                            </div>
                                        </div>

                                        <!-- Passenger Info -->
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <p class="text-[10px] text-muted-foreground uppercase">Nama Penumpang
                                                </p>
                                                <p class="font-bold text-sm"
                                                    x-text="result.tiket.nama_penumpang || 'N/A'"></p>
                                            </div>
                                            <div>
                                                <p class="text-[10px] text-muted-foreground uppercase">Nomor Kursi</p>
                                                <p class="font-bold text-sm text-primary"
                                                    x-text="result.tiket.nomor_kursi || 'N/A'"></p>
                                            </div>
                                        </div>

                                        <!-- Scan Time -->
                                        <div class="p-3 bg-background/50 rounded-lg border">
                                            <p class="text-[10px] text-muted-foreground uppercase mb-2">Waktu Scan</p>
                                            <p class="font-bold text-sm"
                                                x-text="result.tiket.waktu_scan || 'Baru saja'"></p>
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

    <script src="https://cdn.jsdelivr.net/npm/html5-qrcode@2.3.4/html5-qrcode.min.js"></script>
    <script>
        function scanForm() {
            return {
                kodeTiket: '',
                loading: false,
                error: '',
                result: null,
                showScanner: false,
                scanner: null,

                verifyTicket() {
                    if (!this.kodeTiket.trim()) return;

                    this.loading = true;
                    this.error = '';
                    this.result = null;

                    fetch("{{ route('sopir.scan.verify') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                        body: JSON.stringify({
                            kode_tiket: this.kodeTiket,
                        })
                    })
                        .then(response => response.json())
                        .then(data => {
                            // Handle driver response format
                            this.result = {
                                success: data.success,
                                message: data.message,
                                tiket: data.tiket
                            };
                            this.error = data.success ? '' : data.message;
                            if (data.success) {
                                setTimeout(() => {
                                    this.kodeTiket = '';
                                }, 3000);
                            }
                        })
                        .catch(error => {
                            this.error = 'Terjadi kesalahan: ' + error.message;
                        })
                        .finally(() => {
                            this.loading = false;
                        });
                },

                toggleScanner() {
                    this.showScanner = !this.showScanner;

                    if (this.showScanner) {
                        setTimeout(() => this.initScanner(), 100);
                    } else {
                        this.stopScanner();
                    }
                },

                initScanner() {
                    if (this.scanner) return;

                    const html5QrcodeScanner = new Html5Qrcode("reader");

                    html5QrcodeScanner.start(
                        { facingMode: "environment" },
                        {
                            fps: 10,
                            qrbox: { width: 250, height: 250 }
                        },
                        (decodedText) => {
                            this.kodeTiket = decodedText;
                            this.verifyTicket();
                            this.stopScanner();
                        },
                        (errorMessage) => {
                            // Ignore errors
                        }
                    ).catch(() => {
                        this.error = 'Tidak dapat mengakses kamera. Gunakan input manual.';
                        this.showScanner = false;
                    });

                    this.scanner = html5QrcodeScanner;
                },

                stopScanner() {
                    if (this.scanner) {
                        this.scanner.stop().then(() => {
                            this.scanner.clear();
                            this.scanner = null;
                        }).catch(() => {
                            this.scanner = null;
                        });
                    }
                }
            };
        }
    </script>
</x-admin-layout>