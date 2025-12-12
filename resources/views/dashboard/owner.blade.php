<x-admin-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white">Dashboard Owner</h2>
    </x-slot>

    <div class="p-6 bg-secondary/30">
        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <!-- Total Bus -->
            <x-ui.card class="hover:shadow-lg transition-all duration-300 hover:border-primary/50">
                <x-ui.card.content class="px-4">
                    <div class="flex items-center justify-between mb-3">
                        <div class="h-12 w-12 rounded-lg bg-blue-500/10 flex items-center justify-center">
                            <x-lucide-bus class="h-6 w-6 text-blue-600 dark:text-blue-400" />
                        </div>
                        <a href="{{ route('admin/bus.index') }}" class="text-xs text-primary hover:underline flex items-center gap-1">
                            Lihat <x-lucide-arrow-right class="h-3 w-3" />
                        </a>
                    </div>
                    <p class="text-sm text-muted-foreground">Total Bus</p>
                    <p class="text-3xl font-bold">{{ $totalBus ?? 0 }}</p>
                </x-ui.card.content>
            </x-ui.card>

            <!-- Total Terminal -->
            <x-ui.card class="hover:shadow-lg transition-all duration-300 hover:border-primary/50">
                <x-ui.card.content class="px-4">
                    <div class="flex items-center justify-between mb-3">
                        <div class="h-12 w-12 rounded-lg bg-green-500/10 flex items-center justify-center">
                            <x-lucide-building-2 class="h-6 w-6 text-green-600 dark:text-green-400" />
                        </div>
                        <a href="{{ route('admin/terminal.index') }}" class="text-xs text-primary hover:underline flex items-center gap-1">
                            Lihat <x-lucide-arrow-right class="h-3 w-3" />
                        </a>
                    </div>
                    <p class="text-sm text-muted-foreground">Total Terminal</p>
                    <p class="text-3xl font-bold">{{ $totalTerminal ?? 0 }}</p>
                </x-ui.card.content>
            </x-ui.card>

            <!-- Total Rute -->
            <x-ui.card class="hover:shadow-lg transition-all duration-300 hover:border-primary/50">
                <x-ui.card.content class="px-4">
                    <div class="flex items-center justify-between mb-3">
                        <div class="h-12 w-12 rounded-lg bg-purple-500/10 flex items-center justify-center">
                            <x-lucide-route class="h-6 w-6 text-purple-600 dark:text-purple-400" />
                        </div>
                        <a href="{{ route('admin/rute.index') }}" class="text-xs text-primary hover:underline flex items-center gap-1">
                            Lihat <x-lucide-arrow-right class="h-3 w-3" />
                        </a>
                    </div>
                    <p class="text-sm text-muted-foreground">Total Rute</p>
                    <p class="text-3xl font-bold">{{ $totalRute ?? 0 }}</p>
                </x-ui.card.content>
            </x-ui.card>

            <!-- Total Jadwal -->
            <x-ui.card class="hover:shadow-lg transition-all duration-300 hover:border-primary/50">
                <x-ui.card.content class="px-4">
                    <div class="flex items-center justify-between mb-3">
                        <div class="h-12 w-12 rounded-lg bg-orange-500/10 flex items-center justify-center">
                            <x-lucide-calendar class="h-6 w-6 text-orange-600 dark:text-orange-400" />
                        </div>
                        <a href="{{ route('admin/jadwal.index') }}" class="text-xs text-primary hover:underline flex items-center gap-1">
                            Lihat <x-lucide-arrow-right class="h-3 w-3" />
                        </a>
                    </div>
                    <p class="text-sm text-muted-foreground">Total Jadwal</p>
                    <p class="text-3xl font-bold">{{ $totalJadwal ?? 0 }}</p>
                </x-ui.card.content>
            </x-ui.card>
        </div>

        <!-- Revenue & Analytics Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <!-- Total Pendapatan -->
            <x-ui.card class="hover:shadow-lg transition-all duration-300 hover:border-primary/50">
                <x-ui.card.content class="px-4">
                    <div class="flex items-center justify-between mb-3">
                        <div class="h-12 w-12 rounded-lg bg-green-500/10 flex items-center justify-center">
                            <x-lucide-dollar-sign class="h-6 w-6 text-green-600 dark:text-green-400" />
                        </div>
                        <a href="{{ route('admin/laporan.pendapatan') }}" class="text-xs text-primary hover:underline flex items-center gap-1">
                            Lihat <x-lucide-arrow-right class="h-3 w-3" />
                        </a>
                    </div>
                    <p class="text-sm text-muted-foreground">Total Pendapatan</p>
                    <p class="text-3xl font-bold">Rp {{ number_format($totalPendapatan ?? 0, 0, ',', '.') }}</p>
                </x-ui.card.content>
            </x-ui.card>

            <!-- Total Tiket Terjual -->
            <x-ui.card class="hover:shadow-lg transition-all duration-300 hover:border-primary/50">
                <x-ui.card.content class="px-4">
                    <div class="flex items-center justify-between mb-3">
                        <div class="h-12 w-12 rounded-lg bg-blue-500/10 flex items-center justify-center">
                            <x-lucide-ticket class="h-6 w-6 text-blue-600 dark:text-blue-400" />
                        </div>
                        <a href="{{ route('admin/laporan.tiket') }}" class="text-xs text-primary hover:underline flex items-center gap-1">
                            Lihat <x-lucide-arrow-right class="h-3 w-3" />
                        </a>
                    </div>
                    <p class="text-sm text-muted-foreground">Tiket Terjual</p>
                    <p class="text-3xl font-bold">{{ $totalTiket ?? 0 }}</p>
                </x-ui.card.content>
            </x-ui.card>

            <!-- Total Penumpang -->
            <x-ui.card class="hover:shadow-lg transition-all duration-300 hover:border-primary/50">
                <x-ui.card.content class="px-4">
                    <div class="flex items-center justify-between mb-3">
                        <div class="h-12 w-12 rounded-lg bg-purple-500/10 flex items-center justify-center">
                            <x-lucide-users class="h-6 w-6 text-purple-600 dark:text-purple-400" />
                        </div>
                        <a href="{{ route('admin/laporan.penumpang') }}" class="text-xs text-primary hover:underline flex items-center gap-1">
                            Lihat <x-lucide-arrow-right class="h-3 w-3" />
                        </a>
                    </div>
                    <p class="text-sm text-muted-foreground">Total Penumpang</p>
                    <p class="text-3xl font-bold">{{ $totalPenumpang ?? 0 }}</p>
                </x-ui.card.content>
            </x-ui.card>

            <!-- Total Sopir -->
            <x-ui.card class="hover:shadow-lg transition-all duration-300 hover:border-primary/50">
                <x-ui.card.content class="px-4">
                    <div class="flex items-center justify-between mb-3">
                        <div class="h-12 w-12 rounded-lg bg-orange-500/10 flex items-center justify-center">
                            <x-lucide-user-round class="h-6 w-6 text-orange-600 dark:text-orange-400" />
                        </div>
                        <a href="{{ route('admin/sopir.index') }}" class="text-xs text-primary hover:underline flex items-center gap-1">
                            Lihat <x-lucide-arrow-right class="h-3 w-3" />
                        </a>
                    </div>
                    <p class="text-sm text-muted-foreground">Total Sopir</p>
                    <p class="text-3xl font-bold">{{ $totalSopir ?? 0 }}</p>
                </x-ui.card.content>
            </x-ui.card>
        </div>

        <!-- Recent Activities & Quick Links -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Quick Actions -->
            <x-ui.card>
                <x-ui.card.header>
                    <x-ui.card.title>Quick Actions</x-ui.card.title>
                </x-ui.card.header>
                <x-ui.card.content>
                    <div class="space-y-2">
                        <a href="{{ route('admin/bus.create') }}" class="flex items-center gap-3 p-3 rounded-lg hover:bg-accent transition-colors">
                            <x-lucide-plus class="h-5 w-5 text-primary" />
                            <span class="text-sm">Tambah Bus Baru</span>
                        </a>
                        <a href="{{ route('admin/jadwal.create') }}" class="flex items-center gap-3 p-3 rounded-lg hover:bg-accent transition-colors">
                            <x-lucide-plus class="h-5 w-5 text-primary" />
                            <span class="text-sm">Buat Jadwal Baru</span>
                        </a>
                        <a href="{{ route('admin/terminal.create') }}" class="flex items-center gap-3 p-3 rounded-lg hover:bg-accent transition-colors">
                            <x-lucide-plus class="h-5 w-5 text-primary" />
                            <span class="text-sm">Tambah Terminal</span>
                        </a>
                        <a href="{{ route('admin/rute.create') }}" class="flex items-center gap-3 p-3 rounded-lg hover:bg-accent transition-colors">
                            <x-lucide-plus class="h-5 w-5 text-primary" />
                            <span class="text-sm">Buat Rute Baru</span>
                        </a>
                    </div>
                </x-ui.card.content>
            </x-ui.card>

            <!-- System Info -->
            <x-ui.card>
                <x-ui.card.header>
                    <x-ui.card.title>Informasi Sistem</x-ui.card.title>
                </x-ui.card.header>
                <x-ui.card.content>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-sm text-muted-foreground">Role</span>
                            <span class="text-sm font-medium">{{ auth()->user()?->roles->first()?->name ?? 'Unknown' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-muted-foreground">Nama User</span>
                            <span class="text-sm font-medium">{{ auth()->user()?->name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-muted-foreground">Email</span>
                            <span class="text-sm font-medium">{{ auth()->user()?->email }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-muted-foreground">Login Sejak</span>
                            <span class="text-sm font-medium">{{ auth()->user()?->created_at->format('d M Y') }}</span>
                        </div>
                    </div>
                </x-ui.card.content>
            </x-ui.card>
        </div>
    </div>
</x-admin-layout>
