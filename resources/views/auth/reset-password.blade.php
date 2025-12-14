<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-foreground">{{ __('Atur Ulang Password') }}</h2>
        <p class="text-sm text-muted-foreground mt-1">{{ __('Buat password baru untuk akun Anda') }}</p>
    </div>

    <form
 method="POST" action="{{ route('password.store') }}" class="space-y-4">
        @csrf

        <!-- Password Reset Token -->

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div class="space-y-2">
            <x-ui.label for="email">{{ __('Email') }}</x-ui.label>
            <x-ui.input
                id="email"
                type="email"
                name="email"
                :value="old('email', $request->email)"
                required
                autofocus
                autocomplete="username"
                placeholder="nama@contoh.com"
                class="bg-background text-foreground placeholder:text-muted-foreground" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-destructive" />
        </div>

        <!-- Password -->
        <div class="space-y-2">
            <x-ui.label for="password">{{ __('Password') }}</x-ui.label>
            <x-ui.input
                id="password"
                type="password"
                name="password"
                required
                autocomplete="new-password"
                placeholder="Minimal 8 karakter"
                class="bg-background text-foreground placeholder:text-muted-foreground" />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-destructive" />
        </div>

        <!-- Confirm Password -->
        <div class="space-y-2">
            <x-ui.label for="password_confirmation">{{ __('Konfirmasi Password') }}</x-ui.label>
            <x-ui.input
                id="password_confirmation"
                type="password"
                name="password_confirmation"
                required
                autocomplete="new-password"
                placeholder="Ulangi password Anda"
                class="bg-background text-foreground placeholder:text-muted-foreground" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-destructive" />
        </div>

        <!-- Submit Button -->
        <div class="pt-4">
            <x-ui.button.button class="w-full justify-center">
                {{ __('Atur Ulang Password') }}
            </x-ui.button.button>
        </div>

        <!-- Back to Login Link -->
        <div class="text-center pt-4 border-t border-border">
            <a
                href="{{ route('login') }}"
                class="text-sm text-primary font-medium hover:opacity-80 transition"
            >
                {{ __('Kembali ke halaman login') }}
            </a>
        </div>
    </form>
</x-guest-layout>
