@props(['class' => ''])

<main data-slot="sidebar-inset"
    class="bg-background relative flex w-full flex-1 flex-col md:ml-0 md:transition-[margin] md:duration-200 md:ease-linear {{ $class }}"
    x-bind:class="{
        'md:ml-[var(--sidebar-width)]': $data.state === 'expanded' && $root.dataset.variant !== 'inset',
        'md:ml-2 md:mr-2 md:rounded-xl md:shadow-sm': $root.dataset.variant === 'inset',
    }">
    {{ $slot }}
</main>
