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
                        <x-lucide-zap class="w-8 h-8 text-primary" />
                        <span class="text-xl font-bold">Admin Panel</span>
                    </a>
                    <!-- Close button for mobile -->
                    <button onclick="toggleSidebar()" class="lg:hidden p-2 rounded-lg hover:bg-accent">
                        <x-lucide-x class="w-5 h-5" />
                    </button>
                </div>

                <!-- Navigation -->
                <nav id="sidebar-nav" class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
                    <!-- Dashboard -->
                    <a href="{{ route('dashboard') }}" class="group relative flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('dashboard') ? 'bg-primary text-primary-foreground shadow-sm' : 'text-muted-foreground hover:bg-accent hover:text-accent-foreground' }} transition-colors">
                        @if(request()->routeIs('dashboard'))
                            <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-primary-foreground rounded-r-full"></span>
                        @endif
                        <x-lucide-layout-dashboard class="w-5 h-5 shrink-0" />
                        Dashboard
                    </a>

                    <!-- Master Data Section -->
                    <div class="pt-4 pb-2 px-3">
                        <h3 class="text-xs font-semibold text-muted-foreground uppercase tracking-wider">Master Data</h3>
                    </div>

                    <!-- Bus -->
                    <a href="{{ route('admin/bus.index') }}" class="group relative flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin/bus.*') ? 'bg-primary text-primary-foreground shadow-sm' : 'text-muted-foreground hover:bg-accent hover:text-accent-foreground' }} transition-colors">
                        @if(request()->routeIs('admin/bus.*'))
                            <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-primary-foreground rounded-r-full"></span>
                        @endif
                        <x-lucide-bus class="w-5 h-5 shrink-0" />
                        Bus
                    </a>

                    <!-- Fasilitas -->
                    <a href="{{ route('admin/fasilitas.index') }}" class="group relative flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin/fasilitas.*') ? 'bg-primary text-primary-foreground shadow-sm' : 'text-muted-foreground hover:bg-accent hover:text-accent-foreground' }} transition-colors">
                        @if(request()->routeIs('admin/fasilitas.*'))
                            <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-primary-foreground rounded-r-full"></span>
                        @endif
                        <x-lucide-sparkles class="w-5 h-5 shrink-0" />
                        Fasilitas
                    </a>

                    <!-- Sopir -->
                    <a href="{{ route('admin/sopir.index') }}" class="group relative flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin/sopir.*') ? 'bg-primary text-primary-foreground shadow-sm' : 'text-muted-foreground hover:bg-accent hover:text-accent-foreground' }} transition-colors">
                        @if(request()->routeIs('admin/sopir.*'))
                            <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-primary-foreground rounded-r-full"></span>
                        @endif
                        <x-lucide-user-round class="w-5 h-5 shrink-0" />
                        Sopir
                    </a>

                    <!-- Terminal -->
                    <a href="{{ route('admin/terminal.index') }}" class="group relative flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin/terminal.*') ? 'bg-primary text-primary-foreground shadow-sm' : 'text-muted-foreground hover:bg-accent hover:text-accent-foreground' }} transition-colors">
                        @if(request()->routeIs('admin/terminal.*'))
                            <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-primary-foreground rounded-r-full"></span>
                        @endif
                        <x-lucide-building-2 class="w-5 h-5 shrink-0" />
                        Terminal
                    </a>

                    <!-- Rute -->
                    <a href="{{ route('admin/rute.index') }}" class="group relative flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin/rute.*') ? 'bg-primary text-primary-foreground shadow-sm' : 'text-muted-foreground hover:bg-accent hover:text-accent-foreground' }} transition-colors">
                        @if(request()->routeIs('admin/rute.*'))
                            <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-primary-foreground rounded-r-full"></span>
                        @endif
                        <x-lucide-route class="w-5 h-5 shrink-0" />
                        Rute
                    </a>

                    <!-- Jadwal -->
                    <a href="{{ route('admin/jadwal.index') }}" class="group relative flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin/jadwal.*') ? 'bg-primary text-primary-foreground shadow-sm' : 'text-muted-foreground hover:bg-accent hover:text-accent-foreground' }} transition-colors">
                        @if(request()->routeIs('admin/jadwal.*'))
                            <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-primary-foreground rounded-r-full"></span>
                        @endif
                        <x-lucide-calendar class="w-5 h-5 shrink-0" />
                        Jadwal
                    </a>

                    <!-- Kelas Bus -->
                    <a href="{{ route('admin/kelas-bus.index') }}" class="group relative flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin/kelas-bus.*') ? 'bg-primary text-primary-foreground shadow-sm' : 'text-muted-foreground hover:bg-accent hover:text-accent-foreground' }} transition-colors">
                        @if(request()->routeIs('admin/kelas-bus.*'))
                            <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-primary-foreground rounded-r-full"></span>
                        @endif
                        <x-lucide-layers class="w-5 h-5 shrink-0" />
                        Kelas Bus
                    </a>

                    <!-- Jadwal Kelas Bus -->
                    <a href="{{ route('admin/jadwal-kelas-bus.index') }}" class="group relative flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin/jadwal-kelas-bus.*') ? 'bg-primary text-primary-foreground shadow-sm' : 'text-muted-foreground hover:bg-accent hover:text-accent-foreground' }} transition-colors">
                        @if(request()->routeIs('admin/jadwal-kelas-bus.*'))
                            <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-primary-foreground rounded-r-full"></span>
                        @endif
                        <x-lucide-calendar-check class="w-5 h-5 shrink-0" />
                        Jadwal Kelas Bus
                    </a>

                    <!-- Transactions Section -->
                    <div class="pt-4 pb-2 px-3">
                        <h3 class="text-xs font-semibold text-muted-foreground uppercase tracking-wider">Transaksi</h3>
                    </div>

                    <!-- History Pemesanan -->
                    <a href="{{ route('admin/history-pemesanan') }}" class="group relative flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin/history-pemesanan') ? 'bg-primary text-primary-foreground shadow-sm' : 'text-muted-foreground hover:bg-accent hover:text-accent-foreground' }} transition-colors">
                        @if(request()->routeIs('admin/history-pemesanan'))
                            <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-primary-foreground rounded-r-full"></span>
                        @endif
                        <x-lucide-clipboard-list class="w-5 h-5 shrink-0" />
                        History Pemesanan
                    </a>

                    <!-- Reports Section -->
                    <div class="pt-4 pb-2 px-3">
                        <h3 class="text-xs font-semibold text-muted-foreground uppercase tracking-wider">Laporan</h3>
                    </div>

                    <!-- Analytics Dashboard -->
                    <a href="{{ route('admin/laporan.index') }}" class="group relative flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin/laporan.index') ? 'bg-primary text-primary-foreground shadow-sm' : 'text-muted-foreground hover:bg-accent hover:text-accent-foreground' }} transition-colors">
                        @if(request()->routeIs('admin/laporan.index'))
                            <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-primary-foreground rounded-r-full"></span>
                        @endif
                        <x-lucide-bar-chart-3 class="w-5 h-5 shrink-0" />
                        Analytics
                    </a>

                    <!-- Laporan Tiket -->
                    <a href="{{ route('admin/laporan.tiket') }}" class="group relative flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin/laporan.tiket') ? 'bg-primary text-primary-foreground shadow-sm' : 'text-muted-foreground hover:bg-accent hover:text-accent-foreground' }} transition-colors">
                        @if(request()->routeIs('admin/laporan.tiket'))
                            <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-primary-foreground rounded-r-full"></span>
                        @endif
                        <x-lucide-ticket class="w-5 h-5 shrink-0" />
                        Laporan Tiket
                    </a>

                    <!-- Laporan Pendapatan -->
                    <a href="{{ route('admin/laporan.pendapatan') }}" class="group relative flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin/laporan.pendapatan') ? 'bg-primary text-primary-foreground shadow-sm' : 'text-muted-foreground hover:bg-accent hover:text-accent-foreground' }} transition-colors">
                        @if(request()->routeIs('admin/laporan.pendapatan'))
                            <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-primary-foreground rounded-r-full"></span>
                        @endif
                        <x-lucide-dollar-sign class="w-5 h-5 shrink-0" />
                        Laporan Pendapatan
                    </a>

                    <!-- Laporan Penumpang -->
                    <a href="{{ route('admin/laporan.penumpang') }}" class="group relative flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin/laporan.penumpang') ? 'bg-primary text-primary-foreground shadow-sm' : 'text-muted-foreground hover:bg-accent hover:text-accent-foreground' }} transition-colors">
                        @if(request()->routeIs('admin/laporan.penumpang'))
                            <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-primary-foreground rounded-r-full"></span>
                        @endif
                        <x-lucide-users class="w-5 h-5 shrink-0" />
                        Laporan Penumpang
                    </a>
                </nav>

                <!-- User Menu -->
                <div class="border-t border-border p-4 shrink-0">
                    <x-ui.dropdown>
                        <x-slot:trigger>
                            <button class="w-full flex items-center gap-3 p-2 rounded-lg hover:bg-accent transition-colors">
                                <x-ui.avatar>
                                    <x-ui.avatar.avatar-fallback class="bg-primary text-primary-foreground font-semibold">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                                    </x-ui.avatar.avatar-fallback>
                                </x-ui.avatar>
                                <div class="flex-1 min-w-0 text-left">
                                    <p class="text-sm font-medium truncate">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-muted-foreground truncate">{{ auth()->user()->email }}</p>
                                </div>
                                <x-lucide-chevron-right class="w-4 h-4 text-muted-foreground shrink-0" />
                            </button>
                        </x-slot:trigger>
                        <x-ui.dropdown.label>Akun Saya</x-ui.dropdown.label>
                        <x-ui.dropdown.separator />
                        <x-ui.dropdown.item>
                            <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 w-full">
                                <x-lucide-user class="w-4 h-4" />
                                Profile
                            </a>
                        </x-ui.dropdown.item>
                        <x-ui.dropdown.item>
                            <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 w-full">
                                <x-lucide-settings class="w-4 h-4" />
                                Pengaturan
                            </a>
                        </x-ui.dropdown.item>
                        <x-ui.dropdown.separator />
                        <x-ui.dropdown.item>
                            <form method="POST" action="{{ route('logout') }}" class="w-full">
                                @csrf
                                <button type="submit" class="flex items-center gap-2 w-full text-destructive">
                                    <x-lucide-log-out class="w-4 h-4" />
                                    Logout
                                </button>
                            </form>
                        </x-ui.dropdown.item>
                    </x-ui.dropdown>
                </div>
            </aside>

            <!-- Main Content -->
            <div class="flex-1 flex flex-col overflow-hidden w-full lg:w-auto">
                <!-- Top Bar -->
                <header class="h-16 bg-card border-b border-border flex items-center justify-between px-4 lg:px-6 shrink-0">
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
                        <!-- Theme Toggle -->
                        <x-theme-toggle />

                        <span class="text-sm text-muted-foreground hidden sm:inline">{{ now()->format('d M Y, H:i') }}</span>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1 overflow-y-auto bg-background">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </div>

    <!-- Tom Select JS -->
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>

    <script>
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
