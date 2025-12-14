<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-foreground">{{ __('Daftar Akun') }}</h2>
        <p class="text-sm text-muted-foreground mt-1">{{ __('Buat akun baru untuk mengakses sistem') }}</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <!-- Name -->
        <div class="space-y-2">
            <x-ui.label for="name">{{ __('Nama Lengkap') }}</x-ui.label>
            <x-ui.input
                id="name"
                type="text"
                name="name"
                :value="old('name')"
                required
                autofocus
                autocomplete="name"
                placeholder="Masukkan nama lengkap Anda"
                class="bg-background text-foreground placeholder:text-muted-foreground" />
            <x-input-error :messages="$errors->get('name')" class="mt-2 text-destructive" />
        </div>

        <!-- Email Address -->
        <div class="space-y-2">
            <x-ui.label for="email">{{ __('Email') }}</x-ui.label>
            <x-ui.input
                id="email"
                type="email"
                name="email"
                :value="old('email')"
                required
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
                {{ __('Daftar') }}
            </x-ui.button.button>
        </div>

        <!-- Login Link -->
        <div class="text-center pt-4 border-t border-border
">
            <p class="text-sm text-muted-foreground">
                {{ __('Sudah memiliki akun?') }}
                <a
                    href="{{ route('login') }}"
                    class="text-primary font-medium hover:opacity-80 transition"
                >
                    {{ __('Masuk di sini') }}
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>
