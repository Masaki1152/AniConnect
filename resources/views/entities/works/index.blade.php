<x-app-layout>
    <div id="message"
        class="hidden fixed top-[15%] left-1/2 transform -translate-x-1/2 bg-green-500/50 text-white px-6 py-3 rounded-lg shadow-lg flex items-center space-x-4 z-50">
    </div>
    <div class="container mx-auto py-8">
        <h1 class="text-3xl font-bold text-center mb-6">作品一覧</h1>

        <!-- 人気上位の作品 デザインはあとで作成 -->
        <div class="flex-cols">
            <h2>人気作品</h2>
            <ul>
                @foreach ($topPopularityWorks as $topPopularityWork)
                    <li>
                        <h3>{{ $topPopularityWork['item']->name }}</h3>
                    </li>
                @endforeach
            </ul>
        </div>

        <!-- 検索機能 -->
        <div class="flex justify-center mb-6">
            <form action="{{ route('works.index') }}" method="GET" class="flex items-center space-x-2">
                <!-- キーワード検索 -->
                <input type="text" name="search" id="search", value="{{ request('search') }}"
                    placeholder="キーワードを検索" aria-label="検索..."
                    class="px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-blue-200">
                <!-- カテゴリー検索機能 -->
                <div>
                    <button id='toggleCategories' type='button'
                        style="{{ count(request('checkedCategories', [])) > 0 ? 'display: none;' : 'display: inline;' }}">カテゴリーで絞り込む</button>
                    <button id='closeCategories' type='button'
                        style="{{ count(request('checkedCategories', [])) > 0 ? 'display: inline;' : 'display: none;' }}">閉じる</button>
                    <div id='categoryFilter' style="display: {{ request('checkedCategories') ? 'block' : 'none' }};">
                        <h2>カテゴリー</h2>
                        <ul id='categoryList'>
                            @foreach ($categories as $category)
                                <li>
                                    <input type="checkbox" class="categoryCheckbox" name="checkedCategories[]"
                                        value="{{ $category->id }}"
                                        {{ in_array($category->id, request('checkedCategories', [])) ? 'checked' : '' }}>
                                    <label>{{ $category->name }}</label>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <input type="submit" value="検索"
                    class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400">
            </form>
            <div class="ml-4">
                <a href="{{ route('works.index') }}" class="text-blue-500 hover:underline focus:outline-none">キャンセル</a>
            </div>
        </div>
        <div>
            <p>あらすじ、制作会社、登場人物、声優、音楽名、歌手、聖地など何でも検索してみましょう！</p>
            <p>各作品のカテゴリーは、登録メンバーの皆さんの投稿を元に随時更新されています！</p>
            @if (!empty($work->top_categories_updated_at))
                <p>{{ $work->top_categories_updated_at->format('Y/m/d H:i') }}更新</p>
            @endif
        </div>

        <!-- 作品リスト -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- 検索結果がない場合 -->
            @if ($works->isEmpty())
                <h2 class="col-span-full text-center text-gray-500 text-lg font-semibold">
                    @if (!empty($search))
                        キーワード 「{{ $search }}」
                    @endif
                    @if (!empty($search) && !empty($selectedCategories))
                        、
                    @endif
                    @if (!empty($selectedCategories))
                        カテゴリー 「{{ implode('、', $selectedCategories) }}」
                    @endif
                    に一致する結果はありませんでした。</p>
                </h2>
            @else
                <!-- 検索結果がある場合 -->
                @if (!empty($search) || !empty($selectedCategories))
                    <p class="col-span-full text-center text-gray-700 text-lg font-semibold">
                        @if (!empty($search))
                            キーワード 「{{ $search }}」
                        @endif
                        @if (!empty($search) && !empty($selectedCategories))
                            、
                        @endif
                        @if (!empty($selectedCategories))
                            カテゴリー 「{{ implode('、', $selectedCategories) }}」
                        @endif
                        の検索結果：<span class="text-blue-500">{{ $totalResults }}</span>件
                    </p>
                @else
                    <p class="col-span-full text-center text-gray-700 text-lg font-semibold">
                        全作品：<span class="text-blue-500">{{ $totalResults }}</span>件
                    </p>
                @endif
                @foreach ($works as $work)
                    <div class="p-6 border border-gray-200 rounded-lg shadow-sm">
                        <h2 class="text-xl font-semibold mb-2 text-blue-600">
                            <a href="{{ route('works.show', ['work' => $work->id]) }}" class="hover:underline">
                                {{ $work->name }}
                            </a>
                        </h2>
                        <!-- 上位3カテゴリー -->
                        <h5 class='category flex gap-2'>
                            @if ($work->top_categories->isNotEmpty())
                                @foreach ($work->top_categories as $category)
                                    <span class="text-white px-2 py-1 rounded-full text-sm"
                                        style="background-color: {{ $category['color'] }};">
                                        {{ $category['name'] }}
                                    </span>
                                @endforeach
                            @else
                                <p>カテゴリー情報がありません。</p>
                            @endif
                        </h5>
                        @if ($work->image)
                            <div
                                class="relative w-full max-w-md aspect-[3/4] overflow-hidden rounded-md border border-gray-300">
                                <img src="{{ $work->image }}" alt="画像が読み込めません。"
                                    class="absolute inset-0 w-full h-full object-cover">
                            </div>
                            <p>{{ $work->copyright }}</p>
                        @endif
                        <x-molecules.evaluation.star-num-detail :starNum="$work->average_star_num" :postNum="$work->post_num" />
                        <p class="text-gray-600">{{ $work->term }}</p>
                        <x-molecules.button.interested type="works" :root="$work" path="work.interested.index"
                            :prop="['work_id' => $work->id]" isMultiple="false" />
                    </div>
                @endforeach
            @endif
        </div>

        <!-- ページネーション -->
        <div class="mt-8">
            {{ $works->appends(request()->query())->links() }}
        </div>
    </div>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('/js/search_category.js') }}"></script>
    <script src="{{ asset('/js/interested_user.js') }}"></script>
</x-app-layout>
