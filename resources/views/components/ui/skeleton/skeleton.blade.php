@props(['class' => ''])

<div {{ $attributes->merge(['class' => 'bg-accent animate-pulse rounded-md ' . $class]) }}>
</div>
