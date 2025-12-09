<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Sopir') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-4">
                        <strong>Nama:</strong> {{ $sopir->user->name ?? 'N/A' }}
                    </div>
                    <div class="mb-4">
                        <strong>Email:</strong> {{ $sopir->user->email ?? 'N/A' }}
                    </div>
                    <div class="mb-4">
                        <strong>NIK:</strong> {{ $sopir->nik }}
                    </div>
                    <div class="mb-4">
                        <strong>Nomor SIM:</strong> {{ $sopir->nomor_sim }}
                    </div>
                    <div class="mb-4">
                        <strong>Alamat:</strong> {{ $sopir->alamat ?? '-' }}
                    </div>
                    <div class="mb-4">
                        <strong>Telepon:</strong> {{ $sopir->telepon ?? '-' }}
                    </div>
                    <div class="mb-4">
                        <strong>Tanggal Lahir:</strong>
                        {{ $sopir->tanggal_lahir ? $sopir->tanggal_lahir->format('d-m-Y') : '-' }}
                    </div>
                    <div class="mb-4">
                        <strong>Status:</strong> {{ $sopir->status }}
                    </div>
                    <div class="mb-4">
                        <strong>Dibuat:</strong> {{ $sopir->created_at }}
                    </div>
                    <div class="mb-4">
                        <strong>Diupdate:</strong> {{ $sopir->updated_at }}
                    </div>

                    <div class="flex items-center justify-between">
                        <a href="{{ route('admin/sopir.edit', $sopir) }}"
                            class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                            Edit
                        </a>
                        <a href="{{ route('admin/sopir.index') }}" class="text-gray-600 hover:text-gray-900">Kembali</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>