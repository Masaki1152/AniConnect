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
    <div class="edit"><a href="/work_reviews/{{ $post->id }}/edit">編集する</a></div>
    <form action="/work_reviews/{{ $post->id }}" id="form_{{ $post->id }}" method="post">
        @csrf
        @method('DELETE')
        <button type="button" onclick="deletePost({{ $post->id }})">投稿を削除する</button>
    </form>
    <div class="footer">
        <a href="/">戻る</a>
    </div>

    <script>
        function deletePost(id) {
            'use strict'

            if (confirm('削除すると復元できません。\n本当に削除しますか？')) {
                document.getElementById(`form_${id}`).submit();
            }
        }
    </script>
</body>

</html>