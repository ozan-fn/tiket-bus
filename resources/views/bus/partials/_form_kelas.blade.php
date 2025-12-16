<x-ui.card>
    <x-ui.card.header>
        <div class="flex items-center justify-between">
            <div class="flex flex-col gap-2">
                <x-ui.card.title>Kelas Bus</x-ui.card.title>
                <x-ui.card.description class="mt-1">Tambahkan kelas bus dan tentukan jumlah kursi untuk setiap kelas</x-ui.card.description>
            </div>
            <x-ui.button type="button" size="sm" onclick="addKelasBusRow()">
                <x-lucide-plus class="w-4 h-4 mr-2" />
                Tambah Kelas
            </x-ui.button>
        </div>
    </x-ui.card.header>
    <x-ui.card.content>
        <div id="kelas-bus-container" class="space-y-4">
            <!-- Template will be cloned here -->
        </div>

        <!-- Kelas Bus Summary -->
        <div class="mt-6 p-4 rounded-lg bg-muted/50 border border-border">
            <h4 class="text-sm font-semibold mb-2 flex items-center gap-2">
                <x-lucide-info class="w-4 h-4" />
                Ringkasan Kelas Bus
            </h4>
            <div id="kelas-summary" class="text-sm text-muted-foreground">
                <p>Belum ada kelas yang ditambahkan</p>
            </div>
        </div>
    </x-ui.card.content>
</x-ui.card>
