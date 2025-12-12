@props(['search' => null, 'sort' => '-created_at', 'sortField' => 'created_at', 'order' => 'desc', 'dateFrom' => null, 'dateTo' => null])

<div x-data="{ dateOpen: false }" class="flex gap-2 items-center flex-wrap">
    <!-- Date Filter Button -->
    <div class="relative">
        <button
            type="button"
            @click="dateOpen = !dateOpen"
            title="Filter Tanggal"
            class="inline-flex items-center justify-center w-9 h-9 border border-input rounded-lg hover:bg-muted transition-colors"
        >
            <x-lucide-calendar class="w-4 h-4" />
        </button>

        <!-- Dropdown Panel -->
        <div
            x-show="dateOpen"
            @click.outside="dateOpen = false"
            x-transition:enter="transition ease-out duration-100"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-75"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="absolute left-0 sm:left-auto sm:right-0 mt-2 w-screen sm:w-80 bg-popover border border-border rounded-lg shadow-xl p-4"
            style="z-index: 9999; display: none;"
            x-cloak
        >
            <form method="GET" action="{{ url()->current() }}" @submit="dateOpen = false">
                <!-- Preserve search parameter -->
                @if($search)
                    <input type="hidden" name="search" value="{{ $search }}" />
                @endif

                <!-- Preserve sort parameter -->
                @if($sort && $sort !== '-created_at')
                    <input type="hidden" name="sort" value="{{ $sort }}" />
                @endif

                <!-- Date Range Inputs -->
                <div class="space-y-4">
                    <!-- From Date -->
                    <div>
                        <label for="filter_date_from" class="block text-xs font-medium text-muted-foreground mb-1.5">
                            Dari Tanggal
                        </label>
                        <x-datepicker
                            name="date_from"
                            id="filter_date_from"
                            placeholder="Pilih tanggal mulai"
                            :value="$dateFrom"

                        />
                    </div>

                    <!-- To Date -->
                    <div>
                        <label for="filter_date_to" class="block text-xs font-medium text-muted-foreground mb-1.5">
                            Sampai Tanggal
                        </label>
                        <x-datepicker
                            name="date_to"
                            id="filter_date_to"
                            placeholder="Pilih tanggal akhir"
                            :value="$dateTo"

                        />
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-2 pt-4 mt-4 border-t border-border">
                    <!-- Apply Button -->
                    <button
                        type="submit"
                        class="flex-1 px-4 py-2 bg-primary text-primary-foreground text-sm font-medium rounded-md hover:bg-primary/90 transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2"
                    >
                        Terapkan
                    </button>

                    <!-- Reset Button -->
                    <a
                        href="{{ url()->current() }}{{ $search || ($sort && $sort !== '-created_at') ? '?' . http_build_query(array_filter(['search' => $search, 'sort' => $sort !== '-created_at' ? $sort : null])) : '' }}"
                        class="flex-1 px-4 py-2 border border-input text-center text-sm font-medium rounded-md hover:bg-muted transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 inline-flex items-center justify-center"
                    >
                        Reset
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
