@props(['class' => ''])

<caption
    data-slot="table-caption"
    {{ $attributes->merge([
        'class' => 'text-muted-foreground mt-4 text-sm ' . $class
    ]) }}
>
    {{ $slot }}
</caption>
