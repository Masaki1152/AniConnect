@if ($paginator->hasPages())
    <nav role="navigation" class="flex items-center justify-center space-x-2">
        {{-- "前へ" ボタン（最初のページでは非表示） --}}
        @if (!$paginator->onFirstPage())
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
                class="px-2 py-2 bg-blue-500 text-white rounded-md">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                        clip-rule="evenodd" />
                </svg>
            </a>
        @endif

        {{-- 数字リンク --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="px-3 py-2 text-gray-500">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="px-3 py-2 bg-blue-500 text-white rounded-md">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}"
                            class="px-3 py-2 bg-gray-200 rounded-md hover:bg-gray-300">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- "次へ" ボタン（最後のページでは非表示） --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next"
                class="px-2 py-2 bg-blue-500 text-white rounded-md">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd" />
                </svg>
            </a>
        @endif
    </nav>
@endif
