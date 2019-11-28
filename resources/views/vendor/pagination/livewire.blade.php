@if ($paginator->hasPages())
    <nav class="ml-auto">
        <ul class="pagination flex">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="flex items-center justify-center text-gray-400 bg-gray-100 border border-gray-300 rounded-l" aria-disabled="true" aria-label="@lang('pagination.previous')">
                    <span class="block px-1" aria-hidden="true">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path d="M7.05 9.293L6.343 10 12 15.657l1.414-1.414L9.172 10l4.242-4.243L12 4.343z"/>
                        </svg>
                    </span>
                </li>
            @else
                <li class="flex items-center justify-center text-gray-600 hover:text-gray-800 bg-gray-100 hover:bg-gray-400 border border-gray-300 rounded-l">
                    <button wire:click="previousPage" class="block px-1 focus:outline-none" rel="prev" aria-label="@lang('pagination.previous')">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path d="M7.05 9.293L6.343 10 12 15.657l1.414-1.414L9.172 10l4.242-4.243L12 4.343z"/>
                        </svg>
                    </button>
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
                                <button wire:click="gotoPage({{ $page }})" class="block px-2 py-1 focus:outline-none">{{ $page }}</button>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="flex items-center justify-center text-gray-600 hover:text-gray-800 bg-gray-100 hover:bg-gray-400 border border-gray-300 border-l-0 rounded-r">
                    <button wire:click="nextPage" class="block px-1 focus:outline-none" rel="next" aria-label="@lang('pagination.next')">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path d="M12.95 10.707l.707-.707L8 4.343 6.586 5.757 10.828 10l-4.242 4.243L8 15.657l4.95-4.95z"/>
                        </svg>
                    </button>
                </li>
            @else
                <li class="flex items-center justify-center text-gray-400 bg-gray-100 border border-gray-300 border-l-0 rounded-r" aria-disabled="true" aria-label="@lang('pagination.next')">
                    <span class="block px-1" aria-hidden="true">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path d="M12.95 10.707l.707-.707L8 4.343 6.586 5.757 10.828 10l-4.242 4.243L8 15.657l4.95-4.95z"/>
                        </svg>
                    </span>
                </li>
            @endif
        </ul>
    </nav>
@endif
