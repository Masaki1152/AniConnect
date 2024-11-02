<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <title>Blog</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
</head>

<body>
    <h1>「{{ $work->work->name }}」の感想投稿一覧</h1>
    <a href="{{ route('work_reviews.create', ['work_id' => $work->work_id]) }}">新規投稿作成</a>
    <div class='work_reviews'>
        <div class='work_review'>
            @foreach ($work_reviews as $work_review)
            <div class='work_review'>
                <h2 class='title'>
                    <a href="{{ route('work_reviews.show', ['work_id' => $work_review->work_id, 'work_review_id' => $work_review->id]) }}">{{ $work_review->post_title }}</a>
                </h2>
                <div class="like">
                    
                        <!-- ボタンの見た目は後のデザイン作成の際に設定する予定 -->
                        <button class="like-button"
                            data-work-id="{{ $work_review->work_id }}"
                            data-review-id="{{ $work_review->id }}"
                            type="submit">
                            
                        </button>
                    
                    <div class="like_user">
                        <a href="{{ route('work_review_like.index', ['work_id' => $work_review->work_id, 'work_review_id' => $work_review->id]) }}">
                            {{ $work_review->users->count() }}
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
    </div>
    <div class="footer">
        <a href="/works/{{ $work_review->work_id }}">作品詳細画面へ</a>
    </div>
    <div class='paginate'>
        {{ $work_reviews->links() }}
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

        document.addEventListener('DOMContentLoaded', function() {
            const likeButtons = document.querySelectorAll('.like-button');
            likeButtons.forEach(button => {
                button.addEventListener('click', async function() {
                    const workId = this.getAttribute('data-work-id');
                    const reviewId = this.getAttribute('data-review-id');
                    try {
                        const response = await fetch(`/work_reviews/${workId}/reviews/${reviewId}/like`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                        });
                        const data = await response.json();
                        alert(data);
                        if (data.status === 'liked') {
                            this.innerText = 'いいね';
                        } else if (data.status === 'unliked') {
                            this.innerText = 'いいね取り消し';
                        }
                    } catch (error) {
                        console.error('Error:', error);
                    }
                });
            });
        });
    </script>
</body>

</html>