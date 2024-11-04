<!DOCTYPE HTML>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>声優詳細画面</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
</head>

<body>
    <h1 class="title">
        {{ $voice_artist->name }}
    </h1>
    <div class="content">
        <div class="content__voice_artist">
            <h3>名前</h3>
            <p>{{ $voice_artist->name }}</p>
            <h3>Wikipediaへのリンク</h3>
            <p>{{ $voice_artist->wiki_link }}</p>
            <div class='characters'>
                <h3>作品一覧</h3>
                @if($voice_artist->characters->isEmpty())
                <h3 class='no_character'>結果がありません。</h3>
                @else
                @foreach ($voice_artist->characters as $character)
                <div class='character_name'>
                    <a href="{{ route('characters.show', ['character_id' => $character->id]) }}">
                        {{ $character->name }}
                    </a>
                </div>
            </div>
            @endforeach
            @endif
        </div>
    </div>
    </div>
</body>

</html>