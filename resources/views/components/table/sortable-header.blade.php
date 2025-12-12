@props(['name', 'label' => null])

@php
    $label = $label ?? ucfirst(str_replace('_', ' ', $name));
    $currentSort = request('sort');
    $isSorted = $currentSort === $name || $currentSort === "-{$name}";
    $isAsc = $currentSort === $name;
    $nextSort = $isAsc ? "-{$name}" : $name;
@endphp

<x-ui.table.head {{ $attributes }} class="cursor-pointer hover:bg-muted/50 transition-colors">
    <a href="{{ request()->fullUrlWithQuery(['sort' => $nextSort]) }}" class="flex items-center gap-2 group">
        <span>{{ $label }}</span>
        @if($isSorted)
            @if($isAsc)
                <x-lucide-arrow-up class="w-4 h-4 text-primary" />
            @else
                <x-lucide-arrow-down class="w-4 h-4 text-primary" />
            @endif
        @else
            <x-lucide-arrow-up-down class="w-4 h-4 text-muted-foreground group-hover:text-foreground transition-colors" />
        @endif
    </a>
</x-ui.table.head>
