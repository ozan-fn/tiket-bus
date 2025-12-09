@props(['class' => ''])

<div
    data-slot="sidebar-footer"
    data-sidebar="footer"
    class="flex flex-col gap-2 border-t p-2 {{ $class }}"
>
    {{ $slot }}
</div>
