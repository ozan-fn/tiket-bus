@props(['inset' => false])

<span
    data-slot="dropdown-menu-label"
    data-inset="{{ $inset ? 'true' : 'false' }}"
    class="block w-full px-2 py-1.5 text-sm font-medium text-muted-foreground data-[inset]:pl-8"
>
    {{ $slot }}
</span>
