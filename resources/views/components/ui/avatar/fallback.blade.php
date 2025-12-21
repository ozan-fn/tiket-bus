@props(['class' => ''])

<div
    class="bg-muted text-white flex size-full items-center justify-center rounded-full {{ $class }}"
    data-slot="avatar-fallback"
>
    {{ $slot }}
</div>
