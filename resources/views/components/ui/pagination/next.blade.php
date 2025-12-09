@props(['class' => ''])

<x-ui::pagination.link
    aria-label="Go to next page"
    size="default"
    class="gap-1 px-2.5 sm:pr-2.5 {{ $class }}"
    {{ $attributes }}
>
    <span class="hidden sm:block">Next</span>
<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">
        <polyline points="9 18 15 12 9 6"></polyline>
    </svg>
</x-ui::pagination.link>
