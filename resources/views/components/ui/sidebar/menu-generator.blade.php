@props([
    'items' => [],
])

<x-ui::sidebar.menu>
@foreach ($items as $item)
    @if (isset($item['type']) && $item['type'] === 'group')
        <x-ui::sidebar.group>
            <x-ui::sidebar.group-label>
                {{ $item['label'] }}
            </x-ui::sidebar.group-label>

            <x-ui::sidebar.group-content>
                <x-ui::sidebar.menu>
                    @foreach ($item['items'] as $sub)
                        <x-ui::sidebar.menu-item>
                            <x-ui::sidebar.menu-button
                                :active="$sub['active'] ?? false"
                                :tooltip="$sub['tooltip'] ?? null"
                            >
                                {!! $sub['icon'] !!}
                                <span>{{ $sub['label'] }}</span>
                            </x-ui::sidebar.menu-button>
                        </x-ui::sidebar.menu-item>
                    @endforeach
                </x-ui::sidebar.menu>
            </x-ui::sidebar.group-content>
        </x-ui::sidebar.group>

    @elseif (isset($item['type']) && $item['type'] === 'submenu')
        <x-ui::sidebar.menu-item>
            <x-ui::sidebar.menu-button
                :tooltip="$item['tooltip'] ?? null"
            >
                {!! $item['icon'] !!}
                <span>{{ $item['label'] }}</span>
            </x-ui::sidebar.menu-button>

            <x-ui::sidebar.menu-sub>
                @foreach ($item['items'] as $sub)
                    <x-ui::sidebar.menu-sub-item>
                        <x-ui::sidebar.menu-sub-button :active="$sub['active'] ?? false">
                            {!! $sub['icon'] ?? '' !!}
                            <span>{{ $sub['label'] }}</span>
                        </x-ui::sidebar.menu-sub-button>
                    </x-ui::sidebar.menu-sub-item>
                @endforeach
            </x-ui::sidebar.menu-sub>
        </x-ui::sidebar.menu-item>

    @else
        <x-ui::sidebar.menu-item>
            <x-ui::sidebar.menu-button
                :active="$item['active'] ?? false"
                :tooltip="$item['tooltip'] ?? null"
            >
                {!! $item['icon'] !!}
                <span>{{ $item['label'] }}</span>
            </x-ui::sidebar.menu-button>
        </x-ui::sidebar.menu-item>
    @endif
@endforeach
</x-ui::sidebar.menu>
