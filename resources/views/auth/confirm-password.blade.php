<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-foreground">{{ __('Konfirmasi Password') }}</h2>
        <p class="text-sm text-muted-foreground mt-1">{{ __('Ini adalah area aman. Silakan konfirmasi password Anda untuk melanjutkan.') }}</p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-4">
        @csrf

        <!-- Password -->
        <div class="space-y-2">
            <x-ui.label for="password">{{ __('Password') }}</x-ui.label>
            <x-ui.input
                id="password"
                type="password"
                name="password"
                required
                autocomplete="current-password"
                placeholder="Masukkan password Anda"
                class="bg-background text-foreground placeholder:text-muted-foreground" />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-destructive" />
        </div>

        <!-- Submit Button -->
        <div class="pt-4">
            <x-ui.button.button class="w-full justify-center">
                {{ __('Konfirmasi') }}
            </x-ui.button.button>
        </div>
    </form>
</x-guest-layout>
