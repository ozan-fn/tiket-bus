@props(['class' => ''])

<span
    data-slot="breadcrumb-page"
    role="link"
    aria-disabled="true"
    aria-current="page"
    class="text-foreground font-normal {{ $class }}"
    {{ $attributes }}
>
    {{ $slot }}
</span>
