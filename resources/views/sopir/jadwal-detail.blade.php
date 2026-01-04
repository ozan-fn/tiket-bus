<x-admin-layout>
    <x-slot name="header">
        <x-ui.breadcrumb.breadcrumb>
            <x-ui.breadcrumb.list class="text-xs">
                <x-ui.breadcrumb.item>
                    <x-ui.breadcrumb.link href="{{ route('dashboard') }}">
                        Dashboard
                    </x-ui.breadcrumb.link>
                </x-ui.breadcrumb.item>
                <x-ui.breadcrumb.separator>
                    <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                </x-ui.breadcrumb.separator>
                <x-ui.breadcrumb.item>
                    <x-ui.breadcrumb.page>
                        Detail Jadwal
                    </x-ui.breadcrumb.page>
                </x-ui.breadcrumb.item>
            </x-ui.breadcrumb.list>
        </x-ui.breadcrumb.breadcrumb>
    </x-slot>

    <div class="p-4 sm:p-6">
        <div class="max-w-7xl mx-auto space-y-6">
            <!-- Header Jadwal -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800">{{ $jadwal->rute->asal }} →
                                {{ $jadwal->rute->tujuan }}</h1>
                            <p class="text-gray-600 text-sm mt-1">Jadwal:
                                {{ $jadwal->tanggal_berangkat->format('d M Y H:i') }}</p>
                        </div>
                        <div class="text-right">
                            <span
                                class="inline-block px-4 py-2 rounded-full font-semibold {{ $jadwal->status === 'aktif' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($jadwal->status) }}
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div>
                            <p class="text-sm text-gray-600 font-medium">Bus</p>
                            <p class="font-semibold text-gray-800">{{ $jadwal->bus->nama }}</p>
                            <p class="text-xs text-gray-500">{{ $jadwal->bus->plat_nomor }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 font-medium">Kapasitas</p>
                            <p class="font-semibold text-gray-800">{{ $jadwal->bus->kapasitas }} kursi</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 font-medium">Tanggal Berangkat</p>
                            <p class="font-semibold text-gray-800">{{ $jadwal->tanggal_berangkat->format('d M Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 font-medium">Jam Berangkat</p>
                            <p class="font-semibold text-gray-800">{{ $jadwal->jam_berangkat->format('H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistik Kursi -->
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                <div class="bg-white rounded-lg shadow p-4 border-l-4 border-blue-500">
                    <p class="text-xs text-gray-600 font-medium">Total Kursi</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $kursiStats['total'] }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-4 border-l-4 border-green-500">
                    <p class="text-xs text-gray-600 font-medium">Penumpang Hadir</p>
                    <p class="text-2xl font-bold text-green-600">{{ $kursiStats['hadir'] }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-4 border-l-4 border-yellow-500">
                    <p class="text-xs text-gray-600 font-medium">Dipesan</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ $kursiStats['dipesan'] }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-4 border-l-4 border-red-500">
                    <p class="text-xs text-gray-600 font-medium">Tidak Hadir</p>
                    <p class="text-2xl font-bold text-red-600">{{ $kursiStats['tidak_hadir'] }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-4 border-l-4 border-gray-500">
                    <p class="text-xs text-gray-600 font-medium">Kosong</p>
                    <p class="text-2xl font-bold text-gray-600">{{ $kursiStats['kosong'] }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Scan Tiket -->
                <div id="scan" class="bg-white rounded-lg shadow">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                            <i data-lucide="barcode" class="w-5 h-5"></i>
                            Scan Tiket Penumpang
                        </h3>
                    </div>
                    <div class="p-6">
                        <form id="scanForm" class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Kode Tiket</label>
                                <input type="text" id="kodeTiket" placeholder="Masukkan atau scan kode tiket"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                    autocomplete="off">
                            </div>
                            <button type="button" onclick="scanTiketHandler()"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition">
                                <i data-lucide="check" class="w-4 h-4 inline-block mr-2"></i>
                                Scan
                            </button>
                        </form>

                        <!-- Hasil Scan -->
                        <div id="scanResult" class="mt-6 hidden">
                            <div id="scanMessage" class="p-4 rounded-lg mb-4"></div>
                            <div id="scanDetails" class="space-y-3 p-4 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="text-xs text-gray-600">Nama Penumpang</p>
                                    <p id="namaPenumpang" class="font-semibold text-gray-800"></p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-600">Kode Tiket</p>
                                    <p id="detailKodeTiket" class="font-semibold text-gray-800 font-mono"></p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-600">Nomor Kursi</p>
                                    <p id="nomorKursi" class="font-semibold text-gray-800"></p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-600">Waktu Scan</p>
                                    <p id="waktuScan" class="font-semibold text-gray-800"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Daftar Penumpang -->
                <div class="lg:col-span-2 bg-white rounded-lg shadow">
                    <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                            <i data-lucide="users" class="w-5 h-5"></i>
                            Daftar Penumpang
                        </h3>
                        <button type="button" onclick="loadKursiStatus()"
                            class="text-blue-600 hover:text-blue-700 font-medium text-sm flex items-center gap-1">
                            <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                            Refresh
                        </button>
                    </div>
                    <div class="p-6 overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-gray-200">
                                    <th class="text-left py-3 px-4 font-semibold text-gray-700">Kursi</th>
                                    <th class="text-left py-3 px-4 font-semibold text-gray-700">Kelas</th>
                                    <th class="text-left py-3 px-4 font-semibold text-gray-700">Nama Penumpang</th>
                                    <th class="text-left py-3 px-4 font-semibold text-gray-700">Status</th>
                                </tr>
                            </thead>
                            <tbody id="penumpangList" class="divide-y divide-gray-200">
                                <!-- Diisi oleh JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Layout Kursi Bus -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                        <i data-lucide="layout" class="w-5 h-5"></i>
                        Tata Letak Kursi
                    </h3>
                </div>
                <div class="p-6">
                    <div id="kursiLayout" class="grid gap-8">
                        @foreach ($jadwal->jadwalKelasBus as $jkb)
                            <div>
                                <h4 class="font-semibold text-gray-800 mb-4">Kelas: {{ $jkb->kelasBus->nama }}</h4>
                                <div class="grid grid-cols-4 md:grid-cols-6 gap-3">
                                    @foreach ($jkb->kursi as $kursi)
                                        @php
                                            $tiketAktif = $kursi->tikets->where('status', 'dibayar')->first();
                                            $statusClass = 'bg-gray-200';
                                            $statusText = 'Kosong';
                                            $statusIcon = 'circle';

                                            if ($tiketAktif) {
                                                if ($tiketAktif->is_hadir) {
                                                    $statusClass = 'bg-green-500';
                                                    $statusText = 'Hadir';
                                                    $statusIcon = 'check-circle';
                                                } else {
                                                    $statusClass = 'bg-yellow-500';
                                                    $statusText = 'Dipesan';
                                                    $statusIcon = 'clock';
                                                }
                                            }
                                        @endphp
                                        <button type="button"
                                            title="{{ $statusText }}{{ $tiketAktif ? ': ' . $tiketAktif->nama_penumpang : '' }}"
                                            onclick="showKursiDetail({{ $kursi->id }}, '{{ $kursi->nomor_kursi }}', '{{ $statusText }}', {{ $tiketAktif ? 'true' : 'false' }})"
                                            class="aspect-square rounded-lg {{ $statusClass }} text-white font-semibold text-center flex flex-col items-center justify-center hover:opacity-80 transition transform hover:scale-105">
                                            <span class="text-sm">{{ $kursi->nomor_kursi }}</span>
                                            <i data-lucide="{{ $statusIcon }}" class="w-4 h-4 mt-1"></i>
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Legend -->
                    <div class="mt-8 p-4 bg-gray-50 rounded-lg">
                        <p class="font-semibold text-gray-800 mb-3">Keterangan:</p>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded bg-green-500"></div>
                                <span class="text-sm text-gray-700">Hadir</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded bg-yellow-500"></div>
                                <span class="text-sm text-gray-700">Dipesan</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded bg-gray-200"></div>
                                <span class="text-sm text-gray-700">Kosong</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function scanTiketHandler() {
            const kodeTiket = document.getElementById('kodeTiket').value.trim();

            if (!kodeTiket) {
                showScanMessage('Masukkan kode tiket terlebih dahulu', 'error');
                return;
            }

            fetch('{{ route("sopir.jadwal.scan", $jadwal) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ kode_tiket: kodeTiket })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showScanMessage(data.message, data.already_scanned ? 'warning' : 'success');
                        displayScanResult(data.tiket);
                        document.getElementById('kodeTiket').value = '';
                        loadKursiStatus();
                        setTimeout(() => document.getElementById('kodeTiket').focus(), 500);
                    } else {
                        showScanMessage(data.message, 'error');
                        document.getElementById('kodeTiket').value = '';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showScanMessage('Terjadi kesalahan', 'error');
                });
        }

        function showScanMessage(message, type) {
            const resultDiv = document.getElementById('scanResult');
            const messageDiv = document.getElementById('scanMessage');

            const bgClass = type === 'success' ? 'bg-green-100 text-green-800 border-green-300' :
                type === 'warning' ? 'bg-yellow-100 text-yellow-800 border-yellow-300' :
                    'bg-red-100 text-red-800 border-red-300';

            messageDiv.className = `p-4 rounded-lg border ${bgClass}`;
            messageDiv.innerHTML = `<strong>${message}</strong>`;
            resultDiv.classList.remove('hidden');
        }

        function displayScanResult(tiket) {
            document.getElementById('namaPenumpang').textContent = tiket.nama_penumpang;
            document.getElementById('detailKodeTiket').textContent = tiket.kode_tiket;
            document.getElementById('nomorKursi').textContent = tiket.nomor_kursi || '-';
            document.getElementById('waktuScan').textContent = tiket.waktu_scan || '-';
        }

        function loadKursiStatus() {
            fetch('{{ route("sopir.jadwal.kursi-status", $jadwal) }}')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updatePenumpangList(data.kursi);
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        function updatePenumpangList(kursiList) {
            const listDiv = document.getElementById('penumpangList');
            listDiv.innerHTML = '';

            kursiList.forEach(kursi => {
                const row = document.createElement('tr');
                const statusBadge =
                    kursi.status === 'hadir' ? '<span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">Hadir</span>' :
                        kursi.status === 'dipesan' ? '<span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">Dipesan</span>' :
                            '<span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">Kosong</span>';

                row.innerHTML = `
                    <td class="py-3 px-4 font-semibold text-gray-800">${kursi.nomor}</td>
                    <td class="py-3 px-4 text-gray-600">${kursi.kelas}</td>
                    <td class="py-3 px-4 text-gray-600">${kursi.penumpang ? kursi.penumpang.nama : '-'}</td>
                    <td class="py-3 px-4">${statusBadge}</td>
                `;
                listDiv.appendChild(row);
            });
        }

        function showKursiDetail(kursiId, nomor, status, hasPenumpang) {
            // Optional: bisa di-expand untuk show detail lebih lengkap
            console.log(`Kursi ${nomor}: ${status}`);
        }

        // Load data on page load
        document.addEventListener('DOMContentLoaded', function () {
            loadKursiStatus();
            document.getElementById('kodeTiket').focus();
        });

        // Allow Enter key to scan
        document.getElementById('kodeTiket')?.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                scanTiketHandler();
            }
        });
    </script>
</x-admin-layout>