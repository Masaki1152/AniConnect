<x-app-layout>
    <h1>音楽一覧</h1>
    <!-- 検索機能 -->
    <div class=serch>
        <form action="{{ route('music.index') }}" method="GET">
            <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="キーワードを検索"
                aria-label="検索..."
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
        <div class="cancel">
            <a href="{{ route('music.index') }}">キャンセル</a>
        </div>
    </div>
    <div>
        <p>楽曲名、歌手、作曲者、作詞者、作品名、制作会社など何でも検索してみましょう！</p>
        <p>各音楽のカテゴリーは、登録メンバーの皆さんの投稿を元に随時更新されています！</p>
        @if (!empty($music_object->top_categories_updated_at))
            <p>{{ $music_object->top_categories_updated_at->format('Y/m/d H:i') }}更新</p>
        @endif
    </div>
    <div class='music_collection'>
        @if ($music->isEmpty())
            <h2 class="col-span-full text-center text-gray-500 text-lg font-semibold">
                @if (!empty($search))
                    キーワード： 「{{ $search }}」
                @endif
                @if (!empty($search) && !empty($selectedCategories))
                    、
                @endif
                @if (!empty($selectedCategories))
                    カテゴリー： 「{{ implode('、', $selectedCategories) }}」
                @endif
                に一致する結果はありませんでした。</p>
            </h2>
        @else
            <!-- 検索結果がある場合 -->
            @if (!empty($search) || !empty($selectedCategories))
                <p class="col-span-full text-center text-gray-700 text-lg font-semibold">
                    @if (!empty($search))
                        キーワード： 「{{ $search }}」
                    @endif
                    @if (!empty($search) && !empty($selectedCategories))
                        、
                    @endif
                    @if (!empty($selectedCategories))
                        カテゴリー： 「{{ implode('、', $selectedCategories) }}」
                    @endif
                    の検索結果：<span class="text-blue-500">{{ $totalResults }}</span>件
                </p>
            @endif
            @foreach ($music as $music_object)
                <div class='music'>
                    <h2 class='name'>
                        <a href="{{ route('music.show', ['music_id' => $music_object->id]) }}">
                            {{ $music_object->name }}
                        </a>
                    </h2>
                    <!-- 上位3カテゴリー -->
                    <h5 class='category flex gap-2'>
                        @if ($music_object->top_categories->isNotEmpty())
                            @foreach ($music_object->top_categories as $category)
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
                        <a href="{{ route('works.show', ['work' => $music_object->work_id]) }}">
                            {{ $music_object->work->name }}
                        </a>
                    </p>
                    <p class='singer'>
                        歌手:
                        <a href="{{ route('singer.show', ['singer_id' => $music_object->singer_id]) }}">
                            {{ $music_object->singer->name }}
                        </a>
                    </p>
                </div>
            @endforeach
        @endif
    </div>
    <div class='paginate'>
        {{ $music->appends(request()->query())->links() }}
    </div>
    <script src="{{ asset('/js/search_category.js') }}"></script>
</x-app-layout>
