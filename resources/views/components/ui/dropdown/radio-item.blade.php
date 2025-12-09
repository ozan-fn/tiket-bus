@props(['value'])

<div
    x-on:click="$parent.value = '{{ $value }}'"
    data-slot="dropdown-menu-radio-item"
    class="relative flex cursor-pointer items-center gap-2 rounded-sm py-1.5 pr-2 pl-8 text-sm
           focus:bg-accent focus:text-accent-foreground outline-none select-none"
>
    <span class="absolute left-2 flex size-3.5 items-center justify-center">
        <template x-if="$parent.value === '{{ $value }}'">
            <svg class="size-2 fill-current" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="6"/>
            </svg>
        </template>
    </span>

    {{ $slot }}
</div>
