@props([
    'href' => '#',
    'active' => false,
    'class' => '',
])

<li
    data-slot="sidebar-menu-item"
    data-sidebar="menu-item"
    class="group/menu-item relative {{ $class }}"
>
        <a
            href="{{ $href }}"
            class="inline-flex items-center gap-2 w-full rounded-md px-2 py-1.5 text-sm font-medium transition-all disabled:pointer-events-none disabled:opacity-50 outline-none focus-visible:ring-2 focus-visible:ring-ring
            {{ $active 
                ? 'bg-sidebar-accent text-sidebar-foreground' 
                : 'text-sidebar-foreground/70 hover:bg-sidebar-accent hover:text-sidebar-foreground' }}"
        >
        {{ $slot }}
    </a>
</li>
