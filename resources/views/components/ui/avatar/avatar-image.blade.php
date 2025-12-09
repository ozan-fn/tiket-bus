@props(['src' => '', 'alt' => '', 'class' => ''])

<img
    x-data="{ error: false }"
    x-on:error="error = true; $el.style.display = 'none'"
    data-slot="avatar-image"
    src="{{ $src }}"
    alt="{{ $alt }}"
    class="aspect-square size-full {{ $class }}"
/>

<div
    x-show="error"
    class="bg-muted flex size-full items-center justify-center rounded-full"
>
    {{ $slot }}
</div>
