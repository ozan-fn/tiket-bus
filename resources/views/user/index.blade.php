<x-admin-layout>
    <x-slot name="header">
        <x-ui.breadcrumb.breadcrumb>
            <x-ui.breadcrumb.list class="text-xs">
                <x-ui.breadcrumb.item>
                    <x-ui.breadcrumb.link href="{{ route('dashboard') }}">Home</x-ui.breadcrumb.link>
                </x-ui.breadcrumb.item>
                <x-ui.breadcrumb.separator>
                    <x-lucide-chevron-right class="w-3.5 h-3.5" />
                </x-ui.breadcrumb.separator>
                <x-ui.breadcrumb.item>
                    <x-ui.breadcrumb.page>User Management</x-ui.breadcrumb.page>
                </x-ui.breadcrumb.item>
            </x-ui.breadcrumb.list>
        </x-ui.breadcrumb.breadcrumb>
    </x-slot>

    <div class="p-4 sm:p-6">
        @if(session('success'))
            <x-ui.alert class="mb-6">
                <x-slot:icon><x-lucide-check-circle class="w-4 h-4" /></x-slot:icon>
                <x-slot:title>Berhasil!</x-slot:title>
                <x-slot:description>{{ session('success') }}</x-slot:description>
            </x-ui.alert>
        @endif

        <div class="max-w-7xl mx-auto">
            <x-ui.card>
                <x-ui.card.header>
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <div class="flex-1 w-full sm:w-auto">
                            <div class="flex items-center justify-between mb-3">
                                <div>
                                    <x-ui.card.title>Daftar User</x-ui.card.title>
                                    <x-ui.card.description>Kelola semua pengguna di sistem</x-ui.card.description>
                                </div>
                                <a href="{{ route('admin/user.create') }}" class="hidden sm:inline-block">
                                    <x-ui.button>
                                        <x-lucide-plus class="w-4 h-4 mr-2" /> Tambah User
                                    </x-ui.button>
                                </a>
                            </div>

                            <div class="flex flex-col gap-2">
                                <div class="flex gap-2 items-center w-full">
                                    {{-- Search Form --}}
                                    <form method="GET" action="{{ route('admin/user.index') }}" class="flex gap-2 flex-1 min-w-0">
                                        @if(request('role'))
                                            <input type="hidden" name="role" value="{{ request('role') }}">
                                        @endif
                                            <div class="flex flex-row gap-2">
                                        <div class="relative flex-1 min-w-0">
                                            <x-lucide-search class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
                                            <x-ui.input type="text" name="search" placeholder="Cari nama atau email..." value="{{ request('search') }}" class="pl-9 h-10 max-w-md" />
                                        </div>
                                        <x-ui.button size="icon" type="submit" variant="outline" class="h-9 w-9 shrink-0">
                                            <x-lucide-search class="w-4 h-4" />
                                        </x-ui.button></div>
                                        @if(request('search'))
                                            <a href="{{ route('admin/user.index', ['role' => request('role')]) }}" class="shrink-0">
                                                <x-ui.button size="icon" type="button" variant="outline" class="h-10 w-10 shrink-0">
                                                    <x-lucide-x class="w-4 h-4" />
                                                </x-ui.button>
                                            </a>
                                        @endif
                                    </form>

                                    {{-- Filter Dropdown (Updated) --}}
                                    <div class="shrink-0">
                                        {{-- Panggil file dropdown/index.blade.php --}}
                                        <x-ui.dropdown>
                                            <x-slot:trigger>
                                                <x-ui.button size="icon" variant="{{ request('role') ? 'default' : 'outline' }}">
                                                    <x-lucide-filter class="w-4 h-4" />
                                                </x-ui.button>
                                            </x-slot:trigger>

                                            {{-- LANGSUNG ISI ITEM, JANGAN PAKAI CONTENT WRAPPER --}}
                                            <x-ui.dropdown.label>Filter Role</x-ui.dropdown.label>
                                            <x-ui.dropdown.separator />

                                            <x-ui.dropdown.item as="a" href="{{ route('admin/user.index', ['search' => request('search')]) }}" active="{{ !request('role') ? 'true' : 'false' }}">
                                                Semua
                                                @if(!request('role'))
                                                    <x-lucide-check class="w-4 h-4 ml-auto" />
                                                @endif
                                            </x-ui.dropdown.item>

                                            @foreach ($roles as $role)
                                                <x-ui.dropdown.item
                                                    as="a"
                                                    href="{{ route('admin/user.index', ['role' => $role->name, 'search' => request('search')]) }}"
                                                    active="{{ request('role') == $role->name ? 'true' : 'false' }}"
                                                >
                                                    {{ ucfirst($role->name) }}
                                                    @if(request('role') == $role->name)
                                                        <x-lucide-check class="w-4 h-4 ml-auto" />
                                                    @endif
                                                </x-ui.dropdown.item>
                                            @endforeach
                                        </x-ui.dropdown>
                                    </div>
                                    {{-- End Filter --}}
                                </div>
                            </div>
                        </div>

                        {{-- Mobile Add Button --}}
                        <a href="{{ route('admin/user.create') }}" class="sm:hidden w-full">
                            <x-ui.button class="w-full">
                                <x-lucide-plus class="w-4 h-4 mr-2" /> Tambah User
                            </x-ui.button>
                        </a>
                    </div>
                </x-ui.card.header>

                <x-ui.card.content class="p-0 sm:p-6">
                    <div class="overflow-x-auto">
                        @if($users->count() > 0)
                            <div class="overflow-x-auto -mx-4 sm:mx-0">
                                <x-ui.table>
                                    <x-ui.table.header class="hidden sm:table-header-group">
                                        <x-ui.table.row>
                                            <x-ui.table.head class="w-12 sm:w-16">No</x-ui.table.head>
                                            <x-table.sortable-header name="name">Nama</x-table.sortable-header>
                                            <x-table.sortable-header name="email" class="hidden sm:table-cell">Email</x-table.sortable-header>
                                            <x-table.sortable-header name="role">Role</x-table.sortable-header>
                                            <x-ui.table.head class="text-right">Aksi</x-ui.table.head>
                                        </x-ui.table.row>
                                    </x-ui.table.header>
                                    <x-ui.table.body>
                                        @foreach ($users as $index => $user)
                                            <x-ui.table.row class="hidden sm:table-row">
                                                <x-ui.table.cell>{{ $users->firstItem() + $index }}</x-ui.table.cell>
                                                <x-ui.table.cell><div class="font-medium">{{ $user->name }}</div></x-ui.table.cell>
                                                <x-ui.table.cell class="hidden sm:table-cell">{{ $user->email }}</x-ui.table.cell>
                                                <x-ui.table.cell>
                                                    @foreach($user->roles as $role)
                                                        <x-ui.badge variant="secondary" class="mr-1">{{ ucfirst($role->name) }}</x-ui.badge>
                                                    @endforeach
                                                </x-ui.table.cell>
                                                <x-ui.table.cell class="text-right">
                                                    <div class="flex items-center justify-end gap-2">
                                                        <a href="{{ route('admin/user.edit', $user) }}">
                                                            <x-ui.button variant="ghost" size="sm" class="h-8 w-8 p-0"><x-lucide-pencil class="w-4 h-4" /></x-ui.button>
                                                        </a>
                                                        {{-- Modal Delete --}}
                                                        <div x-data="{ open: false }">
                                                            <x-ui.button @click="open = true" variant="ghost" size="sm" class="h-8 w-8 p-0 text-destructive hover:bg-destructive/10"><x-lucide-trash-2 class="w-4 h-4" /></x-ui.button>
                                                            <template x-teleport="body">
                                                                <div x-show="open" x-cloak class="fixed inset-0 z-50 overflow-y-auto" @keydown.escape.window="open = false">
                                                                    <div x-show="open" class="fixed inset-0 bg-black/50 backdrop-blur-sm" @click="open = false"></div>
                                                                    <div class="flex min-h-full items-center justify-center p-4">
                                                                        <div x-show="open" class="relative w-full max-w-lg bg-card rounded-lg shadow-lg border border-border p-6" @click.stop>
                                                                            <h3 class="text-lg font-semibold mb-2">Hapus User</h3>
                                                                            <p class="text-sm text-muted-foreground mb-4">Hapus <strong>{{ $user->name }}</strong>?</p>
                                                                            <div class="flex justify-end gap-2">
                                                                                <x-ui.button type="button" variant="outline" @click="open = false" size="sm">Batal</x-ui.button>
                                                                                <form method="POST" action="{{ route('admin/user.destroy', $user) }}" class="inline">
                                                                                    @csrf @method('DELETE')
                                                                                    <x-ui.button type="submit" size="sm" variant="destructive">Ya, Hapus</x-ui.button>
                                                                                </form>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </template>
                                                        </div>
                                                    </div>
                                                </x-ui.table.cell>
                                            </x-ui.table.row>
                                            {{-- Mobile Row --}}
                                            <x-ui.table.row class="sm:hidden">
                                                <x-ui.table.cell colspan="5">
                                                    <div class="flex justify-between items-center">
                                                        <div>
                                                            <p class="font-medium">{{ $user->name }}</p>
                                                            <p class="text-xs text-muted-foreground">{{ $user->email }}</p>
                                                        </div>
                                                        <div class="flex gap-1">
                                                            <a href="{{ route('admin/user.edit', $user) }}"><x-ui.button variant="ghost" size="sm" class="h-8 w-8 p-0"><x-lucide-pencil class="w-4 h-4" /></x-ui.button></a>
                                                            {{-- Mobile Delete Button Logic Here (Same as Desktop) --}}
                                                        </div>
                                                    </div>
                                                </x-ui.table.cell>
                                            </x-ui.table.row>
                                        @endforeach
                                    </x-ui.table.body>
                                </x-ui.table>
                            </div>
                        @else
                            <div class="text-center py-12">
                                <h3 class="text-lg font-medium">Belum ada user</h3>
                                <p class="text-sm text-muted-foreground mb-4">Silakan tambah user baru.</p>
                                <a href="{{ route('admin/user.create') }}"><x-ui.button>Tambah User</x-ui.button></a>
                            </div>
                        @endif
                    </div>
                </x-ui.card.content>
                @if($users->count() > 0)
                    <x-ui.card.footer>
                        <div class="w-full flex flex-col sm:flex-row items-center justify-between gap-3">
                            <p class="text-xs sm:text-sm text-muted-foreground text-center sm:text-left">
                                Menampilkan {{ $users->firstItem() }} - {{ $users->lastItem() }} dari {{ $users->total() }} user
                            </p>
                            <div class="w-full sm:w-auto flex justify-center">
                                {{ $users->links('vendor.pagination.shadcn') }}
                            </div>
                        </div>
                    </x-ui.card.footer>
                @endif
            </x-ui.card>
        </div>
    </div>
</x-admin-layout>
