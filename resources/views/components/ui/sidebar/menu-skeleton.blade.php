@props([
    'showIcon' => false,
    'class' => '',
])

@php
    // Random width 50% - 90%
    $width = rand(50, 90);
@endphp

<div
    data-slot="sidebar-menu-skeleton"
    data-sidebar="menu-skeleton"
    class="flex h-8 items-center gap-2 rounded-md px-2 {{ $class }}"
>
    @if($showIcon)
        <div class="size-4 rounded-md bg-muted/70 animate-pulse"></div>
    @endif

    <div
        class="h-4 flex-1 rounded-md bg-muted/70 animate-pulse"
        style="max-width: {{ $width }}%;"
    ></div>
</div>
