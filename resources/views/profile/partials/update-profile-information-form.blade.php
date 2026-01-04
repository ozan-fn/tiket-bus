<section>
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
        @csrf
        @method('patch')

        <div class="space-y-2">
            <x-ui.label for="name">{{ __('Nama') }}</x-ui.label>
            <x-ui.input id="name" name="name" type="text" class="block w-full" :value="old('name', $user->name)"
                required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div class="space-y-2">
            <x-ui.label for="email">{{ __('Email') }}</x-ui.label>
            <x-ui.input id="email" name="email" type="email" class="block w-full" :value="old('email', $user->email)"
                required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Alamat email Anda belum diverifikasi.') }}

                        <button form="send-verification"
                            class="underline text-sm text-muted-foreground hover:text-foreground rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                            {{ __('Klik di sini untuk mengirim ulang email verifikasi.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('Tautan verifikasi baru telah dikirim ke alamat email Anda.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="grid md:grid-cols-2 gap-6">
            <div class="space-y-2">
                <x-ui.label for="nik">{{ __('NIK') }}</x-ui.label>
                <x-ui.input id="nik" name="nik" type="text" class="block w-full" :value="old('nik', $user->nik)"
                    placeholder="Nomor Identitas (KTP)" />
                <x-input-error class="mt-2" :messages="$errors->get('nik')" />
            </div>

            <div class="space-y-2">
                <x-ui.label for="nomor_telepon">{{ __('Nomor Telepon') }}</x-ui.label>
                <x-ui.input id="nomor_telepon" name="nomor_telepon" type="tel" class="block w-full"
                    :value="old('nomor_telepon', $user->nomor_telepon)" placeholder="08..." />
                <x-input-error class="mt-2" :messages="$errors->get('nomor_telepon')" />
            </div>

            <div class="space-y-2">
                <x-ui.label for="jenis_kelamin">{{ __('Jenis Kelamin') }}</x-ui.label>
                <select name="jenis_kelamin" id="jenis_kelamin"
                    class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                    <option value="">Pilih Jenis Kelamin</option>
                    <option value="L" {{ old('jenis_kelamin', $user->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki
                    </option>
                    <option value="P" {{ old('jenis_kelamin', $user->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan
                    </option>
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('jenis_kelamin')" />
            </div>

            <div class="space-y-2">
                <x-ui.label for="tanggal_lahir">{{ __('Tanggal Lahir') }}</x-ui.label>
                <x-datepicker id="tanggal_lahir" name="tanggal_lahir" :value="old('tanggal_lahir', $user->tanggal_lahir?->format('Y-m-d'))" />
                <x-input-error class="mt-2" :messages="$errors->get('tanggal_lahir')" />
            </div>
        </div>

        <div class="flex items-center gap-4">
            <x-ui.button>{{ __('Simpan') }}</x-ui.button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-muted-foreground">{{ __('Tersimpan.') }}</p>
            @endif
        </div>
    </form>
</section>