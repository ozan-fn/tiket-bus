@props([
    'model' => null,
    'name' => null,
    'value' => null,
])

<div
    x-data="{
        open: false,
        selectedValue: '{{ $value }}',
        selectedLabel: '',
        choose(v, label) {
            this.selectedValue = v;
            this.selectedLabel = label;
            this.open = false;
        },
        isSelected(v) {
            return this.selectedValue == v;
        }
    }"
    data-slot="select"
>

    @if($name)
        <input type="hidden" name="{{ $name }}" x-model="selectedValue">

    @endif

    {{ $slot }}
</div>
