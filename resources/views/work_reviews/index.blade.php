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
            </div>
            @endforeach
        </div>
    </div>
    <div class='paginate'>
        {{ $posts->links() }}
    </div>
</body>

</html>