<section>
    <form method="post" action="{{ route('password.update') }}" class="space-y-6">
        @csrf
        @method('put')

        <div class="space-y-2">
            <x-ui.label for="update_password_current_password">{{ __('Kata Sandi Saat Ini') }}</x-ui.label>
            <x-ui.input id="update_password_current_password" name="current_password" type="password"
                class="block w-full" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div class="space-y-2">
            <x-ui.label for="update_password_password">{{ __('Kata Sandi Baru') }}</x-ui.label>
            <x-ui.input id="update_password_password" name="password" type="password" class="block w-full"
                autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div class="space-y-2">
            <x-ui.label for="update_password_password_confirmation">{{ __('Konfirmasi Kata Sandi') }}</x-ui.label>
            <x-ui.input id="update_password_password_confirmation" name="password_confirmation" type="password"
                class="block w-full" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <x-ui.button>{{ __('Simpan') }}</x-ui.button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-muted-foreground">{{ __('Tersimpan.') }}</p>
            @endif
        </div>
    </form>
</section>