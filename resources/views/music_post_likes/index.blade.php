<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <title>Blog</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
</head>

<body>
    <h1>いいねしたユーザー</h1>
    <div class='users'>
        @if($users->count() == 0)
            <h2>いいねしたユーザーはいません。</h2>
        @else
            @foreach ($users as $user)
            <div class='user'>
                <h2 class='name'>
                    <!-- あとでユーザー名タップでプロフィール画面に遷移するリンクを作成予定 -->
                    {{ $user->name }}
                </h2>
            </div>
            @endforeach
        @endif
    </div>
</body>

</html>