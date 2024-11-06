<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <title>聖地一覧</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
</head>

<body>
    <h1>聖地一覧</h1>
    <!-- 検索機能 -->
    <div class=serch>
        <form action="{{ route('pilgrimages.index') }}" method="GET">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="キーワードを検索" aria-label="検索...">
            <input type="submit" value="検索">
        </form>
        <div class="cancel">
            <a href="{{ route('pilgrimages.index') }}">キャンセル</a>
        </div>
    </div>
    <div class='pilgrimages'>
        @if($pilgrimages->isEmpty())
        <h2 class='no_result'>結果がありません。</h2>
        @else
        @foreach ($pilgrimages as $pilgrimage)
        <div class='pilgrimage'>
            <h2 class='name'>
                <a href="{{ route('pilgrimages.show', ['pilgrimage_id' => $pilgrimage->id]) }}">
                    {{ $pilgrimage->name }}
                </a>
            </h2>
            <p class='work'>
                <a href="{{ route('works.show', ['work' => $pilgrimage->work->id]) }}">
                    {{ $pilgrimage->work->name }}
                </a>
            </p>
            <p class='place'>
                    {{ $pilgrimage->place }}
            </p>
        </div>
        @endforeach
        @endif
    </div>
    <div class='paginate'>
        {{ $pilgrimages->appends(request()->query())->links() }}
    </div>
</body>

</html>