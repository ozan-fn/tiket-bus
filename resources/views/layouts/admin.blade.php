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

    <!-- Tom Select JS -->
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>

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
                        <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        <span class="text-xl font-bold">Admin Panel</span>
                    </a>
                    <!-- Close button for mobile -->
                    <button onclick="toggleSidebar()" class="lg:hidden p-2 rounded-lg hover:bg-accent">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg
>
                    </button>
                </div>

                <!-- Navigation -->
                <x-ui::scroll-area class="flex-1">
                    <nav class="py-4 px-3 space-y-1">
                        <!-- Dashboard -->
                        <a href="{{ route('dashboard') }}" class="group relative flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('dashboard') ? 'bg-primary text-primary-foreground shadow-sm' : 'text-muted-foreground hover:bg-accent hover:text-accent-foreground' }} transition-colors">
                            @if(request()->routeIs('dashboard'))
                                <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-primary rounded-r-full"></span>
                            @endif
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            Dashboard
                        </a>

                        <!-- Master Data Section -->
                        <div class="pt-4 pb-2 px-3">
                            <h3 class="text-xs font-semibold text-muted-foreground uppercase tracking-wider">Master Data</h3>
                        </div>

                        <!-- Bus -->
                        <a href="{{ route('admin/bus.index') }}" class="group relative flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin/bus.*') ? 'bg-primary text-primary-foreground shadow-sm' : 'text-muted-foreground hover:bg-accent hover:text-accent-foreground' }} transition-colors">
                            @if(request()->routeIs('admin/bus.*'))
                                <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-primary rounded-r-full"></span>
                            @endif
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                            </svg>
                            Bus
                        </a>

                        <!-- Fasilitas -->
                        <a href="{{ route('admin/fasilitas.index') }}" class="group relative flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin/fasilitas.*') ? 'bg-primary text-primary-foreground shadow-sm' : 'text-muted-foreground hover:bg-accent hover:text-accent-foreground' }} transition-colors">
                            @if(request()->routeIs('admin/fasilitas.*'))
                                <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-primary rounded-r-full"></span>
                            @endif
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                            </svg>
                            Fasilitas
                        </a>

                        <!-- Sopir -->
                        <a href="{{ route('admin/sopir.index') }}" class="group relative flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin/sopir.*') ? 'bg-primary text-primary-foreground shadow-sm' : 'text-muted-foreground hover:bg-accent hover:text-accent-foreground' }} transition-colors">
                            @if(request()->routeIs('admin/sopir.*'))
                                <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-primary rounded-r-full"></span>
                            @endif
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Sopir
                        </a>

                        <!-- Terminal -->
                        <a href="{{ route('admin/terminal.index') }}" class="group relative flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin/terminal.*') ? 'bg-primary text-primary-foreground shadow-sm' : 'text-muted-foreground hover:bg-accent hover:text-accent-foreground' }} transition-colors">
                            @if(request()->routeIs('admin/terminal.*'))
                                <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-primary rounded-r-full"></span>
                            @endif
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            Terminal
                        </a>

                        <!-- Rute -->
                        <a href="{{ route('admin/rute.index') }}" class="group relative flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin/rute.*') ? 'bg-primary text-primary-foreground shadow-sm' : 'text-muted-foreground hover:bg-accent hover:text-accent-foreground' }} transition-colors">
                            @if(request()->routeIs('admin/rute.*'))
                                <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-primary rounded-r-full"></span>
                            @endif
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                            </svg>
                            Rute
                        </a>

                        <!-- Jadwal -->
                        <a href="{{ route('admin/jadwal.index') }}" class="group relative flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin/jadwal.*') ? 'bg-primary text-primary-foreground shadow-sm' : 'text-muted-foreground hover:bg-accent hover:text-accent-foreground' }} transition-colors">
                            @if(request()->routeIs('admin/jadwal.*'))
                                <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-primary rounded-r-full"></span>
                            @endif
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Jadwal
                        </a>

                        <!-- Transactions Section -->
                        <div class="pt-4 pb-2 px-3">
                            <h3 class="text-xs font-semibold text-muted-foreground uppercase tracking-wider">Transaksi</h3>
                        </div>

                        <!-- History Pemesanan -->
                        <a href="{{ route('admin.history-pemesanan') }}" class="group relative flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.history-pemesanan') ? 'bg-primary text-primary-foreground shadow-sm' : 'text-muted-foreground hover:bg-accent hover:text-accent-foreground' }} transition-colors">
                            @if(request()->routeIs('admin.history-pemesanan'))
                                <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-primary rounded-r-full"></span>
                            @endif
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                            </svg>
                            History Pemesanan
                        </a>
                    </nav>
                </x-ui::scroll-area>

                <!-- User Menu -->
                <div class="border-t border-border p-4 shrink-0">
                    <x-ui::dropdown>
                        <x-slot:trigger>
                            <button class="w-full flex items-center gap-3 p-2 rounded-lg hover:bg-accent transition-colors">
                                <x-ui::avatar>
                                    <x-ui::avatar.fallback class="bg-primary text-primary-foreground font-semibold">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                                    </x-ui::avatar.fallback>
                                </x-ui::avatar>
                                <div class="flex-1 min-w-0 text-left">
                                    <p class="text-sm font-medium truncate">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-muted-foreground truncate">{{ auth()->user()->email }}</p>
                                </div>
                                <svg class="w-4 h-4 text-muted-foreground shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                            </button>
                        </form>
                    <>Akun Saya</x-ui::dropdown.label>
                        <x-ui::dropdown.separator />
                        <x-ui::dropdown.item>
                            <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 w-full">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Profile
                            </a>
                        </x-ui::dropdown.item>
                        <x-ui::dropdown.item>
                            <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 w-full">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Pengaturan
                            </a>
                        </x-ui::dropdown.item>
                        <x-ui::dropdown.separator />
                        <x-ui::dropdown.item>
                            <form method="POST" action="{{ route('logout') }}" class="w-full">
                                @csrf
                                <button type="submit" class="flex items-center gap-2 w-full text-destructive">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    Logout
                                </button>
                            </form>
                        </x-ui::dropdown.item>
                    </x-ui::dropdown>
                </div>
            </aside>

            <!-- Main Content -->
            <div class="flex-1 flex flex-col overflow-hidden w-full lg:w-auto">
                <!-- Top Bar -->
                <header class="h-16 bg-card border-b border-border flex items-center justify-between px-4 lg:px-6 shrink-0">
                    <div class="flex items-center gap-3">
                        <!-- Mobile menu button -->
                        <button onclick="toggleSidebar()" class="lg:hidden p-2 rounded-lg hover:bg-accent transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                        @isset($header)
                            <div class="flex-1">
                                {{ $header }}
                            </div>
                        @endisset
                    </div>
                    <div class="flex items-center gap-4">
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
    </script>

    @stack('scripts')
</body>

</html>
