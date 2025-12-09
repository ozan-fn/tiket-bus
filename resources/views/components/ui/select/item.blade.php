@props(['value', 'class' => ''])

@php
    $label = trim($slot);
@endphp

<div
    @click="choose('{{ $value }}', '{{ $label }}')"
    :data-state="isSelected('{{ $value }}') ? 'checked' : 'unchecked'"
    data-slot="select-item"
    class="focus:bg-accent focus:text-accent-foreground relative flex w-full cursor-default items-center gap-2 rounded-sm py-1.5 pr-8 pl-2 text-sm select-none {{ $class }}"
>
    <span class="absolute right-2 flex size-3.5 items-center justify-center">
        <template x-if="isSelected('{{ $value }}')">
            <svg class="size-4" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2">
                <path d="M20 6 9 17l-5-5" />
            </svg>
        </template>
    </span>

    {{ $slot }}
</div>
