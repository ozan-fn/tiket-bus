<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Jadwal') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-4">
                        <h3 class="text-lg font-medium">Informasi Jadwal</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <strong>ID:</strong> {{ $jadwal->id }}
                        </div>
                        <div>
                            <strong>Bus:</strong> {{ $jadwal->bus->nama ?? '-' }}
                        </div>
                        <div>
                            <strong>Sopir:</strong> {{ $jadwal->sopir->user->name ?? '-' }}
                        </div>
                        <div>
                            <strong>Rute:</strong> {{ $jadwal->rute->asalTerminal->nama_terminal ?? '-' }} →
                            {{ $jadwal->rute->tujuanTerminal->nama_terminal ?? '-' }}
                        </div>
                        <div>
                            <strong>Tanggal Berangkat:</strong> {{ $jadwal->tanggal_berangkat->format('d-m-Y') }}
                        </div>
                        <div>
                            <strong>Jam Berangkat:</strong> {{ $jadwal->jam_berangkat->format('H:i') }}
                        </div>
                        <div>
                            <strong>Status:</strong> {{ $jadwal->status }}
                        </div>
                        <div>
                            <strong>Dibuat Pada:</strong> {{ $jadwal->created_at->format('d-m-Y H:i') }}
                        </div>
                        <div>
                            <strong>Diupdate Pada:</strong> {{ $jadwal->updated_at->format('d-m-Y H:i') }}
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <a href="{{ route('admin/jadwal.index') }}"
                            class="mr-4 text-gray-600 hover:text-gray-900">Kembali</a>
                        <a href="{{ route('admin/jadwal.edit', $jadwal) }}"
                            class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded mr-2">
                            Edit
                        </a>
                        <form method="POST" action="{{ route('admin/jadwal.destroy', $jadwal) }}" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                                onclick="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?')">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>