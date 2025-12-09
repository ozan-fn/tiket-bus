@props(['checked' => false])

<div
    x-data="{ checked: @json($checked) }"
    x-on:click="checked = !checked"
    data-slot="dropdown-menu-checkbox-item"
    class="relative flex cursor-pointer items-center gap-2 rounded-sm py-1.5 pr-2 pl-8 text-sm
           focus:bg-accent focus:text-accent-foreground outline-none select-none"
>
    <span class="absolute left-2 flex size-3.5 items-center justify-center">
        <template x-if="checked">
            <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M20 6 9 17l-5-5"/>
            </svg>
        </template>
    </span>

    {{ $slot }}
</div>
