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
                <h5 class='category'>
                    @foreach($work_review->categories as $category)
                    {{ $category->name }}
                    @endforeach
                </h5>
                <p class='body'>{{ $work_review->body }}</p>
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
    </script>
</body>

</html>