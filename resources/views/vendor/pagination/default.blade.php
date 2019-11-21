@if ($paginator->hasPages())
    <nav class="ml-auto">
        <ul class="pagination flex">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="text-gray-400 bg-gray-100 border border-gray-300 rounded-l" aria-disabled="true" aria-label="@lang('pagination.previous')">
                    <span class="block px-2 py-1" aria-hidden="true">&lsaquo;</span>
                </li>
            @else
                <li class="text-gray-600 hover:text-gray-800 bg-gray-100 hover:bg-gray-400 border border-gray-300 rounded-l">
                    <a href="{{ $paginator->previousPageUrl() }}" class="block px-2 py-1" rel="prev" aria-label="@lang('pagination.previous')">&lsaquo;</a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="text-gray-400 bg-gray-100 border border-gray-300 border-l-0 flex items-center justify-center px-2 py-1 text-xs" aria-disabled="true">
                        <span class="block px-2 py-1">{{ $element }}</span>
                    </li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="bg-gray-300 border border-gray-300 border-l-0 flex items-center justify-center text-xs" aria-current="page">
                                <span class="block px-2 py-1">{{ $page }}</span>
                            </li>
                        @else
                            <li class="text-gray-600 hover:text-gray-800 bg-gray-100 hover:bg-gray-400 border border-gray-300 border-l-0 flex items-center justify-center text-xs">
                                <a href="{{ $url }}" class="block px-2 py-1">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="text-gray-600 hover:text-gray-800 bg-gray-100 hover:bg-gray-400 border border-gray-300 border-l-0 rounded-r">
                    <a href="{{ $paginator->nextPageUrl() }}" class="block px-2 py-1" rel="next" aria-label="@lang('pagination.next')">&rsaquo;</a>
                </li>
            @else
                <li class="text-gray-400 bg-gray-100 border border-gray-300 border-l-0 rounded-r" aria-disabled="true" aria-label="@lang('pagination.next')">
                    <span class="block px-2 py-1" aria-hidden="true">&rsaquo;</span>
                </li>
            @endif
        </ul>
    </nav>
@endif
