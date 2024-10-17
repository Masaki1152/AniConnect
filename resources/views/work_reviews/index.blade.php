<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <title>Blog</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
</head>

<body>
    <h1>Blog Name</h1>
    <a href='/work_reviews/create'>新規投稿作成</a>
    <div class='posts'>
        <div class='post'>
            @foreach ($posts as $post)
            <div class='post'>
                <h2 class='title'>
                    <a href="/work_reviews/{{ $post->id }}">{{ $post->post_title }}</a>
                </h2>
                <p class='body'>{{ $post->body }}</p>
                <form action="/work_reviews/{{ $post->id }}" id="form_{{ $post->id }}" method="post">
                    @csrf
                    @method('DELETE')
                    <button type="button" onclick="deletePost({{ $post->id }})">投稿を削除する</button>
                </form>
            </div>
            @endforeach
        </div>
    </div>
    <div class='paginate'>
        {{ $posts->links() }}
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