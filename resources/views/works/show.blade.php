<!DOCTYPE HTML>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>作品詳細画面</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
</head>

<body>
    <h1 class="title">
        {{ $work->name }}
    </h1>
    <div class="content">
        <div class="content__post">
            <h3>作品名</h3>
            <p>{{ $work->name }}</p>
            <h3>放映期間</h3>
            <p>{{ $work->term }}</p>
            <h3>制作会社</h3>
            <p>{{ $work->creator_id }}</p>
            <h3>楽曲</h3>
            <p>{{ $work->music_id }}</p>
            <h3>登場人物</h3>
            <p>{{ $work->character_id }}</p>
            <h3>聖地</h3>
            <p>{{ $work->anime_pilgrimage_id }}</p>
            <h3>公式サイトへのリンク</h3>
            <p>{{ $work->official_site_link }}</p>
            <h3>Wikipediaへのリンク</h3>
            <p>{{ $work->wiki_link }}</p>
            <h3>Twitterへのリンク</h3>
            <p>{{ $work->twitter_link }}</p>
            <a href="{{ route('work_reviews.index', ['work_id' => $work->id]) }}">作品感想一覧</a>
        </div>
    </div>
    <div class="footer">
        <a href="/works">戻る</a>
    </div>
</body>

</html>