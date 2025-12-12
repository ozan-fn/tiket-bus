<x-admin-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white">Dashboard Kondektur</h2>
    </x-slot>

    <div class="p-6 bg-secondary/30">
        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
            <!-- Jadwal Hari Ini -->
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
                    <p class="text-sm text-muted-foreground">Jadwal Hari Ini</p>
                    <p class="text-3xl font-bold">{{ $jadwalHariIni ?? 0 }}</p>
                </x-ui.card.content>
            </x-ui.card>

            <!-- Total Penumpang -->
            <x-ui.card class="hover:shadow-lg transition-all duration-300 hover:border-primary/50">
                <x-ui.card.content class="px-4">
                    <div class="flex items-center justify-between mb-3">
                        <div class="h-12 w-12 rounded-lg bg-purple-500/10 flex items-center justify-center">
                            <x-lucide-users class="h-6 w-6 text-purple-600 dark:text-purple-400" />
                        </div>
                        <a href="{{ route('admin/history-pemesanan') }}" class="text-xs text-primary hover:underline flex items-center gap-1">
                            Lihat <x-lucide-arrow-right class="h-3 w-3" />
                        </a>
                    </div>
                    <p class="text-sm text-muted-foreground">Total Penumpang</p>
                    <p class="text-3xl font-bold">{{ $totalPenumpang ?? 0 }}</p>
                </x-ui.card.content>
            </x-ui.card>

            <!-- Tiket Terjual -->
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
        </div>

        <!-- Jadwal Hari Ini & Penumpang -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Jadwal Hari Ini -->
            <x-ui.card>
                <x-ui.card.header>
                    <x-ui.card.title>Jadwal Hari Ini</x-ui.card.title>
                    <x-ui.card.description>Daftar perjalanan pada hari ini</x-ui.card.description>
                </x-ui.card.header>
                <x-ui.card.content>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between p-3 rounded-lg border border-border bg-card hover:bg-accent/50 transition-colors">
                            <div>
                                <p class="text-sm font-semibold">Bus Mawar</p>
                                <p class="text-xs text-muted-foreground">Jakarta → Surabaya</p>
                                <p class="text-xs text-muted-foreground mt-1">06:00 - 15:00</p>
                            </div>
                            <x-ui.badge variant="outline">40 Kursi</x-ui.badge>
                        </div>

                        <div class="flex items-center justify-between p-3 rounded-lg border border-border bg-card hover:bg-accent/50 transition-colors">
                            <div>
                                <p class="text-sm font-semibold">Bus Melati</p>
                                <p class="text-xs text-muted-foreground">Bandung → Yogyakarta</p>
                                <p class="text-xs text-muted-foreground mt-1">08:00 - 16:00</p>
                            </div>
                            <x-ui.badge variant="outline">32 Kursi</x-ui.badge>
                        </div>

                        <div class="flex items-center justify-between p-3 rounded-lg border border-border bg-card hover:bg-accent/50 transition-colors">
                            <div>
                                <p class="text-sm font-semibold">Bus Anggrek</p>
                                <p class="text-xs text-muted-foreground">Surabaya → Denpasar</p>
                                <p class="text-xs text-muted-foreground mt-1">10:00 - 18:00</p>
                            </div>
                            <x-ui.badge variant="outline">35 Kursi</x-ui.badge>
                        </div>
                    </div>
                </x-ui.card.content>
                <x-ui.card.footer class="border-t border-border">
                    <a href="{{ route('admin/jadwal.index') }}" class="text-sm text-primary hover:underline font-medium flex items-center gap-1">
                        Lihat Semua Jadwal
                        <x-lucide-arrow-right class="h-4 w-4" />
                    </a>
                </x-ui.card.footer>
            </x-ui.card>

            <!-- Quick Actions -->
            <x-ui.card>
                <x-ui.card.header>
                    <x-ui.card.title>Tugas Hari Ini</x-ui.card.title>
                    <x-ui.card.description>Aktivitas yang perlu diperhatikan</x-ui.card.description>
                </x-ui.card.header>
                <x-ui.card.content>
                    <div class="space-y-3">
                        <div class="flex items-start gap-3 p-3 rounded-lg bg-blue-500/10 border border-blue-200 dark:border-blue-800">
                            <x-lucide-check-circle class="h-5 w-5 text-blue-600 dark:text-blue-400 mt-0.5 flex-shrink-0" />
                            <div>
                                <p class="text-sm font-medium">Periksa Kehadiran Penumpang</p>
                                <p class="text-xs text-muted-foreground mt-1">Pastikan semua penumpang hadir sebelum keberangkatan</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 p-3 rounded-lg bg-green-500/10 border border-green-200 dark:border-green-800">
                            <x-lucide-check-circle class="h-5 w-5 text-green-600 dark:text-green-400 mt-0.5 flex-shrink-0" />
                            <div>
                                <p class="text-sm font-medium">Verifikasi Tiket</p>
                                <p class="text-xs text-muted-foreground mt-1">Verifikasi semua tiket penumpang sebelum perjalanan</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 p-3 rounded-lg bg-orange-500/10 border border-orange-200 dark:border-orange-800">
                            <x-lucide-check-circle class="h-5 w-5 text-orange-600 dark:text-orange-400 mt-0.5 flex-shrink-0" />
                            <div>
                                <p class="text-sm font-medium">Lapor Kondisi Bus</p>
                                <p class="text-xs text-muted-foreground mt-1">Catat kondisi bus dan fasilitas yang rusak</p>
                            </div>
                        </div>
                    </div>
                </x-ui.card.content>
            </x-ui.card>
        </div>

        <!-- User Info -->
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

        <!-- Info Box -->
        <x-ui.card class="mt-6">
            <x-ui.card.content class="px-4">
                <div class="flex items-start gap-3">
                    <x-lucide-info class="h-5 w-5 text-primary mt-1" />
                    <div>
                        <p class="text-sm font-medium">Informasi Kondektur</p>
                        <p class="text-xs text-muted-foreground mt-1">Sebagai Kondektur, Anda bertugas untuk mengecek kehadiran penumpang, memverifikasi tiket, dan melaporkan kondisi bus selama perjalanan. Pastikan semua penumpang memiliki tiket yang valid sebelum keberangkatan.</p>
                    </div>
                </div>
            </x-ui.card.content>
        </x-ui.card>
    </div>
</x-admin-layout>
