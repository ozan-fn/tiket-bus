<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased bg-background text-foreground font-sans min-h-screen flex flex-col">

        <!-- Navbar -->
        <nav class="sticky top-0 border-b border-border bg-background z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center space-x-8">
                        <span class="text-2xl font-bold text-primary">BusGo</span>
                        <div class="hidden md:flex space-x-6">
                            <a href="#" class="text-foreground hover:text-primary transition">Beranda</a>
                            <a href="#" class="text-foreground hover:text-primary transition">Cek Jadwal</a>
                            <a href="#" class="text-foreground hover:text-primary transition">Bantuan</a>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        <x-theme-toggle />
                        @guest
                            <a href="{{ route('login') }}" class="px-4 py-2 bg-transparent border border-input hover:bg-accent hover:text-accent-foreground rounded-lg transition">
                                Masuk
                            </a>
                            <a href="{{ route('register') }}" class="px-4 py-2 bg-primary text-primary-foreground rounded-lg hover:opacity-90 transition">
                                Daftar
                            </a>
                        @else
                            <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-primary text-primary-foreground rounded-lg hover:opacity-90 transition">
                                Dashboard
                            </a>
                        @endguest
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="flex-1 py-12 md:py-20 bg-gradient-to-br from-primary/10 via-background to-secondary/10 relative overflow-hidden">
            <!-- Decorative Elements -->
            <div class="absolute top-10 left-10 w-4 h-4 bg-primary/30 rounded-full"></div>
            <div class="absolute top-20 right-20 w-6 h-6 bg-secondary/20 rounded-full"></div>
            <div class="absolute bottom-20 left-1/4 w-3 h-3 bg-primary/40 rounded-full"></div>
            <div class="absolute top-1/3 right-10 w-5 h-5 bg-secondary/30 rounded-full"></div>
            <div class="absolute bottom-10 right-1/3 w-2 h-2 bg-primary/50 rounded-full"></div>
            <div class="absolute top-1/2 left-20 w-4 h-4 bg-secondary/25 rounded-full"></div>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="grid md:grid-cols-2 gap-12 items-center">

                    <!-- Text Content -->
                    <div class="space-y-6">
                        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-foreground leading-tight">
                            Perjalanan Nyaman, Harga Aman.
                        </h1>
                        <p class="text-lg text-muted-foreground">
                            Download aplikasi BusGo untuk pesan tiket bus dengan mudah. Pilih jadwal, bayar dengan aman, dan nikmati perjalanan tanpa khawatir.
                        </p>
                        <div class="flex space-x-4">
                            <button class="px-6 py-3 bg-primary text-primary-foreground rounded-lg font-medium hover:opacity-90 transition">
                                Download App
                            </button>
                        </div>
                    </div>

                    <!-- Phone Mockup -->
                    <div class="flex justify-center">
                        <div class="relative w-64 h-[520px]">
                            <!-- Phone Frame -->
                            <div class="absolute inset-0 border-8 border-foreground rounded-[2.5rem] bg-background shadow-2xl overflow-hidden">
                                <!-- Phone Screen Content -->
                                    <div class="h-full overflow-y-auto p-4 space-y-4">
                                        <!-- Header -->
                                        <div class="text-sm font-medium text-foreground mb-4">
                                            Download Sekarang <i data-lucide="hand" class="inline h-4 w-4 text-white" ></i>
                                        </div>

                                        <!-- Download Card -->
                                        <div class="bg-card border border-border shadow-sm rounded-lg p-4 space-y-3">
                                            <h3 class="font-semibold text-card-foreground text-sm">Aplikasi BusGo</h3>

                                            <p class="text-xs text-muted-foreground">
                                                Pesan tiket bus dimana saja dengan aplikasi kami.
                                            </p>

                                            <button class="bg-primary text-primary-foreground w-full py-2 rounded font-medium text-sm hover:opacity-90 transition">
                                                Download di App Store
                                            </button>
                                            <button class="bg-muted text-muted-foreground w-full py-2 rounded font-medium text-sm hover:opacity-90 transition">
                                                Download di Google Play
                                            </button>
                                        </div>

                                        <!-- Quick Features -->
                                        <div class="grid grid-cols-2 gap-2">
                                            <div class="bg-card border border-border rounded-lg p-3 text-center">
                                                <div class="mb-1"><i data-lucide="smartphone" class="h-6 w-6 mx-auto text-white" ></i></div>
                                                <div class="text-xs text-card-foreground font-medium">Mobile App</div>
                                            </div>
                                            <div class="bg-card border border-border rounded-lg p-3 text-center">
                                                <div class="mb-1"><i data-lucide="ticket" class="h-6 w-6 mx-auto text-white" ></i></div>
                                                <div class="text-xs text-card-foreground font-medium">E-Ticket</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Phone Notch -->
                                <div class="absolute top-0 left-1/2 -translate-x-1/2 w-32 h-6 bg-foreground rounded-b-2xl"></div>
                            </div>

                            <!-- Decorative Element Outside Phone -->
                            <div class="absolute top-2 right-2 w-8 h-8 bg-primary/20 rounded-full flex items-center justify-center">
                                <i data-lucide="star" class="h-4 w-4 text-primary" ></i>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section id="features" class="py-16 bg-muted">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold text-foreground mb-4">
                        Keunggulan BusGo
                    </h2>
                    <p class="text-muted-foreground text-lg">
                        Kenapa harus download aplikasi BusGo?
                    </p>
                </div>

                <div class="grid md:grid-cols-3 gap-8">
                    <!-- Feature 1 -->
                    <div class="bg-card text-card-foreground border border-border shadow-sm rounded-xl p-6 space-y-4 hover:shadow-md transition">
                        <div class="w-12 h-12 bg-primary bg-opacity-10 rounded-lg flex items-center justify-center">
                            <i data-lucide="credit-card" class="h-6 w-6 text-white" ></i>
                        </div>
                        <h3 class="text-xl font-semibold">Pemesanan Mudah</h3>
                        <p class="text-muted-foreground">
                            Pesan tiket bus dengan cepat dan mudah melalui aplikasi mobile kami. Pilih rute, tanggal, dan kursi dalam hitungan menit.
                        </p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="bg-card text-card-foreground border border-border shadow-sm rounded-xl p-6 space-y-4 hover:shadow-md transition">
                        <div class="w-12 h-12 bg-primary bg-opacity-10 rounded-lg flex items-center justify-center">
                            <i data-lucide="armchair" class="h-6 w-6 text-white" ></i>
                        </div>
                        <h3 class="text-xl font-semibold">Cek Jadwal Real-time</h3>
                        <p class="text-muted-foreground">
                            Lihat jadwal bus terkini dan update secara real-time di aplikasi. Temukan keberangkatan yang sesuai dengan kebutuhan Anda.
                        </p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="bg-card text-card-foreground border border-border shadow-sm rounded-xl p-6 space-y-4 hover:shadow-md transition">
                        <div class="w-12 h-12 bg-primary bg-opacity-10 rounded-lg flex items-center justify-center">
                            <i data-lucide="headphones" class="h-6 w-6 text-white" ></i>
                        </div>
                        <h3 class="text-xl font-semibold">Dukungan Pelanggan 24/7</h3>
                        <p class="text-muted-foreground">
                            Tim dukungan kami siap membantu Anda kapan saja melalui aplikasi. Hubungi kami untuk bantuan pemesanan atau pertanyaan lainnya.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- FAQ Section -->
        <section class="py-16">
            <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold text-foreground mb-4">
                        Pertanyaan Umum
                    </h2>
                    <p class="text-muted-foreground">
                        Temukan jawaban untuk pertanyaan yang sering diajukan tentang aplikasi
                    </p>
                </div>

                <div class="space-y-4">
                    <details class="group border-b border-border pb-4">
                        <summary class="flex justify-between items-center cursor-pointer text-foreground font-medium py-3">
                            <span>Bagaimana cara download dan menggunakan aplikasi?</span>
                            <i data-lucide="chevron-down" class="text-white group-open:rotate-180 transition h-4 w-4" ></i>
                        </summary>
                        <p class="text-muted-foreground mt-2 pl-4">
                            Download aplikasi dari App Store atau Google Play. Daftar akun dan mulai pesan tiket bus dengan mudah.
                        </p>
                    </details>

                    <details class="group border-b border-border pb-4">
                        <summary class="flex justify-between items-center cursor-pointer text-foreground font-medium py-3">
                            <span>Apakah aplikasi tersedia di semua platform?</span>
                            <i data-lucide="chevron-down" class="text-white group-open:rotate-180 transition h-4 w-4" ></i>
                        </summary>
                        <p class="text-muted-foreground mt-2 pl-4">
                            Ya, aplikasi BusGo tersedia di iOS dan Android. Download sekarang dan nikmati fitur lengkapnya.
                        </p>
                    </details>

                    <details class="group border-b border-border pb-4">
                        <summary class="flex justify-between items-center cursor-pointer text-foreground font-medium py-3">
                            <span>Apakah data saya aman di aplikasi?</span>
                            <i data-lucide="chevron-down" class="text-white group-open:rotate-180 transition h-4 w-4" ></i>
                        </summary>
                        <p class="text-muted-foreground mt-2 pl-4">
                            Kami menggunakan enkripsi end-to-end untuk melindungi data Anda. Privasi dan keamanan adalah prioritas utama kami.
                        </p>
                    </details>

                    <details class="group border-b border-border pb-4">
                        <summary class="flex justify-between items-center cursor-pointer text-foreground font-medium py-3">
                            <span>Bagaimana cara menghubungi dukungan?</span>
                            <i data-lucide="chevron-down" class="text-white group-open:rotate-180 transition h-4 w-4" ></i>
                        </summary>
                        <p class="text-muted-foreground mt-2 pl-4">
                            Hubungi tim dukungan kami melalui aplikasi di menu "Bantuan" atau email support@busgo.com. Kami siap membantu 24/7.
                        </p>
                    </details>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-sidebar py-12 mt-auto">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid md:grid-cols-4 gap-8 mb-8">
                    <div>
                        <h3 class="text-xl font-bold text-foreground mb-4">BusGo</h3>
                        <p class="text-muted-foreground text-sm">
                            Solusi perjalanan bus online terpercaya di Indonesia. Download aplikasi untuk pesan tiket dengan mudah dan aman.
                        </p>
                    </div>

                    <div>
                        <h4 class="font-semibold text-foreground mb-4">Layanan</h4>
                        <ul class="space-y-2 text-muted-foreground text-sm">
                            <li><a href="#" class="hover:text-primary transition">Pesan Tiket</a></li>
                            <li><a href="#" class="hover:text-primary transition">Cek Jadwal</a></li>
                            <li><a href="#" class="hover:text-primary transition">Lacak Bus</a></li>
                            <li><a href="#" class="hover:text-primary transition">Refund</a></li>
                        </ul>
                    </div>

                    <div>
                        <h4 class="font-semibold text-foreground mb-4">Perusahaan</h4>
                        <ul class="space-y-2 text-muted-foreground text-sm">
                            <li><a href="#" class="hover:text-primary transition">Tentang Kami</a></li>
                            <li><a href="#" class="hover:text-primary transition">Karir</a></li>
                            <li><a href="#" class="hover:text-primary transition">Blog</a></li>
                            <li><a href="#" class="hover:text-primary transition">Mitra</a></li>
                        </ul>
                    </div>

                    <div>
                        <h4 class="font-semibold text-foreground mb-4">Bantuan</h4>
                        <ul class="space-y-2 text-muted-foreground text-sm">
                            <li><a href="#" class="hover:text-primary transition">Pusat Bantuan</a></li>
                            <li><a href="#" class="hover:text-primary transition">Syarat & Ketentuan</a></li>
                            <li><a href="#" class="hover:text-primary transition">Kebijakan Privasi</a></li>
                            <li><a href="#" class="hover:text-primary transition">Kontak</a></li>
                        </ul>
                    </div>
                </div>

                <div class="border-t border-border pt-8 text-center text-muted-foreground text-sm">
                    <p>&copy; 2024 BusGo. Hak Cipta Dilindungi.</p>
                </div>
            </div>
        </footer>



    </body>
</html>
