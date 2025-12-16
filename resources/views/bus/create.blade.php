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

                <x-ui.tabs :default="'informasi'">
                    <x-ui.tabs.list class="mb-4 border-b border-border overflow-x-auto gap-x-2">
                        <x-ui.tabs.trigger value="informasi">
                            <x-lucide-info class="w-4 h-4" /> Informasi Bus
                        </x-ui.tabs.trigger>
                        <x-ui.tabs.trigger value="fasilitas">
                            <x-lucide-sparkles class="w-4 h-4" /> Fasilitas
                        </x-ui.tabs.trigger>
                        <x-ui.tabs.trigger value="kelas">
                            <x-lucide-armchair class="w-4 h-4" /> Kelas Bus
                        </x-ui.tabs.trigger>
                        <x-ui.tabs.trigger value="foto">
                            <x-lucide-image class="w-4 h-4" /> Foto
                        </x-ui.tabs.trigger>
                    </x-ui.tabs.list>

                    <x-ui.tabs.content value="informasi" class="space-y-6">
                        @include('bus.partials._form_informasi')
                        <div class="flex justify-end mt-6">
                            {{-- HAPUS x-ui.tabs.trigger, GANTI DENGAN INI: --}}
                            <x-ui.button type="button" variant="primary" @click="tab = 'fasilitas'">
                                Next
                            </x-ui.button>
                        </div>
                    </x-ui.tabs.content>

                    <x-ui.tabs.content value="fasilitas" class="space-y-6">
                        @include('bus.partials._form_fasilitas')
                        <div class="flex justify-between mt-6">
                            <x-ui.button type="button" variant="secondary" @click="tab = 'informasi'">
                                Previous
                            </x-ui.button>

                            <x-ui.button type="button" variant="primary" @click="tab = 'kelas'">
                                Next
                            </x-ui.button>
                        </div>
                    </x-ui.tabs.content>

                    <x-ui.tabs.content value="kelas" class="space-y-6">
                        @include('bus.partials._form_kelas')
                        <div class="flex justify-between mt-6">
                            <x-ui.button type="button" variant="secondary" @click="tab = 'fasilitas'">
                                Previous
                            </x-ui.button>

                            <x-ui.button type="button" variant="primary" @click="tab = 'foto'">
                                Next
                            </x-ui.button>
                        </div>
                    </x-ui.tabs.content>

                    <x-ui.tabs.content value="foto" class="space-y-6">
                        @include('bus.partials._form_foto')
                        <div class="flex justify-between mt-6">
                            <x-ui.button type="button" variant="secondary" @click="tab = 'kelas'">
                                Previous
                            </x-ui.button>

                            <x-ui.button type="submit" variant="primary">
                                Create
                            </x-ui.button>
                        </div>
                    </x-ui.tabs.content>
                </x-ui.tabs>
            </form>
            {{-- Alert info jika tombol Next tidak bisa diklik --}}
            <x-ui.alert.alert
                variant="default"
                :title="'Kenapa tombol Next tidak bisa diklik?'"
                :description="'Tombol Next pada stepper (tabs) akan nonaktif jika ada isian yang wajib diisi namun belum lengkap. Pastikan semua field pada step saat ini sudah terisi dengan benar sebelum melanjutkan ke step berikutnya. Jika ada field yang kosong atau tidak valid, periksa kembali dan lengkapi terlebih dahulu.'"
                class="mt-6"
            />
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
            updateKelasSelectOptions();
        }

        // Remove Kelas Bus Row
        function removeKelasBusRow(button) {
            button.closest('.kelas-bus-row').remove();
            updateKelasSummary();
            updateKelasSelectOptions();
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

        // Disable duplicate kelas in select
        function updateKelasSelectOptions() {
            const rows = document.querySelectorAll('.kelas-bus-row');
            const selectedValues = Array.from(rows)
                .map(row => row.querySelector('select').value)
                .filter(val => val !== '');

            rows.forEach(row => {
                const select = row.querySelector('select');
                const currentValue = select.value;
                Array.from(select.options).forEach(option => {
                    if (option.value === '' || option.value === currentValue) {
                        option.disabled = false;
                    } else {
                        option.disabled = selectedValues.includes(option.value);
                    }
                });
            });
        }

        // Monitor input changes
        document.addEventListener('change', function(e) {
            if (e.target.matches('.kelas-select, .kelas-kursi')) {
                updateKelasSummary();
            }
            if (e.target.matches('.kelas-select')) {
                updateKelasSelectOptions();
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

        // Form validation: prevent duplicate kelas & jumlah kursi < 1
        document.getElementById('busForm').addEventListener('submit', function(e) {
            let valid = true;
            let kelasValues = [];
            let kursiValid = true;
            let duplicate = false;

            const rows = document.querySelectorAll('.kelas-bus-row');
            rows.forEach(row => {
                const select = row.querySelector('select');
                const input = row.querySelector('input[type="number"]');
                if (select.value === '' || kelasValues.includes(select.value)) {
                    duplicate = true;
                }
                kelasValues.push(select.value);

                if (input.value === '' || isNaN(input.value) || Number(input.value) < 1) {
                    kursiValid = false;
                }
            });

            if (duplicate) {
                alert('Tidak boleh ada kelas yang sama pada baris berbeda.');
                valid = false;
            }
            if (!kursiValid) {
                alert('Jumlah kursi harus diisi dan minimal 1 pada setiap baris.');
                valid = false;
            }
            if (!valid) {
                e.preventDefault();
            }
        });
    </script>
    @endpush
</x-admin-layout>
