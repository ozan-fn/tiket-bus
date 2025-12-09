@props(['class' => ''])

<div
    data-slot="card-title"
    class="leading-none font-semibold {{ $class }}"
>
    {{ $slot }}
</div>
