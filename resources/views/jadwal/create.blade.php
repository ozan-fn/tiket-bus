<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Jadwal') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('admin/jadwal.store') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="bus_id" class="block text-sm font-medium text-gray-700">Bus</label>
                            <select name="bus_id" id="bus_id"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                required>
                                <option value="">Pilih Bus</option>
                                @foreach($buses as $bus)
                                    <option value="{{ $bus->id }}">{{ $bus->nama }}</option>
                                @endforeach
                            </select>
                            @error('bus_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="sopir_id" class="block text-sm font-medium text-gray-700">Sopir</label>
                            <select name="sopir_id" id="sopir_id"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                required>
                                <option value="">Pilih Sopir</option>
                                @foreach($sopirs as $sopir)
                                    <option value="{{ $sopir->id }}">{{ $sopir->user->name }}</option>
                                @endforeach
                            </select>
                            @error('sopir_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="rute_id" class="block text-sm font-medium text-gray-700">Rute</label>
                            <select name="rute_id" id="rute_id"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                required>
                                <option value="">Pilih Rute</option>
                                @foreach($rutes as $rute)
                                    <option value="{{ $rute->id }}">{{ $rute->asalTerminal->nama_terminal ?? '-' }} →
                                        {{ $rute->tujuanTerminal->nama_terminal ?? '-' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('rute_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="tanggal_berangkat" class="block text-sm font-medium text-gray-700">Tanggal
                                Berangkat</label>
                            <input type="date" name="tanggal_berangkat" id="tanggal_berangkat"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                required>
                            @error('tanggal_berangkat')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="jam_berangkat" class="block text-sm font-medium text-gray-700">Jam
                                Berangkat</label>
                            <input type="time" name="jam_berangkat" id="jam_berangkat"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                required>
                            @error('jam_berangkat')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status" id="status"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                required>
                                <option value="aktif">Aktif</option>
                                <option value="tidak_aktif">Tidak Aktif</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">
                                <input type="checkbox" name="is_recurring" id="is_recurring" value="1" class="mr-2">
                                Buat Jadwal Berulang
                            </label>
                        </div>

                        <div id="recurring-fields" class="hidden mb-4">
                            <div class="mb-4">
                                <label for="recurring_type" class="block text-sm font-medium text-gray-700">Jenis
                                    Berulang</label>
                                <select name="recurring_type" id="recurring_type"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="daily">Harian</option>
                                    <option value="weekly">Mingguan</option>
                                </select>
                                @error('recurring_type')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="recurring_count" class="block text-sm font-medium text-gray-700">Jumlah
                                    Hari/Minggu</label>
                                <input type="number" name="recurring_count" id="recurring_count" min="1" max="90"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                @error('recurring_count')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('admin/jadwal.index') }}"
                                class="mr-4 text-gray-600 hover:text-gray-900">Batal</a>
                            <button type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Simpan
                            </button>
                        </div>
                    </form>

                    <script>
                        document.getElementById('is_recurring').addEventListener('change', function () {
                            const recurringFields = document.getElementById('recurring-fields');
                            if (this.checked) {
                                recurringFields.classList.remove('hidden');
                            } else {
                                recurringFields.classList.add('hidden');
                            }
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>