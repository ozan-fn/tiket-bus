@props(['class' => ''])

<li
    data-slot="breadcrumb-item"
    class="inline-flex items-center gap-1.5 {{ $class }}"
    {{ $attributes }}
>
    {{ $slot }}
</li>
