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
        <div class="max-w-2xl mx-auto">
            <x-ui.card>
                <x-ui.card.header>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="h-12 w-12 rounded-lg bg-primary/10 flex items-center justify-center">
                                <x-lucide-armchair class="h-6 w-6 text-primary" />
                            </div>
                            <div>
                                <x-ui.card.title>{{ $kelasBus->nama_kelas }}</x-ui.card.title>
                                <x-ui.card.description>Detail informasi kelas bus</x-ui.card.description>
                            </div>
                        </div>
                    </div>
                </x-ui.card.header>
                <x-ui.card.content class="space-y-6">
                    <!-- Nama Kelas -->
                    <div class="border-b border-border pb-6">
                        <p class="text-sm font-medium text-muted-foreground mb-2">Nama Kelas</p>
                        <p class="text-base font-semibold">{{ $kelasBus->nama_kelas }}</p>
                    </div>



                    <!-- Deskripsi -->
                    @if($kelasBus->deskripsi)
                        <div class="border-b border-border pb-6">
                            <p class="text-sm font-medium text-muted-foreground mb-2">Deskripsi</p>
                            <p class="text-base text-foreground">{{ $kelasBus->deskripsi }}</p>
                        </div>
                    @endif

                    <!-- Tanggal Dibuat -->
                    <div>
                        <p class="text-sm font-medium text-muted-foreground mb-2">Tanggal Dibuat</p>
                        <p class="text-base font-semibold">{{ $kelasBus->created_at->format('d M Y H:i') }}</p>
                    </div>
                </x-ui.card.content>
                <x-ui.card.footer class="flex flex-col-reverse sm:flex-row gap-2 justify-between">
                    <a href="{{ route('admin/kelas-bus.index') }}">
                        <x-ui.button variant="outline">
                            <x-lucide-arrow-left class="w-4 h-4 mr-2" />
                            Kembali
                        </x-ui.button>
                    </a>
                    <div class="flex gap-2">
                        <a href="{{ route('admin/kelas-bus.edit', $kelasBus) }}">
                            <x-ui.button variant="outline">
                                <x-lucide-pencil class="w-4 h-4 mr-2" />
                                Edit
                            </x-ui.button>
                        </a>

                        <!-- Delete Dialog -->
                        <div x-data="{ open: false }">
                            <x-ui.button @click="open = true" class="text-destructive hover:bg-destructive/10" variant="outline">
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
                                                        <x-ui.button type="button" variant="outline" @click="open = false" size="sm">
                                                            Batal
                                                        </x-ui.button>
                                                        <form method="POST" action="{{ route('admin/kelas-bus.destroy', $kelasBus) }}" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <x-ui.button type="submit" size="sm" class="w-full sm:w-auto bg-destructive text-destructive-foreground hover:bg-destructive/90">
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
                    </div>
                </x-ui.card.footer>
            </x-ui.card>
        </div>
    </div>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</x-admin-layout>
