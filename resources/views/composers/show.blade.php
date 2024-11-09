<!DOCTYPE HTML>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>作曲者詳細画面</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
</head>

<body>
    <h1 class="title">
        {{ $composer->name }}
    </h1>
    <div class="content">
        <div class="content__creator">
            <h3>名前</h3>
            <p>{{ $composer->name }}</p>
            <h3>Wikipediaへのリンク</h3>
            <p>{{ $composer->wiki_link }}</p>
            <div class='music'>
                <h3>音楽一覧</h3>
                @if($composer->music->isEmpty())
                <h3 class='no_work'>結果がありません。</h3>
                @else
                @foreach ($composer->music as $music)
                <div class='music_name'>
                    <a href="{{ route('music.show', ['music_id' => $music->id]) }}">
                        {{ $music->name }}
                    </a>
                </div>
                @endforeach
                @endif
            </div>
        </div>
    </div>
</body>

</html>