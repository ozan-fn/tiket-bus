<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="mb-6">
        <h2 class="text-2xl font-bold text-foreground">{{ __('Masuk') }}</h2>
        <p class="text-sm text-muted-foreground mt-1">{{ __('Silakan masuk ke akun Anda untuk melanjutkan') }}</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- Email Address -->
        <div class="space-y-2">
            <x-ui.label for="email">{{ __('Email') }}</x-ui.label>
            <x-ui.input
                id="email"
                type="email"
                name="email"
                :value="old('email')"
                required
                autofocus
                autocomplete="username"
                placeholder="nama@example.com"
                class="bg-background text-foreground placeholder:text-muted-foreground" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-destructive" />
        </div>

        <!-- Password -->
        <div class="space-y-2">
            <x-ui.label for="password">{{ __('Password') }}</x-ui.label>
            <x-ui.input
                id="password"
                type="password"
                name="password"
                required
                autocomplete="current-password"
                placeholder="••••••••"
                class="bg-background text-foreground placeholder:text-muted-foreground" />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-destructive" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input
                    id="remember_me"
                    type="checkbox"
                    class="w-4 h-4 rounded border-input text-primary bg-background border focus:ring-2 focus:ring-primary"
                    name="remember">
                <span class="ms-2 text-sm text-muted-foreground">{{ __('Ingat saya') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a
                    class="text-sm text-primary hover:opacity-80 font-medium transition"
                    href="{{ route('password.request') }}">
                    {{ __('Lupa password?') }}
                </a>
            @endif
        </div>

        <!-- Submit Button -->
        <div class="pt-4">
            <x-ui.button.button class="w-full justify-center">
                {{ __('Masuk') }}
            </x-ui.button.button>
        </div>

        <!-- Register Link -->
        <div class="text-center pt-4 border-t border-border">
            <p class="text-sm text-muted-foreground">
                {{ __('Belum memiliki akun?') }}
                <a href="{{ route('register') }}" class="text-primary font-medium hover:opacity-80 transition">
                    {{ __('Daftar di sini') }}
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>
