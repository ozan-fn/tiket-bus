@props(['class' => ''])

<div data-slot="alert-title" {{ $attributes->merge(['class' => 'font-medium tracking-tight ' . $class]) }}>
    {{ $slot }}
</div>