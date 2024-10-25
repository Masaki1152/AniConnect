<!DOCTYPE HTML>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>作品の感想</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
</head>

<body>
    <h1 class="title">
        {{ $work_review->post_title }}
    </h1>
    <div class="like">
        <form action="{{ route('work_reviews.like', ['work_id' => $work_review->work_id, 'work_review_id' => $work_review->id]) }}" method="POST">
            @csrf
            <!-- ボタンの見た目は後のデザイン作成の際に設定する予定 -->
            <button type="submit" class="{{ $work_review->users->contains(auth()->user()) ? 'bg-red-500 hover:bg-red-700' : 'bg-blue-500 hover:bg-blue-700' }} text-white font-bold py-2 px-4 rounded">
                {{ $work_review->users->contains(auth()->user()) ? 'いいね取り消し' : 'いいね' }}
            </button>
        </form>
        {{ $work_review->users->count() }}
    </div>
    <div class="content">
        <div class="content__work_review">
            <h3>作品名</h3>
            <p>{{ $work_review->work->name }}</p>
            <h3>投稿者</h3>
            <p>{{ $work_review->user->name }}</p>
            <h3>カテゴリー</h3>
            <h5 class='category'>
                @foreach($work_review->categories as $category)
                {{ $category->name }}
                @endforeach
            </h5>
            <h3>本文</h3>
            <p>{{ $work_review->body }}</p>
            <h3>作成日</h3>
            <p>{{ $work_review->created_at }}</p>
        </div>
    </div>
    <div class="edit">
        <a href="{{ route('work_reviews.edit', ['work_id' => $work_review->work_id, 'work_review_id' => $work_review->id]) }}">編集する</a>
    </div>
    <form action="{{ route('work_reviews.delete', ['work_id' => $work_review->work_id, 'work_review_id' => $work_review->id]) }}" id="form_{{ $work_review->id }}" method="post">
        @csrf
        @method('DELETE')
        <button type="button" data-post-id="{{ $work_review->id }}" class="delete-button">投稿を削除する</button>
    </form>
    <div class="footer">
        <a href="{{ route('work_reviews.index', ['work_id' => $work_review->work_id]) }}">戻る</a>
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