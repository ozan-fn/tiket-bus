@props(['class' => ''])

<tr
    data-slot="table-row"
    {{ $attributes->merge([
        'class' =>
            'hover:bg-muted/50 data-[state=selected]:bg-muted border-b transition-colors ' . $class
    ]) }}
>
    {{ $slot }}
</tr>
