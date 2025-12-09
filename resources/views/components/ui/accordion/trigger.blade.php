@props(['class' => ''])

<button type="button" data-slot="accordion-trigger" @click="open = !open" x-bind:data-state="open ? 'open' : 'closed'"
    class="focus-visible:border-ring focus-visible:ring-ring/50 flex w-full items-start justify-between gap-4 rounded-md py-4 text-left text-sm font-medium transition-all outline-none hover:underline focus-visible:ring-[3px] disabled:pointer-events-none disabled:opacity-50 [&[data-state=open]>svg]:rotate-180 {{ $class }}">
    <span class="flex-1">
        {{ $slot }}
    </span>

    {{-- Icon arrow --}}
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
        class="text-muted-foreground pointer-events-none size-4 shrink-0 translate-y-0.5 transition-transform duration-200">
        <path d="m6 9 6 6 6-6" />
    </svg>
</button>
