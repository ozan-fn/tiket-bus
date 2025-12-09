@props(['variant' => 'default', 'inset' => false, 'as' => 'button'])

<{{ $as }}
    data-slot="dropdown-menu-item"
    {{ $attributes }}
    class="relative flex items-center gap-2 rounded-sm px-2 py-1.5 text-sm cursor-pointer
        outline-none select-none
        {{ $variant === 'destructive' ? 'text-destructive focus:bg-destructive/10 dark:focus:bg-destructive/20' : 'focus:bg-accent focus:text-accent-foreground' }}
        {{ $inset ? 'pl-8' : '' }}
        [&_svg:not([class*='size-'])]:size-4 [&_svg]:shrink-0 [&_svg]:pointer-events-none"
>
    {{ $slot }}
</{{ $as }}>
