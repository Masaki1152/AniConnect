<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <title>Blog</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
</head>

<body>
    <h1 class="title">{{ $post->work->name }}への投稿編集画面</h1>
    <div class="content">
        <form action="{{ route('work_reviews.update', ['work_id' => $post->work_id, 'post_id' => $post->id]) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="work_id">
                <input type="hidden" name="work_review[work_id]" value="{{ $post->work_id }}">
            </div>
            <div class="user_id">
                <input type="hidden" name="work_review[user_id]" value="{{ $post->user_id }}">
            </div>
            <div class="title">
                <h2>タイトル</h2>
                <input type="text" name="work_review[post_title]" placeholder="タイトル" value="{{ $post->post_title }}"/>
                <p class="title__error" style="color:red">{{ $errors->first('work_review.post_title') }}</p>
            </div>
            <div class="body">
                <h2>内容</h2>
                <textarea name="work_review[body]" placeholder="内容を記入してください。">{{ $post->body }}</textarea>
                <p class="body__error" style="color:red">{{ $errors->first('work_review.body') }}</p>
            </div>
            <button type="submit">変更を保存する</button>
        </form>
    </div>
    <div class="footer">
        <a href="{{ route('work_reviews.show', ['work_id' => $post->work_id, 'post_id' => $post->id]) }}">保存しないで戻る</a>
    </div>
</body>