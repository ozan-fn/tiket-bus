@props(['variant' => 'default', 'inset' => false, 'as' => 'button', 'active' => false])

@php
    $active = filter_var($active, FILTER_VALIDATE_BOOLEAN);
@endphp

<{{ $as }}
    {{ $attributes }}
    data-slot="dropdown-menu-item"
    data-inset="{{ $inset ? 'true' : 'false' }}"
    data-variant="{{ $variant }}"
    class="flex w-full px-2 py-1.5 text-sm rounded-sm hover:bg-accent hover:text-accent-foreground focus:bg-accent focus:text-accent-foreground {{ $active ? 'bg-accent' : '' }} {{ $variant === 'destructive' ? 'text-destructive hover:bg-destructive/10 focus:bg-destructive/10' : '' }} {{ $inset ? 'pl-8' : '' }}"
>
    {{ $slot }}
</{{ $as }}>
