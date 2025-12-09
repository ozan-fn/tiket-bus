@props([
    'checked' => false,
    'disabled' => false,
    'name' => null,
    'value' => '1',
    'class' => '',
])

<div x-data="{ checked: {{ $checked ? 'true' : 'false' }} }" data-slot="checkbox-wrapper" class="inline-flex items-center">
    {{-- Hidden checkbox --}}
    <input type="checkbox" name="{{ $name }}" value="{{ $value }}" x-model="checked"
        @disabled($disabled) class="hidden" />

    {{-- Visible checkbox --}}
    <div @click="if (!{{ $disabled ? 'true' : 'false' }}) checked = !checked" role="checkbox" :aria-checked="checked"
        :data-state="checked ? 'checked' : 'unchecked'" data-slot="checkbox"
        class="peer border-input dark:bg-input/30
               data-[state=checked]:bg-primary
               data-[state=checked]:text-primary-foreground
               dark:data-[state=checked]:bg-primary
               data-[state=checked]:border-primary
               focus-visible:border-ring 
               focus-visible:ring-ring/50 
               aria-invalid:ring-destructive/20 
               dark:aria-invalid:ring-destructive/40 
               aria-invalid:border-destructive 
               size-4 shrink-0 rounded-[4px] border shadow-xs
               transition-shadow outline-none focus-visible:ring-[3px]
               disabled:cursor-not-allowed disabled:opacity-50 
               grid place-content-center cursor-pointer {{ $class }}">
        {{-- Indicator --}}
        <div data-slot="checkbox-indicator" x-show="checked" class="text-current transition-none">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-3.5">
                <path d="M20 6 9 17l-5-5" />
            </svg>
        </div>
    </div>
</div>
