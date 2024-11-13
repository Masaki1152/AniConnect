<x-app-layout>
    <h1>「{{ $work_story_post->workStory->sub_title }}」への新規感想投稿</h1>
    <form action="{{ route('work_story_posts.store', ['work_id' => $work_story_post->work_id, 'work_story_id' => $work_story_post->sub_title_id, 'work_story_post_id' => $work_story_post->id]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="work_id">
            <input type="hidden" name="work_story_post[work_id]" value="{{ $work_story_post->work_id }}">
        </div>
        <div class="sub_title_id">
            <input type="hidden" name="work_story_post[sub_title_id]" value="{{ $work_story_post->sub_title_id }}">
        </div>
        <div class="title">
            <h2>タイトル</h2>
            <input type="text" name="work_story_post[post_title]" placeholder="タイトル" value="{{ old('work_story_post.post_title') }}" />
            <p class="title__error" style="color:red">{{ $errors->first('work_story_post.post_title') }}</p>
        </div>
        <div class="body">
            <h2>内容</h2>
            <textarea name="work_story_post[body]" placeholder="内容を記入してください。">{{ old('work_story_post.body') }}</textarea>
            <p class="body__error" style="color:red">{{ $errors->first('work_story_post.body') }}</p>
        </div>
        <div class="image">
            <h2>画像（4枚まで）</h2>
            <input id="inputElm" type="file" name="images[]" multiple onchange="loadImage(this);">
            <p class="image__error" style="color:red">{{ $errors->first('images') }}</p>
        </div>
        <!-- プレビュー画像の表示 -->
        <div id="preview" style="width: 300px;"></div>
        <button type="submit">投稿する</button>
    </form>
    <div class="footer">
        <a href="{{ route('work_story_posts.index', ['work_id' => $work_story_post->work_id, 'work_story_id' => $work_story_post->sub_title_id]) }}">戻る</a>
    </div>
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
                    // 削除ボタン押下で画像プレビューの削除
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

</x-app-layout>