@props([
    'name' => '',
    'id' => '',
    'value' => '',
    'placeholder' => 'Pilih tanggal',
    'min' => '',
    'max' => '',
    'required' => false,
    'disabled' => false,
    'class' => '',
])

@php
    $inputId = $id ?: $name ?: 'datepicker-' . uniqid();
@endphp

<div class="relative" x-data="datepicker({
    inputId: '{{ $inputId }}',
    value: '{{ $value }}',
    min: '{{ $min }}',
    max: '{{ $max }}'
})">
    <div class="relative">
        <!-- Hidden input for form submission in YYYY-MM-DD format -->
        <input type="hidden" name="{{ $name }}" x-model="hiddenValue" />

        <!-- Display input -->
        <input
            type="text"
            id="{{ $inputId }}"
            placeholder="{{ $placeholder }}"
            @if($required) required @endif
            @if($disabled) disabled @endif
            x-ref="input"
            x-model="displayValue"
            @click="toggleCalendar"
            @keydown.escape="closeCalendar"
            readonly
            {{ $attributes->merge([
                'class' => 'flex h-9 w-full rounded-md border border-input bg-background px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 cursor-pointer ' . $class
            ]) }}
        />
        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-muted-foreground">
                <rect width="18" height="18" x="3" y="4" rx="2" ry="2"/>
                <line x1="16" x2="16" y1="2" y2="6"/>
                <line x1="8" x2="8" y1="2" y2="6"/>
                <line x1="3" x2="21" y1="10" y2="10"/>
            </svg>
        </div>
    </div>

    <!-- Calendar Dropdown -->
    <div x-show="isOpen"
         x-transition
         @click.away="closeCalendar"
         class="absolute z-50 mt-2 w-full min-w-[280px] rounded-md border border-border bg-popover p-3 shadow-md"
         x-cloak>

        <!-- Header -->
        <div class="flex items-center justify-between mb-3">
            <button type="button" @click="prevMonth" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 hover:bg-accent hover:text-accent-foreground h-8 w-8">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m15 18-6-6 6-6"/>
                </svg>
            </button>
            <div class="text-sm font-medium" x-text="monthYear"></div>
            <button type="button" @click="nextMonth" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 hover:bg-accent hover:text-accent-foreground h-8 w-8">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m9 18 6-6-6-6"/>
                </svg>
            </button>
        </div>

        <!-- Weekdays -->
        <div class="grid grid-cols-7 gap-1 mb-2">
            <template x-for="day in weekdays" :key="day">
                <div class="text-center text-xs font-medium text-muted-foreground h-8 flex items-center justify-center" x-text="day"></div>
            </template>
        </div>

        <!-- Days -->
        <div class="grid grid-cols-7 gap-1">
            <template x-for="day in days" :key="day.date">
                <button
                    type="button"
                    @click="selectDate(day)"
                    :disabled="day.disabled"
                    :class="{
                        'text-muted-foreground': day.isOtherMonth,
                        'bg-primary text-primary-foreground hover:bg-primary hover:text-primary-foreground': day.isSelected,
                        'bg-accent text-accent-foreground': day.isToday && !day.isSelected,
                        'hover:bg-accent hover:text-accent-foreground': !day.isSelected && !day.disabled,
                        'opacity-50 cursor-not-allowed': day.disabled
                    }"
                    class="h-8 w-8 rounded-md text-sm font-normal transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2"
                    x-text="day.day"
                ></button>
            </template>
        </div>
    </div>
</div>

@once
    @push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('datepicker', ({ inputId, value, min, max }) => ({
                isOpen: false,
                currentMonth: new Date().getMonth(),
                currentYear: new Date().getFullYear(),
                selectedDate: value ? new Date(value) : null,
                displayValue: '',
                hiddenValue: value || '',
                weekdays: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],

                init() {
                    if (this.selectedDate) {
                        this.displayValue = this.formatDate(this.selectedDate);
                        this.hiddenValue = value;
                        this.currentMonth = this.selectedDate.getMonth();
                        this.currentYear = this.selectedDate.getFullYear();
                    }
                },

                get monthYear() {
                    const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                    return `${months[this.currentMonth]} ${this.currentYear}`;
                },

                get days() {
                    const firstDay = new Date(this.currentYear, this.currentMonth, 1);
                    const lastDay = new Date(this.currentYear, this.currentMonth + 1, 0);
                    const prevLastDay = new Date(this.currentYear, this.currentMonth, 0);
                    const days = [];

                    // Previous month days
                    const firstDayOfWeek = firstDay.getDay();
                    for (let i = firstDayOfWeek - 1; i >= 0; i--) {
                        const day = prevLastDay.getDate() - i;
                        const date = new Date(this.currentYear, this.currentMonth - 1, day);
                        days.push({
                            day,
                            date: date.toISOString().split('T')[0],
                            isOtherMonth: true,
                            isToday: this.isToday(date),
                            isSelected: this.isSelected(date),
                            disabled: this.isDisabled(date)
                        });
                    }

                    // Current month days
                    for (let day = 1; day <= lastDay.getDate(); day++) {
                        const date = new Date(this.currentYear, this.currentMonth, day);
                        days.push({
                            day,
                            date: date.toISOString().split('T')[0],
                            isOtherMonth: false,
                            isToday: this.isToday(date),
                            isSelected: this.isSelected(date),
                            disabled: this.isDisabled(date)
                        });
                    }

                    // Next month days
                    const remainingDays = 42 - days.length;
                    for (let day = 1; day <= remainingDays; day++) {
                        const date = new Date(this.currentYear, this.currentMonth + 1, day);
                        days.push({
                            day,
                            date: date.toISOString().split('T')[0],
                            isOtherMonth: true,
                            isToday: this.isToday(date),
                            isSelected: this.isSelected(date),
                            disabled: this.isDisabled(date)
                        });
                    }

                    return days;
                },

                isToday(date) {
                    const today = new Date();
                    return date.toDateString() === today.toDateString();
                },

                isSelected(date) {
                    if (!this.selectedDate) return false;
                    return date.toDateString() === this.selectedDate.toDateString();
                },

                isDisabled(date) {
                    if (min && date < new Date(min)) return true;
                    if (max && date > new Date(max)) return true;
                    return false;
                },

                formatDate(date) {
                    const day = String(date.getDate()).padStart(2, '0');
                    const month = String(date.getMonth() + 1).padStart(2, '0');
                    const year = date.getFullYear();
                    return `${day}/${month}/${year}`;
                },

                selectDate(day) {
                    if (day.disabled) return;

                    const [year, month, dayNum] = day.date.split('-');
                    this.selectedDate = new Date(year, month - 1, dayNum);
                    this.displayValue = this.formatDate(this.selectedDate);
                    this.hiddenValue = day.date; // YYYY-MM-DD format for backend

                    // Dispatch change event
                    this.$nextTick(() => {
                        const event = new Event('change', { bubbles: true });
                        this.$el.querySelector('input[type="hidden"]').dispatchEvent(event);
                    });

                    this.closeCalendar();
                },

                prevMonth() {
                    if (this.currentMonth === 0) {
                        this.currentMonth = 11;
                        this.currentYear--;
                    } else {
                        this.currentMonth--;
                    }
                },

                nextMonth() {
                    if (this.currentMonth === 11) {
                        this.currentMonth = 0;
                        this.currentYear++;
                    } else {
                        this.currentMonth++;
                    }
                },

                toggleCalendar() {
                    this.isOpen = !this.isOpen;
                },

                closeCalendar() {
                    this.isOpen = false;
                }
            }));
        });
    </script>
    @endpush
@endonce
