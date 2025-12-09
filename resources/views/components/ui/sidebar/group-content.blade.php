@props(['class' => ''])

<div
    data-slot="sidebar-group-content"
    data-sidebar="group-content"
    class="w-full text-sm {{ $class }}"
>
    {{ $slot }}
</div>
