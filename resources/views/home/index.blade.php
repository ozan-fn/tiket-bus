<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Platform Tiket Bus Masa Depan">
    <title>Tiket Bus - Era Baru Perjalanan</title>

    <link rel="icon" href="{{ asset('assets/images/logo.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>

    <script>
        // Cek local storage atau preferensi sistem
        if (localStorage.getItem('theme') === 'light') {
            document.documentElement.classList.remove('dark');
        } else {
            // Default ke dark jika tidak ada preferensi tersimpan
            document.documentElement.classList.add('dark');
        }
    </script>

    <style>
        /* Fallback Variables jika app.css belum load sempurna */
        :root {
            --radius: 0.75rem;
        }

        body {
            font-family: 'Space Grotesk', sans-serif;
            /* Pastikan background dan warna teks dasar mengikuti variabel Shadcn */
            background-color: hsl(var(--background));
            color: hsl(var(--foreground));
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        
        /* Grid Pattern: Adaptif terhadap Light/Dark mode via CSS Variables */
        .bg-grid {
            background-size: 50px 50px;
            background-image: 
                linear-gradient(to right, hsl(var(--border) / 0.3) 1px, transparent 1px),
                linear-gradient(to bottom, hsl(var(--border) / 0.3) 1px, transparent 1px);
            mask-image: radial-gradient(circle at center, black 40%, transparent 100%);
        }
        /* Grid lebih subtle di dark mode agar tidak terlalu ramai */
        .dark .bg-grid {
             background-image: 
                linear-gradient(to right, hsl(var(--border) / 0.15) 1px, transparent 1px),
                linear-gradient(to bottom, hsl(var(--border) / 0.15) 1px, transparent 1px);
        }

        /* Animasi Custom */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
        }
        .animate-float { animation: float 6s ease-in-out infinite; }

        @keyframes pulse-glow {
            0%, 100% { opacity: 0.3; transform: scale(1); }
            50% { opacity: 0.7; transform: scale(1.1); }
        }
        .animate-pulse-glow { animation: pulse-glow 4s ease-in-out infinite; }

        /* Glassmorphism Panel - PERBAIKAN KONTRAS */
        .glass-panel {
            background: hsl(var(--card) / 0.7); /* Light mode: agak transparan */
            backdrop-filter: blur(16px);
            border: 1px solid hsl(var(--border) / 0.8);
            transition: all 0.3s ease;
        }
        
        /* Dark Mode Glass: Lebih opaque (tidak terlalu transparan) agar teks di atasnya lebih jelas */
        .dark .glass-panel {
             background: hsl(var(--card) / 0.85); 
             border-color: hsl(var(--border) / 0.4);
        }
        
        .glass-panel:hover {
            border-color: hsl(var(--primary) / 0.5);
            box-shadow: 0 0 30px -10px hsl(var(--primary) / 0.2);
            transform: translateY(-2px);
        }

        /* Gradient Text */
        .text-gradient {
            background: linear-gradient(135deg, hsl(var(--foreground)) 0%, hsl(var(--primary)) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>
<body class="antialiased min-h-screen relative overflow-x-hidden selection:bg-primary selection:text-primary-foreground">

    <div class="fixed inset-0 z-[-1]">
        <div class="absolute inset-0 bg-background transition-colors duration-300"></div>
        
        <div class="absolute inset-0 bg-grid"></div>
        
        <div class="absolute top-[-10%] left-[-10%] w-[500px] h-[500px] bg-primary/20 dark:bg-primary/10 rounded-full blur-[120px] animate-pulse-glow"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[600px] h-[600px] bg-blue-500/10 dark:bg-blue-600/5 rounded-full blur-[150px] animate-pulse-glow" style="animation-delay: 2s"></div>
    </div>

    <nav class="fixed top-6 left-0 right-0 z-50 px-4 flex justify-center">
        <div class="w-full max-w-6xl glass-panel rounded-full px-6 py-4 flex justify-between items-center shadow-lg shadow-black/5 dark:shadow-black/20">
            <a href="/" class="flex items-center gap-3 group">
                <div class="w-10 h-10 relative flex items-center justify-center transition-transform group-hover:scale-110">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="Logo Tiket Bus" class="w-full h-full object-contain">
                </div>
                <span class="font-bold text-xl tracking-tight text-foreground">Tiket Bus</span>
            </a>

            <div class="hidden md:flex items-center gap-8 text-sm font-medium text-muted-foreground">
                <a href="#fitur" class="hover:text-primary transition-colors dark:hover:text-primary-foreground">Fitur</a>
                <a href="#alur" class="hover:text-primary transition-colors dark:hover:text-primary-foreground">Alur</a>
                <a href="#faq" class="hover:text-primary transition-colors dark:hover:text-primary-foreground">FAQ</a>
            </div>

            <div class="flex items-center gap-3">
                <button id="theme-toggle" class="p-2 rounded-full hover:bg-muted text-muted-foreground hover:text-foreground transition-colors" aria-label="Toggle Dark Mode">
                    <i data-lucide="sun" class="w-5 h-5 block dark:hidden"></i>
                    <i data-lucide="moon" class="w-5 h-5 hidden dark:block"></i>
                </button>

                <div class="h-6 w-px bg-border hidden sm:block"></div>

                @guest
                    <a href="{{ route('login') }}" class="text-sm font-medium text-foreground hover:text-primary transition hidden sm:block dark:hover:text-primary-foreground">Masuk</a>
                    <a href="{{ route('register') }}" class="relative px-6 py-2 rounded-full bg-primary text-primary-foreground font-semibold text-sm overflow-hidden group hover:shadow-[0_0_20px_hsl(var(--primary)/0.5)] transition-all">
                        <span class="relative z-10">Daftar</span>
                        <div class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
                    </a>
                @else
                    <a href="{{ route('dashboard') }}" class="px-5 py-2 rounded-full border border-primary/30 bg-primary/10 text-primary text-sm font-bold hover:bg-primary/20 transition flex items-center gap-2">
                        <i data-lucide="layout" class="w-4 h-4"></i>
                        <span>Dashboard</span>
                    </a>
                @endguest
            </div>
        </div>
    </nav>

    <section class="relative pt-40 pb-20 lg:pt-52 lg:pb-32 px-4 text-center">
        <div class="max-w-6xl mx-auto relative z-10">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full border border-border bg-card/50 dark:bg-card/80 backdrop-blur-sm text-xs font-mono text-primary mb-8 animate-float shadow-sm">
                <span class="relative flex h-2 w-2">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2 w-2 bg-primary"></span>
                </span>
                SISTEM TIKET TERDESENTRALISASI V.2.0
            </div>

            <h1 class="text-5xl md:text-7xl lg:text-8xl font-bold tracking-tighter mb-8 leading-[1.1] text-foreground">
                Revolusi Perjalanan <br>
                <span class="text-gradient">Tanpa Batas</span>
            </h1>

            <p class="text-lg md:text-xl text-muted-foreground dark:text-gray-300 max-w-2xl mx-auto mb-10 leading-relaxed">
                Platform pemesanan tiket bus berbasis teknologi modern. Aman, transparan, dan terintegrasi secara real-time. Tinggalkan cara lama, beralih ke masa depan.
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <a href="{{ route('dashboard') }}" class="w-full sm:w-auto px-8 py-4 bg-primary text-primary-foreground font-bold rounded-xl hover:scale-105 transition-transform duration-200 flex items-center justify-center gap-2 shadow-lg shadow-primary/20">
                    Pesan Tiket
                    <i data-lucide="arrow-right" class="w-5 h-5"></i>
                </a>
                <a href="#fitur" class="w-full sm:w-auto px-8 py-4 glass-panel text-foreground font-medium rounded-xl flex items-center justify-center gap-2 hover:bg-muted/50 dark:hover:bg-card/80">
                    Pelajari Sistem
                    <i data-lucide="mouse-pointer-2" class="w-5 h-5"></i>
                </a>
            </div>
        </div>
        
        <div class="absolute top-1/2 left-10 w-32 h-32 border border-primary/20 rounded-2xl rotate-12 animate-float opacity-30 dark:opacity-10 hidden lg:block"></div>
        <div class="absolute bottom-20 right-10 w-24 h-24 border border-border rounded-full animate-float opacity-30 dark:opacity-10 hidden lg:block" style="animation-delay: 2s;"></div>
    </section>

    <div class="max-w-7xl mx-auto px-4 mb-24">
        <div class="glass-panel rounded-2xl p-8 grid grid-cols-2 md:grid-cols-4 gap-8 text-center bg-card/40 dark:bg-card/60">
            <div class="space-y-1">
                <h3 class="text-3xl font-bold text-foreground">10K+</h3>
                <p class="text-xs font-mono text-muted-foreground uppercase tracking-widest">Transaksi Harian</p>
            </div>
            <div class="space-y-1">
                <h3 class="text-3xl font-bold text-primary">0.1s</h3>
                <p class="text-xs font-mono text-muted-foreground uppercase tracking-widest">Latensi Booking</p>
            </div>
            <div class="space-y-1">
                <h3 class="text-3xl font-bold text-foreground">500+</h3>
                <p class="text-xs font-mono text-muted-foreground uppercase tracking-widest">Armada Terhubung</p>
            </div>
            <div class="space-y-1">
                <h3 class="text-3xl font-bold text-primary">100%</h3>
                <p class="text-xs font-mono text-muted-foreground uppercase tracking-widest">Enkripsi Data</p>
            </div>
        </div>
    </div>

    <section id="fitur" class="py-24 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-16">
                <h2 class="text-3xl md:text-5xl font-bold mb-4 text-foreground">Infrastruktur <span class="text-primary">Digital</span></h2>
                <p class="text-muted-foreground dark:text-gray-300 max-w-xl">Fitur canggih yang dirancang untuk efisiensi dan keamanan maksimal.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 grid-rows-2 gap-6 h-auto md:h-[600px]">
                <div class="md:col-span-2 md:row-span-2 glass-panel rounded-[2rem] p-10 relative overflow-hidden group bg-card/40 dark:bg-card/60">
                    <div class="absolute top-0 right-0 p-40 bg-primary/10 rounded-full blur-[100px] -mr-20 -mt-20"></div>
                    
                    <div class="relative z-10 h-full flex flex-col justify-between">
                        <div>
                            <div class="w-14 h-14 bg-background border border-border rounded-2xl flex items-center justify-center mb-6 shadow-xl">
                                <i data-lucide="zap" class="w-7 h-7 text-primary"></i>
                            </div>
                            <h3 class="text-3xl font-bold mb-3 text-foreground">Smart Settlement</h3>
                            <p class="text-muted-foreground dark:text-gray-200 text-lg leading-relaxed max-w-md">
                                Sistem reservasi instan tanpa jeda. Konfirmasi tiket otomatis detik itu juga setelah pembayaran berhasil. Tidak ada lagi tiket ganda.
                            </p>
                        </div>

                        <div class="mt-8 bg-black/5 dark:bg-card/80 border border-border rounded-xl p-5 font-mono text-xs text-muted-foreground dark:text-gray-400 backdrop-blur-sm shadow-inner">
                            <div class="flex gap-2 mb-3 border-b border-border/50 pb-2">
                                <div class="w-3 h-3 rounded-full bg-red-500/50"></div>
                                <div class="w-3 h-3 rounded-full bg-yellow-500/50"></div>
                                <div class="w-3 h-3 rounded-full bg-green-500/50"></div>
                            </div>
                            <p class="text-primary">> initializing_booking_engine...</p>
                            <p>> seat_allocation: <span class="text-green-600 dark:text-green-400">confirmed</span></p>
                            <p>> generating_qr_hash: [====================] 100%</p>
                            <p class="animate-pulse font-bold text-foreground dark:text-white">> ticket_issued_successfully_</p>
                        </div>
                    </div>
                </div>

                <div class="glass-panel rounded-[2rem] p-8 flex flex-col justify-center group bg-card/40 dark:bg-card/60">
                    <i data-lucide="shield-check" class="w-10 h-10 text-primary mb-4 group-hover:scale-110 transition-transform"></i>
                    <h3 class="text-xl font-bold mb-2 text-foreground">Keamanan Tingkat Protokol</h3>
                    <p class="text-sm text-muted-foreground dark:text-gray-300">Enkripsi data end-to-end melindungi setiap informasi pribadi penumpang.</p>
                </div>

                <div class="glass-panel rounded-[2rem] p-8 flex flex-col justify-center group bg-card/40 dark:bg-card/60">
                    <i data-lucide="scan-line" class="w-10 h-10 text-primary mb-4 group-hover:scale-110 transition-transform"></i>
                    <h3 class="text-xl font-bold mb-2 text-foreground">Tiket Tanpa Kertas</h3>
                    <p class="text-sm text-muted-foreground dark:text-gray-300">Akses gerbang terminal hanya dengan satu kali pemindaian QR Code dinamis.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="alur" class="py-24 border-y border-border/30 bg-card/5 dark:bg-card/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-5xl font-bold text-foreground">Alur <span class="text-primary">Perjalanan</span></h2>
            </div>

            <div class="relative">
                <div class="hidden md:block absolute top-1/2 left-0 w-full h-[1px] bg-gradient-to-r from-transparent via-primary/50 to-transparent -translate-y-1/2 z-0"></div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    @foreach([
                        ['step' => '01', 'title' => 'Daftar', 'desc' => 'Buat identitas digital Anda'],
                        ['step' => '02', 'title' => 'Pilih', 'desc' => 'Tentukan rute & kursi armada'],
                        ['step' => '03', 'title' => 'Transaksi', 'desc' => 'Pembayaran aman multi-kanal'],
                        ['step' => '04', 'title' => 'Berangkat', 'desc' => 'Scan QR & nikmati perjalanan']
                    ] as $item)
                        <div class="relative z-10 glass-panel p-8 rounded-2xl text-center md:text-left transition hover:-translate-y-2 duration-300 bg-background/80 dark:bg-background/60">
                            <div class="text-6xl font-bold text-foreground dark:text-foreground/10 absolute top-2 right-4 select-none">{{ $item['step'] }}</div>
                            
                            <div class="w-12 h-12 bg-card border border-primary rounded-full flex items-center justify-center mb-6 mx-auto md:mx-0 shadow-[0_0_20px_hsl(var(--primary)/0.3)]">
                                <div class="w-3 h-3 bg-foreground rounded-full"></div>
                            </div>
                            
                            <h3 class="text-xl font-bold text-foreground mb-2">{{ $item['title'] }}</h3>
                            <p class="text-sm text-muted-foreground dark:text-gray-300">{{ $item['desc'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section id="faq" class="py-24 px-4">
        <div class="max-w-3xl mx-auto">
            <h2 class="text-3xl font-bold text-center mb-12 text-foreground">Pusat <span class="text-primary">Informasi</span></h2>

            <div class="space-y-4">
                @foreach([
                    ['q' => 'Apakah saya perlu mencetak tiket?', 'a' => 'Tidak perlu. Sistem kami sepenuhnya digital (paperless). Cukup tunjukkan QR Code pada smartphone Anda kepada petugas.'],
                    ['q' => 'Berapa lama proses konfirmasi booking?', 'a' => 'Konfirmasi tiket terjadi instan setelah pembayaran berhasil. Tiket Anda langsung tersimpan di aplikasi dan siap digunakan.'],
                    ['q' => 'Metode pembayaran apa yang tersedia?', 'a' => 'Kami mendukung Transfer Bank Virtual Account, E-Wallet (Gopay, OVO, Dana), dan pembayaran retail (Indomaret/Alfamart).']
                ] as $index => $faq)
                    <div class="glass-panel rounded-xl overflow-hidden group">
                        <details class="w-full">
                            <summary class="flex justify-between items-center font-medium cursor-pointer list-none p-6 hover:bg-muted/30 dark:hover:bg-card/50 transition text-foreground">
                                <span>{{ $faq['q'] }}</span>
                                <span class="transition-transform group-open:rotate-180 text-primary">
                                    <i data-lucide="chevron-down" class="w-5 h-5"></i>
                                </span>
                            </summary>
                            <div class="px-6 pb-6 pt-0 text-muted-foreground border-t border-border/30 bg-muted/10 dark:bg-card/30">
                                <p class="mt-4 dark:text-gray-300">{{ $faq['a'] }}</p>
                            </div>
                        </details>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="py-32 relative overflow-hidden text-center px-4">
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[400px] bg-primary/20 dark:bg-primary/10 blur-[120px] rounded-full z-[-1]"></div>

        <div class="max-w-4xl mx-auto relative z-10">
            <h2 class="text-4xl md:text-6xl font-bold mb-8 text-foreground">Siap Memulai <br> <span class="text-primary">Perjalanan?</span></h2>
            <p class="text-xl text-muted-foreground dark:text-gray-300 mb-12 max-w-2xl mx-auto">
                Bergabunglah dengan ekosistem Tiket Bus sekarang. Pengalaman perjalanan modern menanti Anda.
            </p>
            
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                @guest
                    <a href="{{ route('register') }}" class="px-10 py-4 bg-foreground text-background font-bold rounded-full hover:shadow-[0_0_30px_rgba(255,255,255,0.3)] dark:hover:shadow-[0_0_30px_rgba(255,255,255,0.1)] transition-all">
                        Buat Akun Baru
                    </a>
                    <a href="{{ route('login') }}" class="px-10 py-4 glass-panel text-foreground font-bold rounded-full hover:bg-muted dark:hover:bg-card/50 transition-all">
                        Masuk
                    </a>
                @else
                    <a href="{{ route('dashboard') }}" class="px-12 py-5 bg-primary text-primary-foreground font-bold text-lg rounded-full hover:shadow-[0_0_40px_hsl(var(--primary)/0.4)] hover:scale-105 transition-all duration-300">
                        Buka Dashboard
                    </a>
                @endguest
            </div>
        </div>
    </section>

    <footer class="border-t border-border bg-card/30 dark:bg-card/80 pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-8">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 relative flex items-center justify-center grayscale hover:grayscale-0 transition-all opacity-80 hover:opacity-100">
                         <img src="{{ asset('assets/images/logo.png') }}" alt="Logo Tiket Bus" class="w-full h-full object-contain">
                    </div>
                    <span class="font-bold text-xl text-foreground">Tiket Bus</span>
                </div>
                
                <div class="flex flex-wrap justify-center gap-8 text-sm font-medium text-muted-foreground dark:text-gray-400">
                    <a href="#" class="hover:text-primary transition-colors">Syarat & Ketentuan</a>
                    <a href="#" class="hover:text-primary transition-colors">Kebijakan Privasi</a>
                    <a href="#" class="hover:text-primary transition-colors">Bantuan</a>
                </div>

                <div class="text-muted-foreground dark:text-gray-500 text-sm font-mono">
                    &copy; {{ date('Y') }} Tiket Bus Protocol.
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Init Lucide Icons
        lucide.createIcons();

        // Dark Mode Toggle Logic
        const themeToggleBtn = document.getElementById('theme-toggle');
        const htmlElement = document.documentElement;

        themeToggleBtn.addEventListener('click', () => {
            // Toggle class 'dark'
            htmlElement.classList.toggle('dark');
            
            // Simpan preferensi ke LocalStorage
            if (htmlElement.classList.contains('dark')) {
                localStorage.setItem('theme', 'dark');
            } else {
                localStorage.setItem('theme', 'light');
            }
        });
    </script>
</body>
</html>
