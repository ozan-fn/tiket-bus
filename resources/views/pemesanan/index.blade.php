<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pemesanan Tiket') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="GET" action="{{ route('pemesanan.index') }}" class="mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label for="asal" class="block text-sm font-medium text-gray-700">Terminal Asal</label>
                                <input type="text" name="asal" id="asal" value="{{ request('asal') }}"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="Contoh: Jakarta">
                            </div>
                            <div>
                                <label for="tujuan" class="block text-sm font-medium text-gray-700">Terminal
                                    Tujuan</label>
                                <input type="text" name="tujuan" id="tujuan" value="{{ request('tujuan') }}"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="Contoh: Bandung">
                            </div>
                            <div>
                                <label for="tanggal" class="block text-sm font-medium text-gray-700">Tanggal
                                    Berangkat</label>
                                <x-datepicker name="tanggal" id="tanggal" value="{{ request('tanggal') }}"
                                    placeholder="Pilih tanggal..." class="mt-1" />
                            </div>
                            <div class="flex items-end">
                                <button type="submit"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Cari
                                </button>
                            </div>
                        </div>
                    </form>

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <x-ui.table>
                        <x-ui.table.header>
                            <x-ui.table.row>
                                <x-ui.table.head>Rute</x-ui.table.head>
                                <x-ui.table.head>Bus</x-ui.table.head>
                                <x-ui.table.head>Tanggal & Jam</x-ui.table.head>
                                <x-ui.table.head>Harga</x-ui.table.head>
                                <x-ui.table.head>Aksi</x-ui.table.head>
                            </x-ui.table.row>
                        </x-ui.table.header>
                        <x-ui.table.body>
                            @foreach($jadwals as $jadwal)
                                <x-ui.table.row>
                                    <x-ui.table.cell>
                                        {{ $jadwal->rute->asalTerminal->nama_terminal ?? '-' }} →
                                        {{ $jadwal->rute->tujuanTerminal->nama_terminal ?? '-' }}
                                    </x-ui.table.cell>
                                    <x-ui.table.cell>
                                        {{ $jadwal->bus->nama }}
                                    </x-ui.table.cell>
                                    <x-ui.table.cell>
                                        {{ $jadwal->tanggal_berangkat->format('d-m-Y') }}
                                        {{ $jadwal->jam_berangkat->format('H:i') }}
                                    </x-ui.table.cell>
                                    <x-ui.table.cell>Rp 100.000</x-ui.table.cell>
                                    <x-ui.table.cell>
                                        <a href="{{ route('pemesanan.create', $jadwal) }}"
                                            class="text-indigo-600 hover:text-indigo-900">Pesan</a>
                                    </x-ui.table.cell>
                                </x-ui.table.row>
                            @endforeach
                        </x-ui.table.body>
                    </x-ui.table>

                    <div class="mt-4">
                        {{ $jadwals->links('vendor.pagination.shadcn') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
