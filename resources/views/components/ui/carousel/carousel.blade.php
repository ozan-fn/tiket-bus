<div
    x-data="{
        currentSlide: 0,
        slides: @js($photos),
        totalSlides() { return this.slides.length },
        nextSlide() { this.currentSlide = (this.currentSlide + 1) % this.totalSlides() },
        prevSlide() { this.currentSlide = (this.currentSlide - 1 + this.totalSlides()) % this.totalSlides() },
        canPrev() { return this.totalSlides() > 1 },
        canNext() { return this.totalSlides() > 1 }
    }"
    class="relative w-full"
>
    <!-- Carousel Container -->
    <div class="relative overflow-hidden rounded-lg">
        <!-- Images -->
        <div class="relative h-80">
            @foreach($photos as $index => $photo)
                <img
                    src="{{ asset('storage/' . $photo->path) }}"
                    alt="{{ $title }}"
                    x-show="currentSlide === {{ $index }}"
                    x-transition
                    class="absolute inset-0 w-full h-full object-cover"
                />
            @endforeach
        </div>

        <!-- Previous Button -->
        <button
            @click="prevSlide()"
            :disabled="!canPrev()"
            class="absolute left-4 top-1/2 -translate-y-1/2 z-40 bg-black/50 hover:bg-black/70 text-white rounded-full p-2 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </button>

        <!-- Next Button -->
        <button
            @click="nextSlide()"
            :disabled="!canNext()"
            class="absolute right-4 top-1/2 -translate-y-1/2 z-40 bg-black/50 hover:bg-black/70 text-white rounded-full p-2 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </button>

        <!-- Slide Counter -->
        <div class="absolute bottom-4 right-4 z-40 bg-black/50 text-white px-3 py-1 rounded-full text-sm">
            <span x-text="currentSlide + 1"></span> / <span x-text="totalSlides()"></span>
        </div>
    </div>

    <!-- Thumbnails -->
    @if(count($photos) > 1)
        <div class="flex gap-2 mt-3 overflow-x-auto pb-2">
            @foreach($photos as $index => $photo)
                <button
                    @click="currentSlide = {{ $index }}"
                    :class="currentSlide === {{ $index }} ? 'ring-2 ring-primary' : 'ring-1 ring-border'"
                    class="shrink-0 w-16 h-16 rounded border overflow-hidden transition-all hover:opacity-80"
                >
                    <img
                        src="{{ asset('storage/' . $photo->path) }}"
                        alt="{{ $title }}"
                        class="w-full h-full object-cover"
                    />
                </button>
            @endforeach
        </div>
    @endif
</div>
