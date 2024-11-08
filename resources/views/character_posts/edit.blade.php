<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <title>Blog</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
</head>

<body>
    <h1 class="title">{{ $work_story_post->character->name }}への投稿編集画面</h1>
    <div class="content">
        <form action="{{ route('work_story_posts.update', ['character_id' => $character_post->character_id, 'character_post_id' => $character_post->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="work_id">
                <input type="hidden" name="character_post[work_id]" value="{{ $character_post->work_id }}">
            </div>
            <div class="character_id">
                <input type="hidden" name="character_post[character_id]" value="{{ $character_post->character_id }}">
            </div>
            <div class="user_id">
                <input type="hidden" name="character_post[user_id]" value="{{ $character_post->user_id }}">
            </div>
            <div class="title">
                <h2>タイトル</h2>
                <input type="text" name="character_post[post_title]" placeholder="タイトル" value="{{ $character_post->post_title }}" />
                <p class="title__error" style="color:red">{{ $errors->first('character_post.post_title') }}</p>
            </div>
            <div class="star_num">
                <h2>星の数</h2>
                <select name="character_post[star_num]">
                    @php
                    $numbers = array(1 => '★', 2 => '★★', 3 => '★★★', 4 => '★★★★', 5 => '★★★★★');
                    @endphp
                    @foreach($numbers as $num => $star)
                    <option value="{{ $num }}" @if($character_post->star_num == $num) selected @endif>
                        {{$star}}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="category">
                <h2>カテゴリー（3個まで）</h2>
                @php
                // old() が存在すればそれを使用し、なければ過去の保存値を使用
                $existingCategories = $character_post->categories->pluck('id')->toArray();
                $selectedCategories = old('character_post.categories_array', $existingCategories);
                @endphp
                <select name="character_post[categories_array][]" multiple>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}"
                        {{ in_array($category->id, $selectedCategories) ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                    @endforeach
                </select>

                @if ($errors->has('character_post.categories_array'))
                <p class="category__error" style="color:red">{{ $errors->first('character_post.categories_array') }}</p>
                @endif
            </div>
            <div class="body">
                <h2>内容</h2>
                <textarea name="character_post[body]" placeholder="内容を記入してください。">{{ $character_post->body }}</textarea>
                <p class="body__error" style="color:red">{{ $errors->first('character_post.body') }}</p>
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
        <a href="{{ route('character_posts.show', ['character_id' => $character_post->character_id, 'character_post_id' => $character_post->id]) }}">保存しないで戻る</a>
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