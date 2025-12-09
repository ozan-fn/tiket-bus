@props(['class' => ''])

<nav
    role="navigation"
    aria-label="pagination"
    data-slot="pagination"
    {{ $attributes->merge([
        'class' => 'mx-auto flex w-full justify-center ' . $class
    ]) }}
>
    {{ $slot }}
</nav>
