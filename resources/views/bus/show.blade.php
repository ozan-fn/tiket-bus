<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Bus') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-4">
                        <strong>ID:</strong> {{ $bus->id }}
                    </div>
                    <div class="mb-4">
                        <strong>Nama:</strong> {{ $bus->nama }}
                    </div>
                    <div class="mb-4">
                        <strong>Kapasitas:</strong> {{ $bus->kapasitas }}
                    </div>
                    <div class="mb-4">
                        <strong>Plat Nomor:</strong> {{ $bus->plat_nomor }}
                    </div>
                    <div class="mb-4">
                        <strong>Fasilitas:</strong>
                        <ul class="list-disc list-inside">
                            @forelse($bus->fasilitas as $fasilitas)
                                <li>{{ $fasilitas->nama }}</li>
                            @empty
                                <li>Tidak ada fasilitas</li>
                            @endforelse
                        </ul>
                    </div>
                    <div class="mb-4">
                        <strong>Foto:</strong>
                        @if($bus->photos->count() > 0)
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mt-2">
                                @foreach($bus->photos as $photo)
                                    <img src="{{ asset('storage/' . $photo->path) }}" alt="Foto Bus"
                                        class="w-full h-32 object-cover rounded">
                                @endforeach
                            </div>
                        @else
                            <p>Tidak ada foto</p>
                        @endif
                    </div>
                    <div class="mb-4">
                        <strong>Dibuat:</strong> {{ $bus->created_at }}
                    </div>
                    <div class="mb-4">
                        <strong>Diupdate:</strong> {{ $bus->updated_at }}
                    </div>

                    <div class="flex items-center justify-between">
                        <a href="{{ route('admin/bus.edit', $bus) }}"
                            class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                            Edit
                        </a>
                        <a href="{{ route('admin/bus.index') }}" class="text-gray-600 hover:text-gray-900">Kembali</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>