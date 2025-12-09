@props(['class' => ''])

<div
    data-slot="accordion"
    {{ $attributes->merge(['class' => $class]) }}
>
    {{ $slot }}
</div>
