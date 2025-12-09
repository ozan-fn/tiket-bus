<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Rute') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('admin/rute.store') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="asal_terminal_id" class="block text-sm font-medium text-gray-700">Terminal
                                Asal</label>
                            <select name="asal_terminal_id" id="asal_terminal_id"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                required>
                                <option value="">Pilih Terminal Asal</option>
                                @foreach($terminals as $terminal)
                                    <option value="{{ $terminal->id }}" {{ old('asal_terminal_id') == $terminal->id ? 'selected' : '' }}>{{ $terminal->nama_terminal }} ({{ $terminal->nama_kota }})
                                    </option>
                                @endforeach
                            </select>
                            @error('asal_terminal_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="tujuan_terminal_id" class="block text-sm font-medium text-gray-700">Terminal
                                Tujuan</label>
                            <select name="tujuan_terminal_id" id="tujuan_terminal_id"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                required>
                                <option value="">Pilih Terminal Tujuan</option>
                                @foreach($terminals as $terminal)
                                    <option value="{{ $terminal->id }}" {{ old('tujuan_terminal_id') == $terminal->id ? 'selected' : '' }}>{{ $terminal->nama_terminal }} ({{ $terminal->nama_kota }})
                                    </option>
                                @endforeach
                            </select>
                            @error('tujuan_terminal_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-between">
                            <button type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Simpan
                            </button>
                            <a href="{{ route('admin/rute.index') }}"
                                class="text-gray-600 hover:text-gray-900">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>