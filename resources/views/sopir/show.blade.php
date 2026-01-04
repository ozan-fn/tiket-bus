@extends('layouts.admin')
@section('content')
    @push('header')
        <x-ui.breadcrumb.breadcrumb>
            <x-ui.breadcrumb.list class="text-xs">
                <x-ui.breadcrumb.item>
                    <x-ui.breadcrumb.link href="{{ route('dashboard') }}">
                        Home
                    </x-ui.breadcrumb.link>
                </x-ui.breadcrumb.item>
                <x-ui.breadcrumb.separator>
                    <i data-lucide="chevron-right" class="w-3.5 h-3.5" ></i>
                </x-ui.breadcrumb.separator>
                <x-ui.breadcrumb.item>
                    <x-ui.breadcrumb.link href="{{ route('admin/sopir.index') }}">
                        Sopir
                    </x-ui.breadcrumb.link>
                </x-ui.breadcrumb.item>
                <x-ui.breadcrumb.separator>
                    <i data-lucide="chevron-right" class="w-3.5 h-3.5" ></i>
                </x-ui.breadcrumb.separator>
                <x-ui.breadcrumb.item>
                    <x-ui.breadcrumb.page>
                        Detail Sopir
                    </x-ui.breadcrumb.page>
                </x-ui.breadcrumb.item>
            </x-ui.breadcrumb.list>
        </x-ui.breadcrumb.breadcrumb>
@endpush

    <div class="p-4 sm:p-6">
        <div class="max-w-5xl mx-auto space-y-6">
            <!-- Main Info Card -->
            <x-ui.card>
                <x-ui.card.header>
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="h-16 w-16 rounded-lg bg-primary/10 flex items-center justify-center">
                                <i data-lucide="user-round" class="h-8 w-8 text-primary" ></i>
                            </div>
                            <div>
                                <x-ui.card.title class="text-2xl">{{ $sopir->user->name ?? 'N/A' }}</x-ui.card.title>
                                <x-ui.card.description>Detail informasi sopir</x-ui.card.description>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 w-full sm:w-auto">
                            <a href="{{ route('admin/sopir.edit', $sopir) }}" class="flex-1 sm:flex-initial">
                                <x-ui.button variant="outline" class="w-full">
                                    <i data-lucide="pencil" class="w-4 h-4 mr-2" ></i>
                                    Edit
                                </x-ui.button>
                            </a>

                            <!-- Delete Dialog -->
                            <div x-data="{ open: false }">
                                <x-ui.button @click="open = true" variant="outline" class="text-destructive hover:bg-destructive/10">
                                    <i data-lucide="trash-2" class="w-4 h-4 mr-2" ></i>
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
                                                        <i data-lucide="alert-triangle" class="h-6 w-6 text-destructive" ></i>
                                                    </div>
                                                    <div class="flex-1">
                                                        <h3 class="text-lg font-semibold mb-2">Hapus Sopir</h3>
                                                        <p class="text-sm text-muted-foreground mb-4">
                                                            Apakah Anda yakin ingin menghapus sopir <strong>{{ $sopir->user->name ?? 'N/A' }}</strong>?
                                                            Tindakan ini tidak dapat dibatalkan.
                                                        </p>

                                                        <div class="flex flex-col-reverse sm:flex-row gap-2 justify-end">
                                                            <x-ui.button type="button" variant="outline" @click="open = false">
                                                                Batal
                                                            </x-ui.button>
                                                            <form method="POST" action="{{ route('admin/sopir.destroy', $sopir) }}" class="inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <x-ui.button type="submit" class="w-full sm:w-auto bg-destructive text-destructive-foreground hover:bg-destructive/90">
                                                                    <i data-lucide="trash-2" class="w-4 h-4 mr-2" ></i>
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

                            <a href="{{ route('admin/sopir.index') }}">
                                <x-ui.button variant="outline">
                                    <i data-lucide="arrow-left" class="w-4 h-4 mr-2" ></i>
                                    Kembali
                                </x-ui.button>
                            </a>
                        </div>
                    </div>
                </x-ui.card.header>
                <x-ui.card.content>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- ID Sopir -->
                        <div class="flex items-start gap-3 p-4 rounded-lg bg-muted/50">
                            <div class="h-10 w-10 rounded-lg bg-blue-500/10 flex items-center justify-center shrink-0">
                                <i data-lucide="fingerprint" class="h-5 w-5 text-blue-600 dark:text-blue-400" ></i>
                            </div>
                            <div>
                                <p class="text-sm text-muted-foreground">ID Sopir</p>
                                <p class="text-xl font-bold">#{{ $sopir->id }}</p>
                            </div>
                        </div>

                        <!-- NIK -->
                        <div class="flex items-start gap-3 p-4 rounded-lg bg-muted/50">
                            <div class="h-10 w-10 rounded-lg bg-purple-500/10 flex items-center justify-center shrink-0">
                                <i data-lucide="credit-card" class="h-5 w-5 text-purple-600 dark:text-purple-400" ></i>
                            </div>
                            <div>
                                <p class="text-sm text-muted-foreground">NIK</p>
                                <p class="text-lg font-bold">{{ $sopir->nik }}</p>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="flex items-start gap-3 p-4 rounded-lg bg-muted/50">
                            <div class="h-10 w-10 rounded-lg bg-green-500/10 flex items-center justify-center shrink-0">
                                @if($sopir->status === 'aktif')
                                    <i data-lucide="circle-check" class="h-5 w-5 text-green-600 dark:text-green-400" ></i>
                                @else
                                    <i data-lucide="circle-x" class="h-5 w-5 text-gray-600 dark:text-gray-400" ></i>
                                @endif
                            </div>
                            <div>
                                <p class="text-sm text-muted-foreground">Status</p>
                                @if($sopir->status === 'aktif')
                                    <x-ui.badge class="bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                                        <i data-lucide="circle-check" class="h-3 w-3" ></i>
                                        Aktif
                                    </x-ui.badge>
                                @else
                                    <x-ui.badge variant="secondary">
                                        <i data-lucide="circle-x" class="h-3 w-3" ></i>
                                        Tidak Aktif
                                    </x-ui.badge>
                                @endif
                            </div>
                        </div>
                    </div>
                </x-ui.card.content>
            </x-ui.card>

            <!-- Detail Sopir Card -->
            <x-ui.card>
                <x-ui.card.header>
                    <div class="flex items-center gap-2">
                        <i data-lucide="user" class="w-5 h-5 text-primary" ></i>
                        <x-ui.card.title>Informasi Pribadi</x-ui.card.title>
                    </div>
                    <x-ui.card.description>Data pribadi sopir</x-ui.card.description>
                </x-ui.card.header>
                <x-ui.card.content>
                    <div class="space-y-6">
                        <!-- Nama -->
                        <div class="flex items-center gap-4 pb-4 border-b dark:border-gray-700">
                            <div class="flex-shrink-0">
                                <div class="h-12 w-12 rounded-lg bg-blue-100 dark:bg-blue-900/20 flex items-center justify-center">
                                    <i data-lucide="user" class="h-6 w-6 text-blue-600 dark:text-blue-400" ></i>
                                </div>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm text-muted-foreground">Nama Lengkap</p>
                                <p class="text-lg font-semibold">{{ $sopir->user->name ?? 'N/A' }}</p>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="flex items-center gap-4 pb-4 border-b dark:border-gray-700">
                            <div class="flex-shrink-0">
                                <div class="h-12 w-12 rounded-lg bg-purple-100 dark:bg-purple-900/20 flex items-center justify-center">
                                    <i data-lucide="mail" class="h-6 w-6 text-purple-600 dark:text-purple-400" ></i>
                                </div>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm text-muted-foreground">Email</p>
                                <p class="text-lg font-semibold">{{ $sopir->user->email ?? 'N/A' }}</p>
                            </div>
                        </div>

                        <!-- NIK -->
                        <div class="flex items-center gap-4 pb-4 border-b dark:border-gray-700">
                            <div class="flex-shrink-0">
                                <div class="h-12 w-12 rounded-lg bg-green-100 dark:bg-green-900/20 flex items-center justify-center">
                                    <i data-lucide="credit-card" class="h-6 w-6 text-green-600 dark:text-green-400" ></i>
                                </div>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm text-muted-foreground">NIK</p>
                                <p class="text-lg font-semibold">{{ $sopir->nik }}</p>
                            </div>
                        </div>

                        <!-- Nomor SIM -->
                        <div class="flex items-center gap-4 pb-4 border-b dark:border-gray-700">
                            <div class="flex-shrink-0">
                                <div class="h-12 w-12 rounded-lg bg-orange-100 dark:bg-orange-900/20 flex items-center justify-center">
                                    <i data-lucide="id-card" class="h-6 w-6 text-orange-600 dark:text-orange-400" ></i>
                                </div>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm text-muted-foreground">Nomor SIM</p>
                                <p class="text-lg font-semibold">{{ $sopir->nomor_sim }}</p>
                            </div>
                        </div>

                        <!-- Alamat -->
                        <div class="flex items-center gap-4 pb-4 border-b dark:border-gray-700">
                            <div class="flex-shrink-0">
                                <div class="h-12 w-12 rounded-lg bg-indigo-100 dark:bg-indigo-900/20 flex items-center justify-center">
                                    <i data-lucide="map-pin" class="h-6 w-6 text-indigo-600 dark:text-indigo-400" ></i>
                                </div>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm text-muted-foreground">Alamat</p>
                                <p class="text-lg font-semibold">{{ $sopir->alamat ?? '-' }}</p>
                            </div>
                        </div>

                        <!-- Telepon -->
                        <div class="flex items-center gap-4 pb-4 border-b dark:border-gray-700">
                            <div class="flex-shrink-0">
                                <div class="h-12 w-12 rounded-lg bg-pink-100 dark:bg-pink-900/20 flex items-center justify-center">
                                    <i data-lucide="phone" class="h-6 w-6 text-pink-600 dark:text-pink-400" ></i>
                                </div>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm text-muted-foreground">Telepon</p>
                                <p class="text-lg font-semibold">{{ $sopir->telepon ?? '-' }}</p>
                            </div>
                        </div>

                        <!-- Tanggal Lahir -->
                        <div class="flex items-center gap-4">
                            <div class="flex-shrink-0">
                                <div class="h-12 w-12 rounded-lg bg-yellow-100 dark:bg-yellow-900/20 flex items-center justify-center">
                                    <i data-lucide="cake" class="h-6 w-6 text-yellow-600 dark:text-yellow-400" ></i>
                                </div>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm text-muted-foreground">Tanggal Lahir</p>
                                <p class="text-lg font-semibold">{{ $sopir->tanggal_lahir ? $sopir->tanggal_lahir->format('d M Y') : '-' }}</p>
                                @if($sopir->tanggal_lahir)
                                    <p class="text-xs text-muted-foreground">{{ $sopir->tanggal_lahir->age }} tahun</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </x-ui.card.content>
            </x-ui.card>

            <!-- Informasi Tambahan -->
            <x-ui.card>
                <x-ui.card.header>
                    <div class="flex items-center gap-2">
                        <i data-lucide="info" class="w-5 h-5 text-primary" ></i>
                        <x-ui.card.title>Informasi Tambahan</x-ui.card.title>
                    </div>
                </x-ui.card.header>
                <x-ui.card.content>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="flex items-start gap-3">
                            <i data-lucide="calendar-plus" class="w-5 h-5 text-muted-foreground mt-0.5" ></i>
                            <div>
                                <p class="text-sm text-muted-foreground">Dibuat</p>
                                <p class="text-sm font-medium">{{ $sopir->created_at->format('d M Y, H:i') }}</p>
                                <p class="text-xs text-muted-foreground">{{ $sopir->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <i data-lucide="calendar-check" class="w-5 h-5 text-muted-foreground mt-0.5" ></i>
                            <div>
                                <p class="text-sm text-muted-foreground">Terakhir Diperbarui</p>
                                <p class="text-sm font-medium">{{ $sopir->updated_at->format('d M Y, H:i') }}</p>
                                <p class="text-xs text-muted-foreground">{{ $sopir->updated_at->diffForHumans() }}</p>
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

@endsection
