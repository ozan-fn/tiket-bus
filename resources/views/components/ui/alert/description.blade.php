@props(['class' => ''])

<div data-slot="alert-description" {{ $attributes->merge(['class' => 'text-muted-foreground [&_p]:leading-relaxed ' . $class]) }}>
    {{ $slot }}
</div>