<x-admin-layout>
    <x-slot name="header">
        <x-ui.breadcrumb.breadcrumb>
            <x-ui.breadcrumb.list class="text-xs">
                <x-ui.breadcrumb.item>
                    <x-ui.breadcrumb.link href="{{ route('dashboard') }}">
                        Home
                    </x-ui.breadcrumb.link>
                </x-ui.breadcrumb.item>
                <x-ui.breadcrumb.separator>
                    <x-lucide-chevron-right class="w-3.5 h-3.5" />
                </x-ui.breadcrumb.separator>
                <x-ui.breadcrumb.item>
                    <x-ui.breadcrumb.link href="{{ route('admin/bus.index') }}">
                        Bus
                    </x-ui.breadcrumb.link>
                </x-ui.breadcrumb.item>
                <x-ui.breadcrumb.separator>
                    <x-lucide-chevron-right class="w-3.5 h-3.5" />
                </x-ui.breadcrumb.separator>
                <x-ui.breadcrumb.item>
                    <x-ui.breadcrumb.page>
                        Detail Bus
                    </x-ui.breadcrumb.page>
                </x-ui.breadcrumb.item>
            </x-ui.breadcrumb.list>
        </x-ui.breadcrumb.breadcrumb>
    </x-slot>

    <div class="p-4 sm:p-6">
        <div class="max-w-5xl mx-auto space-y-6">
            <!-- Main Info Card -->
            <x-ui.card>
                <x-ui.card.header>
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="h-16 w-16 rounded-lg bg-primary/10 flex items-center justify-center">
                                <x-lucide-bus class="h-8 w-8 text-primary" />
                            </div>
                            <div>
                                <x-ui.card.title class="text-2xl">{{ $bus->nama }}</x-ui.card.title>
                                <x-ui.card.description>{{ $bus->plat_nomor }}</x-ui.card.description>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 w-full sm:w-auto">
                            <a href="{{ route('admin/bus.edit', $bus) }}" class="flex-1 sm:flex-initial">
                                <x-ui.button variant="outline" class="w-full">
                                    <x-lucide-pencil class="w-4 h-4 mr-2" />
                                    Edit
                                </x-ui.button>
                            </a>

                            <!-- Delete Dialog -->
                            <div x-data="{ open: false }">
                                <x-ui.button @click="open = true" variant="outline" class="text-destructive hover:bg-destructive/10">
                                    <x-lucide-trash-2 class="w-4 h-4 mr-2" />
                                    Hapus
                                </x-ui.button>

                                <!-- Dialog Overlay & Content -->
                                <template x-teleport="body">
                                    <div x-show="open"
                                         x-cloak
                                         class="fixed inset-0 z-50 overflow-y-auto"
                                         @keydown.escape.window="open = false">
                                        <!-- Overlay -->
                                        <div x-show="open"
                                             x-transition:enter="transition ease-out duration-200"
                                             x-transition:enter-start="opacity-0"
                                             x-transition:enter-end="opacity-100"
                                             x-transition:leave="transition ease-in duration-150"
                                             x-transition:leave-start="opacity-100"
                                             x-transition:leave-end="opacity-0"
                                             @click="open = false"
                                             class="fixed inset-0 bg-black/50 backdrop-blur-sm">
                                        </div>

                                        <!-- Dialog Content -->
                                        <div class="flex min-h-full items-center justify-center p-4">
                                            <div x-show="open"
                                                 x-transition:enter="transition ease-out duration-200"
                                                 x-transition:enter-start="opacity-0 scale-95"
                                                 x-transition:enter-end="opacity-100 scale-100"
                                                 x-transition:leave="transition ease-in duration-150"
                                                 x-transition:leave-start="opacity-100 scale-100"
                                                 x-transition:leave-end="opacity-0 scale-95"
                                                 @click.stop
                                                 class="relative w-full max-w-lg bg-card rounded-lg shadow-lg border border-border p-6">

                                                <div class="flex items-start gap-4">
                                                    <div class="h-12 w-12 rounded-full bg-destructive/10 flex items-center justify-center shrink-0">
                                                        <x-lucide-alert-triangle class="h-6 w-6 text-destructive" />
                                                    </div>
                                                    <div class="flex-1">
                                                        <h3 class="text-lg font-semibold mb-2">Hapus Bus</h3>
                                                        <p class="text-sm text-muted-foreground mb-4">
                                                            Apakah Anda yakin ingin menghapus bus <strong>{{ $bus->nama }}</strong>?
                                                            Tindakan ini tidak dapat dibatalkan.
                                                        </p>

                                                        <div class="flex flex-col-reverse sm:flex-row gap-2 justify-end">
                                                            <x-ui.button type="button" variant="outline" @click="open = false">
                                                                Batal
                                                            </x-ui.button>
                                                            <form method="POST" action="{{ route('admin/bus.destroy', $bus) }}" class="inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <x-ui.button type="submit" class="w-full sm:w-auto bg-destructive text-destructive-foreground hover:bg-destructive/90">
                                                                    <x-lucide-trash-2 class="w-4 h-4 mr-2" />
                                                                    Ya, Hapus
                                                                </x-ui.button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <a href="{{ route('admin/bus.index') }}">
                                <x-ui.button variant="outline">
                                    <x-lucide-arrow-left class="w-4 h-4 mr-2" />
                                    Kembali
                                </x-ui.button>
                            </a>
                        </div>
                    </div>
                </x-ui.card.header>
                <x-ui.card.content>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Kapasitas -->
                        <div class="flex items-start gap-3 p-4 rounded-lg bg-muted/50">
                            <div class="h-10 w-10 rounded-lg bg-blue-500/10 flex items-center justify-center shrink-0">
                                <x-lucide-users class="h-5 w-5 text-blue-600 dark:text-blue-400" />
                            </div>
                            <div>
                                <p class="text-sm text-muted-foreground">Kapasitas</p>
                                <p class="text-2xl font-bold">{{ $bus->kapasitas }}</p>
                                <p class="text-xs text-muted-foreground">Kursi</p>
                            </div>
                        </div>

                        <!-- Plat Nomor -->
                        <div class="flex items-start gap-3 p-4 rounded-lg bg-muted/50">
                            <div class="h-10 w-10 rounded-lg bg-purple-500/10 flex items-center justify-center shrink-0">
                                <x-lucide-hash class="h-5 w-5 text-purple-600 dark:text-purple-400" />
                            </div>
                            <div>
                                <p class="text-sm text-muted-foreground">Plat Nomor</p>
                                <p class="text-xl font-bold">{{ $bus->plat_nomor }}</p>
                            </div>
                        </div>

                        <!-- ID Bus -->
                        <div class="flex items-start gap-3 p-4 rounded-lg bg-muted/50">
                            <div class="h-10 w-10 rounded-lg bg-green-500/10 flex items-center justify-center shrink-0">
                                <x-lucide-fingerprint class="h-5 w-5 text-green-600 dark:text-green-400" />
                            </div>
                            <div>
                                <p class="text-sm text-muted-foreground">ID Bus</p>
                                <p class="text-xl font-bold">#{{ $bus->id }}</p>
                            </div>
                        </div>
                    </div>
                </x-ui.card.content>
            </x-ui.card>

            <!-- Fasilitas Card -->
            <x-ui.card>
                <x-ui.card.header>
                    <div class="flex items-center gap-2">
                        <x-lucide-sparkles class="w-5 h-5 text-primary" />
                        <x-ui.card.title>Fasilitas Bus</x-ui.card.title>
                    </div>
                    <x-ui.card.description>Fasilitas yang tersedia di bus ini</x-ui.card.description>
                </x-ui.card.header>
                <x-ui.card.content>
                    @if($bus->fasilitas && $bus->fasilitas->count() > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                            @foreach($bus->fasilitas as $fasilitas)
                                <div class="flex items-center gap-3 p-3 rounded-lg border border-border bg-muted/30">
                                    <div class="h-8 w-8 rounded-full bg-primary/10 flex items-center justify-center shrink-0">
                                        <x-lucide-check class="h-4 w-4 text-primary" />
                                    </div>
                                    <span class="text-sm font-medium">{{ $fasilitas->nama }}</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="h-12 w-12 rounded-full bg-muted flex items-center justify-center mx-auto mb-3">
                                <x-lucide-package-x class="w-6 h-6 text-muted-foreground" />
                            </div>
                            <p class="text-sm text-muted-foreground">Tidak ada fasilitas</p>
                        </div>
                    @endif
                </x-ui.card.content>
            </x-ui.card>

            <!-- Foto Card -->
            <x-ui.card>
                <x-ui.card.header>
                    <div class="flex items-center gap-2">
                        <x-lucide-images class="w-5 h-5 text-primary" />
                        <x-ui.card.title>Galeri Foto</x-ui.card.title>
                    </div>
                    <x-ui.card.description>Foto-foto bus</x-ui.card.description>
                </x-ui.card.header>
                <x-ui.card.content>
                    @if($bus->photos && $bus->photos->count() > 0)
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                            @foreach($bus->photos as $photo)
                                <div class="relative group aspect-video rounded-lg overflow-hidden border border-border">
                                    <img src="{{ asset('storage/' . $photo->path) }}"
                                         alt="Foto Bus {{ $bus->nama }}"
                                         class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                        <a href="{{ asset('storage/' . $photo->path) }}" target="_blank">
                                            <x-ui.button size="sm" variant="secondary">
                                                <x-lucide-maximize-2 class="w-4 h-4 mr-2" />
                                                Lihat
                                            </x-ui.button>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="h-16 w-16 rounded-full bg-muted flex items-center justify-center mx-auto mb-4">
                                <x-lucide-image-off class="w-8 h-8 text-muted-foreground" />
                            </div>
                            <p class="text-sm text-muted-foreground">Tidak ada foto</p>
                        </div>
                    @endif
                </x-ui.card.content>
            </x-ui.card>

            <!-- Informasi Tambahan -->
            <x-ui.card>
                <x-ui.card.header>
                    <div class="flex items-center gap-2">
                        <x-lucide-info class="w-5 h-5 text-primary" />
                        <x-ui.card.title>Informasi Tambahan</x-ui.card.title>
                    </div>
                </x-ui.card.header>
                <x-ui.card.content>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="flex items-start gap-3">
                            <x-lucide-calendar-plus class="w-5 h-5 text-muted-foreground mt-0.5" />
                            <div>
                                <p class="text-sm text-muted-foreground">Dibuat</p>
                                <p class="text-sm font-medium">{{ $bus->created_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <x-lucide-calendar-check class="w-5 h-5 text-muted-foreground mt-0.5" />
                            <div>
                                <p class="text-sm text-muted-foreground">Terakhir Diupdate</p>
                                <p class="text-sm font-medium">{{ $bus->updated_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </x-ui.card.content>
            </x-ui.card>
        </div>
    </div>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</x-admin-layout>
