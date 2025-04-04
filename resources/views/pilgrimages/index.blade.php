<x-app-layout>
    <div id="message"
        class="hidden fixed top-[15%] left-1/2 transform -translate-x-1/2 bg-green-500/50 text-white px-6 py-3 rounded-lg shadow-lg flex items-center space-x-4 z-50">
    </div>
    <h1>聖地一覧</h1>

    <!-- 人気上位の聖地 デザインはあとで作成 -->
    <div class="flex-cols">
        <h2>人気の聖地</h2>
        <ul>
            @foreach ($topPopularityPilgrimages as $topPopularityPilgrimage)
                <li>
                    <h3>{{ $topPopularityPilgrimage['item']->name }}</h3>
                </li>
            @endforeach
        </ul>
    </div>

    <!-- 検索機能 -->
    <div class='serch'>
        <form action="{{ route('pilgrimages.index') }}" method="GET">
            <div class=keword_serch>
                <p>聖地検索</p>
                <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="キーワードを検索"
                    aria-label="検索..."
                    class="px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-blue-200">
                <input type="submit" value="検索"
                    class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <div class='prefecture_serch'>
                <p>県名検索</p>
                <select name="prefecture_search" class="form-control" value="{{ request('prefecture_search') }}">
                    <option value="">未選択</option>
                    @foreach ($prefectures as $id => $prefecture_name)
                        <option value="{{ $id }}" @if ($prefecture_search == $id) selected @endif>
                            {{ $prefecture_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <!-- カテゴリー検索機能 -->
            <div class='category_search'>
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
        </form>
        <div class="cancel">
            <a href="{{ route('pilgrimages.index') }}">キャンセル</a>
        </div>
    </div>
    <div>
        <p>聖地名、シーン、住所、作品名、関連人物名など何でも検索してみましょう！</p>
        <p>各聖地のカテゴリーは、登録メンバーの皆さんの投稿を元に随時更新されています！</p>
        @if (!empty($pilgrimage->top_categories_updated_at))
            <p>{{ $pilgrimage->top_categories_updated_at->format('Y/m/d H:i') }}更新</p>
        @endif
    </div>
    <div class='pilgrimages'>
        @if ($pilgrimages->isEmpty())
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
                @if (!empty($selectedCategories) && !empty($selected_prefecture))
                    、
                @endif
                @if (empty($selectedCategories) && !empty($selected_prefecture) && !empty($search))
                    、
                @endif
                @if (!empty($selected_prefecture))
                    県名 「{{ $selected_prefecture }}」
                @endif
                に一致する結果はありませんでした。</p>
            </h2>
        @else
            <!-- 検索結果がある場合 -->
            @if (!empty($search) || !empty($selectedCategories) || !empty($selected_prefecture))
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
                    @if (!empty($selectedCategories) && !empty($selected_prefecture))
                        、
                    @endif
                    @if (empty($selectedCategories) && !empty($selected_prefecture) && !empty($search))
                        、
                    @endif
                    @if (!empty($selected_prefecture))
                        県名 「{{ $selected_prefecture }}」
                    @endif
                    の検索結果：<span class="text-blue-500">{{ $totalResults }}</span>件
                </p>
            @else
                <p class="col-span-full text-center text-gray-700 text-lg font-semibold">
                    全聖地：<span class="text-blue-500">{{ $totalResults }}</span>件
                </p>
            @endif
            @foreach ($pilgrimages as $pilgrimage)
                <div class='pilgrimage'>
                    <h2 class='name'>
                        <a href="{{ route('pilgrimages.show', ['pilgrimage_id' => $pilgrimage->id]) }}">
                            {{ $pilgrimage->name }}
                        </a>
                    </h2>
                    <!-- 上位3カテゴリー -->
                    <h5 class='category flex gap-2'>
                        @if ($pilgrimage->top_categories->isNotEmpty())
                            @foreach ($pilgrimage->top_categories as $category)
                                <span class="text-white px-2 py-1 rounded-full text-sm"
                                    style="background-color: {{ $category['color'] }};">
                                    {{ $category['name'] }}
                                </span>
                            @endforeach
                        @else
                            <p>カテゴリー情報がありません。</p>
                        @endif
                    </h5>
                    <p class='work'>
                        {{-- 関連する作品の数だけ繰り返す --}}
                        @foreach ($pilgrimage->works as $pilgrimage_work)
                            <a href="{{ route('works.show', ['work' => $pilgrimage_work->id]) }}">
                                {{ $pilgrimage_work->name }}
                            </a>
                        @endforeach
                    </p>
                    <p class='place'>
                        {{ $pilgrimage->place }}
                    </p>
                    <x-star-num-detail :starNum="$pilgrimage->average_star_num" :postNum="$pilgrimage->post_num" />
                    <x-interested type="animePilgrimage" :root="$pilgrimage" path="pilgrimages.interested.index"
                        :prop="['pilgrimage_id' => $pilgrimage->id]" isMultiple="false" />
                </div>
            @endforeach
        @endif
    </div>
    <div class='paginate'>
        {{ $pilgrimages->appends(request()->query())->links() }}
    </div>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('/js/search_category.js') }}"></script>
    <script src="{{ asset('/js/interested_user.js') }}"></script>
</x-app-layout>
