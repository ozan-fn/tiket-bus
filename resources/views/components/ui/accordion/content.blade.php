@props(['class' => ''])

<div
    data-slot="accordion-content"
    x-show="open"
    x-transition
    x-bind:data-state="open ? 'open' : 'closed'"
    class="overflow-hidden text-sm"
>
    <div class="pt-0 pb-4 {{ $class }}">
        {{ $slot }}
    </div>
</div>
