@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="mt-6 flex justify-center">
        <ul class="inline-flex items-center space-x-1 text-sm">
            {{-- Previous --}}
            @if ($paginator->onFirstPage())
                <li class="px-3 py-1 text-gray-400 cursor-default">← Previous</li>
            @else
                <li>
                    <a href="{{ $paginator->previousPageUrl() }}" class="px-3 py-1 text-gray-700 hover:text-green-500">← Previous</a>
                </li>
            @endif

            {{-- Page Number --}}
            @php
                $current = $paginator->currentPage();
                $last    = $paginator->lastPage();
            @endphp

            {{-- first page --}}
            @if ($current > 2)
                <li>
                    <a href="{{ $paginator->url(1) }}" class="px-3 py-1 text-gray-700 hover:text-green-500">1</a>
                </li>
                @if ($current > 3)
                    <li class="px-3 py-1 text-gray-400">...</li>
                @endif
            @endif

            {{-- current page --}}
            @for ($i = max(1, $current - 1); $i <= min($last, $current + 1); $i++)
                @if ($i == $current)
                    <li class="px-3 py-1 text-white bg-green-500 rounded">{{ $i }}</li>
                @else
                    <li><a href="{{ $paginator->url($i) }}" class="px-3 py-1 text-gray-700 hover:text-green-500">{{ $i }}</a></li>
                @endif
            @endfor

            {{-- last page --}}
            @if ($current < $last - 1)
                @if ($current < $last - 2)
                    <li class="px-3 py-1 text-gray-400">...</li>
                @endif
                <li><a href="{{ $paginator->url($last) }}" class="px-3 py-1 text-gray-700 hover:text-green-500">{{ $last }}</a></li>
            @endif

            {{-- @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="px-3 py-1 text-gray-400">{{ $element }}</li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="px-3 py-1 text-white bg-green-500 rounded">{{ $page }}</li>
                        @else
                            <li>
                                <a href="{{ $url }}" class="px-3 py-1 text-gray-700 hover:text-green-500">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach --}}

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <li>
                    <a href="{{ $paginator->nextPageUrl() }}" class="px-3 py-1 text-gray-700 hover:text-green-500">Next →</a>
                </li>
            @else
                <li class="px-3 py-1 text-gray-400 cursor-default">Next →</li>
            @endif
        </ul>
    </nav>
@endif
