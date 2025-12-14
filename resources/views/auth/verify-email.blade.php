<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-foreground">{{ __('Verifikasi Email') }}</h2>
        <p class="text-sm text-muted-foreground mt-1">{{ __('Terima kasih telah mendaftar! Sebelum memulai, harap verifikasi alamat email Anda dengan mengklik link yang kami kirimkan.') }}</p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 p-4 bg-accent/10 border border-accent rounded-lg">
            <p class="text-sm font-medium text-accent">
                {{ __('Link verifikasi baru telah dikirim ke alamat email yang Anda daftarkan.') }}
            </p>
        </div>
    @endif

    <div class="space-y-4">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <x-ui.button.button class="w-full justify-center">
                {{ __('Kirim Ulang Email Verifikasi') }}
            </x-ui.button.button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <x-ui.button.button variant="outline" class="w-full justify-center">
                {{ __('Keluar') }}
            </x-ui.button.button>
        </form>
    </div>

    <!-- Info Box -->
    <div class="mt-6 pt-6 border-t border-border">
        <p class="text-xs text-muted-foreground">
            {{ __('Jika Anda tidak menerima email, silakan periksa folder spam atau coba kirim ulang link verifikasi di atas.') }}
        </p>
    </div>
</x-guest-layout>
