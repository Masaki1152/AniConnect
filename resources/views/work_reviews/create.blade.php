<x-app-layout>
    <h1>「{{$workreview->work->name}}」への新規感想投稿</h1>
    <form action="{{ route('work_reviews.store', ['work_id' => $workreview->work_id]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="work_id">
            <input type="hidden" name="work_review[work_id]" value="{{ $workreview->work_id }}">
        </div>
        <div class="title">
            <h2>タイトル</h2>
            <input type="text" name="work_review[post_title]" placeholder="タイトル" value="{{ old('work_review.post_title') }}" />
            <p class="title__error" style="color:red">{{ $errors->first('work_review.post_title') }}</p>
        </div>
        <div class="category">
            <h2>カテゴリー（3個まで）</h2>
            <select name="work_review[categories_array][]" multiple>
                @foreach($categories as $category)
                <option value="{{ $category->id }}" @if(in_array($category->id, old('work_review.categories_array', []))) selected @endif>
                    {{$category->name}}
                </option>
                @endforeach
            </select>
            @if ($errors->has('work_review.categories_array'))
            <p class="category__error" style="color:red">{{ $errors->first('work_review.categories_array') }}</p>
            @endif
        </div>
        <div class="body">
            <h2>内容</h2>
            <textarea name="work_review[body]" placeholder="内容を記入してください。">{{ old('work_review.body') }}</textarea>
            <p class="body__error" style="color:red">{{ $errors->first('work_review.body') }}</p>
        </div>
        <div class="image">
            <h2>画像（4枚まで）</h2>
            <label>
                <input id="inputElm" type="file" style="display:none" name="images[]" multiple onchange="loadImage(this);">画像の追加
                <div id="count">現在、0枚の画像を選択しています。</div>
            </label>
            <p class="image__error" style="color:red">{{ $errors->first('images') }}</p>
        </div>
        <!-- プレビュー画像の表示 -->
        <div id="preview" style="width: 300px;"></div>
        <button type="submit">投稿する</button>
    </form>
    <div class="footer">
        <a href="{{ route('work_reviews.index', ['work_id' => $workreview->work_id]) }}">戻る</a>
    </div>
    <script>
        // 元々選択されているファイルのリスト
        let selectedImages = [];

        function loadImage(obj) {
            // 新しく選択されたファイル
            const newImages = Array.from(obj.files);

            // 合計が4枚を超える場合のチェック
            // 元々選択されていたファイルと新しいファイルの合計を確認
            if (selectedImages.length + newImages.length > 4) {
                alert('画像は4枚までアップロード可能です');
                // プレビューを更新し、以前選択していたファイルを再表示する
                // 新しく選択していた方のファイルは破棄
                renderPreviews();
                return;
            }

            // 新しいファイルを選択済みリストに追加
            selectedImages.push(...newImages);

            // プレビューの更新
            renderPreviews();
        }

        function renderPreviews() {
            // プレビューを取得後、クリア
            const preview = document.getElementById('preview');
            preview.innerHTML = '';
            // 選択している画像の枚数を表示する
            countImages(selectedImages);

            selectedImages.forEach((image, index) => {
                const fileReader = new FileReader();

                fileReader.onload = function(e) {
                    const figure = document.createElement('figure');
                    figure.setAttribute('id', `img-${index}`);
                    figure.className = 'relative flex flex-col items-center mb-4';

                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.alt = 'preview';
                    img.className = 'w-36 h-36 object-cover rounded-md border border-gray-300 mb-2';

                    const rmBtn = document.createElement('button');
                    rmBtn.type = 'button';
                    rmBtn.textContent = '削除';
                    rmBtn.className = 'px-2 py-1 bg-red-500 text-white text-sm rounded hover:bg-red-600';
                    rmBtn.onclick = function() {
                        removeImage(index);
                    };

                    figure.appendChild(img);
                    figure.appendChild(rmBtn);
                    preview.appendChild(figure);
                };

                fileReader.readAsDataURL(image);
            });

            // 選択しているファイルを反映
            updateInputElement();
        }

        function removeImage(index) {
            // 選択済みファイルリストから該当インデックスのファイルを削除
            selectedImages.splice(index, 1);

            // プレビューを再描画
            renderPreviews();
        }

        function updateInputElement() {
            const dataTransfer = new DataTransfer();
            selectedImages.forEach(image => dataTransfer.items.add(image));

            // 選択されたファイルを反映
            const inputElm = document.getElementById('inputElm');
            inputElm.files = dataTransfer.files;
        }

        // 選択している画像の枚数を表示する
        function countImages() {
            const count = document.getElementById('count');
            count.innerHTML = '';
            const countText = document.createElement('p');
            const ImageCount = selectedImages.length;
            countText.textContent = `現在、${ImageCount}枚の画像を選択しています。`;
            count.appendChild(countText);
        }
    </script>

</x-app-layout>