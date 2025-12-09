@props([
    'orientation' => 'vertical',
    'class' => '',
])<div data-slot="scroll-area"
  class="relative {{ $class }}"
     x-data>
    <div data-slot="scroll-area-viewport"
         class="focus-visible:ring-ring/50 size-full rounded-[inherit] transition-[color,box-shadow] outline-none focus-visible:ring-[3px] focus-visible:outline-1 overflow-auto">
        {{ $slot }}
    </div>

    <x-ui::scrollbar :orientation="$orientation" />

    <div data-slot="scroll-area-corner"></div>
</div>
