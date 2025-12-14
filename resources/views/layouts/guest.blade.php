<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

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
    <div class="min-h-screen flex flex-col items-center justify-center px-4 py-6 sm:py-12">
        <div class="w-full sm:max-w-md">
            <!-- Card Container with CSS Variables -->
            <div class="bg-card border border-border shadow-md rounded-lg overflow-hidden p-6">
                {{ $slot }}
            </div>
        </div>
    </div>

    @stack('scripts')
</body>

</html>
