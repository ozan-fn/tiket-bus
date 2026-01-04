@php
    $userRole = auth()->user()?->roles->first()?->name ?? 'user';

    $menus = [
        ['label' => 'Dashboard', 'route' => 'dashboard', 'icon' => 'layout-dashboard', 'type' => 'menu', 'roles' => ['owner', 'agent', 'conductor', 'driver', 'user']],
        
        // User/Passenger Menu
        ['label' => 'Pemesanan', 'type' => 'section', 'roles' => ['user']],
        ['label' => 'Pesan Tiket', 'route' => 'pemesanan.index', 'icon' => 'ticket', 'type' => 'menu', 'roles' => ['user']],
        ['label' => 'Tiket Saya', 'route' => 'tiket.index', 'icon' => 'clipboard-list', 'type' => 'menu', 'roles' => ['user']],
        ['label' => 'Profil', 'type' => 'section', 'roles' => ['user']],
        ['label' => 'Data Profil', 'route' => 'profile.edit', 'icon' => 'user', 'type' => 'menu', 'roles' => ['user']],
        
        // Owner/Admin Menu
        ['label' => 'Data Setup', 'type' => 'section', 'roles' => ['owner']],
        ['label' => 'Kelas Bus', 'route' => 'admin/kelas-bus.index', 'icon' => 'layers', 'type' => 'menu', 'roles' => ['owner']],
        ['label' => 'Fasilitas', 'route' => 'admin/fasilitas.index', 'icon' => 'sparkles', 'type' => 'menu', 'roles' => ['owner']],
        ['label' => 'Bus', 'route' => 'admin/bus.index', 'icon' => 'bus', 'type' => 'menu', 'roles' => ['owner']],
        ['label' => 'User', 'route' => 'admin/user.index', 'icon' => 'users', 'type' => 'menu', 'roles' => ['owner']],
        ['label' => 'Banner', 'route' => 'admin/banner.index', 'icon' => 'image', 'type' => 'menu', 'roles' => ['owner']],
        
        // Rute & Jadwal
        ['label' => 'Rute & Jadwal', 'type' => 'section', 'roles' => ['owner', 'conductor']],
        ['label' => 'Terminal', 'route' => 'admin/terminal.index', 'icon' => 'building-2', 'type' => 'menu', 'roles' => ['owner']],
        ['label' => 'Rute', 'route' => 'admin/rute.index', 'icon' => 'route', 'type' => 'menu', 'roles' => ['owner']],
        ['label' => 'Jadwal', 'route' => 'admin/jadwal.index', 'icon' => 'calendar', 'type' => 'menu', 'roles' => ['owner', 'conductor']],
        
        // Conductor Menu
        ['label' => 'Penumpang', 'type' => 'section', 'roles' => ['conductor']],
        ['label' => 'Check Penumpang', 'route' => 'admin/history-pemesanan', 'icon' => 'users-check', 'type' => 'menu', 'roles' => ['conductor']],
        
        // Agent Menu
        ['label' => 'Beli Tiket', 'route' => 'admin/pemesanan.index', 'icon' => 'ticket', 'type' => 'menu', 'roles' => ['agent']],
        ['label' => 'History Pemesanan', 'route' => 'admin/history-pemesanan', 'icon' => 'clipboard-list', 'type' => 'menu', 'roles' => ['owner', 'agent']],
        ['label' => 'Pembayaran Manual', 'route' => 'admin/pembayaran-manual.index', 'icon' => 'wallet', 'type' => 'menu', 'roles' => ['owner', 'agent']],
        ['label' => 'Pricing', 'type' => 'section', 'roles' => ['owner', 'agent']],
        ['label' => 'Harga Tiket', 'route' => 'admin/jadwal-kelas-bus.index', 'icon' => 'tag', 'type' => 'menu', 'roles' => ['owner', 'agent']],
        ['label' => 'Scan', 'type' => 'section', 'roles' => ['agent']],
        ['label' => 'Scan Tiket', 'route' => 'admin/scan.index', 'icon' => 'qr-code', 'type' => 'menu', 'roles' => ['agent']],
        ['label' => 'Agent Users', 'route' => 'admin/user.index', 'icon' => 'users', 'type' => 'menu', 'roles' => ['agent']],
        ['label' => 'Pemeriksaan', 'type' => 'section', 'roles' => ['agent']],
        ['label' => 'Cek Kursi', 'route' => 'admin/cek-kursi.index', 'icon' => 'armchair', 'type' => 'menu', 'roles' => ['agent']],
        
        // Driver Menu
        ['label' => 'Operasional', 'type' => 'section', 'roles' => ['driver']],
        ['label' => 'Scan Tiket', 'route' => 'sopir.scan.index', 'icon' => 'qr-code', 'type' => 'menu', 'roles' => ['driver']],
        ['label' => 'Cek Kursi', 'route' => 'sopir.cek-kursi.index', 'icon' => 'armchair', 'type' => 'menu', 'roles' => ['driver']],
        
        // Laporan (Owner only)
        ['label' => 'Laporan', 'type' => 'section', 'roles' => ['owner']],
        ['label' => 'Analytics', 'route' => 'admin/laporan.index', 'icon' => 'bar-chart-3', 'type' => 'menu', 'roles' => ['owner']],
        ['label' => 'Laporan Tiket', 'route' => 'admin/laporan.tiket', 'icon' => 'ticket', 'type' => 'menu', 'roles' => ['owner']],
        ['label' => 'Laporan Pendapatan', 'route' => 'admin/laporan.pendapatan', 'icon' => 'dollar-sign', 'type' => 'menu', 'roles' => ['owner']],
        ['label' => 'Laporan Penumpang', 'route' => 'admin/laporan.penumpang', 'icon' => 'users', 'type' => 'menu', 'roles' => ['owner']],
    ];

    // Filter menu berdasarkan user role
    $filteredMenus = array_filter($menus, function ($menu) use ($userRole) {
        return in_array($userRole, $menu['roles'] ?? []);
    });
@endphp

@foreach($filteredMenus as $menu)
    @if($menu['type'] === 'section')
        <!-- {{ $menu['label'] }} Section -->
        <div class="pt-6 pb-2 px-2 first:pt-0">
            <h3 class="text-xs font-bold text-muted-foreground/70 dark:text-muted-foreground/60 uppercase tracking-widest">{{ $menu['label'] }}</h3>
        </div>
    @else
        <!-- {{ $menu['label'] }} Menu Item -->
        @php
            $isActive = request()->routeIs($menu['route']) || request()->routeIs($menu['route'] . '*');
        @endphp
        <a href="{{ route($menu['route']) }}"
            class="group relative flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ $isActive ? 'bg-primary text-primary-foreground shadow-md dark:shadow-primary/20' : 'text-muted-foreground hover:text-foreground hover:bg-accent/60 dark:hover:bg-accent/40' }}">
            @if($isActive)
                <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-7 bg-primary-foreground rounded-r-full"></span>
            @endif
            <i data-lucide="{{ $menu['icon'] }}" class="w-5 h-5 shrink-0 {{ $isActive ? '' : 'group-hover:scale-110' }} transition-transform"></i>
            <span class="truncate">{{ $menu['label'] }}</span>
        </a>
    @endif
@endforeach