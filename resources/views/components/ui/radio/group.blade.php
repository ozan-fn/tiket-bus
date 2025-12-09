@props([
    'class' => '',
    'model' => null,
    'name' => null,
    'orientation' => 'vertical',
])

@php
    $dir = $orientation === 'inline' ? 'flex flex-wrap gap-4' : 'grid gap-3';
    $field = $model ?? $name;
@endphp

<div
    x-data="{ value: @js(old($field)) }"
    data-slot="radio-group"
    class="{{ $dir }} {{ $class }}"
>
    {{ $slot }}
</div>
