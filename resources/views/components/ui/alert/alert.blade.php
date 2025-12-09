@props([
    'variant' => 'default',
    'class' => '',
])

@php
    
    $variantClasses = [
        'default' => 'bg-card text-card-foreground',
        'destructive' => 'text-destructive bg-card [&>svg]:text-current *:data-[slot=alert-description]:text-destructive/90',
    ];


    $baseClasses = 'relative w-full rounded-lg border px-4 py-3 text-sm grid has-[>svg]:grid-cols-[calc(var(--spacing)*4)_1fr] grid-cols-[0_1fr] has-[>svg]:gap-x-3 gap-y-0.5 items-start [&>svg]:size-4 [&>svg]:translate-y-0.5 [&>svg]:text-current';
    $finalClasses = $baseClasses . ' ' . ($variantClasses[$variant] ?? $variantClasses['default']) . ' ' . $class;
@endphp

<div
    role="alert"
    data-slot="alert"
    {{ $attributes->merge(['class' => $finalClasses]) }}
>

    {{ $icon }}

    <div class="col-start-2 grid justify-items-start gap-1 text-sm">
        @if($title)
            <div data-slot="alert-title" class="font-medium tracking-tight">
                {{ $title }}
            </div>
        @endif

    
        @if($description)
            <div data-slot="alert-description" class="text-muted-foreground [&_p]:leading-relaxed">
                {!! $description !!}
            </div>
        @endif

      
        @if(!$title && !$description)
            <div data-slot="alert-description" class="text-muted-foreground [&_p]:leading-relaxed">
                {!! $slot !!}
            </div>
        @endif
    </div>
</div>