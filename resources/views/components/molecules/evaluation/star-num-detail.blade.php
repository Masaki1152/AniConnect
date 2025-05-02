@if ($starNum == 9.9)
    <p class="text-sm text-gray-500">評価数が足りません</p>
@else
    <div class="content mt-2">
        <p class="flex items-center text-lg font-semibold text-gray-800 gap-1 leading-none">
            <span>{{ __('common.evaluation_colon') }} {{ $starNum }}</span>
            <span class="text-sm text-gray-500">（{{ $postNum }}件の評価）</span>
        </p>
        <div class="relative flex">
            @for ($i = 1; $i <= 5; $i++)
                <div class="relative w-6 h-6">
                    <!-- 空の星（グレー） -->
                    <svg class="absolute text-gray-300 w-full h-full" fill="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M12 .587l3.668 7.431 8.208 1.192-5.938 5.792 1.398 8.168L12 18.891l-7.336 3.856 1.398-8.168-5.938-5.792 8.208-1.192L12 .587z" />
                    </svg>
                    <!-- 塗りつぶしの星（黄色, clipPath で部分塗り） -->
                    <svg class="absolute text-yellow-400 w-full h-full" fill="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg"
                        style="clip-path: inset(0 {{ max(0, min(100, (1 - max(0, min(1, $starNum - ($i - 1)))) * 100)) }}% 0 0);">
                        <path
                            d="M12 .587l3.668 7.431 8.208 1.192-5.938 5.792 1.398 8.168L12 18.891l-7.336 3.856 1.398-8.168-5.938-5.792 8.208-1.192L12 .587z" />
                    </svg>
                </div>
            @endfor
        </div>
    </div>
@endif
