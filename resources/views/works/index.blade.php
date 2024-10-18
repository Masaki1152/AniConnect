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
    <div class='works'>
        @foreach ($works as $work)
        <div class='work'>
            <h2 class='title'>{{ $work->name }}</h2>
            <p class='term'>{{ $work->term }}</p>
        </div>
        @endforeach
    </div>
</body>

</html>