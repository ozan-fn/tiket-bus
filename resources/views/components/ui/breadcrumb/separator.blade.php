@props(['class' => ''])

<li
    data-slot="breadcrumb-separator"
    role="presentation"
    aria-hidden="true"
    class="[&>svg]:size-3.5 {{ $class }}"
    {{ $attributes }}
>
    {{ $slot ?? '<svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>' }}
</li>
