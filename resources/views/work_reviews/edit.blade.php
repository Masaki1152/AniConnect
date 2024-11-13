<x-app-layout>
    <h1 class="title">{{ $work_review->work->name }}への投稿編集画面</h1>
    <div class="content">
        <form action="{{ route('work_reviews.update', ['work_id' => $work_review->work_id, 'work_review_id' => $work_review->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="work_id">
                <input type="hidden" name="work_review[work_id]" value="{{ $work_review->work_id }}">
            </div>
            <div class="user_id">
                <input type="hidden" name="work_review[user_id]" value="{{ $work_review->user_id }}">
            </div>
            <div class="title">
                <h2>タイトル</h2>
                <input type="text" name="work_review[post_title]" placeholder="タイトル" value="{{ $work_review->post_title }}" />
                <p class="title__error" style="color:red">{{ $errors->first('work_review.post_title') }}</p>
            </div>
            <div class="category">
                <h2>カテゴリー（3個まで）</h2>
                @php
                // old() が存在すればそれを使用し、なければ過去の保存値を使用
                $existingCategories = $work_review->categories->pluck('id')->toArray();
                $selectedCategories = old('work_review.categories_array', $existingCategories);
                @endphp
                <select name="work_review[categories_array][]" multiple>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}"
                        {{ in_array($category->id, $selectedCategories) ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                    @endforeach
                </select>

                @if ($errors->has('work_review.categories_array'))
                <p class="category__error" style="color:red">{{ $errors->first('work_review.categories_array') }}</p>
                @endif
            </div>
            <div class="body">
                <h2>内容</h2>
                <textarea name="work_review[body]" placeholder="内容を記入してください。">{{ $work_review->body }}</textarea>
                <p class="body__error" style="color:red">{{ $errors->first('work_review.body') }}</p>
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
        <a href="{{ route('work_reviews.show', ['work_id' => $work_review->work_id, 'work_review_id' => $work_review->id]) }}">保存しないで戻る</a>
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