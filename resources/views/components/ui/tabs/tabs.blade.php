@props([
    'default' => null,
    'orientation' => 'horizontal',
    'class' => '',
])

<div
    x-data="{ tab: '{{ $default }}' }"
    data-orientation="{{ $orientation }}"
    data-slot="tabs"
    {{ $attributes->merge([
        'class' => 'flex flex-col gap-2 ' . $class
    ]) }}
>
    {{ $slot }}
</div>
