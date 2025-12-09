@props(['open' => false])

<div x-data="{ open: @js($open) }" data-slot="dialog">
    {{ $slot }}
</div>
