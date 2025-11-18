<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Terminal') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('admin/terminal.update', $terminal) }}"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="nama_terminal" class="block text-sm font-medium text-gray-700">Nama
                                Terminal</label>
                            <input type="text" name="nama_terminal" id="nama_terminal"
                                value="{{ old('nama_terminal', $terminal->nama_terminal) }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                required>
                            @error('nama_terminal')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="nama_kota" class="block text-sm font-medium text-gray-700">Nama Kota</label>
                            <input type="text" name="nama_kota" id="nama_kota"
                                value="{{ old('nama_kota', $terminal->nama_kota) }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                required>
                            @error('nama_kota')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat</label>
                            <textarea name="alamat" id="alamat"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('alamat', $terminal->alamat) }}</textarea>
                            @error('alamat')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="foto" class="block text-sm font-medium text-gray-700">Foto Terminal</label>
                            @if($terminal->photos->count() > 0)
                                <div class="mt-2 grid grid-cols-2 md:grid-cols-3 gap-4">
                                    @foreach($terminal->photos as $photo)
                                        <img src="{{ asset('storage/' . $photo->path) }}" alt="Foto Terminal"
                                            class="w-full h-32 object-cover rounded">
                                    @endforeach
                                </div>
                            @endif
                            <input type="file" name="foto[]" id="foto" multiple
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                accept="image/*">
                            @error('foto')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">Pilih satu atau lebih foto baru untuk mengganti yang
                                lama (maksimal 2MB per foto)</p>
                        </div>

                        <div class="flex items-center justify-between">
                            <button type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Update
                            </button>
                            <a href="{{ route('admin/terminal.index') }}"
                                class="text-gray-600 hover:text-gray-900">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>