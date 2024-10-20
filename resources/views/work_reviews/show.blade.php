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
        {{ $post->post_title }}
    </h1>
    <div class="content">
        <div class="content__post">
            <h3>作品名</h3>
            <p>{{ $post->work->name }}</p>
            <h3>投稿者</h3>
            <p>{{ $post->user->name }}</p>
            <h3>本文</h3>
            <p>{{ $post->body }}</p>
            <h3>作成日</h3>
            <p>{{ $post->created_at }}</p>
        </div>
    </div>
    <div class="edit">
        <a href="{{ route('work_reviews.edit', ['work_id' => $post->work_id, 'post_id' => $post->id]) }}">編集する</a>
    </div>
    <form action="/work_reviews/{{ $post->id }}" id="form_{{ $post->id }}" method="post">
        @csrf
        @method('DELETE')
        <button type="button" data-post-id="{{ $post->id }}" class="delete-button">投稿を削除する</button>
    </form>
    <div class="footer">
        <a href="{{ route('work_reviews.index', ['work_id' => $post->work_id]) }}">戻る</a>
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