<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Bus') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('admin/bus.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-4">
                            <label for="nama" class="block text-sm font-medium text-gray-700">Nama Bus</label>
                            <input type="text" name="nama" id="nama" value="{{ old('nama') }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                required>
                            @error('nama')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="kapasitas" class="block text-sm font-medium text-gray-700">Kapasitas</label>
                            <input type="number" name="kapasitas" id="kapasitas" value="{{ old('kapasitas') }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                required min="1">
                            @error('kapasitas')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="plat_nomor" class="block text-sm font-medium text-gray-700">Plat Nomor</label>
                            <input type="text" name="plat_nomor" id="plat_nomor" value="{{ old('plat_nomor') }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                required>
                            @error('plat_nomor')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="fasilitas_ids" class="block text-sm font-medium text-gray-700">Fasilitas</label>
                            <select name="fasilitas_ids[]" id="fasilitas_ids" multiple
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                @foreach($fasilitas as $fasilitasItem)
                                    <option value="{{ $fasilitasItem->id }}" {{ in_array($fasilitasItem->id, old('fasilitas_ids', [])) ? 'selected' : '' }}>
                                        {{ $fasilitasItem->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('fasilitas_ids')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="foto" class="block text-sm font-medium text-gray-700">Foto Bus</label>
                            <input type="file" name="foto[]" id="foto" multiple
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                accept="image/*">
                            @error('foto')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">Pilih satu atau lebih foto (maksimal 2MB per foto)</p>
                        </div>

                        <div class="flex items-center justify-between">
                            <button type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Simpan
                            </button>
                            <a href="{{ route('admin/bus.index') }}"
                                class="text-gray-600 hover:text-gray-900">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>