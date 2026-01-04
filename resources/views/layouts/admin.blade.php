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
                        {{-- <i data-lucide="zap" class="w-8 h-8 text-primary" ></i> --}}
                        <img class="w-8" src="{{ asset('assets/images/logo.png') }}" alt="">
                        <span class="text-xl font-bold">Tiket Bus</span>
                    </a>
                    <!-- Close button for mobile -->
                    <button onclick="toggleSidebar()" class="lg:hidden p-2 rounded-lg hover:bg-accent">
                        <i data-lucide="x" class="w-5 h-5" ></i>
                    </button>
                </div>

                <!-- Navigation -->
                <nav id="sidebar-nav" class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
                    @php
                        $userRole = auth()->user()?->roles->first()?->name ?? 'agent';

                        $menus = [
                            ['label' => 'Dashboard', 'route' => 'dashboard', 'icon' => 'layout-dashboard', 'type' => 'menu', 'roles' => ['owner', 'agent', 'conductor', 'driver']],
                            ['label' => 'Data Setup', 'type' => 'section', 'roles' => ['owner']],
                            ['label' => 'Kelas Bus', 'route' => 'admin/kelas-bus.index', 'icon' => 'layers', 'type' => 'menu', 'roles' => ['owner']],
                            ['label' => 'Fasilitas', 'route' => 'admin/fasilitas.index', 'icon' => 'sparkles', 'type' => 'menu', 'roles' => ['owner']],
                            ['label' => 'Bus', 'route' => 'admin/bus.index', 'icon' => 'bus', 'type' => 'menu', 'roles' => ['owner']],
                            ['label' => 'User', 'route' => 'admin/user.index', 'icon' => 'users', 'type' => 'menu', 'roles' => ['owner']],
                            ['label' => 'Banner', 'route' => 'admin/banner.index', 'icon' => 'image', 'type' => 'menu', 'roles' => ['owner']],
                            ['label' => 'Rute & Jadwal', 'type' => 'section', 'roles' => ['owner', 'conductor']],
                            ['label' => 'Terminal', 'route' => 'admin/terminal.index', 'icon' => 'building-2', 'type' => 'menu', 'roles' => ['owner']],
                            ['label' => 'Rute', 'route' => 'admin/rute.index', 'icon' => 'route', 'type' => 'menu', 'roles' => ['owner']],
                            ['label' => 'Jadwal', 'route' => 'admin/jadwal.index', 'icon' => 'calendar', 'type' => 'menu', 'roles' => ['owner', 'conductor']],
                            ['label' => 'Penumpang', 'type' => 'section', 'roles' => ['conductor']],
                            ['label' => 'Check Penumpang', 'route' => 'admin/history-pemesanan', 'icon' => 'users-check', 'type' => 'menu', 'roles' => ['conductor']],
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
                            ['label' => 'Operasional', 'type' => 'section', 'roles' => ['driver']],
                            ['label' => 'Scan Tiket', 'route' => 'sopir.scan.index', 'icon' => 'qr-code', 'type' => 'menu', 'roles' => ['driver']],
                            ['label' => 'Cek Kursi', 'route' => 'sopir.cek-kursi.index', 'icon' => 'armchair', 'type' => 'menu', 'roles' => ['driver']],
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

                                        <i data-lucide="{{ $menu['icon'] }}" class="w-5 h-5 shrink-0"></i>
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
                            <i data-lucide="chevron-right" class="w-4 h-4 text-muted-foreground shrink-0 transition-transform" x-bind:class="open ? 'rotate-90' : ''" ></i>
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
                                <i data-lucide="user" class="w-4 h-4" ></i>
                                Profile
                            </a>
                            <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 w-full px-2 py-1.5 text-sm rounded-sm hover:bg-accent transition-colors">
                                <i data-lucide="settings" class="w-4 h-4" ></i>
                                Pengaturan
                            </a>
                            <div class="h-px bg-border my-1"></div>
                            <form method="POST" action="{{ route('logout') }}" class="w-full">
                                @csrf
                                <button type="submit" class="flex items-center gap-2 w-full px-2 py-1.5 text-sm rounded-sm hover:bg-accent transition-colors text-destructive">
                                    <i data-lucide="log-out" class="w-4 h-4" ></i>
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
                    <div class="flex items-center gap-3">
                        <!-- Mobile menu button -->
                        <button onclick="toggleSidebar()" class="lg:hidden p-2 rounded-lg hover:bg-accent transition-colors">
                            <i data-lucide="menu" class="w-6 h-6" ></i>
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
                            <i data-lucide="maximize" class="w-5 h-5" id="fullscreen-icon-max" ></i>
                            <i data-lucide="minimize" class="w-5 h-5 hidden" id="fullscreen-icon-min" ></i>
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

    <!-- Lucide Icons CDN -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <script>
        // Initialize icons when DOM is ready and after AJAX calls
        function initLucideIcons() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', initLucideIcons);

        // Re-initialize after Alpine components update
        if (typeof window.Alpine !== 'undefined') {
            document.addEventListener('alpine:init', initLucideIcons);
        }
    </script>
</body>

</html>
