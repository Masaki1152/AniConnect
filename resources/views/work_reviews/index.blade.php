<x-app-layout>
    <h1>「{{ $work->work->name }}」の感想投稿一覧</h1>
    <a href="{{ route('work_reviews.create', ['work_id' => $work->work_id]) }}">新規投稿作成</a>
    <!-- 検索機能 -->
    <div class=search>
        <form action="{{ route('work_reviews.index', ['work_id' => $work->work->id]) }}" method="GET">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="キーワードを検索" aria-label="検索...">
            <input type="submit" value="キーワード検索">
        </form>
        <div class="cancel">
            <a href="{{ route('work_reviews.index', ['work_id' => $work->work->id]) }}">キャンセル</a>
        </div>
    </div>
    <div class='work_reviews'>
        @if($work_reviews->isEmpty())
        <h2 class='no_result'>結果がありません。</h2>
        @else
        <div class='work_review'>
            @foreach ($work_reviews as $work_review)
            <div class='work_review'>
                <h2 class='title'>
                    <a href="{{ route('work_reviews.show', ['work_id' => $work_review->work_id, 'work_review_id' => $work_review->id]) }}">{{ $work_review->post_title }}</a>
                </h2>
                <div class="like">

                    <!-- ボタンの見た目は後のデザイン作成の際に設定する予定 -->
                    <button id="like_button"
                        data-work-id="{{ $work_review->work_id }}"
                        data-review-id="{{ $work_review->id }}"
                        type="submit">
                        {{ $work_review->users->contains(auth()->user()) ? 'いいね取り消し' : 'いいね' }}
                    </button>
                    <div class="like_user">
                        <a href="{{ route('work_review_like.index', ['work_id' => $work_review->work_id, 'work_review_id' => $work_review->id]) }}">
                            <p id="like_count">{{ $work_review->users->count() }}</p>
                        </a>
                    </div>
                </div>
                <h5 class='category'>
                    @foreach($work_review->categories as $category)
                    {{ $category->name }}
                    @endforeach
                </h5>
                <p class='body'>{{ $work_review->body }}</p>
                @if($work_review->image1)
                <div>
                    <img src="{{ $work_review->image1 }}" alt="画像が読み込めません。">
                </div>
                @endif
                <form action="{{ route('work_reviews.delete', ['work_id' => $work_review->work_id, 'work_review_id' => $work_review->id]) }}" id="form_{{ $work_review->id }}" method="post">
                    @csrf
                    @method('DELETE')
                    <button type="button" data-post-id="{{ $work_review->id }}" class="delete-button">投稿を削除する</button>
                </form>
            </div>
            @endforeach
        </div>
        @endif
    </div>
    <div class="footer">
        <a href="/works/{{ $work->work->id }}">作品詳細画面へ</a>
    </div>
    <div class='paginate'>
        {{ $work_reviews->appends(request()->query())->links() }}
    </div>

    <script>
        // DOMツリー読み取り完了後にイベント発火
        document.addEventListener('DOMContentLoaded', function() {
            // delete-buttonに一致するすべてのHTML要素を取得
            document.querySelectorAll('.delete-button').forEach(function(button) {
                button.addEventListener('click', function() {
                    const postId = button.getAttribute('data-post-id');
                    deletePost(postId);
                });
            });
        });

        // 削除処理を行う
        function deletePost(postId) {
            'use strict'

            if (confirm('削除すると復元できません。\n本当に削除しますか？')) {
                document.getElementById(`form_${postId}`).submit();
            }
        }

        // いいね処理を非同期で行う
        document.addEventListener('DOMContentLoaded', function() {
            const likeClasses = document.querySelectorAll('.like');
            likeClasses.forEach(element => {
                // いいねボタンのクラスの取得
                let button = element.querySelector('#like_button');
                // いいねしたユーザー数のクラス取得とpタグの取得
                let likeClass = element.querySelector('.like_user');
                let users = likeClass.querySelector('#like_count');

                //いいねボタンクリックによる非同期処理
                button.addEventListener('click', async function() {
                    const workId = button.getAttribute('data-work-id');
                    const reviewId = button.getAttribute('data-review-id');
                    try {
                        const response = await fetch(`/work_reviews/${workId}/reviews/${reviewId}/like`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                        });
                        const data = await response.json();
                        if (data.status === 'liked') {
                            button.innerText = 'いいね取り消し';
                            users.innerText = data.like_user;
                        } else if (data.status === 'unliked') {
                            button.innerText = 'いいね';
                            users.innerText = data.like_user;
                        }
                    } catch (error) {
                        console.error('Error:', error);
                    }
                });
            });
        });
    </script>
</x-app-layout>