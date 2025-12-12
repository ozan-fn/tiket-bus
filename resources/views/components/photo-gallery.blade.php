<div x-data="{ photoOpen: false }">
    {{ $slot }}

    <template x-teleport="body">
        <div x-show="photoOpen" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center">
            <div @click="photoOpen = false" class="absolute inset-0 bg-black/50"></div>
            <div class="relative bg-background rounded-lg border shadow-lg p-6 max-w-md w-full mx-4 max-h-[80vh] overflow-y-auto">
                <button @click="photoOpen = false" class="absolute top-4 right-4 text-muted-foreground hover:text-foreground z-50">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
                <h2 class="text-lg font-semibold mb-4">{{ $title ?? 'Galeri Foto' }}</h2>
                <x-ui.carousel.carousel :photos="$photos" :title="$title ?? 'Foto'" />
            </div>
        </div>
    </template>
</div>
