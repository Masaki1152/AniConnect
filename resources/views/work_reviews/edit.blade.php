<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <title>Blog</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
</head>

<body>
    <h1 class="title">投稿の編集画面</h1>
    <div class="content">
        <form action="/work_reviews/{{ $post->id }}" method="POST">
            @csrf
            @method('PUT')
            <div class="work_id">
                <h2>作品名</h2>
                <input type="text" name="work_review[work_id]" placeholder="作品名" value="{{ $post->work_id }}"/>
                <p class="id__error" style="color:red">{{ $errors->first('work_review.work_id') }}</p>
            </div>
            <div class="title">
                <h2>タイトル</h2>
                <input type="text" name="work_review[post_title]" placeholder="タイトル" value="{{ $post->post_title }}"/>
                <p class="title__error" style="color:red">{{ $errors->first('work_review.post_title') }}</p>
            </div>
            <div class="user_id">
                <h2>投稿者</h2>
                <input type="text" name="work_review[user_id]" placeholder="投稿者" value="{{ $post->user_id }}"/>
                <p class="user__error" style="color:red">{{ $errors->first('work_review.user_id') }}</p>
            </div>
            <div class="body">
                <h2>内容</h2>
                <textarea name="work_review[body]" placeholder="内容を記入してください。">{{ $post->body }}</textarea>
                <p class="body__error" style="color:red">{{ $errors->first('work_review.body') }}</p>
            </div>
            <input type="submit" value="保存する">
        </form>
    </div>
</body>