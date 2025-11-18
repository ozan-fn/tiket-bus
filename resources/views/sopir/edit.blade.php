<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Sopir') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('admin/sopir.update', $sopir) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="user_id" class="block text-sm font-medium text-gray-700">User</label>
                            <select name="user_id" id="user_id"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                required>
                                @if($sopir->user)
                                    <option value="{{ $sopir->user->id }}" selected>{{ $sopir->user->name }}
                                        ({{ $sopir->user->email }})</option>
                                @endif
                            </select>
                            @error('user_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="nik" class="block text-sm font-medium text-gray-700">NIK</label>
                            <input type="text" name="nik" id="nik" value="{{ old('nik', $sopir->nik) }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                required>
                            @error('nik')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="nomor_sim" class="block text-sm font-medium text-gray-700">Nomor SIM</label>
                            <input type="text" name="nomor_sim" id="nomor_sim"
                                value="{{ old('nomor_sim', $sopir->nomor_sim) }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                required>
                            @error('nomor_sim')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat</label>
                            <textarea name="alamat" id="alamat"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('alamat', $sopir->alamat) }}</textarea>
                            @error('alamat')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="telepon" class="block text-sm font-medium text-gray-700">Telepon</label>
                            <input type="text" name="telepon" id="telepon" value="{{ old('telepon', $sopir->telepon) }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            @error('telepon')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700">Tanggal
                                Lahir</label>
                            <input type="date" name="tanggal_lahir" id="tanggal_lahir"
                                value="{{ old('tanggal_lahir', $sopir->tanggal_lahir ? $sopir->tanggal_lahir->format('Y-m-d') : '') }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                required>
                            @error('tanggal_lahir')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status" id="status"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                required>
                                <option value="aktif" {{ old('status', $sopir->status) == 'aktif' ? 'selected' : '' }}>
                                    Aktif</option>
                                <option value="tidak_aktif" {{ old('status', $sopir->status) == 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-between">
                            <button type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Update
                            </button>
                            <a href="{{ route('admin/sopir.index') }}"
                                class="text-gray-600 hover:text-gray-900">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const select = new TomSelect('#user_id', {
                valueField: 'value',
                labelField: 'text',
                searchField: 'text',
                load: function (query, callback) {
                    if (!query.length) return callback();
                    fetch('{{ route("admin.sopir.search-users") }}?q=' + encodeURIComponent(query), {
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
            fetch('{{ route("admin.sopir.search-users") }}', {
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
</x-app-layout>