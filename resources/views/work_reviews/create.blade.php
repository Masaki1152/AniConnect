<!DOCTYPE HTML>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <title>作品の感想投稿</title>
    </head>
    <body>
        <h1>「{{$workreview->work->name}}」への新規感想投稿</h1>
        <form action="/work_reviews" method="POST">
            @csrf
            <div class="work_id">
                <h2>作品名</h2>
                <input type="text" name="work_review[work_id]" placeholder="作品名" value="{{ old('work_review.work_id') }}"/>
                <p class="id__error" style="color:red">{{ $errors->first('work_review.work_id') }}</p>
            </div>
            <div class="title">
                <h2>タイトル</h2>
                <input type="text" name="work_review[post_title]" placeholder="タイトル" value="{{ old('work_review.post_title') }}"/>
                <p class="title__error" style="color:red">{{ $errors->first('work_review.post_title') }}</p>
            </div>
            <div class="user_id">
                <h2>投稿者</h2>
                <input type="text" name="work_review[user_id]" placeholder="投稿者" value="{{ old('work_review.user_id') }}"/>
                <p class="user__error" style="color:red">{{ $errors->first('work_review.user_id') }}</p>
            </div>
            <div class="body">
                <h2>内容</h2>
                <textarea name="work_review[body]" placeholder="内容を記入してください。">{{ old('work_review.body') }}</textarea>
                <p class="body__error" style="color:red">{{ $errors->first('work_review.body') }}</p>
            </div>
            <input type="submit" value="作成する"/>
        </form>
        <div class="footer">
            <a href="/">戻る</a>
        </div>
    </body>
</html>