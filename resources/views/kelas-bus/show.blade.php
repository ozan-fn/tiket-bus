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
                    <x-ui.breadcrumb.link href="{{ route('admin/kelas-bus.index') }}">
                        Kelas Bus
                    </x-ui.breadcrumb.link>
                </x-ui.breadcrumb.item>
                <x-ui.breadcrumb.separator>
                    <x-lucide-chevron-right class="w-3.5 h-3.5" />
                </x-ui.breadcrumb.separator>
                <x-ui.breadcrumb.item>
                    <x-ui.breadcrumb.page>
                        Detail Kelas Bus
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
                                <x-lucide-armchair class="h-8 w-8 text-primary" />
                            </div>
                            <div>
                                <x-ui.card.title class="text-2xl">{{ $kelasBus->nama_kelas }}</x-ui.card.title>
                                <x-ui.card.description>Detail informasi kelas bus</x-ui.card.description>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 w-full sm:w-auto">
                            <a href="{{ route('admin/kelas-bus.edit', $kelasBus) }}" class="flex-1 sm:flex-initial">
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
                                                        <h3 class="text-lg font-semibold mb-2">Hapus Kelas Bus</h3>
                                                        <p class="text-sm text-muted-foreground mb-4">
                                                            Apakah Anda yakin ingin menghapus kelas bus <strong>{{ $kelasBus->nama_kelas }}</strong>?
                                                            Tindakan ini tidak dapat dibatalkan.
                                                        </p>

                                                        <div class="flex flex-col-reverse sm:flex-row gap-2 justify-end">
                                                            <x-ui.button type="button" variant="outline" @click="open = false">
                                                                Batal
                                                            </x-ui.button>
                                                            <form method="POST" action="{{ route('admin/kelas-bus.destroy', $kelasBus) }}" class="inline">
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

                            <a href="{{ route('admin/kelas-bus.index') }}">
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
                        <!-- ID Kelas Bus -->
                        <div class="flex items-start gap-3 p-4 rounded-lg bg-muted/50">
                            <div class="h-10 w-10 rounded-lg bg-blue-500/10 flex items-center justify-center shrink-0">
                                <x-lucide-fingerprint class="h-5 w-5 text-blue-600 dark:text-blue-400" />
                            </div>
                            <div>
                                <p class="text-sm text-muted-foreground">ID Kelas</p>
                                <p class="text-xl font-bold">#{{ $kelasBus->id }}</p>
                            </div>
                        </div>

                        <!-- Jumlah Kursi -->
                        <div class="flex items-start gap-3 p-4 rounded-lg bg-muted/50">
                            <div class="h-10 w-10 rounded-lg bg-purple-500/10 flex items-center justify-center shrink-0">
                                <x-lucide-armchair class="h-5 w-5 text-purple-600 dark:text-purple-400" />
                            </div>
                            <div>
                                <p class="text-sm text-muted-foreground">Jumlah Kursi</p>
                                <p class="text-2xl font-bold">{{ $kelasBus->jumlah_kursi }}</p>
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
                    </div>
                </x-ui.card.content>
            </x-ui.card>

            <!-- Bus Info Card -->
            <x-ui.card>
                <x-ui.card.header>
                    <div class="flex items-center gap-2">
                        <x-lucide-bus class="w-5 h-5 text-primary" />
                        <x-ui.card.title>Informasi Bus</x-ui.card.title>
                    </div>
                    <x-ui.card.description>Detail bus yang terkait dengan kelas ini</x-ui.card.description>
                </x-ui.card.header>
                <x-ui.card.content>
                    <div class="space-y-4">
                        <div class="flex items-center gap-4 p-4 rounded-lg border border-border bg-card">
                            <div class="h-16 w-16 rounded-lg bg-primary/10 flex items-center justify-center shrink-0">
                                <x-lucide-bus class="h-8 w-8 text-primary" />
                            </div>
                            <div class="flex-1">
                                <h4 class="text-lg font-semibold">{{ $kelasBus->bus->nama }}</h4>
                                <div class="flex items-center gap-4 mt-2 text-sm text-muted-foreground">
                                    <div class="flex items-center gap-1">
                                        <x-lucide-hash class="h-4 w-4" />
                                        <span>{{ $kelasBus->bus->plat_nomor }}</span>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <x-lucide-users class="h-4 w-4" />
                                        <span>Kapasitas: {{ $kelasBus->bus->kapasitas }} kursi</span>
                                    </div>
                                </div>
                            </div>
                            <a href="{{ route('admin/bus.show', $kelasBus->bus) }}">
                                <x-ui.button variant="outline" size="sm">
                                    <x-lucide-eye class="w-4 h-4 mr-2" />
                                    Lihat Bus
                                </x-ui.button>
                            </a>
                        </div>
                    </div>
                </x-ui.card.content>
            </x-ui.card>

            <!-- Detail Kelas Card -->
            <x-ui.card>
                <x-ui.card.header>
                    <div class="flex items-center gap-2">
                        <x-lucide-info class="w-5 h-5 text-primary" />
                        <x-ui.card.title>Detail Kelas</x-ui.card.title>
                    </div>
                    <x-ui.card.description>Informasi lengkap tentang kelas bus</x-ui.card.description>
                </x-ui.card.header>
                <x-ui.card.content>
                    <div class="space-y-6">
                        <!-- Nama Kelas -->
                        <div class="flex items-center gap-4 pb-4 border-b dark:border-gray-700">
                            <div class="flex-shrink-0">
                                <div class="h-12 w-12 rounded-lg bg-blue-100 dark:bg-blue-900/20 flex items-center justify-center">
                                    <x-lucide-tag class="h-6 w-6 text-blue-600 dark:text-blue-400" />
                                </div>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm text-muted-foreground">Nama Kelas</p>
                                <p class="text-lg font-semibold">{{ $kelasBus->nama_kelas }}</p>
                            </div>
                        </div>

                        <!-- Deskripsi -->
                        <div class="flex items-start gap-4 pb-4 border-b dark:border-gray-700">
                            <div class="flex-shrink-0">
                                <div class="h-12 w-12 rounded-lg bg-purple-100 dark:bg-purple-900/20 flex items-center justify-center">
                                    <x-lucide-file-text class="h-6 w-6 text-purple-600 dark:text-purple-400" />
                                </div>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm text-muted-foreground">Deskripsi</p>
                                <p class="text-base mt-1">{{ $kelasBus->deskripsi ?? '-' }}</p>
                            </div>
                        </div>

                        <!-- Jumlah Kursi -->
                        <div class="flex items-center gap-4 pb-4 border-b dark:border-gray-700">
                            <div class="flex-shrink-0">
                                <div class="h-12 w-12 rounded-lg bg-green-100 dark:bg-green-900/20 flex items-center justify-center">
                                    <x-lucide-armchair class="h-6 w-6 text-green-600 dark:text-green-400" />
                                </div>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm text-muted-foreground">Jumlah Kursi</p>
                                <p class="text-lg font-semibold">{{ $kelasBus->jumlah_kursi }} Kursi</p>
                            </div>
                        </div>

                        <!-- Tanggal Dibuat -->
                        <div class="flex items-center gap-4 pb-4 border-b dark:border-gray-700">
                            <div class="flex-shrink-0">
                                <div class="h-12 w-12 rounded-lg bg-cyan-100 dark:bg-cyan-900/20 flex items-center justify-center">
                                    <x-lucide-calendar-plus class="h-6 w-6 text-cyan-600 dark:text-cyan-400" />
                                </div>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm text-muted-foreground">Tanggal Dibuat</p>
                                <p class="text-lg font-semibold">{{ $kelasBus->created_at->format('d M Y, H:i') }}</p>
                                <p class="text-xs text-muted-foreground">{{ $kelasBus->created_at->diffForHumans() }}</p>
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
                                <p class="text-lg font-semibold">{{ $kelasBus->updated_at->format('d M Y, H:i') }}</p>
                                <p class="text-xs text-muted-foreground">{{ $kelasBus->updated_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>
                </x-ui.card.content>
            </x-ui.card>

            <!-- Daftar Kursi Card -->
            @if($kelasBus->kursi && $kelasBus->kursi->count() > 0)
            <x-ui.card>
                <x-ui.card.header>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <x-lucide-layout-grid class="w-5 h-5 text-primary" />
                            <x-ui.card.title>Daftar Kursi</x-ui.card.title>
                        </div>
                        <x-ui.badge variant="secondary">
                            {{ $kelasBus->kursi->count() }} Kursi
                        </x-ui.badge>
                    </div>
                    <x-ui.card.description>Layout kursi yang tersedia di kelas ini</x-ui.card.description>
                </x-ui.card.header>
                <x-ui.card.content>
                    <!-- Scrollable container with max height -->
                    <div class="max-h-[400px] overflow-y-auto pr-2 custom-scrollbar">
                        <div class="grid grid-cols-5 sm:grid-cols-8 md:grid-cols-10 lg:grid-cols-12 xl:grid-cols-15 gap-2">
                            @foreach($kelasBus->kursi as $kursi)
                                <div class="flex flex-col items-center justify-center p-2 rounded-lg border border-border bg-muted/30 hover:bg-muted/50 hover:border-primary/50 transition-all cursor-pointer group">
                                    <x-lucide-armchair class="h-5 w-5 text-muted-foreground group-hover:text-primary mb-1 transition-colors" />
                                    <span class="text-xs font-medium">{{ $kursi->nomor_kursi }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Info footer -->
                    <div class="mt-4 pt-4 border-t border-border">
                        <div class="flex items-center gap-2 text-xs text-muted-foreground">
                            <x-lucide-info class="w-4 h-4" />
                            <span>Total {{ $kelasBus->kursi->count() }} kursi tersedia</span>
                        </div>
                    </div>
                </x-ui.card.content>
            </x-ui.card>
            @endif
        </div>
    </div>

    <style>
        [x-cloak] { display: none !important; }

        /* Custom scrollbar styling */
        .custom-scrollbar::-webkit-scrollbar {
            width: 8px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: hsl(var(--muted));
            border-radius: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: hsl(var(--muted-foreground) / 0.3);
            border-radius: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: hsl(var(--muted-foreground) / 0.5);
        }

        /* Firefox scrollbar */
        .custom-scrollbar {
            scrollbar-width: thin;
            scrollbar-color: hsl(var(--muted-foreground) / 0.3) hsl(var(--muted));
        }

        /* Grid for extra large screens */
        @media (min-width: 1536px) {
            .xl\:grid-cols-15 {
                grid-template-columns: repeat(15, minmax(0, 1fr));
            }
        }
    </style>
</x-admin-layout>
