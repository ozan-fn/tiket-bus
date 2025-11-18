<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Fasilitas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-4">
                        <strong>ID:</strong> {{ $fasilitas->id }}
                    </div>
                    <div class="mb-4">
                        <strong>Nama:</strong> {{ $fasilitas->nama }}
                    </div>
                    <div class="mb-4">
                        <strong>Dibuat:</strong> {{ $fasilitas->created_at }}
                    </div>
                    <div class="mb-4">
                        <strong>Diupdate:</strong> {{ $fasilitas->updated_at }}
                    </div>

                    <div class="flex items-center justify-between">
                        <a href="{{ route('admin/fasilitas.edit', $fasilitas) }}"
                            class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                            Edit
                        </a>
                        <a href="{{ route('admin/fasilitas.index') }}"
                            class="text-gray-600 hover:text-gray-900">Kembali</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>