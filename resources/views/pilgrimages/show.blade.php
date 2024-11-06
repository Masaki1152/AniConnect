<!DOCTYPE HTML>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>聖地詳細画面</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
</head>

<body>
    <h1 class="title">
        {{ $pilgrimage->name }}
    </h1>
    <div class="content">
        <div class="content__pilgrimage">
            <h3>名前</h3>
            <p>{{ $pilgrimage->name }}</p>
            <div class='work'>
                <h3>登場作品</h3>
                <a href="{{ route('works.show', ['work' => $pilgrimage->work->id]) }}">
                    {{ $pilgrimage->work->name }}
                </a>
            </div>
            <h3>Google Mapsへのリンク</h3>
            <p>{{ $pilgrimage->map_link }}</p>
            <h3>Wikipediaへのリンク</h3>
            <p>{{ $pilgrimage->wiki_link }}</p>
        </div>
    </div>
    <div class="post_link">
        <a href="{{ route('character_posts.index', ['character_id' => $pilgrimage->id]) }}">聖地投稿一覧</a>
    </div>
    <div class="footer">
        <a href="/works">作品一覧へ</a>
    </div>
</body>

</html>