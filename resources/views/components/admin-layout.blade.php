@props(['header' => null])

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

<body class="font-sans antialiased bg-background text-foreground">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside class="hidden lg:flex lg:flex-col lg:w-64 lg:fixed lg:inset-y-0 bg-sidebar border-r border-sidebar-border">
            <div class="flex flex-col flex-1 min-h-0">
                <!-- Logo -->
                <div class="flex items-center h-16 flex-shrink-0 px-4 bg-sidebar border-b border-sidebar-border">
                    <a href="{{ route('dashboard') }}" class="flex items-center">
                        <x-application-logo class="block h-9 w-auto fill-current text-sidebar-foreground" />
                        <span class="ml-2 text-lg font-semibold text-sidebar-foreground">{{ config('app.name') }}</span>
                    </a>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 px-2 py-4 bg-sidebar space-y-1">
                    <x-sidebar-menu />
                </nav>
            </div>
        </aside>

        <!-- Main content -->
        <div class="lg:pl-64 flex flex-col flex-1">
            <!-- Top bar -->
            <header class="bg-background border-b border-border px-4 py-3 lg:px-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <!-- Mobile menu button -->
                        <button type="button" class="lg:hidden -ml-2 mr-2 h-10 w-10 rounded-md inline-flex items-center justify-center text-muted-foreground hover:text-foreground hover:bg-accent focus:outline-none focus:ring-2 focus:ring-inset focus:ring-primary">
                            <span class="sr-only">Open sidebar</span>
                            <i data-lucide="menu" class="h-6 w-6"></i>
                        </button>
                        <h1 class="text-2xl font-semibold text-foreground">{{ $header ?? 'Dashboard' }}</h1>
                    </div>

                    <div class="flex items-center space-x-4">
                        <!-- Theme toggle -->
                        <x-theme-toggle />

                        <!-- Profile dropdown -->
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2" id="user-menu-button" aria-expanded="false" data-dropdown-toggle="user-dropdown">
                                    <img class="h-8 w-8 rounded-full" src="{{ Auth::user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&color=7F9CF5&background=EBF4FF' }}" alt="{{ Auth::user()->name }}">
                                    <span class="ml-2 text-foreground hidden md:block">{{ Auth::user()->name }}</span>
                                    <i data-lucide="chevron-down" class="ml-1 h-4 w-4 text-muted-foreground"></i>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <x-dropdown-link href="{{ route('profile.edit') }}">
                                    {{ __('Profile') }}
                                </x-dropdown-link>
                                <x-dropdown-link href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                                    @csrf
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>
                </div>
            </header>

            <!-- Page content -->
            <main class="flex-1">
                {{ $slot }}
            </main>
        </div>
    </div>

    @stack('scripts')

    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <script>
        lucide.createIcons();
    </script>
</body>

</html>