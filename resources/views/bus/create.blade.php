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
                        Tambah Bus
                    </x-ui.breadcrumb.page>
                </x-ui.breadcrumb.item>
            </x-ui.breadcrumb.list>
        </x-ui.breadcrumb.breadcrumb>
    </x-slot>

    <div class="p-4 sm:p-6">
        <div class="max-w-6xl mx-auto space-y-6">
            @if ($errors->any())
                <x-ui.alert variant="destructive" class="mb-6">
                    <x-lucide-alert-circle class="w-5 h-5" />
                    <div>
                        <h4 class="font-semibold mb-2">Terjadi Kesalahan</h4>
                        <ul class="text-sm space-y-1">
                            @foreach ($errors->all() as $error)
                                <li class="flex items-center gap-2">
                                    <span class="h-1 w-1 rounded-full bg-current"></span>
                                    {{ $error }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </x-ui.alert>
            @endif

            <form method="POST" action="{{ route('admin/bus.store') }}" enctype="multipart/form-data" id="busForm">
                @csrf

                <!-- Tab Navigation -->
                <div class="flex gap-2 mb-6 border-b border-border overflow-x-auto">
                    <button type="button" data-tab="informasi" class="tab-button active px-4 py-2 border-b-2 border-primary text-sm font-medium text-primary whitespace-nowrap">
                        <div class="flex items-center gap-2">
                            <x-lucide-info class="w-4 h-4" />
                            Informasi Bus
                        </div>
                    </button>
                    <button type="button" data-tab="fasilitas" class="tab-button px-4 py-2 border-b-2 border-transparent text-sm font-medium text-muted-foreground hover:text-foreground whitespace-nowrap">
                        <div class="flex items-center gap-2">
                            <x-lucide-sparkles class="w-4 h-4" />
                            Fasilitas
                        </div>
                    </button>
                    <button type="button" data-tab="kelas" class="tab-button px-4 py-2 border-b-2 border-transparent text-sm font-medium text-muted-foreground hover:text-foreground whitespace-nowrap">
                        <div class="flex items-center gap-2">
                            <x-lucide-armchair class="w-4 h-4" />
                            Kelas Bus
                        </div>
                    </button>
                    <button type="button" data-tab="foto" class="tab-button px-4 py-2 border-b-2 border-transparent text-sm font-medium text-muted-foreground hover:text-foreground whitespace-nowrap">
                        <div class="flex items-center gap-2">
                            <x-lucide-image class="w-4 h-4" />
                            Foto
                        </div>
                    </button>
                </div>

                <!-- Tab Content -->
                <!-- Tab 1: Informasi Bus -->
                <div id="tab-informasi" class="tab-content space-y-6">
                    <x-ui.card>
                        <x-ui.card.header>
                            <x-ui.card.title>Informasi Dasar Bus</x-ui.card.title>
                            <x-ui.card.description>Masukkan detail identitas bus yang akan ditambahkan</x-ui.card.description>
                        </x-ui.card.header>
                        <x-ui.card.content>
                            <div class="space-y-6">
                                <!-- Nama Bus -->
                                <div class="space-y-2">
                                    <x-ui.label for="nama">
                                        <div class="flex items-center gap-2">
                                            <x-lucide-bus class="w-4 h-4" />
                                            Nama Bus
                                            <span class="text-red-500">*</span>
                                        </div>
                                    </x-ui.label>
                                    <x-ui.input
                                        type="text"
                                        id="nama"
                                        name="nama"
                                        value="{{ old('nama') }}"
                                        placeholder="Contoh: Haryanto Executive, Pahala Kencana"
                                        required
                                        class="@error('nama') border-red-500 @enderror"
                                    />
                                    @error('nama')
                                        <p class="text-sm text-destructive flex items-center gap-1">
                                            <x-lucide-alert-circle class="w-4 h-4" />
                                            {{ $message }}
                                        </p>
                                    @enderror
                                    <p class="text-xs text-muted-foreground">Masukkan nama merek atau nama armada bus</p>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Kapasitas -->
                                    <div class="space-y-2">
                                        <x-ui.label for="kapasitas">
                                            <div class="flex items-center gap-2">
                                                <x-lucide-users class="w-4 h-4" />
                                                Kapasitas Kursi
                                                <span class="text-red-500">*</span>
                                            </div>
                                        </x-ui.label>
                                        <x-ui.input
                                            type="number"
                                            id="kapasitas"
                                            name="kapasitas"
                                            value="{{ old('kapasitas') }}"
                                            placeholder="Contoh: 40"
                                            min="1"
                                            required
                                            class="@error('kapasitas') border-red-500 @enderror"
                                        />
                                        @error('kapasitas')
                                            <p class="text-sm text-destructive flex items-center gap-1">
                                                <x-lucide-alert-circle class="w-4 h-4" />
                                                {{ $message }}
                                            </p>
                                        @enderror
                                        <p class="text-xs text-muted-foreground">Total jumlah kursi di bus</p>
                                    </div>

                                    <!-- Plat Nomor -->
                                    <div class="space-y-2">
                                        <x-ui.label for="plat_nomor">
                                            <div class="flex items-center gap-2">
                                                <x-lucide-hash class="w-4 h-4" />
                                                Plat Nomor
                                                <span class="text-red-500">*</span>
                                            </div>
                                        </x-ui.label>
                                        <x-ui.input
                                            type="text"
                                            id="plat_nomor"
                                            name="plat_nomor"
                                            value="{{ old('plat_nomor') }}"
                                            placeholder="Contoh: B 1234 XYZ"
                                            required
                                            class="@error('plat_nomor') border-red-500 @enderror"
                                        />
                                        @error('plat_nomor')
                                            <p class="text-sm text-destructive flex items-center gap-1">
                                                <x-lucide-alert-circle class="w-4 h-4" />
                                                {{ $message }}
                                            </p>
                                        @enderror
                                        <p class="text-xs text-muted-foreground">Nomor identitas kendaraan</p>
                                    </div>
                                </div>
                            </div>
                        </x-ui.card.content>
                    </x-ui.card>
                </div>

                <!-- Tab 2: Fasilitas -->
                <div id="tab-fasilitas" class="tab-content hidden space-y-6">
                    <x-ui.card>
                        <x-ui.card.header>
                            <x-ui.card.title>Fasilitas Bus</x-ui.card.title>
                            <x-ui.card.description>Pilih fasilitas yang tersedia di bus ini</x-ui.card.description>
                        </x-ui.card.header>
                        <x-ui.card.content>
                            <div class="space-y-2">
                                <x-ui.label>
                                    <div class="flex items-center gap-2">
                                        <x-lucide-sparkles class="w-4 h-4" />
                                        Pilih Fasilitas
                                    </div>
                                </x-ui.label>
                                <select
                                    name="fasilitas_ids[]"
                                    id="fasilitas_ids"
                                    multiple
                                    class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring"
                                >
                                    @foreach($fasilitas as $fasilitasItem)
                                        <option value="{{ $fasilitasItem->id }}" {{ in_array($fasilitasItem->id, old('fasilitas_ids', [])) ? 'selected' : '' }}>
                                            {{ $fasilitasItem->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                <p class="text-xs text-muted-foreground flex items-center gap-1">
                                    <x-lucide-info class="w-3 h-3" />
                                    Tekan Ctrl (Windows) atau Cmd (Mac) untuk memilih lebih dari satu
                                </p>
                            </div>

                            <!-- Fasilitas Preview -->
                            <div class="mt-6 space-y-3">
                                <h4 class="text-sm font-semibold">Fasilitas yang Dipilih:</h4>
                                <div id="fasilitas-preview" class="flex flex-wrap gap-2">
                                    @foreach($fasilitas as $fasilitasItem)
                                        @if(in_array($fasilitasItem->id, old('fasilitas_ids', [])))
                                            <x-ui.badge>
                                                <x-lucide-check class="w-3 h-3 mr-1" />
                                                {{ $fasilitasItem->nama }}
                                            </x-ui.badge>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </x-ui.card.content>
                    </x-ui.card>
                </div>

                <!-- Tab 3: Kelas Bus -->
                <div id="tab-kelas" class="tab-content hidden space-y-6">
                    <x-ui.card>
                        <x-ui.card.header>
                            <div class="flex items-center justify-between">
                                <div>
                                    <x-ui.card.title>Kelas Bus</x-ui.card.title>
                                    <x-ui.card.description>Tambahkan kelas bus dan tentukan jumlah kursi untuk setiap kelas</x-ui.card.description>
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
                </div>

                <!-- Tab 4: Foto -->
                <div id="tab-foto" class="tab-content hidden space-y-6">
                    <x-ui.card>
                        <x-ui.card.header>
                            <x-ui.card.title>Foto Bus</x-ui.card.title>
                            <x-ui.card.description>Unggah foto atau gambar bus dari berbagai sudut</x-ui.card.description>
                        </x-ui.card.header>
                        <x-ui.card.content>
                            <div class="space-y-4">
                                <!-- Upload Area -->
                                <div class="border-2 border-dashed border-border rounded-lg p-8 text-center hover:border-primary hover:bg-primary/5 transition-all cursor-pointer bg-muted/30">
                                    <x-lucide-upload class="h-12 w-12 text-muted-foreground mx-auto mb-3" />
                                    <label for="foto" class="cursor-pointer">
                                        <span class="text-sm text-primary hover:underline font-medium">Klik untuk upload</span>
                                        <span class="text-sm text-muted-foreground"> atau drag & drop</span>
                                    </label>
                                    <input
                                        type="file"
                                        name="foto[]"
                                        id="foto"
                                        multiple
                                        accept="image/*"
                                        class="hidden"
                                    />
                                    <p class="text-xs text-muted-foreground mt-2 flex items-center justify-center gap-1">
                                        <x-lucide-file-image class="w-3 h-3" />
                                        PNG, JPG, JPEG, GIF (Max. 2MB per file)
                                    </p>
                                </div>

                                @error('foto')
                                    <x-ui.alert variant="destructive">
                                        <x-lucide-alert-circle class="w-5 h-5" />
                                        <x-ui.alert.title>Error Upload</x-ui.alert.title>
                                        <x-ui.alert.description>{{ $message }}</x-ui.alert.description>
                                    </x-ui.alert>
                                @enderror

                                <!-- Preview -->
                                <div>
                                    <h4 class="text-sm font-semibold mb-3 flex items-center gap-2">
                                        <x-lucide-image class="w-4 h-4" />
                                        Preview Foto
                                    </h4>
                                    <div id="preview" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4"></div>
                                </div>
                            </div>
                        </x-ui.card.content>
                    </x-ui.card>
                </div>

                <!-- Form Actions -->
                <div class="flex flex-col-reverse sm:flex-row items-center justify-between gap-3 sticky bottom-0 bg-background border-t border-border p-4 rounded-b-lg">
                    <a href="{{ route('admin/bus.index') }}" class="w-full sm:w-auto">
                        <x-ui.button type="button" variant="outline" class="w-full sm:w-auto">
                            <x-lucide-x class="w-4 h-4 mr-2" />
                            Batal
                        </x-ui.button>
                    </a>
                    <x-ui.button type="submit" class="w-full sm:w-auto">
                        <x-lucide-save class="w-4 h-4 mr-2" />
                        Simpan Bus
                    </x-ui.button>
                </div>
            </form>
        </div>
    </div>

    <!-- Hidden Kelas Bus Row Template -->
    <template id="kelas-bus-template">
        <div class="kelas-bus-row flex gap-3 items-end p-4 rounded-lg border border-border bg-muted/30 hover:bg-muted/50 transition-colors">
            <div class="flex-1">
                <label class="text-xs font-medium text-muted-foreground block mb-2">Pilih Kelas</label>
                <select name="kelas_bus_data[INDEX][kelas_id]" class="kelas-select w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                    <option value="">-- Pilih Kelas --</option>
                    @foreach($kelasBus as $kelas)
                        <option value="{{ $kelas->id }}">{{ $kelas->nama_kelas }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1">
                <label class="text-xs font-medium text-muted-foreground block mb-2">Jumlah Kursi</label>
                <x-ui.input
                    type="number"
                    name="kelas_bus_data[INDEX][jumlah_kursi]"
                    placeholder="Masukkan jumlah kursi"
                    min="1"
                    class="kelas-kursi"
                />
            </div>
            <x-ui.button type="button" variant="destructive" size="sm" onclick="removeKelasBusRow(this)">
                <x-lucide-trash-2 class="w-4 h-4" />
            </x-ui.button>
        </div>
    </template>

    @push('scripts')
    <script>
        let kelasBusCount = 0;

        // Tab Navigation
        document.querySelectorAll('.tab-button').forEach(button => {
            button.addEventListener('click', function() {
                const tabName = this.dataset.tab;

                document.querySelectorAll('.tab-button').forEach(btn => {
                    btn.classList.remove('active', 'border-primary', 'text-primary');
                    btn.classList.add('border-transparent', 'text-muted-foreground');
                });

                document.querySelectorAll('.tab-content').forEach(content => {
                    content.classList.add('hidden');
                });

                this.classList.add('active', 'border-primary', 'text-primary');
                this.classList.remove('border-transparent', 'text-muted-foreground');
                document.getElementById('tab-' + tabName).classList.remove('hidden');
            });
        });

        // Fasilitas Selection
        document.getElementById('fasilitas_ids').addEventListener('change', function() {
            const selected = Array.from(this.selectedOptions).map(opt => opt.text);
            const preview = document.getElementById('fasilitas-preview');
            preview.innerHTML = selected.map(name =>
                `<x-ui.badge class="bg-primary text-primary-foreground"><x-lucide-check class="w-3 h-3 mr-1" /> ${name}</x-ui.badge>`
            ).join('');
        });

        // Add Kelas Bus Row
        function addKelasBusRow() {
            const container = document.getElementById('kelas-bus-container');
            const template = document.getElementById('kelas-bus-template');
            const clone = template.content.cloneNode(true);

            const rowHtml = clone.querySelector('.kelas-bus-row').outerHTML
                .replace(/\[INDEX\]/g, `[${kelasBusCount}]`);

            const newRow = document.createElement('div');
            newRow.innerHTML = rowHtml;
            container.appendChild(newRow.firstElementChild);

            kelasBusCount++;
            updateKelasSummary();
        }

        // Remove Kelas Bus Row
        function removeKelasBusRow(button) {
            button.closest('.kelas-bus-row').remove();
            updateKelasSummary();
        }

        // Update Kelas Summary
        function updateKelasSummary() {
            const rows = document.querySelectorAll('.kelas-bus-row');
            const summary = document.getElementById('kelas-summary');

            if (rows.length === 0) {
                summary.innerHTML = '<p>Belum ada kelas yang ditambahkan</p>';
                return;
            }

            let html = '<div class="space-y-2">';
            rows.forEach((row, idx) => {
                const selectEl = row.querySelector('select');
                const inputEl = row.querySelector('input[type="number"]');
                const selectedText = selectEl.options[selectEl.selectedIndex]?.text || '(Pilih Kelas)';
                const kursi = inputEl.value || '0';

                html += `<div class="flex justify-between items-center p-2 bg-background rounded border border-border">
                    <span class="font-medium">${selectedText}</span>
                    <x-ui.badge variant="secondary">${kursi} Kursi</x-ui.badge>
                </div>`;
            });
            html += '</div>';

            summary.innerHTML = html;
        }

        // Monitor input changes
        document.addEventListener('change', function(e) {
            if (e.target.matches('.kelas-select, .kelas-kursi')) {
                updateKelasSummary();
            }
        });

        // Tom Select for Fasilitas
        new TomSelect('#fasilitas_ids', {
            plugins: ['remove_button'],
            placeholder: 'Pilih fasilitas...',
            maxItems: null
        });

        // Store selected files for foto
        let selectedFiles = new DataTransfer();

        // Preview Upload Foto
        document.getElementById('foto').addEventListener('change', function(e) {
            const preview = document.getElementById('preview');
            const input = e.target;
            const files = Array.from(input.files);

            files.forEach((file) => {
                if (file.type.startsWith('image/')) {
                    selectedFiles.items.add(file);

                    const reader = new FileReader();
                    reader.onload = function(readerEvent) {
                        const div = document.createElement('div');
                        div.className = 'relative group';
                        div.innerHTML = `
                            <img src="${readerEvent.target.result}" class="w-full h-24 object-cover rounded-lg border border-border">
                            <button type="button" class="removeBtn absolute top-1 right-1 bg-destructive text-destructive-foreground rounded-full p-1 opacity-0 group-hover:opacity-100 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        `;

                        div.querySelector('.removeBtn').addEventListener('click', function() {
                            div.remove();
                            const newDataTransfer = new DataTransfer();
                            Array.from(selectedFiles.files).forEach((f) => {
                                if (f !== file) {
                                    newDataTransfer.items.add(f);
                                }
                            });
                            selectedFiles = newDataTransfer;
                            input.files = selectedFiles.files;
                        });

                        preview.appendChild(div);
                    };
                    reader.readAsDataURL(file);
                }
            });

            input.files = selectedFiles.files;
        });
    </script>
    @endpush
</x-admin-layout>
