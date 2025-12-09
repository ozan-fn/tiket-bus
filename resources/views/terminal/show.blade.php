<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Terminal') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-4">
                        <strong>Nama Terminal:</strong> {{ $terminal->nama_terminal }}
                    </div>
                    <div class="mb-4">
                        <strong>Nama Kota:</strong> {{ $terminal->nama_kota }}
                    </div>
                    <div class="mb-4">
                        <strong>Alamat:</strong> {{ $terminal->alamat ?? '-' }}
                    </div>
                    <div class="mb-4">
                        <strong>Foto:</strong>
                        @if($terminal->photos->count() > 0)
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mt-2">
                                @foreach($terminal->photos as $photo)
                                    <img src="{{ asset('storage/' . $photo->path) }}" alt="Foto Terminal"
                                        class="w-full h-32 object-cover rounded">
                                @endforeach
                            </div>
                        @else
                            <p>Tidak ada foto</p>
                        @endif
                    </div>
                    <div class="mb-4">
                        <strong>Dibuat:</strong> {{ $terminal->created_at }}
                    </div>
                    <div class="mb-4">
                        <strong>Diupdate:</strong> {{ $terminal->updated_at }}
                    </div>

                    <div class="flex items-center justify-between">
                        <a href="{{ route('admin/terminal.edit', $terminal) }}"
                            class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                            Edit
                        </a>
                        <a href="{{ route('admin/terminal.index') }}"
                            class="text-gray-600 hover:text-gray-900">Kembali</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>