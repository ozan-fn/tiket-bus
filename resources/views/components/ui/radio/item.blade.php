@props([
    'value',
    'name' => null,
    'disabled' => false,
    'size' => 'md',
    'class' => '',
])

@php
    $sizes = [
        'sm' => 'h-3 w-3',
        'md' => 'h-4 w-4',
        'lg' => 'h-5 w-5',
    ];

    $dotSizes = [
        'sm' => 'h-1.5 w-1.5',
        'md' => 'h-2 w-2',
        'lg' => 'h-2.5 w-2.5',
    ];
@endphp

<label class="inline-flex items-center gap-2 select-none {{ $disabled ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer' }}">
    <div class="relative flex items-center justify-center">
        <input
            type="radio"
            x-model="value"
            name="{{ $name }}"
            value="{{ $value }}"
            @disabled($disabled)
            class="peer rounded-full border border-input bg-transparent shadow-xs
                   {{ $sizes[$size] }}
                   checked:border-primary
                   focus-visible:ring-ring/50 focus-visible:ring-[3px]"
        />

        <div class="pointer-events-none absolute inset-0 flex items-center justify-center">
            <div
                class="bg-primary rounded-full transition-transform duration-150 scale-0 peer-checked:scale-100 {{ $dotSizes[$size] }}"
            ></div>
        </div>
    </div>

    <span class="text-sm">{{ $slot }}</span>
</label>
