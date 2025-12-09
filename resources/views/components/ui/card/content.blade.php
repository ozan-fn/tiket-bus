@props(['class' => ''])

<div
    data-slot="card-content"
    class="px-6 {{ $class }}"
>
    {{ $slot }}
</div>
