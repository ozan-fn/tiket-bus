@props([
    'orientation' => 'vertical',
    'class' => '',
])

<div data-slot="scroll-area-scrollbar"
     data-orientation="{{ $orientation }}"
     class="flex touch-none p-px transition-colors select-none
     {{ $orientation === 'vertical' ? 'h-full w-2.5 border-l border-l-transparent' : '' }}
     {{ $orientation === 'horizontal' ? 'h-2.5 flex-col border-t border-t-transparent' : '' }}
     {{ $class }}">
    <div data-slot="scroll-area-thumb"
         class="bg-border relative flex-1 rounded-full"></div>
</div>
