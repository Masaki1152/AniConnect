<x-app-layout>
    <div id="message"
        class="hidden fixed top-[15%] left-1/2 transform -translate-x-1/2 bg-green-500/50 text-white px-6 py-3 rounded-lg shadow-lg flex items-center space-x-4 z-50">
    </div>

    <h1>お知らせ一覧</h1>
    <!-- 検索機能 -->
    <div class=serch>
        <form action="{{ route('notifications.index') }}" method="GET">
            <!-- キーワード検索 -->
            <input type="text" name="search" id="search", value="{{ request('search') }}" placeholder="キーワードを検索"
                aria-label="検索...">
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
            <input type="submit" value="検索">
        </form>
        <div class="cancel">
            <a href="{{ route('notifications.index') }}">キャンセル</a>
        </div>
    </div>
    <div class='notifications'>
        <!-- 検索結果がない場合 -->
        @if ($notifications->isEmpty())
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
                    全投稿：<span class="text-blue-500">{{ $totalResults }}</span>件
                </p>
            @endif
            <div class='notification_list'>
                @foreach ($notifications as $notification)
                    <div class='notification_post'>
                        <h2 class='title'>
                            <a
                                href="{{ route('notifications.show', ['notification_id' => $notification->id]) }}">{{ $notification->title }}</a>
                        </h2>
                        <h5 class='category flex gap-2'>
                            @foreach ($notification->categories as $category)
                                <span class="text-white px-2 py-1 rounded-full text-sm"
                                    style="background-color: {{ getCategoryColor($category->name) }};">
                                    {{ $category->name }}
                                </span>
                            @endforeach
                        </h5>
                        <div class='created_at'>
                            <p>{{ $notification->created_at->format('Y/m/d H:i') }}</p>
                        </div>
                        <div class='comment_num'>
                            @if ($notification->notificationComments)
                                <p>コメント:{{ count($notification->notificationComments) }}件</p>
                            @else
                                <p>コメント:0件</p>
                            @endif
                        </div>
                        <x-molecules.button.like-post-button type="notification" :post="$notification" />
                    </div>
                @endforeach
            </div>
        @endif
    </div>
    <div class='paginate'>
        {{ $notifications->appends(request()->query())->links() }}
    </div>
    <script src="{{ asset('/js/like_post.js') }}"></script>
    <script src="{{ asset('/js/search_category.js') }}"></script>
</x-app-layout>
