<x-admin-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white">Dashboard Agent</h2>
    </x-slot>

    <div class="p-6 bg-secondary/30">
        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
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

            <!-- Total Tiket Terjual -->
            <x-ui.card class="hover:shadow-lg transition-all duration-300 hover:border-primary/50">
                <x-ui.card.content class="px-4">
                    <div class="flex items-center justify-between mb-3">
                        <div class="h-12 w-12 rounded-lg bg-blue-500/10 flex items-center justify-center">
                            <x-lucide-ticket class="h-6 w-6 text-blue-600 dark:text-blue-400" />
                        </div>
                        <a href="{{ route('admin/history-pemesanan') }}" class="text-xs text-primary hover:underline flex items-center gap-1">
                            Lihat <x-lucide-arrow-right class="h-3 w-3" />
                        </a>
                    </div>
                    <p class="text-sm text-muted-foreground">Tiket Terjual</p>
                    <p class="text-3xl font-bold">{{ $totalTiket ?? 0 }}</p>
                </x-ui.card.content>
            </x-ui.card>

            <!-- Total Pendapatan -->
            <x-ui.card class="hover:shadow-lg transition-all duration-300 hover:border-primary/50">
                <x-ui.card.content class="px-4">
                    <div class="flex items-center justify-between mb-3">
                        <div class="h-12 w-12 rounded-lg bg-green-500/10 flex items-center justify-center">
                            <x-lucide-dollar-sign class="h-6 w-6 text-green-600 dark:text-green-400" />
                        </div>
                        <a href="{{ route('admin/history-pemesanan') }}" class="text-xs text-primary hover:underline flex items-center gap-1">
                            Lihat <x-lucide-arrow-right class="h-3 w-3" />
                        </a>
                    </div>
                    <p class="text-sm text-muted-foreground">Total Pendapatan</p>
                    <p class="text-3xl font-bold">Rp {{ number_format($totalPendapatan ?? 0, 0, ',', '.') }}</p>
                </x-ui.card.content>
            </x-ui.card>
        </div>

        <!-- Recent Bookings & Quick Links -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Quick Actions -->
            <x-ui.card>
                <x-ui.card.header>
                    <x-ui.card.title>Quick Actions</x-ui.card.title>
                </x-ui.card.header>
                <x-ui.card.content>
                    <div class="space-y-2">
                        <a href="{{ route('admin/jadwal.index') }}" class="flex items-center gap-3 p-3 rounded-lg hover:bg-accent transition-colors">
                            <x-lucide-calendar class="h-5 w-5 text-primary" />
                            <span class="text-sm">Lihat Jadwal</span>
                        </a>
                        <a href="{{ route('admin/jadwal-kelas-bus.index') }}" class="flex items-center gap-3 p-3 rounded-lg hover:bg-accent transition-colors">
                            <x-lucide-tag class="h-5 w-5 text-primary" />
                            <span class="text-sm">Kelola Harga Tiket</span>
                        </a>
                        <a href="{{ route('admin/history-pemesanan') }}" class="flex items-center gap-3 p-3 rounded-lg hover:bg-accent transition-colors">
                            <x-lucide-clipboard-list class="h-5 w-5 text-primary" />
                            <span class="text-sm">Lihat History Pemesanan</span>
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

        <!-- Info Box -->
        <x-ui.card class="mt-6">
            <x-ui.card.content class="px-4">
                <div class="flex items-start gap-3">
                    <x-lucide-info class="h-5 w-5 text-primary mt-1" />
                    <div>
                        <p class="text-sm font-medium">Akses Terbatas</p>
                        <p class="text-xs text-muted-foreground mt-1">Sebagai Agent, Anda hanya memiliki akses ke Jadwal, Harga Tiket, dan History Pemesanan. Untuk akses ke fitur lengkap, hubungi Administrator Owner.</p>
                    </div>
                </div>
            </x-ui.card.content>
        </x-ui.card>
    </div>
</x-admin-layout>
