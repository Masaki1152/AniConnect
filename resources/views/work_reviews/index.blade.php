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

    <!-- 投稿がない場合 -->
    @if (is_null($work_review_first))
        <h2 class='no_post_result'>「{{ $work->name }}」への感想投稿はまだありません。<br>1人目の投稿者になってみましょう！</h2>
        <a href="{{ route('work_reviews.create', ['work_id' => $work_id]) }}">新規投稿作成</a>
        <!-- 投稿がある場合 -->
    @else
        <h1>「{{ $work_review_first->work->name }}」の感想投稿一覧</h1>
        <a href="{{ route('work_reviews.create', ['work_id' => $work_id]) }}">新規投稿作成</a>
        <!-- 検索機能 -->
        <div class=search>
            <form action="{{ route('work_reviews.index', ['work_id' => $work_review_first->work->id]) }}"
                method="GET">
                <!-- キーワード検索 -->
                <input type="text" name="search" id="search", value="{{ request('search') }}"
                    placeholder="キーワードを検索" aria-label="検索...">
                <!-- カテゴリー検索機能 -->
                <div>
                    <button id='toggleCategories' type='button'>カテゴリーで絞り込む</button>
                    <button id='closeCategories'>閉じる</button>
                    <div id='categoryFilter' style='display: none;'>
                        <h2>カテゴリー</h2>
                        <ul id='categoryList'></ul>
                    </div>
                    <input type='hidden' id='checkedCategories' name='checkedCategories[]' value=''>
                </div>
                <input type="submit" value="検索">
            </form>
            <div class="cancel">
                <a href="{{ route('work_reviews.index', ['work_id' => $work_review_first->work->id]) }}">キャンセル</a>
            </div>
        </div>
        <div class='work_reviews'>
            @if ($work_reviews->isEmpty())
                <h2 class='no_result'>結果がありません。</h2>
            @else
                <div class='work_review'>
                    @foreach ($work_reviews as $work_review)
                        <div class='work_review'>
                            <h2 class='title'>
                                <a
                                    href="{{ route('work_reviews.show', ['work_id' => $work_review->work_id, 'work_review_id' => $work_review->id]) }}">{{ $work_review->post_title }}</a>
                            </h2>
                            <div class='user'>
                                <p>{{ $work_review->user->name }}</p>
                            </div>
                            <div class='created_at'>
                                <p>{{ $work_review->created_at }}</p>
                            </div>
                            <div class="like">

                                <!-- ボタンの見た目は後のデザイン作成の際に設定する予定 -->
                                <button id="like_button" data-work-id="{{ $work_review->work_id }}"
                                    data-review-id="{{ $work_review->id }}" type="submit">
                                    {{ $work_review->users->contains(auth()->user()) ? 'いいね取り消し' : 'いいね' }}
                                </button>
                                <div class="like_user">
                                    <a
                                        href="{{ route('work_review_like.index', ['work_id' => $work_review->work_id, 'work_review_id' => $work_review->id]) }}">
                                        <p id="like_count">{{ $work_review->users->count() }}</p>
                                    </a>
                                </div>
                            </div>
                            <h5 class='category flex gap-2'>
                                @foreach ($work_review->categories as $category)
                                    <span class="bg-blue-500 text-white px-2 py-1 rounded-full text-sm">
                                        {{ $category->name }}
                                    </span>
                                @endforeach
                            </h5>
                            <p class='body'>{{ $work_review->body }}</p>
                            @if ($work_review->image1)
                                <div>
                                    <img src="{{ $work_review->image1 }}" alt="画像が読み込めません。">
                                </div>
                            @endif
                            <form
                                action="{{ route('work_reviews.delete', ['work_id' => $work_review->work_id, 'work_review_id' => $work_review->id]) }}"
                                id="form_{{ $work_review->id }}" method="post">
                                @csrf
                                @method('DELETE')
                                <button type="button" data-post-id="{{ $work_review->id }}"
                                    class="delete-button">投稿を削除する</button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
        <div class="footer">
            <a href="/works/{{ $work_review_first->work->id }}">作品詳細画面へ</a>
        </div>
        <div class='paginate'>
            {{ $work_reviews->appends(request()->query())->links() }}
        </div>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <script src="{{ asset('/js/like_posts/like_work_post.js') }}"></script>
        <script src="{{ asset('/js/delete_post.js') }}"></script>
        <script>
            const workId = <?php echo $work_id; ?>;

            document.addEventListener('DOMContentLoaded', function() {
                // カテゴリー一覧を取得して表示
                fetch(`/work_reviews/${workId}/categories`)
                    .then(response => response.json())
                    .then(categories => {
                        const categoryList = document.getElementById('categoryList');
                        categories.forEach(category => {
                            const li = document.createElement('li');
                            const checkbox = document.createElement('input');
                            checkbox.type = 'checkbox';
                            checkbox.classList.add('categoryCheckbox');
                            checkbox.setAttribute('data-id', category.id);
                            li.appendChild(checkbox);
                            li.appendChild(document.createTextNode(category.name));
                            categoryList.appendChild(li);
                        });
                    });
                // 「カテゴリーで絞り込む」ボタンの表示/非表示
                const toggleCategoriesButton = document.getElementById('toggleCategories');
                const categoryFilter = document.getElementById('categoryFilter');
                toggleCategoriesButton.addEventListener('click', () => {
                    categoryFilter.style.display = categoryFilter.style.display === 'none' ? 'block' : 'none';
                });
                // カテゴリーの選択状態が変わったときの処理
                const categoryList = document.getElementById('categoryList');
                const checkedCategories = document.getElementById('checkedCategories');
                categoryList.addEventListener('change', function() {
                    // 選択されているカテゴリーを取得してHTMLに反映
                    const selectedCategories = [];
                    const checkboxes = document.querySelectorAll('.categoryCheckbox:checked');
                    checkboxes.forEach(checkbox => {
                        selectedCategories.push(checkbox.getAttribute('data-id'));
                    });
                    // 配列を格納
                    checkedCategories.value = selectedCategories;
                });

                // 選択されているカテゴリーを取得してHTMLに反映する関数
                function getSelectedCategories() {
                    const selectedCategories = [];
                    const checkedCategories = document.getElementById('checkedCategories');
                    const checkboxes = document.querySelectorAll('.categoryCheckbox:checked');
                    checkboxes.forEach(checkbox => {
                        selectedCategories.push(checkbox.getAttribute('data-id'));
                    });
                    checkedCategories.value = [];
                    checkedCategories.value = selectedCategories;
                }
            });
        </script>
    @endif
</x-app-layout>
