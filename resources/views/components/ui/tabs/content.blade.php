@props([
    'value',
    'class' => '',
])

<div
    x-show="tab === '{{ $value }}'"
    data-slot="tabs-content"
    {{ $attributes->merge([
        'class' => 'flex-1 outline-none ' . $class
    ]) }}
>
    {{ $slot }}
</div>
