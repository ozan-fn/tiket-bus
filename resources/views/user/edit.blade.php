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
                    <x-lucide-chevron-right class="w-3.5 h-3.5" />
                </x-ui.breadcrumb.separator>
                <x-ui.breadcrumb.item>
                    <x-ui.breadcrumb.link href="{{ route('admin/user.index') }}">
                        User
                    </x-ui.breadcrumb.link>
                </x-ui.breadcrumb.item>
                <x-ui.breadcrumb.separator>
                    <x-lucide-chevron-right class="w-3.5 h-3.5" />
                </x-ui.breadcrumb.separator>
                <x-ui.breadcrumb.item>
                    <x-ui.breadcrumb.page>
                        Edit User
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
                            <x-ui.card.title>Edit User</x-ui.card.title>
                            <x-ui.card.description>Perbarui informasi user</x-ui.card.description>
                        </div>
                        <a href="{{ route('admin/user.index') }}">
                            <x-ui.button variant="outline" size="sm">
                                <x-lucide-arrow-left class="w-4 h-4 mr-2" />
                                Kembali
                            </x-ui.button>
                        </a>
                    </div>
                </x-ui.card.header>
                <x-ui.card.content>
                    <form method="POST" action="{{ route('admin/user.update', $user) }}" x-data="{ selectedRole: '{{ old('role', $user->roles->first()->name ?? '') }}', initialRole: '{{ old('role', $user->roles->first()->name ?? '') }}' }" novalidate>
                        @csrf
                        @method('PUT')

                        <div class="space-y-6">
                            <!-- Nama -->
                            <div class="space-y-2">
                                <x-ui.label for="name">
                                    Nama Lengkap
                                    <span class="text-red-500">*</span>
                                </x-ui.label>
                                <x-ui.input
                                    type="text"
                                    id="name"
                                    name="name"
                                    value="{{ old('name', $user->name) }}"
                                    placeholder="Masukkan nama lengkap"
                                    required
                                />
                                @error('name')
                                    <p class="text-sm text-destructive mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="space-y-2">
                                <x-ui.label for="email">
                                    Email
                                    <span class="text-red-500">*</span>
                                </x-ui.label>
                                <x-ui.input
                                    type="email"
                                    id="email"
                                    name="email"
                                    value="{{ old('email', $user->email) }}"
                                    placeholder="Masukkan email"
                                    required
                                />
                                @error('email')
                                    <p class="text-sm text-destructive mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div class="space-y-2">
                                <x-ui.label for="password">
                                    Password Baru
                                </x-ui.label>
                                <x-ui.input
                                    type="password"
                                    id="password"
                                    name="password"
                                    placeholder="Masukkan password baru (opsional)"
                                />
                                @error('password')
                                    <p class="text-sm text-destructive mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Role -->
                            <div class="space-y-2">
                                <x-ui.label for="role">
                                    Role
                                    <span class="text-red-500">*</span>
                                </x-ui.label>
                                <select
                                    name="role"
                                    id="role"
                                    required
                                    class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring"
                                    x-model="selectedRole">
                                    @foreach($roles as $role)
                                        <option value="{{ $role->name }}" {{ old('role', $user->roles->first()->name ?? '') == $role->name ? 'selected' : '' }}>
                                            {{ ucfirst($role->name) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('role')
                                    <p class="text-sm text-destructive mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Driver Details -->
                            <div x-show="selectedRole === 'driver'" x-cloak class="space-y-6 border-t border-border pt-6">
                                <h3 class="text-lg font-medium">Detail Sopir</h3>

                                <!-- NIK -->
                                <div class="space-y-2">
                                    <x-ui.label for="nik">
                                        <div class="flex items-center gap-2">
                                            <x-lucide-id-card class="w-4 h-4" />
                                            NIK
                                        </div>
                                        <span class="text-red-500">*</span>
                                    </x-ui.label>
                                    <x-ui.input
                                        type="text"
                                        id="nik"
                                        name="nik"
                                        value="{{ old('nik', $sopir?->nik ?? '') }}"
                                        placeholder="Masukkan NIK"
                                        x-bind:required="selectedRole === 'driver'"
                                        x-bind:disabled="selectedRole !== 'driver'"
                                        @if(old('role', $user->roles->first()->name ?? '') !== 'driver') disabled @endif
                                    />
                                    @error('nik')
                                        <p class="text-sm text-destructive mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Nomor SIM -->
                                <div class="space-y-2">
                                    <x-ui.label for="nomor_sim">
                                        <div class="flex items-center gap-2">
                                            <x-lucide-file-text class="w-4 h-4" />
                                            Nomor SIM
                                        </div>
                                        <span class="text-red-500">*</span>
                                    </x-ui.label>
                                    <x-ui.input
                                        type="text"
                                        id="nomor_sim"
                                        name="nomor_sim"
                                        value="{{ old('nomor_sim', $sopir?->nomor_sim ?? '') }}"
                                        placeholder="Masukkan nomor SIM"
                                        x-bind:required="selectedRole === 'driver'"
                                        x-bind:disabled="selectedRole !== 'driver'"
                                        @if(old('role', $user->roles->first()->name ?? '') !== 'driver') disabled @endif
                                    />
                                    @error('nomor_sim')
                                        <p class="text-sm text-destructive mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Alamat -->
                                <div class="space-y-2">
                                    <x-ui.label for="alamat">
                                        <div class="flex items-center gap-2">
                                            <x-lucide-map-pin class="w-4 h-4" />
                                            Alamat
                                        </div>
                                    </x-ui.label>
                                    <x-ui.input
                                        type="text"
                                        id="alamat"
                                        name="alamat"
                                        value="{{ old('alamat', $sopir?->alamat ?? '') }}"
                                        placeholder="Masukkan alamat"
                                        x-bind:disabled="selectedRole !== 'driver'"
                                        @if(old('role', $user->roles->first()->name ?? '') !== 'driver') disabled @endif
                                    />
                                    @error('alamat')
                                        <p class="text-sm text-destructive mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Telepon -->
                                <div class="space-y-2">
                                    <x-ui.label for="telepon">
                                        <div class="flex items-center gap-2">
                                            <x-lucide-phone class="w-4 h-4" />
                                            Telepon
                                        </div>
                                    </x-ui.label>
                                    <x-ui.input
                                        type="text"
                                        id="telepon"
                                        name="telepon"
                                        value="{{ old('telepon', $sopir?->telepon ?? '') }}"
                                        placeholder="Masukkan nomor telepon"
                                        x-bind:disabled="selectedRole !== 'driver'"
                                        @if(old('role', $user->roles->first()->name ?? '') !== 'driver') disabled @endif
                                    />
                                    @error('telepon')
                                        <p class="text-sm text-destructive mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Tanggal Lahir -->
                                <div class="space-y-2">
                                    <x-ui.label for="tanggal_lahir">
                                        <div class="flex items-center gap-2">
                                            <x-lucide-calendar class="w-4 h-4" />
                                            Tanggal Lahir
                                        </div>
                                        <span class="text-red-500">*</span>
                                    </x-ui.label>
                                    <x-datepicker
                                        name="tanggal_lahir"
                                        id="tanggal_lahir"
                                        value="{{ old('tanggal_lahir', $sopir?->tanggal_lahir?->format('Y-m-d') ?? '') }}"
                                        placeholder="Pilih tanggal..."
                                        x-bind:required="selectedRole === 'driver'"
                                        x-bind:disabled="selectedRole !== 'driver'"
                                        @if(old('role', $user->roles->first()->name ?? '') !== 'driver') disabled @endif
                                    />
                                    @error('tanggal_lahir')
                                        <p class="text-sm text-destructive mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Status -->
                                <div class="space-y-2">
                                    <x-ui.label for="status">
                                        <div class="flex items-center gap-2">
                                            <x-lucide-check-circle class="w-4 h-4" />
                                            Status
                                        </div>
                                        <span class="text-red-500">*</span>
                                    </x-ui.label>
                                    <select
                                        name="status"
                                        id="status"
                                        x-bind:required="selectedRole === 'driver'"
                                        x-bind:disabled="selectedRole !== 'driver'"
                                        @if(old('role', $user->roles->first()->name ?? '') !== 'driver') disabled @endif
                                        class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                                        <option value="">Pilih Status</option>
                                        <option value="aktif" {{ old('status', $sopir?->status ?? '') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                        <option value="tidak_aktif" {{ old('status', $sopir?->status ?? '') == 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                                    </select>
                                    @error('status')
                                        <p class="text-sm text-destructive mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col-reverse sm:flex-row items-center justify-end gap-3 mt-8 pt-6 border-t border-border">
                            <a href="{{ route('admin/user.index') }}" class="w-full sm:w-auto">
                                <x-ui.button type="button" variant="outline" class="w-full sm:w-auto">
                                    <x-lucide-x class="w-4 h-4 mr-2" />
                                    Batal
                                </x-ui.button>
                            </a>
                            <x-ui.button type="submit" class="w-full sm:w-auto">
                                <x-lucide-save class="w-4 h-4 mr-2" />
                                Update User
                            </x-ui.button>
                        </div>
                    </form>
                </x-ui.card.content>
            </x-ui.card>
        </div>
    </div>
</x-admin-layout>
