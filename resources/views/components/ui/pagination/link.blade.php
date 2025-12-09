@props([
    'isActive' => false,
    'size' => 'icon',
    'class' => '',
])

@php
    // map Tailwind class dari buttonVariants Shadcn
    $base = 'inline-flex items-center justify-center whitespace-nowrap rounded-md 
             text-sm font-medium ring-offset-background transition-colors 
             focus-visible:outline-none focus-visible:ring-2 
             focus-visible:ring-ring focus-visible:ring-offset-2 
             disabled:pointer-events-none disabled:opacity-50';

    $sizes = [
        'icon' => 'h-9 w-9',
        'default' => 'h-9 px-3',
        'sm' => 'h-8 px-2 rounded-md',
        'lg' => 'h-10 px-4 rounded-md',
    ];

    $variants = [
        'ghost' => 'hover:bg-accent hover:text-accent-foreground',
        'outline' => 'border border-input bg-background hover:bg-accent hover:text-accent-foreground',
    ];

    $variant = $isActive ? 'outline' : 'ghost';
@endphp

<a
    data-slot="pagination-link"
    aria-current="{{ $isActive ? 'page' : '' }}"
    data-active="{{ $isActive ? 'true' : 'false' }}"
    {{ $attributes->merge([
        'class' => $base . ' ' . ($variants[$variant] ?? '') . ' ' . ($sizes[$size] ?? '') . ' ' . $class
    ]) }}
>
    {{ $slot }}
</a>
