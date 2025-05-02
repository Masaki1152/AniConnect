<x-app-layout>
    @if (session('status'))
        <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)"
            class="fixed top-[15%] left-1/2 transform -translate-x-1/2 bg-red-500/50 text-white px-6 py-3 rounded-lg shadow-lg flex items-center space-x-4 z-50">
            <div class="text-white">
                {{ session('status') }}
            </div>
        </div>
    @endif
    <div id="like-message"
        class="hidden fixed top-[15%] left-1/2 transform -translate-x-1/2 bg-green-500/50 text-white px-6 py-3 rounded-lg shadow-lg flex items-center space-x-4 z-50">
    </div>

    @if (is_null($music_first))
        <h2 class='no_post_result'>「{{ $music->name }}」への感想投稿はまだありません。<br>1人目の投稿者になってみましょう！</h2>
        <a href="{{ route('music_posts.create', ['music_id' => $music->id]) }}">新規投稿作成</a>
    @else
        <h1>「{{ $music_first->music->name }}」の感想投稿一覧</h1>
        <a href="{{ route('music_posts.create', ['music_id' => $music_first->music_id]) }}">新規投稿作成</a>
        <!-- 検索機能 -->
        <div class=serch>
            <form action="{{ route('music_posts.index', ['music_id' => $music_first->music_id]) }}" method="GET">
                <!-- キーワード検索 -->
                <input type="text" name="search" id="search", value="{{ request('search') }}"
                    placeholder="キーワードを検索" aria-label="検索...">
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
                <a href="{{ route('music_posts.index', ['music_id' => $music_first->music_id]) }}">キャンセル</a>
            </div>
        </div>
        <!-- 検索結果がない場合 -->
        <div class='music_posts'>
            @if ($music_posts->isEmpty())
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
                <div class='music_post'>
                    @foreach ($music_posts as $music_post)
                        <div class='music_post'>
                            <h2 class='title'>
                                <a
                                    href="{{ route('music_posts.show', ['music_id' => $music_post->music_id, 'music_post_id' => $music_post->id]) }}">{{ $music_post->post_title }}</a>
                            </h2>
                            <p>{{ $music_post->user->name }}</p>
                            <div class='created_at'>
                                <p>{{ $music_post->created_at->format('Y/m/d H:i') }}</p>
                            </div>
                            <x-molecules.evaluation.star-num :starNum="$music_post->star_num" />
                            <div class="like">
                                <!-- ボタンの見た目は後のデザイン作成の際に設定する予定 -->
                                <button id="like_button" data-music-id="{{ $music_post->music_id }}"
                                    data-post-id="{{ $music_post->id }}" type="submit">
                                    {{ $music_post->users->contains(auth()->user()) ? 'いいね取り消し' : 'いいね' }}
                                </button>
                                <div class="like_user">
                                    <a
                                        href="{{ route('music_post_like.index', ['music_id' => $music_post->music_id, 'music_post_id' => $music_post->id]) }}">
                                        <p id="like_count">{{ $music_post->users->count() }}</p>
                                    </a>
                                </div>
                            </div>
                            <h5 class='category flex gap-2'>
                                @foreach ($music_post->categories as $category)
                                    <span class="text-white px-2 py-1 rounded-full text-sm"
                                        style="background-color: {{ getCategoryColor($category->name) }};">
                                        {{ $category->name }}
                                    </span>
                                @endforeach
                            </h5>
                            <p class='body'>{{ $music_post->body }}</p>
                            @if ($music_post->image1)
                                <div>
                                    <a href="{{ $music_post->image1 }}" data-lightbox="{{ $music_post->image1 }}"
                                        data-title="画像">
                                        <img src="{{ $music_post->image1 }}" alt="画像が読み込めません。"
                                            class='w-36 h-36 object-cover rounded-md border border-gray-300 mb-2'>
                                    </a>
                                </div>
                            @endif
                            <div class='comment_num'>
                                @if ($music_post->musicPostComments)
                                    <p>コメント:{{ count($music_post->musicPostComments) }}件</p>
                                @else
                                    <p>コメント:0件</p>
                                @endif
                            </div>
                            <form
                                action="{{ route('music_posts.delete', ['music_id' => $music_post->music_id, 'music_post_id' => $music_post->id]) }}"
                                id="form_{{ $music_post->id }}" method="post">
                                @csrf
                                @method('DELETE')
                                <button type="button" data-post-id="{{ $music_post->id }}"
                                    class="delete-button">投稿を削除する</button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
        <div class="footer">
            <a href="{{ route('music.show', ['music_id' => $music->id]) }}">音楽詳細画面へ</a>
        </div>
        <div class='paginate'>
            {{ $music_posts->appends(request()->query())->links() }}
        </div>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <script src="{{ asset('/js/like_posts/like_music_post.js') }}"></script>
        <script src="{{ asset('/js/delete_post.js') }}"></script>
        <script src="{{ asset('/js/search_category.js') }}"></script>
    @endif
</x-app-layout>
