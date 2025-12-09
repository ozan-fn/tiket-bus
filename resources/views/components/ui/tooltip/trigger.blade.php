<div
    x-on:mouseenter="timer = setTimeout(() => open = true, {{ $delay }})"
    x-on:mouseleave="clearTimeout(timer); open = false"
    x-on:focus="timer = setTimeout(() => open = true, {{ $delay }})"
    x-on:blur="open = false"
    data-slot="tooltip-trigger"
>
    {{ $slot }}
</div>
