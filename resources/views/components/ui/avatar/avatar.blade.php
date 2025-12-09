@props([
    'size' => 'md',
    'ring' => false,
    'status' => null,
    'class' => '',
])
@php
    $sizes = [
        'sm' => 'size-6',
        'md' => 'size-8',
        'lg' => 'size-10',
        'xl' => 'size-12',
    ];

    $ringClass = $ring ? 'ring-2 ring-ring ring-offset-2 ring-offset-background' : '';
@endphp

<div
    data-slot="avatar"
class="relative flex shrink-0 overflow-hidden rounded-full {{ $sizes[$size] }} {{ $ringClass }} {{ $class }}"
>
    {{ $slot }}

    @if ($status)
        <x-ui::avatar.avatar-status :size="$size" :status="$status" />
    @endif
</div>
