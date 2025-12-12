@php
    use App\Data\SidebarMenuData;
    $userRole = auth()->user()?->roles->first()?->name ?? 'passenger';
    $filteredMenus = SidebarMenuData::getFilteredMenus($userRole);
@endphp

@foreach($filteredMenus as $key => $menu)
    @if(isset($menu['type']) && $menu['type'] === 'section')
        <!-- {{ $menu['label'] }} Section -->
        <div class="pt-4 pb-2 px-3">
            <h3 class="text-xs font-semibold text-muted-foreground uppercase tracking-wider">{{ $menu['label'] }}</h3>
        </div>
    @else
        <!-- {{ $menu['label'] }} Menu Item -->
        <a href="{{ route($menu['route']) }}" class="group relative flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs($menu['route'] . '*') || request()->routeIs($menu['route']) ? 'bg-primary text-primary-foreground shadow-sm' : 'text-muted-foreground hover:bg-accent hover:text-accent-foreground' }} transition-colors">
            @if(request()->routeIs($menu['route'] . '*') || request()->routeIs($menu['route']))
                <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-primary-foreground rounded-r-full"></span>
            @endif
            <x-dynamic-component :component="'lucide-' . $menu['icon']" class="w-5 h-5 shrink-0" />
            {{ $menu['label'] }}
        </a>
    @endif
@endforeach
