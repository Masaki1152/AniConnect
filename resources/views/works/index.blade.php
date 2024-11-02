<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <title>Blog</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
</head>

<body>
    <h1>作品一覧</h1>
    <!-- 検索機能 -->
    <div class=serch>
        <form action="{{ route('works.index') }}" method="GET">
            <input type="text" name="search" value="{{ request('serch') }}" placeholder="キーワードを検索" aria-label="検索...">
            <input type="submit" value="検索">
        </form>
    </div>
    <div class='works'>
        @if($works->isEmpty())
        <h2 class='no_result'>結果がありません。</h2>
        @else
            @foreach ($works as $work)
            <div class='work'>
                <h2 class='name'>
                    <a href="/works/{{ $work->id }}">{{ $work->name }}</a>
                </h2>
                <p class='term'>{{ $work->term }}</p>
            </div>
            @endforeach
        @endif
    </div>
    <div class='paginate'>
        {{ $works->links() }}
    </div>
</body>

</html>