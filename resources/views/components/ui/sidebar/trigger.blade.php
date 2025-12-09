@props([
    'class' => '',
])

<button
    type="button"
    data-slot="sidebar-trigger"
    data-sidebar="trigger"
    class="inline-flex items-center justify-center rounded-md border border-transparent bg-transparent hover:bg-muted hover:text-muted-foreground size-7 text-sm shadow-xs transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 {{ $class }}"
    @click="toggleSidebar()"
>
    {{-- Icon panel kiri (simple) --}}
    <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <rect x="3" y="4" width="18" height="16" rx="2" ry="2"></rect>
        <line x1="9" y1="4" x2="9" y2="20"></line>
    </svg>
    <span class="sr-only">Toggle Sidebar</span>
</button>
