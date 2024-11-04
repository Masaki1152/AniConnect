<!DOCTYPE HTML>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>制作会社詳細画面</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
</head>

<body>
    <h1 class="title">
        {{ $creator->name }}
    </h1>
    <div class="content">
        <div class="content__creator">
            <h3>会社名</h3>
            <p>{{ $creator->name }}</p>
            <h3>Wikipediaへのリンク</h3>
            <p>{{ $creator->wiki_link }}</p>
            <div class='works'>
                <h3>作品一覧</h3>
                @if($creator->works->isEmpty())
                <h3 class='no_work'>結果がありません。</h3>
                @else
                @foreach ($creator->works as $work)
                <div class='work_name'>
                    <h3>{{ $work->name }}</h3>
                </div>
                @endforeach
                @endif
            </div>
        </div>
    </div>
</body>

</html>