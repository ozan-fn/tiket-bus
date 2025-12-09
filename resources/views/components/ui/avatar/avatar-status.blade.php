@props(['status' => 'online', 'size' => 'md'])

@php
$dotSize = [
    'sm' => 'size-1.5',
    'md' => 'size-2',
    'lg' => 'size-2.5',
    'xl' => 'size-3',
];

$colors = [
    'online' => 'bg-green-500',
    'offline' => 'bg-gray-400',
    'busy' => 'bg-red-500',
    'away' => 'bg-yellow-500',
];
@endphp

<div
    class="absolute bottom-0 right-0 rounded-full border-2 border-background {{ $dotSize[$size] }} {{ $colors[$status] }}"
></div>
