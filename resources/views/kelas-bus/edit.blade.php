@extends('layouts.admin')
@section('content')
    @push('header')
        <x-ui.breadcrumb.breadcrumb>
            <x-ui.breadcrumb.list class="text-xs">
                <x-ui.breadcrumb.item>
                    <x-ui.breadcrumb.link href="{{ route('dashboard') }}">
                        Home
                    </x-ui.breadcrumb.link>
                </x-ui.breadcrumb.item>
                <x-ui.breadcrumb.separator>
                    <i data-lucide="chevron-right" class="w-3.5 h-3.5" ></i>
                </x-ui.breadcrumb.separator>
                <x-ui.breadcrumb.item>
                    <x-ui.breadcrumb.link href="{{ route('admin/kelas-bus.index') }}">
                        Kelas Bus
                    </x-ui.breadcrumb.link>
                </x-ui.breadcrumb.item>
                <x-ui.breadcrumb.separator>
                    <i data-lucide="chevron-right" class="w-3.5 h-3.5" ></i>
                </x-ui.breadcrumb.separator>
                <x-ui.breadcrumb.item>
                    <x-ui.breadcrumb.page>
                        Edit Kelas Bus
                    </x-ui.breadcrumb.page>
                </x-ui.breadcrumb.item>
            </x-ui.breadcrumb.list>
        </x-ui.breadcrumb.breadcrumb>
@endpush

    <div class="p-4 sm:p-6">
        <div class="max-w-4xl mx-auto">
            <x-ui.card>
                <x-ui.card.header>
                    <div class="flex items-center justify-between">
                        <div>
                            <x-ui.card.title>Edit Kelas Bus</x-ui.card.title>
                            <x-ui.card.description>Perbarui informasi kelas bus</x-ui.card.description>
                        </div>
                        <a href="{{ route('admin/kelas-bus.index') }}">
                            <x-ui.button variant="outline" size="sm">
                                <i data-lucide="arrow-left" class="w-4 h-4 mr-2" ></i>
                                Kembali
                            </x-ui.button>
                        </a>
                    </div>
                </x-ui.card.header>
                <x-ui.card.content>
                    <form method="POST" action="{{ route('admin/kelas-bus.update', $kelasBus) }}">
                        @csrf
                        @method('PUT')

                        <div class="space-y-6">
                            <!-- Nama Kelas -->
                            <div class="space-y-2">
                                <x-ui.label for="nama_kelas">
                                    <div class="flex items-center gap-2">
                                        <i data-lucide="star" class="w-4 h-4" ></i>
                                        Nama Kelas
                                    </div>
                                    <span class="text-red-500">*</span>
                                </x-ui.label>
                                <x-ui.input
                                    type="text"
                                    id="nama_kelas"
                                    name="nama_kelas"
                                    value="{{ old('nama_kelas', $kelasBus->nama_kelas) }}"
                                    placeholder="Contoh: Ekonomi, VIP, Premium"
                                    required
                                    maxlength="100"
                                />
                                @error('nama_kelas')
                                    <p class="text-sm text-destructive mt-1 flex items-center gap-1">
                                        <i data-lucide="alert-circle" class="w-4 h-4" ></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                                <p class="text-xs text-muted-foreground flex items-center gap-1">
                                    <i data-lucide="info" class="w-3 h-3" ></i>
                                    Nama kelas bus yang akan ditawarkan (maksimal 100 karakter)
                                </p>
                            </div>



                            <!-- Deskripsi -->
                            <div class="space-y-2">
                                <x-ui.label for="deskripsi">
                                    <div class="flex items-center gap-2">
                                        <i data-lucide="file-text" class="w-4 h-4" ></i>
                                        Deskripsi
                                    </div>
                                </x-ui.label>
                                <textarea
                                    id="deskripsi"
                                    name="deskripsi"
                                    placeholder="Masukkan deskripsi kelas bus (opsional)"
                                    class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring"
                                    rows="4"
                                >{{ old('deskripsi', $kelasBus->deskripsi) }}</textarea>
                                @error('deskripsi')
                                    <p class="text-sm text-destructive mt-1 flex items-center gap-1">
                                        <i data-lucide="alert-circle" class="w-4 h-4" ></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                                <p class="text-xs text-muted-foreground flex items-center gap-1">
                                    <i data-lucide="info" class="w-3 h-3" ></i>
                                    Deskripsi tambahan tentang kelas ini
                                </p>
                            </div>

                            <!-- Info Box -->
                            <div class="p-4 rounded-lg border border-border bg-muted/50">
                                <div class="flex items-center gap-2 mb-3">
                                    <i data-lucide="info" class="w-5 h-5 text-primary" ></i>
                                    <p class="text-sm font-medium">Informasi</p>
                                </div>
                                <ul class="space-y-2 text-sm text-muted-foreground">
                                    <li class="flex items-start gap-2">
                                        <i data-lucide="check" class="w-4 h-4 text-green-600 mt-0.5 shrink-0" ></i>
                                        <span>Nama kelas harus jelas dan mudah dipahami</span>
                                    </li>
                                    <li class="flex items-start gap-2">
                                        <i data-lucide="check" class="w-4 h-4 text-green-600 mt-0.5 shrink-0" ></i>
                                        <span>Kelas yang diupdate akan mempengaruhi semua jadwal yang menggunakannya</span>
                                    </li>
                                    <li class="flex items-start gap-2">
                                        <i data-lucide="check" class="w-4 h-4 text-green-600 mt-0.5 shrink-0" ></i>
                                        <span>Pastikan nama tidak duplikat dengan kelas lain</span>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="flex flex-col-reverse sm:flex-row items-center justify-end gap-3 mt-8 pt-6 border-t border-border">
                            <a href="{{ route('admin/kelas-bus.index') }}" class="w-full sm:w-auto">
                                <x-ui.button type="button" variant="outline" class="w-full sm:w-auto">
                                    <i data-lucide="x" class="w-4 h-4 mr-2" ></i>
                                    Batal
                                </x-ui.button>
                            </a>
                            <x-ui.button type="submit" class="w-full sm:w-auto">
                                <i data-lucide="save" class="w-4 h-4 mr-2" ></i>
                                Update Kelas Bus
                            </x-ui.button>
                        </div>
                    </form>
                </x-ui.card.content>
            </x-ui.card>
        </div>
    </div>

@endsection
