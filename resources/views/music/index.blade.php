<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <title>音楽一覧</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
</head>

<body>
    <h1>音楽一覧</h1>
    <!-- 検索機能 -->
    <div class=serch>
        <form action="{{ route('music.index') }}" method="GET">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="キーワードを検索" aria-label="検索...">
            <input type="submit" value="検索">
        </form>
        <div class="cancel">
            <a href="{{ route('music.index') }}">キャンセル</a>
        </div>
    </div>
    <div class='music_collection'>
        @if($music->isEmpty())
        <h2 class='no_result'>結果がありません。</h2>
        @else
        @foreach ($music as $music_model)
        <div class='music'>
            <h2 class='name'>
                <a href="{{ route('music.show', ['music_id' => $music_model->id]) }}">
                    {{ $music_model->name }}
                </a>
            </h2>
            <p class='work'>
                <a href="{{ route('works.show', ['work' => $music_model->work_id]) }}">
                    {{ $music_model->work->name }}
                </a>
            </p>
            <p class='singer'>
                歌手:{{ $music_model->singer_id }}
            </p>
        </div>
        @endforeach
        @endif
    </div>
    <div class='paginate'>
        {{ $music->appends(request()->query())->links() }}
    </div>
</body>

</html>