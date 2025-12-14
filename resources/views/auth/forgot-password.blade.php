<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-foreground">{{ __('Lupa Password?') }}</h2>
        <p class="text-sm text-muted-foreground mt-1">{{ __('Tidak masalah. Beritahu kami email Anda dan kami akan mengirimkan link untuk mengatur ulang password.') }}</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
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
                placeholder="nama@contoh.com"
                class="bg-background text-foreground placeholder:text-muted-foreground" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-destructive" />
        </div>

        <!-- Submit Button -->
        <div class="pt-4">
            <x-ui.button.button class="w-full justify-center">
                {{ __('Kirim Link Reset Password') }}
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
