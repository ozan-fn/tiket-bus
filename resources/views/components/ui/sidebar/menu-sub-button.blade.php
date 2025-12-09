@props([
    'active' => false,
    'size' => 'md', // sm | md
    'class' => '',
])

@php
    $base = "text-sidebar-foreground ring-sidebar-ring hover:bg-sidebar-accent hover:text-sidebar-accent-foreground active:bg-sidebar-accent active:text-sidebar-accent-foreground flex h-7 min-w-0 -translate-x-px items-center gap-2 overflow-hidden rounded-md px-2 outline-hidden [&>span:last-child]:truncate [&>svg]:size-4 [&>svg]:shrink-0";
    $sizes = [
        'sm' => 'text-xs',
        'md' => 'text-sm',
    ];
    $classes = $base.' '.($sizes[$size] ?? '').' '.$class;
@endphp

<a
    data-slot="sidebar-menu-sub-button"
    data-sidebar="menu-sub-button"
    data-active="{{ $active ? 'true' : 'false' }}"
    class="{{ $classes }} {{ $active ? 'bg-sidebar-accent text-sidebar-accent-foreground' : '' }}"
>
    {{ $slot }}
</a>
