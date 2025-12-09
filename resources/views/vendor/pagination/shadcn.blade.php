@if ($paginator->hasPages())
    <x-ui.pagination>
        <x-ui.pagination.content>
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <x-ui.pagination.item>
                    <x-ui.pagination.previous disabled />
                </x-ui.pagination.item>
            @else
                <x-ui.pagination.item>
                    <x-ui.pagination.previous href="{{ $paginator->previousPageUrl() }}" />
                </x-ui.pagination.item>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <x-ui.pagination.item>
                        <x-ui.pagination.ellipsis />
                    </x-ui.pagination.item>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        <x-ui.pagination.item>
                            @if ($page == $paginator->currentPage())
                                <x-ui.pagination.link href="{{ $url }}" isActive>
                                    {{ $page }}
                                </x-ui.pagination.link>
                            @else
                                <x-ui.pagination.link href="{{ $url }}">
                                    {{ $page }}
                                </x-ui.pagination.link>
                            @endif
                        </x-ui.pagination.item>
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <x-ui.pagination.item>
                    <x-ui.pagination.next href="{{ $paginator->nextPageUrl() }}" />
                </x-ui.pagination.item>
            @else
                <x-ui.pagination.item>
                    <x-ui.pagination.next disabled />
                </x-ui.pagination.item>
            @endif
        </x-ui.pagination.content>
    </x-ui.pagination>
@endif
