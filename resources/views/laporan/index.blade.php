<x-admin-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
            <x-ui.breadcrumb.breadcrumb>
                <x-ui.breadcrumb.list class="text-xs">
                    <x-ui.breadcrumb.item>
                        <x-ui.breadcrumb.link href="{{ route('dashboard') }}">Beranda</x-ui.breadcrumb.link>
                    </x-ui.breadcrumb.item>
                    <x-ui.breadcrumb.separator>
                        <x-lucide-chevron-right class="w-3.5 h-3.5" />
                    </x-ui.breadcrumb.separator>
                    <x-ui.breadcrumb.item>
                        <x-ui.breadcrumb.page>Analitik</x-ui.breadcrumb.page>
                    </x-ui.breadcrumb.item>
                </x-ui.breadcrumb.list>
            </x-ui.breadcrumb.breadcrumb>

            <div class="flex items-center gap-2">
                <x-ui.button variant="outline" size="sm" class="h-8 text-xs bg-background hover:bg-accent hover:text-accent-foreground dark:bg-background dark:hover:bg-accent">
                    <x-lucide-calendar class="mr-2 h-3.5 w-3.5" />
                    {{ now()->translatedFormat('d M Y') }}
                </x-ui.button>
            </div>
        </div>
    </x-slot>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Deteksi Dark Mode untuk menyesuaikan warna grid/text chart jika diperlukan
            const isDark = document.documentElement.classList.contains('dark');
            const textColor = isDark ? '#94a3b8' : '#64748b'; // slate-400 vs slate-500
            const gridColor = isDark ? '#334155' : '#e2e8f0'; // slate-700 vs slate-200

            const chartOptions = {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        grid: { display: true, color: 'rgba(0,0,0,0.05)' },
                        border: { display: false },
                        ticks: { font: { size: 10 }, color: textColor }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 10 }, color: textColor }
                    }
                }
            };

            // 1. Line Chart Pendapatan
            new Chart(document.getElementById('pendapatanChart'), {
                type: 'line',
                data: {
                    labels: @json($pendapatanPerBulan->keys()),
                    datasets: [{
                        data: @json($pendapatanPerBulan->values()),
                        borderColor: '#3b82f6', // blue-500
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        fill: true,
                        tension: 0.4,
                        borderWidth: 2,
                        pointRadius: 3,
                        pointBackgroundColor: '#3b82f6'
                    }]
                },
                options: {
                    ...chartOptions,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: {
                            grid: { display: true, borderDash: [2, 2], color: isDark ? 'rgba(255,255,255,0.05)' : 'rgba(0,0,0,0.05)' },
                            ticks: {
                                color: textColor,
                                callback: (v) => 'Rp ' + v.toLocaleString('id-ID', { notation: "compact", compactDisplay: "short" })
                            }
                        },
                        x: {
                            ticks: { color: textColor }
                        }
                    }
                }
            });

            // 2. Pie Chart Status
            new Chart(document.getElementById('tiketStatusChart'), {
                type: 'doughnut',
                data: {
                    labels: @json($tiketPerStatus->keys()),
                    datasets: [{
                        data: @json($tiketPerStatus->values()),
                        backgroundColor: ['#fbbf24', '#10b981', '#3b82f6', '#ef4444'],
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    cutout: '75%',
                    plugins: { legend: { display: false } }
                }
            });
        });
    </script>

    <div class="p-6 space-y-8">

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <x-ui.card class="overflow-hidden bg-card text-card-foreground dark:border-border shadow-sm">
                <x-ui.card.content class="p-6">
                    <div class="flex items-center justify-between space-y-0 pb-2">
                        <p class="text-sm font-medium text-muted-foreground">Total Tiket</p>
                        <x-lucide-ticket class="h-4 w-4 text-muted-foreground" />
                    </div>
                    <div class="text-2xl font-bold text-foreground">{{ number_format($totalTiket) }}</div>
                    <p class="text-[10px] text-muted-foreground mt-1 uppercase tracking-wider">Volume Penjualan</p>
                </x-ui.card.content>
            </x-ui.card>

            <x-ui.card class="overflow-hidden bg-card text-card-foreground dark:border-border shadow-sm">
                <x-ui.card.content class="p-6">
                    <div class="flex items-center justify-between space-y-0 pb-2">
                        <p class="text-sm font-medium text-muted-foreground">Total Pendapatan</p>
                        <x-lucide-dollar-sign class="h-4 w-4 text-muted-foreground" />
                    </div>
                    <div class="text-2xl font-bold text-foreground">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
                    <p class="text-[10px] text-green-600 dark:text-green-400 mt-1 font-medium italic">Pendapatan Kotor</p>
                </x-ui.card.content>
            </x-ui.card>

            <x-ui.card class="overflow-hidden bg-card text-card-foreground dark:border-border shadow-sm">
                <x-ui.card.content class="p-6">
                    <div class="flex items-center justify-between space-y-0 pb-2">
                        <p class="text-sm font-medium text-muted-foreground">Total Penumpang</p>
                        <x-lucide-users class="h-4 w-4 text-muted-foreground" />
                    </div>
                    <div class="text-2xl font-bold text-foreground">{{ number_format($totalPenumpang) }}</div>
                    <p class="text-[10px] text-muted-foreground mt-1 uppercase tracking-wider">Pelanggan Unik</p>
                </x-ui.card.content>
            </x-ui.card>

            <x-ui.card class="overflow-hidden bg-card text-card-foreground dark:border-border shadow-sm">
                <x-ui.card.content class="p-6">
                    <div class="flex items-center justify-between space-y-0 pb-2">
                        <p class="text-sm font-medium text-muted-foreground">Total Armada</p>
                        <x-lucide-bus class="h-4 w-4 text-muted-foreground" />
                    </div>
                    <div class="text-2xl font-bold text-foreground">{{ number_format($totalBus) }}</div>
                    <p class="text-[10px] text-muted-foreground mt-1 uppercase tracking-wider">Bus Aktif</p>
                </x-ui.card.content>
            </x-ui.card>
        </div>

        <div class="grid grid-cols-1 gap-4 lg:grid-cols-7">
            <x-ui.card class="lg:col-span-4 shadow-sm bg-card text-card-foreground dark:border-border">
                <x-ui.card.header>
                    <x-ui.card.title class="text-base text-foreground">Ringkasan Pendapatan</x-ui.card.title>
                    <x-ui.card.description class="text-muted-foreground">Analisis pendapatan 6 bulan terakhir</x-ui.card.description>
                </x-ui.card.header>
                <x-ui.card.content>
                    <div class="h-[300px] w-full mt-4">
                        <canvas id="pendapatanChart"></canvas>
                    </div>
                </x-ui.card.content>
            </x-ui.card>

            <x-ui.card class="lg:col-span-3 shadow-sm bg-card text-card-foreground dark:border-border">
                <x-ui.card.header>
                    <x-ui.card.title class="text-base text-foreground">Status Distribusi</x-ui.card.title>
                    <x-ui.card.description class="text-muted-foreground">Proporsi status tiket saat ini</x-ui.card.description>
                </x-ui.card.header>
                <x-ui.card.content>
                    <div class="h-[200px] w-full relative">
                        <canvas id="tiketStatusChart"></canvas>
                    </div>
                    <div class="mt-6 space-y-3">
                        @foreach($tiketPerStatus as $status => $total)
                            @php
                                $percent = $totalTiket > 0 ? ($total / $totalTiket) * 100 : 0;
                                $color = ['Dipesan'=>'bg-yellow-400', 'Dibayar'=>'bg-emerald-500', 'Digunakan'=>'bg-blue-500', 'Dibatalkan'=>'bg-red-500'][$status] ?? 'bg-slate-400';
                            @endphp
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <div class="h-2 w-2 rounded-full {{ $color }} ring-1 ring-white/20"></div>
                                    <span class="text-xs font-medium text-foreground">{{ $status }}</span>
                                </div>
                                <span class="text-xs text-muted-foreground font-mono">{{ number_format($percent, 1) }}%</span>
                            </div>
                        @endforeach
                    </div>
                </x-ui.card.content>
            </x-ui.card>
        </div>

        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
            <x-ui.card class="shadow-sm bg-card text-card-foreground dark:border-border">
                <x-ui.card.header class="flex flex-row items-center justify-between">
                    <div>
                        <x-ui.card.title class="text-base text-blue-700 dark:text-blue-400">Rute Terpopuler</x-ui.card.title>
                        <x-ui.card.description class="text-muted-foreground">Top 5 performa rute</x-ui.card.description>
                    </div>
                    <x-lucide-trending-up class="h-4 w-4 text-muted-foreground" />
                </x-ui.card.header>
                <x-ui.card.content class="p-0 border-t dark:border-border">
                    <x-ui.table>
                        <x-ui.table.header class="bg-muted/50 dark:bg-muted/30">
                            <x-ui.table.row class="border-b dark:border-border hover:bg-transparent">
                                <x-ui.table.head class="pl-6 text-[10px] uppercase text-muted-foreground">Rute</x-ui.table.head>
                                <x-ui.table.head class="text-right text-[10px] uppercase text-muted-foreground">Tiket</x-ui.table.head>
                                <x-ui.table.head class="pr-6 text-right text-[10px] uppercase text-muted-foreground">Pendapatan</x-ui.table.head>
                            </x-ui.table.row>
                        </x-ui.table.header>
                        <x-ui.table.body>
                            @foreach($ruteTerpopuler as $rute)
                            <x-ui.table.row class="border-b dark:border-border hover:bg-muted/50 dark:hover:bg-muted/20">
                                <x-ui.table.cell class="pl-6 py-3">
                                    <div class="flex items-center gap-2 text-sm font-medium text-foreground">
                                        {{ $rute->asal }}
                                        <x-lucide-arrow-right class="h-3 w-3 text-muted-foreground" />
                                        {{ $rute->tujuan }}
                                    </div>
                                </x-ui.table.cell>
                                <x-ui.table.cell class="text-right">
                                    <span class="inline-flex items-center rounded-md bg-blue-50 dark:bg-blue-900/30 px-2 py-1 text-xs font-medium text-blue-700 dark:text-blue-300 ring-1 ring-inset ring-blue-700/10 dark:ring-blue-500/20">
                                        {{ number_format($rute->total_tiket) }}
                                    </span>
                                </x-ui.table.cell>
                                <x-ui.table.cell class="pr-6 text-right font-semibold text-sm text-foreground">
                                    Rp {{ number_format($rute->total_pendapatan, 0, ',', '.') }}
                                </x-ui.table.cell>
                            </x-ui.table.row>
                            @endforeach
                        </x-ui.table.body>
                    </x-ui.table>
                </x-ui.card.content>
            </x-ui.card>

            <x-ui.card class="shadow-sm bg-card text-card-foreground dark:border-border">
                <x-ui.card.header>
                    <x-ui.card.title class="text-base text-foreground">Tiket Terjual per Hari</x-ui.card.title>
                    <x-ui.card.description class="text-muted-foreground">{{ $periode }} hari terakhir</x-ui.card.description>
                </x-ui.card.header>
                <x-ui.card.content>
                    <div class="space-y-5">
                        @php $maxTiket = $tiketPerHari->max() ?: 1; @endphp
                        @foreach($tiketPerHari as $tanggal => $total)
                        <div class="space-y-1.5">
                            <div class="flex items-center justify-between text-xs">
                                <span class="font-medium text-foreground">{{ $tanggal }}</span>
                                <span class="text-muted-foreground italic">{{ number_format($total) }} unit</span>
                            </div>
                            <div class="h-1.5 w-full bg-secondary dark:bg-muted/40 rounded-full overflow-hidden">
                                <div class="h-full bg-primary dark:bg-primary transition-all duration-500" style="width: {{ ($total/$maxTiket)*100 }}%"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </x-ui.card.content>
            </x-ui.card>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @php
                $links = [
                    ['route' => 'admin/laporan.tiket', 'title' => 'Laporan Tiket', 'icon' => 'lucide-ticket', 'color' => 'blue'],
                    ['route' => 'admin/laporan.pendapatan', 'title' => 'Laporan Pendapatan', 'icon' => 'lucide-dollar-sign', 'color' => 'emerald'],
                    ['route' => 'admin/laporan.penumpang', 'title' => 'Laporan Penumpang', 'icon' => 'lucide-users', 'color' => 'purple'],
                ];
            @endphp

            @foreach($links as $link)
            <a href="{{ route($link['route']) }}" class="group block">
                <x-ui.card class="bg-card text-card-foreground transition-all duration-200 hover:border-primary/50 dark:hover:border-primary/50 hover:shadow-md dark:border-border dark:bg-card">
                    <x-ui.card.content class="p-4 flex items-center gap-4">
                        <div class="h-10 w-10 rounded-lg bg-secondary dark:bg-secondary flex items-center justify-center transition-colors group-hover:bg-primary group-hover:text-primary-foreground dark:group-hover:bg-primary dark:group-hover:text-primary-foreground">
                            <x-dynamic-component :component="$link['icon']" class="h-5 w-5" />
                        </div>
                        <div>
                            <h4 class="text-sm font-semibold tracking-tight text-foreground">{{ $link['title'] }}</h4>
                            <p class="text-[10px] text-muted-foreground italic">Klik untuk detail laporan</p>
                        </div>
                        <x-lucide-chevron-right class="h-4 w-4 ml-auto text-muted-foreground group-hover:text-foreground transition-colors" />
                    </x-ui.card.content>
                </x-ui.card>
            </a>
            @endforeach
        </div>
    </div>
</x-admin-layout>
