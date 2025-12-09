@props(['class' => ''])

<div
    data-slot="tabs-list"
    {{ $attributes->merge([
        'class' =>
            'bg-muted text-muted-foreground inline-flex h-9 w-fit items-center justify-center rounded-lg p-[3px] ' .
            $class
    ]) }}
>
    {{ $slot }}
</div>
