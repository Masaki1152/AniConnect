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
            <div class='creator'>
                <h3>制作会社</h3>
                <a href="{{ route('creator.show', ['creator_id' => $work->creator->id]) }}">{{ $work->creator->name }}</a>
            </div>
            <div class='music'>
                <h3>楽曲</h3>
                @if($work->music->isEmpty())
                <h3 class='no_music'>結果がありません。</h3>
                @else
                @foreach ($work->music as $music)
                <div class='music_name'>
                    <h3>{{ $music->name }}</h3>
                </div>
                @endforeach
                @endif
            </div>
            <div class='character'>
                <h3>登場人物</h3>
                @if($work->characters->isEmpty())
                <h3 class='no_character'>結果がありません。</h3>
                @else
                @foreach ($work->characters as $character)
                <div class='character_name'>
                    <h3>{{ $character->name }}</h3>
                </div>
                @endforeach
                @endif
            </div>
            <div class='anime_pilgrimage'>
                <h3>聖地</h3>
                @if($work->animePilgrimages->isEmpty())
                <h3 class='no_anime_pilgrimage'>結果がありません。</h3>
                @else
                @foreach ($work->animePilgrimages as $anime_pilgrimage)
                <div class='anime_pilgrimage_name'>
                    <h3>{{ $anime_pilgrimage->name }}</h3>
                </div>
                @endforeach
                @endif
            </div>
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
        <a href="/works">作品一覧へ</a>
    </div>
</body>

</html>