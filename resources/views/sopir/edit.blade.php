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
                    <x-ui.breadcrumb.link href="{{ route('admin/sopir.index') }}">
                        Sopir
                    </x-ui.breadcrumb.link>
                </x-ui.breadcrumb.item>
                <x-ui.breadcrumb.separator>
                    <x-lucide-chevron-right class="w-3.5 h-3.5" />
                </x-ui.breadcrumb.separator>
                <x-ui.breadcrumb.item>
                    <x-ui.breadcrumb.page>
                        Edit Sopir
                    </x-ui.breadcrumb.page>
                </x-ui.breadcrumb.item>
            </x-ui.breadcrumb.list>
        </x-ui.breadcrumb.breadcrumb>
    </x-slot>

    <div class="p-4 sm:p-6">
        <div class="max-w-4xl mx-auto">
            <x-ui.card>
                <x-ui.card.header>
                    <div class="flex items-center justify-between">
                        <div>
                            <x-ui.card.title>Edit Informasi Sopir</x-ui.card.title>
                            <x-ui.card.description>Perbarui detail informasi sopir {{ $sopir->user->name ?? '' }}</x-ui.card.description>
                        </div>
                        <a href="{{ route('admin/sopir.index') }}">
                            <x-ui.button variant="outline" size="sm">
                                <x-lucide-arrow-left class="w-4 h-4 mr-2" />
                                Kembali
                            </x-ui.button>
                        </a>
                    </div>
                </x-ui.card.header>
                <x-ui.card.content>
                    <form method="POST" action="{{ route('admin/sopir.update', $sopir) }}" id="sopirForm">
                        @csrf
                        @method('PUT')

                        <div class="space-y-6">
                            <!-- User -->
                            <div class="space-y-2">
                                <x-ui.label for="user_id">
                                    <div class="flex items-center gap-2">
                                        <x-lucide-user class="w-4 h-4" />
                                        User
                                    </div>
                                    <span class="text-red-500">*</span>
                                </x-ui.label>
                                <select
                                    name="user_id"
                                    id="user_id"
                                    required
                                    class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                                    @if($sopir->user)
                                        <option value="{{ $sopir->user->id }}" selected>{{ $sopir->user->name }} ({{ $sopir->user->email }})</option>
                                    @endif
                                </select>
                                <p class="text-xs text-muted-foreground flex items-center gap-1">
                                    <x-lucide-info class="w-3 h-3" />
                                    Cari dan pilih user untuk sopir ini
                                </p>
                                @error('user_id')
                                    <p class="text-sm text-destructive mt-1 flex items-center gap-1">
                                        <x-lucide-alert-circle class="w-4 h-4" />
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- NIK -->
                                <div class="space-y-2">
                                    <x-ui.label for="nik">
                                        NIK
                                        <span class="text-red-500">*</span>
                                    </x-ui.label>
                                    <x-ui.input
                                        type="text"
                                        id="nik"
                                        name="nik"
                                        value="{{ old('nik', $sopir->nik) }}"
                                        placeholder="Contoh: 3201234567890123"
                                        required
                                    />
                                    @error('nik')
                                        <p class="text-sm text-destructive mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Nomor SIM -->
                                <div class="space-y-2">
                                    <x-ui.label for="nomor_sim">
                                        Nomor SIM
                                        <span class="text-red-500">*</span>
                                    </x-ui.label>
                                    <x-ui.input
                                        type="text"
                                        id="nomor_sim"
                                        name="nomor_sim"
                                        value="{{ old('nomor_sim', $sopir->nomor_sim) }}"
                                        placeholder="Contoh: 1234567890123"
                                        required
                                    />
                                    @error('nomor_sim')
                                        <p class="text-sm text-destructive mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Tanggal Lahir -->
                                <div class="space-y-2">
                                    <x-ui.label for="tanggal_lahir">
                                        <div class="flex items-center gap-2">
                                            <x-lucide-calendar class="w-4 h-4" />
                                            Tanggal Lahir
                                        </div>
                                        <span class="text-red-500">*</span>
                                    </x-ui.label>
                                    <x-datepicker
                                        name="tanggal_lahir"
                                        id="tanggal_lahir"
                                        value="{{ old('tanggal_lahir', $sopir->tanggal_lahir ? $sopir->tanggal_lahir->format('Y-m-d') : '') }}"
                                        placeholder="Pilih tanggal lahir..."
                                        required
                                    />
                                    @error('tanggal_lahir')
                                        <p class="text-sm text-destructive mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Telepon -->
                                <div class="space-y-2">
                                    <x-ui.label for="telepon">
                                        <div class="flex items-center gap-2">
                                            <x-lucide-phone class="w-4 h-4" />
                                            Telepon
                                        </div>
                                    </x-ui.label>
                                    <x-ui.input
                                        type="text"
                                        id="telepon"
                                        name="telepon"
                                        value="{{ old('telepon', $sopir->telepon) }}"
                                        placeholder="Contoh: 081234567890"
                                    />
                                    @error('telepon')
                                        <p class="text-sm text-destructive mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Alamat -->
                            <div class="space-y-2">
                                <x-ui.label for="alamat">
                                    <div class="flex items-center gap-2">
                                        <x-lucide-map-pin class="w-4 h-4" />
                                        Alamat
                                    </div>
                                </x-ui.label>
                                <textarea
                                    name="alamat"
                                    id="alamat"
                                    rows="3"
                                    placeholder="Masukkan alamat lengkap"
                                    class="flex min-h-[80px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                                >{{ old('alamat', $sopir->alamat) }}</textarea>
                                @error('alamat')
                                    <p class="text-sm text-destructive mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div class="space-y-2">
                                <x-ui.label for="status">
                                    <div class="flex items-center gap-2">
                                        <x-lucide-check-circle class="w-4 h-4" />
                                        Status
                                    </div>
                                    <span class="text-red-500">*</span>
                                </x-ui.label>
                                <select
                                    name="status"
                                    id="status"
                                    required
                                    class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                                    <option value="aktif" {{ old('status', $sopir->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="tidak_aktif" {{ old('status', $sopir->status) == 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                                </select>
                                @error('status')
                                    <p class="text-sm text-destructive mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex flex-col-reverse sm:flex-row items-center justify-end gap-3 mt-8 pt-6 border-t border-border">
                            <a href="{{ route('admin/sopir.index') }}" class="w-full sm:w-auto">
                                <x-ui.button type="button" variant="outline" class="w-full sm:w-auto">
                                    <x-lucide-x class="w-4 h-4 mr-2" />
                                    Batal
                                </x-ui.button>
                            </a>
                            <x-ui.button type="submit" class="w-full sm:w-auto">
                                <x-lucide-save class="w-4 h-4 mr-2" />
                                Update Sopir
                            </x-ui.button>
                        </div>
                    </form>
                </x-ui.card.content>
            </x-ui.card>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const select = new TomSelect('#user_id', {
                valueField: 'value',
                labelField: 'text',
                searchField: 'text',
                load: function (query, callback) {
                    if (!query.length) return callback();
                    fetch('{{ route("admin/sopir.search-users") }}?q=' + encodeURIComponent(query), {
                        headers: {
                            'Accept': 'application/json'
                        }
                    })
                        .then(response => response.json())
                        .then(data => callback(data.results))
                        .catch(() => callback());
                },
                placeholder: 'Cari dan pilih user...',
                maxOptions: null,
                render: {
                    option: function (item, escape) {
                        return '<div>' + escape(item.text) + '</div>';
                    },
                    item: function (item, escape) {
                        return '<div>' + escape(item.text) + '</div>';
                    }
                }
            });

            // Load default users
            fetch('{{ route("admin/sopir.search-users") }}', {
                headers: {
                    'Accept': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    select.addOptions(data.results);
                });

            // Preload current user if exists
            @if($sopir->user)
                select.addOption({
                    value: '{{ $sopir->user->id }}',
                    text: '{{ $sopir->user->name }} ({{ $sopir->user->email }})'
                });
                select.setValue('{{ $sopir->user->id }}');
            @endif
        });
    </script>
    @endpush
</x-admin-layout>
