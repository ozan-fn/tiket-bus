@props(['class' => ''])

<ul
    data-slot="pagination-content"
    {{ $attributes->merge([
        'class' => 'flex flex-row items-center gap-1 ' . $class
    ]) }}
>
    {{ $slot }}
</ul>
