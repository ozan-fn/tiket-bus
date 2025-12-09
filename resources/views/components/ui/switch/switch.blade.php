@props([
    'checked' => false,
    'disabled' => false,
    'name' => null,
    'value' => '1',
    'class' => '',
])

<div x-data="{ on: {{ $checked ? 'true' : 'false' }} }" data-slot="switch-wrapper" class="inline-flex items-center">

    @if ($name)
        <input type="hidden" name="{{ $name }}" x-model="on" :value="on ? '{{ $value }}' : ''">
    @endif

    {{-- Switch --}}
    <button type="button" @click="if (!{{ $disabled ? 'true' : 'false' }}) on = !on" role="switch" :aria-checked="on"
        :data-state="on ? 'checked' : 'unchecked'" data-slot="switch" {{ $disabled ? 'disabled' : '' }}
        class="peer data-[state=checked]:bg-primary 
               data-[state=unchecked]:bg-input 
               focus-visible:border-ring focus-visible:ring-ring/50 
               dark:data-[state=unchecked]:bg-input/80 
               inline-flex h-[1.15rem] w-8 shrink-0 items-center rounded-full 
               border border-transparent shadow-xs transition-all 
               outline-none focus-visible:ring-[3px] 
               disabled:cursor-not-allowed disabled:opacity-50 
               {{ $class }}">
        {{-- Thumb --}}
        <span data-slot="switch-thumb" :data-state="on ? 'checked' : 'unchecked'"
            class="bg-background 
                   dark:data-[state=unchecked]:bg-foreground 
                   dark:data-[state=checked]:bg-primary-foreground 
                   pointer-events-none block size-4 rounded-full ring-0 
                   transition-transform 
                   data-[state=checked]:translate-x-[calc(100%-2px)] 
                   data-[state=unchecked]:translate-x-0"></span>
    </button>
</div>
