<!DOCTYPE HTML>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>音楽詳細画面</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
</head>

<body>
    <h1 class="title">
        {{ $music->name }}
    </h1>
    <div class="content">
        <div class="content__music">
            <h3>名前</h3>
            <p>{{ $music->name }}</p>
            <div class='singer'>
                <h3>歌手</h3>
                <a href="{{ route('singer.show', ['singer_id' => $music->singer_id]) }}">
                    {{ $music->singer->name }}
                </a>
            </div>
            <div class='work'>
                <h3>登場作品</h3>
                <a href="{{ route('works.show', ['work' => $music->work_id]) }}">
                    {{ $music->work->name }}
                </a>
            </div>
            <div class='lyric_writer'>
                <h3>作詞者</h3>
                <a href="{{ route('lyric_writer.show', ['lyric_writer_id' => $music->lyric_writer_id]) }}">
                    {{ $music->lyricWriter->name }}
                </a>
            </div>
            <div class='composer'>
            <h3>作曲者</h3>
            <a href="{{ route('composer.show', ['composer_id' => $music->composer_id]) }}">
                    {{ $music->composer->name }}
                </a>
            </div>
            <h3>Wikipediaへのリンク</h3>
            <p>{{ $music->wiki_link }}</p>
            <h3>YouTubeのリンク</h3>
            <p>{{ $music->youtube_link }}</p>
        </div>
    </div>
    <div class="post_link">
        <a href="{{ route('music_posts.index', ['music_id' => $music->id]) }}">音楽感想一覧</a>
    </div>
</body>

</html>