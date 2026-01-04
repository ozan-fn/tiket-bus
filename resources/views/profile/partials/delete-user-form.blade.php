<section class="space-y-6">
    <x-ui.button variant="destructive" x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')">{{ __('Hapus Akun') }}</x-ui.button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-medium text-gray-900 dark:text-white">
                {{ __('Apakah Anda yakin ingin menghapus akun Anda?') }}
            </h2>

            <p class="mt-1 text-sm text-muted-foreground">
                {{ __('Setelah akun Anda dihapus, semua sumber daya dan datanya akan dihapus secara permanen. Silakan masukkan kata sandi Anda untuk mengonfirmasi bahwa Anda ingin menghapus akun Anda secara permanen.') }}
            </p>

            <div class="mt-6 space-y-2">
                <x-ui.label for="password" value="{{ __('Kata Sandi') }}" class="sr-only" />

                <x-ui.input id="password" name="password" type="password" class="mt-1 block w-3/4"
                    placeholder="{{ __('Kata Sandi') }}" />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <x-ui.button variant="outline" x-on:click="$dispatch('close')">
                    {{ __('Batal') }}
                </x-ui.button>

                <x-ui.button variant="destructive">
                    {{ __('Hapus Akun') }}
                </x-ui.button>
            </div>
        </form>
    </x-modal>
</section>