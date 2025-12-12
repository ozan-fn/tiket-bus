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
                    <x-ui.breadcrumb.link href="{{ route('admin/fasilitas.index') }}">
                        Fasilitas
                    </x-ui.breadcrumb.link>
                </x-ui.breadcrumb.item>
                <x-ui.breadcrumb.separator>
                    <x-lucide-chevron-right class="w-3.5 h-3.5" />
                </x-ui.breadcrumb.separator>
                <x-ui.breadcrumb.item>
                    <x-ui.breadcrumb.page>
                        Detail
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
                                <x-lucide-sparkles class="h-8 w-8 text-primary" />
                            </div>
                            <div>
                                <x-ui.card.title class="text-2xl">{{ $fasilitas->nama }}</x-ui.card.title>
                                <x-ui.card.description>Detail informasi fasilitas bus</x-ui.card.description>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 w-full sm:w-auto">
                            <a href="{{ route('admin/fasilitas.edit', $fasilitas) }}" class="flex-1 sm:flex-initial">
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
                                                        <h3 class="text-lg font-semibold mb-2">Hapus Fasilitas</h3>
                                                        <p class="text-sm text-muted-foreground mb-4">
                                                            Apakah Anda yakin ingin menghapus fasilitas <strong>{{ $fasilitas->nama }}</strong>?
                                                            Tindakan ini tidak dapat dibatalkan.
                                                        </p>

                                                        <div class="flex flex-col-reverse sm:flex-row gap-2 justify-end">
                                                            <x-ui.button type="button" variant="outline" @click="open = false">
                                                                Batal
                                                            </x-ui.button>
                                                            <form method="POST" action="{{ route('admin/fasilitas.destroy', $fasilitas) }}" class="inline">
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

                            <a href="{{ route('admin/fasilitas.index') }}">
                                <x-ui.button variant="outline">
                                    <x-lucide-arrow-left class="w-4 h-4 mr-2" />
                                    Kembali
                                </x-ui.button>
                            </a>
                        </div>
                    </div>
                </x-ui.card.header>
                <x-ui.card.content>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <!-- ID Fasilitas -->
                        <div class="flex items-start gap-3 p-4 rounded-lg bg-muted/50">
                            <div class="h-10 w-10 rounded-lg bg-blue-500/10 flex items-center justify-center shrink-0">
                                <x-lucide-fingerprint class="h-5 w-5 text-blue-600 dark:text-blue-400" />
                            </div>
                            <div>
                                <p class="text-sm text-muted-foreground">ID Fasilitas</p>
                                <p class="text-xl font-bold">#{{ $fasilitas->id }}</p>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="flex items-start gap-3 p-4 rounded-lg bg-muted/50">
                            <div class="h-10 w-10 rounded-lg bg-green-500/10 flex items-center justify-center shrink-0">
                                <x-lucide-check-circle class="h-5 w-5 text-green-600 dark:text-green-400" />
                            </div>
                            <div>
                                <p class="text-sm text-muted-foreground">Status</p>
                                <x-ui.badge class="bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                                    <x-lucide-circle-check class="h-3 w-3" />
                                    Aktif
                                </x-ui.badge>
                            </div>
                        </div>

                        <!-- Digunakan -->
                        <div class="flex items-start gap-3 p-4 rounded-lg bg-muted/50">
                            <div class="h-10 w-10 rounded-lg bg-purple-500/10 flex items-center justify-center shrink-0">
                                <x-lucide-bus class="h-5 w-5 text-purple-600 dark:text-purple-400" />
                            </div>
                            <div>
                                <p class="text-sm text-muted-foreground">Digunakan</p>
                                <p class="text-2xl font-bold">{{ $fasilitas->bus()->count() }}</p>
                                <p class="text-xs text-muted-foreground">Bus</p>
                            </div>
                        </div>
                    </div>
                </x-ui.card.content>
            </x-ui.card>

            <!-- Detail Fasilitas Card -->
            <x-ui.card>
                <x-ui.card.header>
                    <div class="flex items-center gap-2">
                        <x-lucide-info class="w-5 h-5 text-primary" />
                        <x-ui.card.title>Detail Fasilitas</x-ui.card.title>
                    </div>
                    <x-ui.card.description>Informasi lengkap tentang fasilitas</x-ui.card.description>
                </x-ui.card.header>
                <x-ui.card.content>
                    <div class="space-y-6">
                    <!-- ID -->
                    <div class="flex items-center gap-4 pb-4 border-b dark:border-gray-700">
                        <div class="flex-shrink-0">
                            <div class="h-12 w-12 rounded-lg bg-blue-100 dark:bg-blue-900/20 flex items-center justify-center">
                                <x-lucide-hash class="h-6 w-6 text-blue-600 dark:text-blue-400" />
                            </div>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-muted-foreground">ID Fasilitas</p>
                            <p class="text-lg font-semibold">{{ $fasilitas->id }}</p>
                        </div>
                    </div>

                    <!-- Nama -->
                    <div class="flex items-center gap-4 pb-4 border-b dark:border-gray-700">
                        <div class="flex-shrink-0">
                            <div class="h-12 w-12 rounded-lg bg-purple-100 dark:bg-purple-900/20 flex items-center justify-center">
                                <x-lucide-sparkles class="h-6 w-6 text-purple-600 dark:text-purple-400" />
                            </div>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-muted-foreground">Nama Fasilitas</p>
                            <p class="text-lg font-semibold">{{ $fasilitas->nama }}</p>
                        </div>
                    </div>

                    <!-- Tanggal Dibuat -->
                    <div class="flex items-center gap-4 pb-4 border-b dark:border-gray-700">
                        <div class="flex-shrink-0">
                            <div class="h-12 w-12 rounded-lg bg-green-100 dark:bg-green-900/20 flex items-center justify-center">
                                <x-lucide-calendar-plus class="h-6 w-6 text-green-600 dark:text-green-400" />
                            </div>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-muted-foreground">Tanggal Dibuat</p>
                            <p class="text-lg font-semibold">{{ $fasilitas->created_at->format('d M Y, H:i') }}</p>
                            <p class="text-xs text-muted-foreground">{{ $fasilitas->created_at->diffForHumans() }}</p>
                        </div>
                    </div>

                        <!-- Tanggal Diperbarui -->
                        <div class="flex items-center gap-4">
                            <div class="flex-shrink-0">
                                <div class="h-12 w-12 rounded-lg bg-orange-100 dark:bg-orange-900/20 flex items-center justify-center">
                                    <x-lucide-calendar-clock class="h-6 w-6 text-orange-600 dark:text-orange-400" />
                                </div>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm text-muted-foreground">Terakhir Diperbarui</p>
                                <p class="text-lg font-semibold">{{ $fasilitas->updated_at->format('d M Y, H:i') }}</p>
                                <p class="text-xs text-muted-foreground">{{ $fasilitas->updated_at->diffForHumans() }}</p>
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
