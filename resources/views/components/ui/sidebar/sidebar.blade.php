<div 
    data-slot="sidebar"
    data-state="{{ $state ?? 'expanded' }}"
    data-collapsible="{{ $collapsible ?? '' }}"
    data-variant="{{ $variant ?? 'sidebar' }}"
    data-side="{{ $side ?? 'left' }}"
    class="group peer text-sidebar-foreground hidden md:block"
>
    {{-- GAP (untuk floating / inset) --}}
    <div 
        data-slot="sidebar-gap"
        class="relative w-(--sidebar-width) bg-transparent transition-[width] duration-200 ease-linear
        group-data-[collapsible=offcanvas]:w-0
        group-data-[side=right]:rotate-180
        @if(($variant ?? 'sidebar') === 'floating' || ($variant ?? 'sidebar') === 'inset')
            group-data-[collapsible=icon]:w-[calc(var(--sidebar-width-icon)+(--spacing(4)))]
        @else
            group-data-[collapsible=icon]:w-(--sidebar-width-icon)
        @endif"
    ></div>

    {{-- CONTAINER --}}
    <div 
        data-slot="sidebar-container"
        class="fixed inset-y-0 z-10 hidden h-svh w-(--sidebar-width) 
        transition-[left,right,width] duration-200 ease-linear md:flex
        {{ ($side ?? 'left') === 'left' 
            ? 'left-0 group-data-[collapsible=offcanvas]:left-[calc(var(--sidebar-width)*-1)]'
            : 'right-0 group-data-[collapsible=offcanvas]:right-[calc(var(--sidebar-width)*-1)]' }}
        @if(($variant ?? 'sidebar') === 'floating' || ($variant ?? 'sidebar') === 'inset')
            p-2 group-data-[collapsible=icon]:w-[calc(var(--sidebar-width-icon)+(--spacing(4))+2px)]
        @else
            group-data-[collapsible=icon]:w-(--sidebar-width-icon) 
            group-data-[side=left]:border-r group-data-[side=right]:border-l
        @endif"
    >
        <div 
            data-slot="sidebar-inner"
            data-sidebar="sidebar"
            class="
              bg-sidebar group-data-[variant=floating]:border-sidebar-border flex h-full w-full flex-col 
              group-data-[variant=floating]:rounded-lg group-data-[variant=floating]:border
              group-data-[variant=floating]:shadow-sm
            "
        >
            {{ $slot }}
        </div>
    </div>
</div>
