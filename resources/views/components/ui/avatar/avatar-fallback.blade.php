@props(['class' => ''])

<div
    data-slot="avatar-fallback"
    class="bg-muted flex size-full items-center justify-center rounded-full {{ $class }}"
>
    {{ $slot }}
</div>
