@props([
    'asChild' => false,
    'class' => '',
])

@if($asChild)
    {{ $slot }}
@else
    <a
        data-slot="breadcrumb-link"
        class="hover:text-foreground transition-colors {{ $class }}"
        {{ $attributes }}
    >
        {{ $slot }}
    </a>
@endif
