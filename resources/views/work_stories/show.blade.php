<!DOCTYPE HTML>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>あらすじ詳細画面</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
</head>

<body>
    <h1 class="title">
        {{ $work_story->sub_title }}
    </h1>
    <div class="content">
        <div class="content__work_story">
            <div class='episode'>
                <h3>話数</h3>
                <p>{{ $work_story->episode }}</p>
            </div>
            <div class='body'>
                <h3>あらすじ(公式サイトより)</h3>
                <a>{{ $work_story->body }}</a>
            </div>
            <div class='official_link'>
                <h3>公式サイトへのリンク</h3>
                <a>{{ $work_story->official_link }}</a>
            </div>
        </div>
    </div>
    <div class="post_link">
        <a href="{{ route('work_story_posts.index', ['work_id' => $work_story->work_id, 'work_story_id' => $work_story->id]) }}">あらすじ感想一覧</a>
    </div>
</body>

</html>