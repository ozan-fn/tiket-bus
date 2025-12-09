@props([
    'side' => 'top',
    'delay' => 0,
    'sideOffset' => 6,
])

<div 
    x-data="{
        open: false, 
        timer: null,
        show() {
            clearTimeout(this.timer)
            this.timer = setTimeout(() => this.open = true, {{ $delay }})
        },
        hide() {
            clearTimeout(this.timer)
            this.open = false
        }
    }"
    class="relative inline-block"
>

    {{-- TRIGGER --}}
    <div 
        x-on:mouseenter="show()"
        x-on:mouseleave="hide()"
        x-on:focus="show()"
        x-on:blur="hide()"
        data-slot="tooltip-trigger"
    >
        {{ $trigger }}
    </div>

    {{-- CONTENT --}}
    <div
        x-show="open"
        x-transition.opacity.scale.80
        x-cloak
        data-slot="tooltip-content"
        class="absolute z-50 w-fit bg-foreground text-background 
               text-xs px-3 py-1.5 rounded-md shadow 
               whitespace-nowrap"
        style="
            @switch($side)
                @case('top') bottom: calc(100% + {{ $sideOffset }}px); left: 50%; transform: translateX(-50%); @break
                @case('bottom') top: calc(100% + {{ $sideOffset }}px); left: 50%; transform: translateX(-50%); @break
                @case('left') right: calc(100% + {{ $sideOffset }}px); top: 50%; transform: translateY(-50%); @break
                @case('right') left: calc(100% + {{ $sideOffset }}px); top: 50%; transform: translateY(-50%); @break
            @endswitch
        "
    >
        {{ $slot }}

        {{-- ARROW --}}
        <div
            class="absolute size-2.5 bg-foreground rotate-45"
            style="
                @switch($side)
                    @case('top') top: 100%; left: 50%; transform: translateX(-50%); @break
                    @case('bottom') bottom: 100%; left: 50%; transform: translateX(-50%); @break
                    @case('left') left: 100%; top: 50%; transform: translateY(-50%); @break
                    @case('right') right: 100%; top: 50%; transform: translateY(-50%); @break
                @endswitch
            "
        ></div>
    </div>

</div>
