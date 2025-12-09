@props([
    'orientation' => 'vertical',
    'class' => '',
])

<div
    data-slot="button-group-separator"
    data-orientation="{{ $orientation }}"
    class="bg-input relative !m-0 self-stretch 
           {{ $orientation === 'vertical' ? 'h-auto' : '' }} 
           {{ $class }}"
></div>
