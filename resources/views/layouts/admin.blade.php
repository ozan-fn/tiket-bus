<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Admin</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Tom Select CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">

    <!-- Dark Mode Script (Prevent Flash) -->
    <script>
        // Apply theme immediately before page renders
        (function() {
            const theme = localStorage.getItem('theme') || 'light';
            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
    <style>
        @keyframes duck-walk-random-1 {
            0% { transform: translateX(-100px); }
            8% { transform: translateX(15vw) scaleX(1); }
            12% { transform: translateX(18vw) scaleX(1); }
            22% { transform: translateX(35vw) scaleX(1); }
            28% { transform: translateX(42vw) scaleX(1); }
            35% { transform: translateX(52vw) scaleX(1); }
            42% { transform: translateX(65vw) scaleX(-1); }
            50% { transform: translateX(78vw) scaleX(-1); }
            58% { transform: translateX(65vw) scaleX(-1); }
            68% { transform: translateX(45vw) scaleX(-1); }
            76% { transform: translateX(28vw) scaleX(-1); }
            82% { transform: translateX(12vw) scaleX(1); }
            90% { transform: translateX(8vw) scaleX(1); }
            100% { transform: translateX(-100px) scaleX(1); }
        }

        @keyframes duck-walk-random-2 {
            0% { transform: translateX(-120px); }
            10% { transform: translateX(20vw) scaleX(1); }
            18% { transform: translateX(40vw) scaleX(1); }
            25% { transform: translateX(48vw) scaleX(1); }
            32% { transform: translateX(58vw) scaleX(1); }
            40% { transform: translateX(72vw) scaleX(-1); }
            48% { transform: translateX(82vw) scaleX(-1); }
            55% { transform: translateX(70vw) scaleX(-1); }
            65% { transform: translateX(48vw) scaleX(-1); }
            73% { transform: translateX(32vw) scaleX(-1); }
            81% { transform: translateX(15vw) scaleX(1); }
            88% { transform: translateX(5vw) scaleX(1); }
            100% { transform: translateX(-120px) scaleX(1); }
        }

        @keyframes duck-walk-random-3 {
            0% { transform: translateX(-80px); }
            7% { transform: translateX(12vw) scaleX(1); }
            14% { transform: translateX(32vw) scaleX(1); }
            21% { transform: translateX(44vw) scaleX(1); }
            29% { transform: translateX(56vw) scaleX(1); }
            38% { transform: translateX(70vw) scaleX(-1); }
            46% { transform: translateX(80vw) scaleX(-1); }
            54% { transform: translateX(68vw) scaleX(-1); }
            62% { transform: translateX(50vw) scaleX(-1); }
            70% { transform: translateX(35vw) scaleX(-1); }
            78% { transform: translateX(18vw) scaleX(1); }
            86% { transform: translateX(6vw) scaleX(1); }
            100% { transform: translateX(-80px) scaleX(1); }
        }

        @keyframes leg-swing {
            0%, 100% { transform: rotateX(0deg); }
            50% { transform: rotateX(15deg); }
        }

        .duck-parent {
            position: absolute;
            bottom: 0;
            z-index: 10;
            animation-timing-function: ease-in-out;
        }

        .duck-parent.parent-1 {
            animation: duck-walk-random-1 38s infinite;
            animation-delay: 0s;
        }

        .duck-parent.child-1 {
            animation: duck-walk-random-2 42s infinite;
            animation-delay: -3s;
            opacity: 0.85;
        }

        .duck-parent.child-2 {
            animation: duck-walk-random-3 45s infinite;
            animation-delay: -6s;
            opacity: 0.7;
        }

        .duck-legs {
            transform-origin: center top;
            animation: leg-swing 0.4s ease-in-out infinite;
        }
    </style>
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-background">
        <!-- Mobile Overlay -->
        <div id="sidebar-overlay" class="fixed inset-0 bg-background/80 backdrop-blur-sm z-40 lg:hidden hidden" onclick="toggleSidebar()"></div>

        <div class="flex h-screen overflow-hidden">
            <!-- Sidebar -->
            <aside id="sidebar" class="fixed lg:static inset-y-0 left-0 z-50 w-64 bg-card border-r border-border flex flex-col transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out">
                <!-- Logo -->
                <div class="h-16 flex items-center justify-between px-6 border-b border-border shrink-0">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                        {{-- <x-lucide-zap class="w-8 h-8 text-primary" /> --}}
                        <span class="text-xl font-bold">tiket bus</span>
                    </a>
                    <!-- Close button for mobile -->
                    <button onclick="toggleSidebar()" class="lg:hidden p-2 rounded-lg hover:bg-accent">
                        <x-lucide-x class="w-5 h-5" />
                    </button>
                </div>

                <!-- Navigation -->
                <nav id="sidebar-nav" class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
                    @php
                        $userRole = auth()->user()?->roles->first()?->name ?? 'agent';

                        $menus = [
                            ['label' => 'Dashboard', 'route' => 'dashboard', 'icon' => 'layout-dashboard', 'type' => 'menu', 'roles' => ['owner', 'agent', 'conductor']],
                            ['label' => 'Data Setup', 'type' => 'section', 'roles' => ['owner']],
                            ['label' => 'Bus', 'route' => 'admin/bus.index', 'icon' => 'bus', 'type' => 'menu', 'roles' => ['owner']],
                            ['label' => 'Fasilitas', 'route' => 'admin/fasilitas.index', 'icon' => 'sparkles', 'type' => 'menu', 'roles' => ['owner']],
                            ['label' => 'Kelas Bus', 'route' => 'admin/kelas-bus.index', 'icon' => 'layers', 'type' => 'menu', 'roles' => ['owner']],
                            ['label' => 'User', 'route' => 'admin/user.index', 'icon' => 'users', 'type' => 'menu', 'roles' => ['owner']],
                            ['label' => 'Banner', 'route' => 'admin/banner.index', 'icon' => 'image', 'type' => 'menu', 'roles' => ['owner']],
                            ['label' => 'Rute & Jadwal', 'type' => 'section', 'roles' => ['owner',  'conductor']],
                            ['label' => 'Terminal', 'route' => 'admin/terminal.index', 'icon' => 'building-2', 'type' => 'menu', 'roles' => ['owner']],
                            ['label' => 'Rute', 'route' => 'admin/rute.index', 'icon' => 'route', 'type' => 'menu', 'roles' => ['owner']],
                            ['label' => 'Jadwal', 'route' => 'admin/jadwal.index', 'icon' => 'calendar', 'type' => 'menu', 'roles' => ['owner',  'conductor']],
                            ['label' => 'Penumpang', 'type' => 'section', 'roles' => ['conductor']],
                            ['label' => 'Check Penumpang', 'route' => 'admin/history-pemesanan', 'icon' => 'users-check', 'type' => 'menu', 'roles' => ['conductor']],
                            ['label' => 'Transaksi', 'type' => 'section', 'roles' => ['owner', 'agent']],
                            ['label' => 'Beli Tiket', 'route' => 'admin/pemesanan.index', 'icon' => 'ticket', 'type' => 'menu', 'roles' => ['agent']],
                            ['label' => 'History Pemesanan', 'route' => 'admin/history-pemesanan', 'icon' => 'clipboard-list', 'type' => 'menu', 'roles' => ['owner', 'agent']],
                            ['label' => 'Pembayaran Manual', 'route' => 'admin/pembayaran-manual.index', 'icon' => 'wallet', 'type' => 'menu', 'roles' => ['owner', 'agent']],
                            ['label' => 'Pricing', 'type' => 'section', 'roles' => ['owner', 'agent']],
                            ['label' => 'Harga Tiket', 'route' => 'admin/jadwal-kelas-bus.index', 'icon' => 'tag', 'type' => 'menu', 'roles' => ['owner', 'agent']],
                            ['label' => 'Scan', 'type' => 'section', 'roles' => ['agent']],
                            ['label' => 'Scan Tiket', 'route' => 'admin/scan.index', 'icon' => 'qr-code', 'type' => 'menu', 'roles' => ['agent']],
                            ['label' => 'User', 'route' => 'admin/user.index', 'icon' => 'users', 'type' => 'menu', 'roles' => ['agent']],
                            ['label' => 'Pemeriksaan', 'type' => 'section', 'roles' => ['agent']],
                            ['label' => 'Cek Kursi', 'route' => 'admin/cek-kursi.index', 'icon' => 'armchair', 'type' => 'menu', 'roles' => ['agent']],
                            ['label' => 'Laporan', 'type' => 'section', 'roles' => ['owner']],
                            ['label' => 'Analytics', 'route' => 'admin/laporan.index', 'icon' => 'bar-chart-3', 'type' => 'menu', 'roles' => ['owner']],
                            ['label' => 'Laporan Tiket', 'route' => 'admin/laporan.tiket', 'icon' => 'ticket', 'type' => 'menu', 'roles' => ['owner']],
                            ['label' => 'Laporan Pendapatan', 'route' => 'admin/laporan.pendapatan', 'icon' => 'dollar-sign', 'type' => 'menu', 'roles' => ['owner']],
                            ['label' => 'Laporan Penumpang', 'route' => 'admin/laporan.penumpang', 'icon' => 'users', 'type' => 'menu', 'roles' => ['owner']],
                        ];
                    @endphp

                    @foreach($menus as $menu)
                            @if(in_array($userRole, $menu['roles']))
                                @if($menu['type'] === 'section')
                                    <div class="pt-4 pb-2 px-3">
                                        <h3 class="text-xs font-semibold text-muted-foreground uppercase tracking-wider">{{ $menu['label'] }}</h3>
                                    </div>
                                @else
                                    @php
                                        // 1. Cek Exact Match (Kecocokan Tepat)
                                        $isActive = request()->routeIs($menu['route']);

                                        // 2. Logic Khusus Resource (hanya jika belum aktif dan route berakhiran .index)
                                        if (!$isActive && \Illuminate\Support\Str::endsWith($menu['route'], '.index')) {
                                            $baseRoute = \Illuminate\Support\Str::replace('.index', '', $menu['route']);

                                            // PERBAIKAN DI SINI:
                                            // Alih-alih pakai wildcard '.*' yang mengambil semuanya,
                                            // Kita hanya cek spesifik ke action CRUD standar.
                                            // Ini mencegah 'admin/laporan.tiket' dianggap anak dari 'admin/laporan.index'
                                            $isActive = request()->routeIs([
                                                $baseRoute . '.create',
                                                $baseRoute . '.store',
                                                $baseRoute . '.edit',
                                                $baseRoute . '.update',
                                                $baseRoute . '.show',
                                                $baseRoute . '.destroy',
                                                // Tambahkan action lain jika route resource punya method custom yang memang anaknya halaman ini
                                            ]);
                                        }

                                        // 3. Fallback Wildcard (Opsional, hati-hati pakai ini jika struktur nama route mirip)
                                        // Kita tambahkan pengecekan agar tidak menimpa logic di atas
                                        if (!$isActive && !str_contains($menu['route'], '.index')) {
                                            $isActive = request()->routeIs($menu['route'] . '*');
                                        }
                                    @endphp

                                    <a href="{{ route($menu['route']) }}"
                                       class="group relative flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors
                                       {{ $isActive ? 'bg-primary text-primary-foreground shadow-sm' : 'text-muted-foreground hover:bg-accent hover:text-accent-foreground' }}">

                                        @if($isActive)
                                            <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-primary-foreground rounded-r-full"></span>
                                        @endif

                                        <x-dynamic-component :component="'lucide-' . $menu['icon']" class="w-5 h-5 shrink-0" />
                                        {{ $menu['label'] }}
                                    </a>
                                @endif
                            @endif
                        @endforeach
                </nav>

                <!-- User Menu -->
                <div class="border-t border-border p-4 shrink-0">
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="w-full flex items-center gap-3 p-2 rounded-lg hover:bg-accent transition-colors">
                            <x-ui.avatar>
                                <x-ui.avatar.fallback class="bg-primary text-primary-foreground font-semibold">
                                    @if(auth()->check())
                                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                                    @else
                                        GU
                                    @endif
                                </x-ui.avatar.fallback>
                            </x-ui.avatar>
                            <div class="flex-1 min-w-0 text-left">
                                <p class="text-sm font-medium truncate">
                                    @if(auth()->check())
                                        {{ auth()->user()->name }}
                                    @else
                                        Guest
                                    @endif
                                </p>
                                <p class="text-xs text-muted-foreground truncate">
                                    @if(auth()->check())
                                        {{ auth()->user()->email }}
                                    @else
                                        guest@example.com
                                    @endif
                                </p>
                            </div>
                            <x-lucide-chevron-right class="w-4 h-4 text-muted-foreground shrink-0 transition-transform" x-bind:class="open ? 'rotate-90' : ''" />
                        </button>

                        <div
                            x-show="open"
                            x-transition
                            @click.outside="open = false"
                            class="absolute bottom-full left-0 right-0 mb-2 z-50 rounded-md border bg-popover text-popover-foreground shadow-lg p-1"
                        >
                            <div class="px-2 py-1.5 text-sm font-semibold">Akun Saya</div>
                            <div class="h-px bg-border my-1"></div>
                            <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 w-full px-2 py-1.5 text-sm rounded-sm hover:bg-accent transition-colors">
                                <x-lucide-user class="w-4 h-4" />
                                Profile
                            </a>
                            <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 w-full px-2 py-1.5 text-sm rounded-sm hover:bg-accent transition-colors">
                                <x-lucide-settings class="w-4 h-4" />
                                Pengaturan
                            </a>
                            <div class="h-px bg-border my-1"></div>
                            <form method="POST" action="{{ route('logout') }}" class="w-full">
                                @csrf
                                <button type="submit" class="flex items-center gap-2 w-full px-2 py-1.5 text-sm rounded-sm hover:bg-accent transition-colors text-destructive">
                                    <x-lucide-log-out class="w-4 h-4" />
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- Main Content -->
            <div class="flex-1 flex flex-col overflow-hidden w-full lg:w-auto">
                <!-- Top Bar -->
                <header class="h-16 bg-card border-b border-border flex items-center justify-between px-4 lg:px-6 shrink-0 relative overflow-hidden">
                    <!-- Parent Duck -->
                    <div class="duck-parent parent-1">
                        <svg width="45" height="35" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="text-primary">
                            <circle cx="18" cy="8" r="3" fill="currentColor" class="text-primary"/>
                            <path d="M21 8h2" stroke="currentColor" stroke-width="1.5" class="text-primary"/>
                            <ellipse cx="13" cy="14" rx="7" ry="5" fill="currentColor" class="text-primary" opacity="0.9"/>
                            <g class="duck-legs">
                                <path d="M11 19v2M14 19v2" stroke="currentColor" stroke-width="1.5" class="text-primary"/>
                            </g>
                        </svg>
                    </div>

                    <!-- Child Duck 1 -->
                    <div class="duck-parent child-1">
                        <svg width="32" height="25" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="text-primary">
                            <circle cx="18" cy="8" r="2.5" fill="currentColor" class="text-primary"/>
                            <path d="M20.5 8h1.5" stroke="currentColor" stroke-width="1.5" class="text-primary"/>
                            <ellipse cx="13.5" cy="13.5" rx="5.5" ry="4" fill="currentColor" class="text-primary" opacity="0.85"/>
                            <g class="duck-legs">
                                <path d="M12 18v1.5M14.5 18v1.5" stroke="currentColor" stroke-width="1.5" class="text-primary"/>
                            </g>
                        </svg>
                    </div>

                    <!-- Child Duck 2 -->
                    <div class="duck-parent child-2">
                        <svg width="28" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="text-primary">
                            <circle cx="18" cy="8" r="2" fill="currentColor" class="text-primary"/>
                            <path d="M20 8h1.5" stroke="currentColor" stroke-width="1.5" class="text-primary"/>
                            <ellipse cx="13" cy="13" rx="5" ry="3.5" fill="currentColor" class="text-primary" opacity="0.8"/>
                            <g class="duck-legs">
                                <path d="M11.5 17.5v1.5M13.5 17.5v1.5" stroke="currentColor" stroke-width="1.5" class="text-primary"/>
                            </g>
                        </svg>
                    </div>
                    <div class="flex items-center gap-3">
                        <!-- Mobile menu button -->
                        <button onclick="toggleSidebar()" class="lg:hidden p-2 rounded-lg hover:bg-accent transition-colors">
                            <x-lucide-menu class="w-6 h-6" />
                        </button>
                        @isset($header)
                            <div class="flex-1">
                                {{ $header }}
                            </div>
                        @endisset
                    </div>
                    <div class="flex items-center gap-2">
                        <!-- Fullscreen Toggle -->
                        <button onclick="toggleFullscreen()" class="p-2 rounded-lg hover:bg-accent transition-colors" title="Toggle Fullscreen">
                            <x-lucide-maximize class="w-5 h-5" id="fullscreen-icon-max" />
                            <x-lucide-minimize class="w-5 h-5 hidden" id="fullscreen-icon-min" />
                        </button>

                        <!-- Theme Toggle -->
                        <x-theme-toggle />

                        <span class="text-sm text-muted-foreground hidden sm:inline" id="current-time">{{ now()->format('d M Y, H:i:s') }}</span>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1 overflow-y-auto bg-muted">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </div>

    <!-- Tom Select JS -->
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>

    <script>
        // Update time every second
        function updateTime() {
            const now = new Date();
            const options = { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false };
            const formattedTime = now.toLocaleDateString('id-ID', options).replace(',', '');
            document.getElementById('current-time').textContent = formattedTime;
        }

        // Update time immediately and then every second
        updateTime();
        setInterval(updateTime, 1000);

        // Fullscreen toggle
        function toggleFullscreen() {
            const maxIcon = document.getElementById('fullscreen-icon-max');
            const minIcon = document.getElementById('fullscreen-icon-min');

            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen().then(() => {
                    maxIcon.classList.add('hidden');
                    minIcon.classList.remove('hidden');
                }).catch((err) => {
                    console.error('Error attempting to enable fullscreen:', err);
                });
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen().then(() => {
                        maxIcon.classList.remove('hidden');
                        minIcon.classList.add('hidden');
                    });
                }
            }
        }

        // Listen for fullscreen changes (ESC key or F11)
        document.addEventListener('fullscreenchange', function() {
            const maxIcon = document.getElementById('fullscreen-icon-max');
            const minIcon = document.getElementById('fullscreen-icon-min');

            if (document.fullscreenElement) {
                maxIcon.classList.add('hidden');
                minIcon.classList.remove('hidden');
            } else {
                maxIcon.classList.remove('hidden');
                minIcon.classList.add('hidden');
            }
        });

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');

            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }

        // Close sidebar when clicking outside on mobile
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 1024) {
                const sidebar = document.getElementById('sidebar');
                const overlay = document.getElementById('sidebar-overlay');
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.add('hidden');
            }
        });

        // Save & Restore Sidebar Scroll Position
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarNav = document.getElementById('sidebar-nav');

            if (sidebarNav) {
                // Restore scroll position
                const savedScrollPosition = sessionStorage.getItem('sidebarScrollPosition');
                if (savedScrollPosition !== null) {
                    sidebarNav.scrollTop = parseInt(savedScrollPosition, 10);
                }

                // Save scroll position before page unload
                window.addEventListener('beforeunload', function() {
                    sessionStorage.setItem('sidebarScrollPosition', sidebarNav.scrollTop);
                });

                // Save scroll position on navigation links click
                const navLinks = sidebarNav.querySelectorAll('a');
                navLinks.forEach(link => {
                    link.addEventListener('click', function() {
                        sessionStorage.setItem('sidebarScrollPosition', sidebarNav.scrollTop);
                    });
                });
            }
        });
    </script>

    @stack('scripts')
</body>

</html>
