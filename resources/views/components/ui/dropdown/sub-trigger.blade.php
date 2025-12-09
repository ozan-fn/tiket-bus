<div
    x-data="{ open: false }"
    class="relative"
>
    <div
        x-on:mouseenter="open = true"
        x-on:mouseleave="open = false"
        data-slot="dropdown-menu-sub-trigger"
        class="flex items-center gap-2 px-2 py-1.5 text-sm rounded-sm cursor-pointer
               focus:bg-accent focus:text-accent-foreground"
    >
        {{ $slot }}
         <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="ml-auto size-4">
            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
        </svg>
    </div>
