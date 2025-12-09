@props(['class' => ''])

<div
    data-slot="card-description"
    class="text-muted-foreground text-sm {{ $class }}"
>
    {{ $slot }}
</div>
