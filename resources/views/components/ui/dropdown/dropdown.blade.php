@props(['sideOffset' => 4])

<div x-data="{ open: false }" class="relative inline-block">
    <div x-on:click="open = !open" x-ref="trigger" data-slot="dropdown-menu-trigger">
        {{ $trigger }}
    </div>

    <div
        x-show="open"
        x-transition
        x-on:click.outside="open = false"
        x-ref="content"
        data-slot="dropdown-menu-content"
        class="absolute z-50 min-w-[8rem] rounded-md border bg-popover text-popover-foreground shadow-md p-1
               data-[side=right]:left-full data-[side=left]:right-full data-[side=bottom]:top-full data-[side=top]:bottom-full"
        style="margin-top: {{ $sideOffset }}px;"
    >
        {{ $slot }}
    </div>
</div>
