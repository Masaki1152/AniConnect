<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <title>Blog</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
</head>

<body>
    <h1 class="title">{{ $music_post->music->name }}への投稿編集画面</h1>
    <div class="content">
        <form action="{{ route('music_posts.update', ['music_id' => $music_post->music_id, 'music_post_id' => $music_post->id]) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="work_id">
                <input type="hidden" name="music_post[work_id]" value="{{ $music_post->work_id }}">
            </div>
            <div class="music_id">
                <input type="hidden" name="music_post[music_id]" value="{{ $music_post->music_id }}">
            </div>
            <div class="user_id">
                <input type="hidden" name="music_post[user_id]" value="{{ $music_post->user_id }}">
            </div>
            <div class="title">
                <h2>タイトル</h2>
                <input type="text" name="music_post[post_title]" placeholder="タイトル" value="{{ $music_post->post_title }}" />
                <p class="title__error" style="color:red">{{ $errors->first('music_post.post_title') }}</p>
            </div>
            <div class="star_num">
                <h2>星の数</h2>
                <select name="music_post[star_num]">
                    @php
                    $numbers = array(1 => '★', 2 => '★★', 3 => '★★★', 4 => '★★★★', 5 => '★★★★★');
                    @endphp
                    @foreach($numbers as $num => $star)
                    <option value="{{ $num }}" @if($music_post->star_num == $num) selected @endif>
                        {{$star}}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="body">
                <h2>内容</h2>
                <textarea name="music_post[body]" placeholder="内容を記入してください。">{{ $music_post->body }}</textarea>
                <p class="body__error" style="color:red">{{ $errors->first('music_post.body') }}</p>
            </div>
            <button type="submit">変更を保存する</button>
        </form>
    </div>
    <div class="footer">
        <a href="{{ route('music_posts.show', ['music_id' => $music_post->music_id, 'music_post_id' => $music_post->id]) }}">保存しないで戻る</a>
    </div>
</body>

</html>