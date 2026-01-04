<x-admin-layout>
    <x-slot name="header">
        <x-ui.breadcrumb.breadcrumb>
            <x-ui.breadcrumb.list class="text-xs">
                <x-ui.breadcrumb.item>
                    <x-ui.breadcrumb.link href="{{ route('dashboard') }}">
                        Home
                    </x-ui.breadcrumb.link>
                </x-ui.breadcrumb.item>
                <x-ui.breadcrumb.separator>
                    <i data-lucide="chevron-right" class="w-3.5 h-3.5" ></i>
                </x-ui.breadcrumb.separator>
                <x-ui.breadcrumb.item>
                    <x-ui.breadcrumb.link href="{{ route('admin/user.index') }}">
                        User
                    </x-ui.breadcrumb.link>
                </x-ui.breadcrumb.item>
                <x-ui.breadcrumb.separator>
                    <i data-lucide="chevron-right" class="w-3.5 h-3.5" ></i>
                </x-ui.breadcrumb.separator>
                <x-ui.breadcrumb.item>
                    <x-ui.breadcrumb.page>
                        Detail User - {{ $user->name }}
                    </x-ui.breadcrumb.page>
                </x-ui.breadcrumb.item>
            </x-ui.breadcrumb.list>
        </x-ui.breadcrumb.breadcrumb>
    </x-slot>

    <div class="p-4 sm:p-6">
        <div class="max-w-4xl mx-auto">
            <x-ui.card>
                <x-ui.card.header>
                    <div class="flex items-center justify-between">
                        <div>
                            <x-ui.card.title>Detail User</x-ui.card.title>
                            <x-ui.card.description>Informasi lengkap user</x-ui.card.description>
                        </div>
                        <a href="{{ route('admin/user.index') }}">
                            <x-ui.button variant="outline" size="sm">
                                <i data-lucide="arrow-left" class="w-4 h-4 mr-2" ></i>
                                Kembali
                            </x-ui.button>
                        </a>
                    </div>
                </x-ui.card.header>
                <x-ui.card.content>
                    <div class="space-y-6">
                        <!-- Nama -->
                        <div class="space-y-2">
                            <x-ui.label>Nama Lengkap</x-ui.label>
                            <p class="text-sm font-medium">{{ $user->name }}</p>
                        </div>

                        <!-- Email -->
                        <div class="space-y-2">
                            <x-ui.label>Email</x-ui.label>
                            <p class="text-sm font-medium">{{ $user->email }}</p>
                        </div>

                        <!-- Role -->
                        <div class="space-y-2">
                            <x-ui.label>Role</x-ui.label>
                            <div class="flex flex-wrap gap-2">
                                @foreach($user->roles as $role)
                                    <x-ui.badge variant="secondary">{{ ucfirst($role->name) }}</x-ui.badge>
                                @endforeach
                            </div>
                        </div>

                        <!-- Driver Details -->
                        @if($user->roles->contains('name', 'driver') && $user->sopirs->isNotEmpty())
                            <div class="space-y-6 border-t border-border pt-6">
                                <h3 class="text-lg font-medium">Detail Sopir</h3>

                                <!-- NIK -->
                                <div class="space-y-2">
                                    <x-ui.label>NIK</x-ui.label>
                                    <p class="text-sm font-medium">{{ $user->sopirs->first()->nik }}</p>
                                </div>

                                <!-- Nomor SIM -->
                                <div class="space-y-2">
                                    <x-ui.label>Nomor SIM</x-ui.label>
                                    <p class="text-sm font-medium">{{ $user->sopirs->first()->nomor_sim }}</p>
                                </div>

                                <!-- Alamat -->
                                <div class="space-y-2">
                                    <x-ui.label>Alamat</x-ui.label>
                                    <p class="text-sm font-medium">{{ $user->sopirs->first()->alamat ?? '-' }}</p>
                                </div>

                                <!-- Telepon -->
                                <div class="space-y-2">
                                    <x-ui.label>Telepon</x-ui.label>
                                    <p class="text-sm font-medium">{{ $user->sopirs->first()->telepon ?? '-' }}</p>
                                </div>

                                <!-- Tanggal Lahir -->
                                <div class="space-y-2">
                                    <x-ui.label>Tanggal Lahir</x-ui.label>
                                    <p class="text-sm font-medium">{{ $user->sopirs->first()->tanggal_lahir ? $user->sopirs->first()->tanggal_lahir->format('d M Y') : '-' }}</p>
                                </div>

                                <!-- Status -->
                                <div class="space-y-2">
                                    <x-ui.label>Status</x-ui.label>
                                    <x-ui.badge variant="{{ $user->sopirs->first()->status == 'aktif' ? 'default' : 'secondary' }}">
                                        {{ ucfirst($user->sopirs->first()->status) }}
                                    </x-ui.badge>
                                </div>
                            </div>
                        @endif

                        <!-- Timestamps -->
                        <div class="space-y-4 border-t border-border pt-6">
                            <h3 class="text-lg font-medium">Informasi Tambahan</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <x-ui.label>Dibuat Pada</x-ui.label>
                                    <p class="text-sm font-medium">{{ $user->created_at->format('d M Y H:i') }}</p>
                                </div>
                                <div class="space-y-2">
                                    <x-ui.label>Terakhir Diupdate</x-ui.label>
                                    <p class="text-sm font-medium">{{ $user->updated_at->format('d M Y H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col-reverse sm:flex-row items-center justify-end gap-3 mt-8 pt-6 border-t border-border">
                        <a href="{{ route('admin/user.index') }}" class="w-full sm:w-auto">
                            <x-ui.button type="button" variant="outline" class="w-full sm:w-auto">
                                <i data-lucide="arrow-left" class="w-4 h-4 mr-2" ></i>
                                Kembali
                            </x-ui.button>
                        </a>
                        <a href="{{ route('admin/user.edit', $user) }}" class="w-full sm:w-auto">
                            <x-ui.button class="w-full sm:w-auto">
                                <i data-lucide="pencil" class="w-4 h-4 mr-2" ></i>
                                Edit User
                            </x-ui.button>
                        </a>
                    </div>
                </x-ui.card.content>
            </x-ui.card>
        </div>
    </div>
</x-admin-layout>
