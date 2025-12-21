@props(['src' => '', 'alt' => '', 'class' => ''])

<img
    x-on:error="$parent.error = true; $el.style.display = 'none'"
    data-slot="avatar-image"
    src="{{ $src }}"
    alt="{{ $alt }}"
    class="aspect-square size-full absolute inset-0 {{ $class }}"
/>

<div
    x-show="$parent.error"
    class="bg-muted flex size-full items-center justify-center rounded-full relative"
>
    {{ $slot }}
</div>
