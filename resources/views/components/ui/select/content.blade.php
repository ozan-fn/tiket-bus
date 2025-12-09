@props(['position' => 'popper', 'align' => 'center', 'class' => ''])

<div
    x-show="open"
    @click.away="open = false"
    x-transition
    data-slot="select-content"
    class="absolute z-50 mt-1 bg-popover text-popover-foreground 
           data-[state=open]:animate-in data-[state=closed]:animate-out 
           data-[state=closed]:fade-out-0 data-[state=open]:fade-in-0 
           data-[state=closed]:zoom-out-95 data-[state=open]:zoom-in-95 
           min-w-[8rem] rounded-md border shadow-md overflow-hidden 
           {{ $class }}"
>
    <div class="p-1 max-h-60 overflow-auto">
        {{ $slot }}
    </div>
</div>
