@props(['class' => ''])

<x-ui::pagination.link aria-label="Go to previous page" size="default" class="gap-1 px-2.5 sm:pl-2.5 {{ $class }}" {{ $attributes }}>
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">
        <polyline points="15 18 9 12 15 6"></polyline>
    </svg>
    <span class="hidden sm:block">Previous</span>
</x-ui::pagination.link>