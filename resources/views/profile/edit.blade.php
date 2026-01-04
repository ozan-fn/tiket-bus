@extends('layouts.admin')
@section('content')
    @push('header')
        <h2 class="text-xl font-bold text-gray-900 dark:text-white">Profil Saya</h2>
    @endpush

    <div class="p-6 space-y-6">
        <div>
            <h1 class="text-2xl font-bold tracking-tight">Pengaturan Akun</h1>
            <p class="text-muted-foreground">Kelola informasi profil dan keamanan akun Anda</p>
        </div>

        <div class="grid gap-6 max-w-4xl">
            <x-ui.card>
                <x-ui.card.header>
                    <x-ui.card.title>Informasi Profil</x-ui.card.title>
                    <x-ui.card.description>Perbarui informasi profil dan alamat email akun Anda.</x-ui.card.description>
                </x-ui.card.header>
                <x-ui.card.content>
                    @include('profile.partials.update-profile-information-form')
                </x-ui.card.content>
            </x-ui.card>

            <x-ui.card>
                <x-ui.card.header>
                    <x-ui.card.title>Perbarui Kata Sandi</x-ui.card.title>
                    <x-ui.card.description>Pastikan akun Anda menggunakan kata sandi yang panjang dan acak agar tetap
                        aman.</x-ui.card.description>
                </x-ui.card.header>
                <x-ui.card.content>
                    @include('profile.partials.update-password-form')
                </x-ui.card.content>
            </x-ui.card>

            <x-ui.card class="border-destructive/20">
                <x-ui.card.header>
                    <x-ui.card.title class="text-destructive">Hapus Akun</x-ui.card.title>
                    <x-ui.card.description>Setelah akun Anda dihapus, semua sumber daya dan datanya akan dihapus secara
                        permanen.</x-ui.card.description>
                </x-ui.card.header>
                <x-ui.card.content>
                    @include('profile.partials.delete-user-form')
                </x-ui.card.content>
            </x-ui.card>
        </div>
    </div>
@endsection