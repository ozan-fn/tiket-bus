@props(['class' => ''])

<tfoot
    data-slot="table-footer"
    {{ $attributes->merge([
        'class' =>
            'bg-muted/50 border-t font-medium [&>tr]:last:border-b-0 ' . $class
    ]) }}
>
    {{ $slot }}
</tfoot>
