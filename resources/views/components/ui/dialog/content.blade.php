<div 
    x-show="$root.open"
    x-transition.opacity
    x-cloak
>
    <div 
        data-slot="dialog-overlay"
        class="data-[state=open]:animate-in data-[state=closed]:animate-out 
               data-[state=closed]:fade-out-0 data-[state=open]:fade-in-0
               fixed inset-0 z-50 bg-black/50"
        x-on:click="$root.open = false"
    ></div>

    <div 
        x-show="$root.open"
        x-transition.scale.origin.center
        x-cloak
        data-slot="dialog-content"
        class="bg-background data-[state=open]:animate-in data-[state=closed]:animate-out 
               data-[state=closed]:fade-out-0 data-[state=open]:fade-in-0 
               data-[state=closed]:zoom-out-95 data-[state=open]:zoom-in-95
               fixed top-[50%] left-[50%] z-50 grid w-full 
               max-w-[calc(100%-2rem)] translate-x-[-50%] translate-y-[-50%] 
               gap-4 rounded-lg border p-6 shadow-lg duration-200 sm:max-w-lg"
    >
        {{ $slot }}

        <button 
            x-on:click="$root.open = false"
            data-slot="dialog-close"
            class="ring-offset-background focus:ring-ring 
                   absolute top-4 right-4 rounded-xs opacity-70 
                   transition-opacity hover:opacity-100 
                   focus:ring-2 focus:ring-offset-2 focus:outline-hidden"
        >
            <svg class="size-4 shrink-0 pointer-events-none" 
                 xmlns="http://www.w3.org/2000/svg" 
                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M6 18L18 6M6 6l12 12" />
            </svg>
            <span class="sr-only">Close</span>
        </button>
    </div>
</div>
