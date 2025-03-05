<x-app-layout>
    <div id="message"
        class="hidden fixed top-[15%] left-1/2 transform -translate-x-1/2 bg-green-500/50 text-white px-6 py-3 rounded-lg shadow-lg flex items-center space-x-4 z-50">
    </div>
    <h1>「{{ $work_story_model->work->name }}」のあらすじ一覧</h1>
    <!-- 検索機能 -->
    <div class=serch>
        <form action="{{ route('work_stories.index', ['work_id' => $work_story_model->work_id]) }}" method="GET">
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
            <a href="{{ route('work_stories.index', ['work_id' => $work_story_model->work_id]) }}">キャンセル</a>
        </div>
    </div>
    <div>
        <p>あらすじ、話数、内容など何でも検索してみましょう！</p>
        <p>各あらすじのカテゴリーは、登録メンバーの皆さんの投稿を元に随時更新されています！</p>
        @if (!empty($work_story_model->top_categories_updated_at))
            <p>{{ $work_story_model->top_categories_updated_at->format('Y/m/d H:i') }}更新</p>
        @endif
    </div>
    <div class='work_stories'>
        @if ($work_stories->isEmpty())
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
                    全あらすじ：<span class="text-blue-500">{{ $totalResults }}</span>件
                </p>
            @endif
            @foreach ($work_stories as $work_story)
                <div class='work_story'>
                    <h2 class='episode'>
                        {{ $work_story->episode }}
                    </h2>
                    <p class='sub_title'>
                        <a
                            href="{{ route('work_stories.show', ['work_id' => $work_story->work_id, 'work_story_id' => $work_story->id]) }}">
                            {{ $work_story->sub_title }}
                        </a>
                    </p>
                    <!-- 上位3カテゴリー -->
                    <h5 class='category flex gap-2'>
                        @if ($work_story->top_categories->isNotEmpty())
                            @foreach ($work_story->top_categories as $category)
                                <span class="text-white px-2 py-1 rounded-full text-sm"
                                    style="background-color: {{ $category['color'] }};">
                                    {{ $category['name'] }}
                                </span>
                            @endforeach
                        @else
                            <p>カテゴリー情報がありません。</p>
                        @endif
                    </h5>
                    <x-interested type="workStories" :root="$work_story" path="work_stories.interested.index"
                        :prop="['work_id' => $work_story->work_id, 'work_story_id' => $work_story->id]" isMultiple="true" />
                </div>
            @endforeach
        @endif
    </div>
    <div class='paginate'>
        {{ $work_stories->appends(request()->query())->links() }}
    </div>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('/js/search_category.js') }}"></script>
    <script src="{{ asset('/js/interested_user.js') }}"></script>
</x-app-layout>
