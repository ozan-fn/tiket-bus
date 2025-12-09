@props([
    'class' => '',
    'defaultOpen' => false,
])

<div
    x-data="{ open: {{ $defaultOpen ? 'true' : 'false' }} }"
    data-slot="accordion-item"
    class="border-b last:border-b-0 {{ $class }}"
>
    {{ $slot }}
</div>
