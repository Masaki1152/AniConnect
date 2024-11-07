<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <title>Blog</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
</head>

<body>
    <h1 class="title">{{ $pilgrimage_post->animePilgrimage->name }}への投稿編集画面</h1>
    <div class="content">
        <form action="{{ route('pilgrimage_posts.update', ['pilgrimage_id' => $pilgrimage_post->anime_pilgrimage_id, 'pilgrimage_post_id' => $pilgrimage_post->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="user_id">
                <input type="hidden" name="pilgrimage_post[user_id]" value="{{ $pilgrimage_post->user_id }}">
            </div>
            <div class="anime_pilgrimage_id">
                <input type="hidden" name="pilgrimage_post[anime_pilgrimage_id]" value="{{ $pilgrimage_post->anime_pilgrimage_id }}">
            </div>
            <div class="title">
                <h2>タイトル</h2>
                <input type="text" name="pilgrimage_post[title]" placeholder="タイトル" value="{{ $pilgrimage_post->title }}" />
                <p class="title__error" style="color:red">{{ $errors->first('pilgrimage_post.title') }}</p>
            </div>
            <div class="scene">
                <h2>シーン</h2>
                <input type="text" name="pilgrimage_post[scene]" placeholder="シーン" value="{{ $pilgrimage_post->scene }}" />
                <p class="title__error" style="color:red">{{ $errors->first('pilgrimage_post.scene') }}</p>
            </div>
            <div class="body">
                <h2>内容</h2>
                <textarea name="pilgrimage_post[body]" placeholder="内容を記入してください。">{{ $pilgrimage_post->body }}</textarea>
                <p class="body__error" style="color:red">{{ $errors->first('pilgrimage_post.body') }}</p>
            </div>
            <div class="image">
                <h2>画像（4枚まで）</h2>
                <input id="inputElm" type="file" name="images[]" multiple onchange="loadImage(this);">
                <p class="image__error" style="color:red">{{ $errors->first('images') }}</p>
            </div>
            <!-- プレビュー画像の表示 -->
            <div id="preview" style="width: 300px;"></div>
            <button type="submit">変更を保存する</button>
        </form>
    </div>
    <div class="footer">
        <a href="{{ route('pilgrimage_posts.show', ['pilgrimage_id' => $pilgrimage_post->anime_pilgrimage_id, 'pilgrimage_post_id' => $pilgrimage_post->id]) }}">保存しないで戻る</a>
    </div>
</body>
<script>
    let key = 0;

    function loadImage(obj) {
        // 以前に選択したファイルは保持されないため削除
        document.querySelectorAll('figure').forEach(function(figure) {
            figure.remove();
            key = 0;
        });
        // 選択されたファイルの枚数分だけ画像を追加
        for (i = 0; i < obj.files.length; i++) {
            var fileReader = new FileReader();
            fileReader.onload = (function(e) {
                var field = document.getElementById("preview");
                var figure = document.createElement("figure");
                var rmBtn = document.createElement("input");
                var img = new Image();
                img.src = e.target.result;
                rmBtn.type = "button";
                rmBtn.name = key;
                rmBtn.value = "削除";
                rmBtn.onclick = (function() {
                    var element = document.getElementById("img-" + String(rmBtn.name)).remove();
                });
                figure.setAttribute("id", "img-" + key);
                figure.appendChild(img);
                figure.appendChild(rmBtn)
                field.appendChild(figure);
                key++;
            });
            fileReader.readAsDataURL(obj.files[i]);
        }
    }
</script>

</html>