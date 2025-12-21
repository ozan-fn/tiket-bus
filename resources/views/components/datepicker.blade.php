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
        <input type="hidden" name="{{ $name }}" x-model="hiddenValue" />

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

    <div x-show="isOpen"
         x-transition
         @click.away="closeCalendar"
         class="absolute mt-2 w-auto rounded-md border border-border bg-popover p-3 shadow-md"
         style="z-index: 50; min-width: 280px;"
         x-cloak>

        <div class="flex items-center justify-between mb-3 gap-2">
            <button type="button" @click="prevMonth" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 hover:bg-accent hover:text-accent-foreground h-8 w-8 shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m15 18-6-6 6-6"/>
                </svg>
            </button>

            <div class="flex items-center gap-1 w-full justify-center">
                <select
                    x-model="currentMonth"
                    @click.stop
                    class="h-8 w-[100px] rounded-md border border-input bg-background px-2 py-1 text-sm shadow-sm focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring cursor-pointer"
                >
                    <template x-for="(month, index) in monthNames" :key="index">
                        <option :value="index" x-text="month" :selected="currentMonth === index"></option>
                    </template>
                </select>

                <select
                    x-model="currentYear"
                    @click.stop
                    class="h-8 w-[80px] rounded-md border border-input bg-background px-2 py-1 text-sm shadow-sm focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring cursor-pointer"
                >
                    <template x-for="year in years" :key="year">
                        <option :value="year" x-text="year" :selected="currentYear === year"></option>
                    </template>
                </select>
            </div>

            <button type="button" @click="nextMonth" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 hover:bg-accent hover:text-accent-foreground h-8 w-8 shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m9 18 6-6-6-6"/>
                </svg>
            </button>
        </div>

        <div class="grid grid-cols-7 gap-1 mb-1">
            <template x-for="day in weekdays" :key="day">
                <div class="text-center text-xs font-medium text-muted-foreground h-8 flex items-center justify-center" x-text="day"></div>
            </template>
        </div>

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
                currentMonth: (() => {
                    if (value) {
                        const parts = value.split('-');
                        if (parts.length === 3) return Number(parts[1]) - 1;
                    }
                    return new Date().getMonth();
                })(),
                currentYear: (() => {
                    if (value) {
                        const parts = value.split('-');
                        if (parts.length === 3) return Number(parts[0]);
                    }
                    return new Date().getFullYear();
                })(),
                selectedDate: value ? (() => {
                    const parts = value.split('-');
                    if (parts.length === 3) {
                        return new Date(Number(parts[0]), Number(parts[1]) - 1, Number(parts[2]));
                    }
                    return null;
                })() : null,
                displayValue: '',
                hiddenValue: value || '',
                weekdays: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
                monthNames: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],

                init() {
                    if (this.selectedDate) {
                        this.displayValue = this.formatDate(this.selectedDate);
                        this.hiddenValue = value;
                    }
                },

                // Menghasilkan daftar tahun dinamis
                get years() {
                    let startYear = new Date().getFullYear() - 100;
                    let endYear = new Date().getFullYear() + 20;

                    if (min) {
                        startYear = new Date(min).getFullYear();
                    }
                    if (max) {
                        endYear = new Date(max).getFullYear();
                    }

                    let years = [];
                    for (let i = startYear; i <= endYear; i++) {
                        years.push(i);
                    }
                    // Balik urutan jika ingin tahun terbaru di atas, atau biarkan asc
                    return years.reverse();
                },

                formatLocalISO(date) {
                    const year = date.getFullYear();
                    const month = String(date.getMonth() + 1).padStart(2, '0');
                    const day = String(date.getDate()).padStart(2, '0');
                    return `${year}-${month}-${day}`;
                },

                get days() {
                    // Paksa konversi ke Number agar kalkulasi tanggal benar saat dropdown berubah
                    const year = Number(this.currentYear);
                    const month = Number(this.currentMonth);

                    const firstDay = new Date(year, month, 1);
                    const lastDay = new Date(year, month + 1, 0);
                    const prevLastDay = new Date(year, month, 0);
                    const days = [];

                    const firstDayOfWeek = firstDay.getDay();
                    for (let i = firstDayOfWeek - 1; i >= 0; i--) {
                        const day = prevLastDay.getDate() - i;
                        const date = new Date(year, month - 1, day);
                        days.push({
                            day,
                            date: this.formatLocalISO(date),
                            isOtherMonth: true,
                            isToday: this.isToday(date),
                            isSelected: this.isSelected(date),
                            disabled: this.isDisabled(date)
                        });
                    }

                    for (let day = 1; day <= lastDay.getDate(); day++) {
                        const date = new Date(year, month, day);
                        days.push({
                            day,
                            date: this.formatLocalISO(date),
                            isOtherMonth: false,
                            isToday: this.isToday(date),
                            isSelected: this.isSelected(date),
                            disabled: this.isDisabled(date)
                        });
                    }

                    const remainingDays = 42 - days.length;
                    for (let day = 1; day <= remainingDays; day++) {
                        const date = new Date(year, month + 1, day);
                        days.push({
                            day,
                            date: this.formatLocalISO(date),
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
                    this.selectedDate = new Date(Number(year), Number(month) - 1, Number(dayNum));
                    this.currentMonth = Number(month) - 1; // Sync dropdown
                    this.currentYear = Number(year);       // Sync dropdown
                    this.displayValue = this.formatDate(this.selectedDate);
                    this.hiddenValue = day.date;

                    this.$nextTick(() => {
                        const event = new Event('change', { bubbles: true });
                        this.$el.querySelector('input[type="hidden"]').dispatchEvent(event);
                    });

                    setTimeout(() => {
                        this.closeCalendar();
                    }, 100);
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
                },

                handleClickOutside(event) {
                    if (this.isOpen && !this.$el.contains(event.target)) {
                        this.closeCalendar();
                    }
                }
            }));
        });
    </script>
    @endpush
@endonce
