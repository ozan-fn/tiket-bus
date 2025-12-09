@props([
    'active' => false,
    'variant' => 'default', // default | outline
    'size' => 'default', // default | sm | lg
    'tooltip' => null,
    'class' => '',
    'icon' => null,
])

@php
    $base =
        'peer/menu-button flex w-full items-center gap-2 overflow-hidden rounded-md p-2 text-left text-sm outline-hidden ring-sidebar-ring transition hover:bg-sidebar-accent hover:text-sidebar-accent-foreground active:bg-sidebar-accent active:text-sidebar-accent-foreground disabled:pointer-events-none disabled:opacity-50 [&_svg]:size-4 [&_svg]:shrink-0';

    $variants = [
        'default' => '',
        'outline' => 'bg-background shadow-[0_0_0_1px_hsl(var(--sidebar-border))]',
    ];

    $sizes = [
        'default' => 'h-8 text-sm',
        'sm' => 'h-7 text-xs',
        'lg' => 'h-12 text-sm',
    ];

    $classes = $base.' '.($variants[$variant] ?? '').' '.($sizes[$size] ?? '').' '.$class;
@endphp

<button
    type="button"
    data-slot="sidebar-menu-button"
    data-sidebar="menu-button"
    data-active="{{ $active ? 'true' : 'false' }}"

    x-bind:class="{
        'justify-center p-2': $root.dataset.collapsible === 'icon' && $data.state === 'collapsed',
    }"

    class="{{ $classes }} {{ $active ? 'bg-sidebar-accent text-sidebar-accent-foreground font-medium' : '' }}"

    
    @if ($tooltip)
        x-bind:title="$data.state === 'collapsed' && window.innerWidth >= 768 ? '{{ e($tooltip) }}' : ''"
    @endif
>
    
    @if ($icon ?? false)
        <span class="shrink-0">{!! $icon !!}</span>
    @endif


    <span
        class="truncate"
        x-show="!($root.dataset.collapsible === 'icon' && $data.state === 'collapsed')"
        x-transition.opacity
    >
        {{ $slot }}
    </span>
</button>
