<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <title>登場人物一覧</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
</head>

<body>
    <h1>登場人物一覧</h1>
    <!-- 検索機能 -->
    <div class=serch>
        <form action="{{ route('characters.index') }}" method="GET">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="キーワードを検索" aria-label="検索...">
            <input type="submit" value="検索">
        </form>
        <div class="cancel">
            <a href="{{ route('characters.index') }}">キャンセル</a>
        </div>
    </div>
    <div class='characters'>
        @if($characters->isEmpty())
        <h2 class='no_result'>結果がありません。</h2>
        @else
        @foreach ($characters as $character)
        <div class='character'>
            <h2 class='name'>
                <a href="{{ route('characters.show', ['character_id' => $character->id]) }}">
                    {{ $character->name }}
                </a>
            </h2>
            <p class='work'>
                <a href="{{ route('works.show', ['work' => $character->work->id]) }}">
                    {{ $character->work->name }}
                </a>
            </p>
            <p class='voice_artist'>
            <a href="{{ route('voice_artist.show', ['voice_artist_id' => $character->voiceArtist->id]) }}">
                CV:{{ $character->voiceArtist->name }}
            </a>
            </p>
        </div>
        @endforeach
        @endif
    </div>
    <div class='paginate'>
        {{ $characters->appends(request()->query())->links() }}
    </div>
</body>

</html>