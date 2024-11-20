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
                @php
                // 既にファイルが選択されている場合はそれらを表示する
                $existingImages = [];
                $numbers = array(1, 2, 3, 4);
                foreach($numbers as $number){
                $image = "image".$number;
                if($work_review->$image){
                array_push($existingImages, $work_review->$image);
                }
                }
                $existingImages = json_encode($existingImages);
                @endphp
                <label>
                    <input id="inputElm" type="file" style="display:none" name="images[]" multiple onchange="loadImage(this);">画像の追加
                    <div id="count"></div>
                </label>
                <!-- 削除された既存画像のリスト -->
                <input type="hidden" name="removedImages[]" id="removedImages" value="">
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
        // 元々選択されている画像のリスト
        let selectedImages = [];
        // 既存の画像URLを保持
        let existingImages = [];
        // 既存の画像のうち、削除されていない画像のURLを保持
        let removedImages = []

        // 編集画面にて、以前画像が選択されていた場合、それらの画像を反映する
        // DOMツリー読み取り完了後にイベント発火
        document.addEventListener('DOMContentLoaded', function() {
            // 既存の画像を取得
            const ImagePaths = JSON.parse('<?php echo $existingImages; ?>');
            ImagePaths.forEach((path, index) => {
                existingImages.push({
                    id: index,
                    url: path
                });

                // 既存画像をプレビューとして表示
                renderExistingImages();
            })
            // 削除されていない画像のURLをフォームに反映
            document.getElementById('removedImages').value = JSON.stringify(existingImages);
        });

        // 既存画像をプレビューとして表示
        function renderExistingImages() {
            const preview = document.getElementById('preview');
            // プレビューを初期化
            preview.innerHTML = '';
            // 選択している画像の枚数を表示する
            countImages(existingImages);

            existingImages.forEach(image => {
                const figure = document.createElement('figure');
                figure.setAttribute('id', `existing-img-${image.id}`);
                figure.className = 'relative flex flex-col items-center mb-4';

                const img = document.createElement('img');
                // サーバー上の画像URLを使用
                img.src = image.url;
                img.alt = 'existing preview';
                img.className = 'w-36 h-36 object-cover rounded-md border border-gray-300 mb-2';

                const rmBtn = document.createElement('button');
                rmBtn.type = 'button';
                rmBtn.textContent = '削除';
                rmBtn.className = 'px-2 py-1 bg-red-500 text-white text-sm rounded hover:bg-red-600';
                rmBtn.onclick = function() {
                    removeExistingImage(image.id);
                };

                figure.appendChild(img);
                figure.appendChild(rmBtn);
                preview.appendChild(figure);
            });
        }

        function loadImage(obj) {
            // 新しく選択された画像
            const newImages = Array.from(obj.files);
            // 合計が4枚を超える場合のチェック
            // 元々選択されていた画像と新しい画像、以前保存していた画像の合計を確認
            if (selectedImages.length + newImages.length + existingImages.length > 4) {
                alert('画像は4枚までアップロード可能です');
                // プレビューを更新し、以前選択していた画像を再表示する
                // 新しく選択していた方の画像は破棄
                renderPreviews();
                return;
            }

            // 新しい画像を選択済みリストに追加
            selectedImages.push(...newImages);

            // プレビューの更新
            renderPreviews();
        }

        function renderPreviews() {
            // プレビューを取得後、クリア
            const preview = document.getElementById('preview');
            preview.innerHTML = '';

            // 既存画像を表示
            renderExistingImages();
            // 新規追加された画像を表示
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

            // 選択している画像を反映
            updateInputElement();
        }

        // 既存画像リストから該当画像を削除
        function removeExistingImage(id) {
            const index = existingImages.findIndex(img => img.id === id);
            if (index !== -1) {
                existingImages.splice(index, 1);
            }
            // 削除されていない画像のURLをフォームに反映
            document.getElementById('removedImages').value = JSON.stringify(existingImages); 
            console.log(document.getElementById('removedImages').value);
            // プレビューを再描画
            renderPreviews();
        }

        function removeImage(index) {
            // 選択済み画像リストから該当インデックスの画像を削除
            selectedImages.splice(index, 1);

            // プレビューを再描画
            renderPreviews();
        }

        function updateInputElement() {
            const dataTransfer = new DataTransfer();
            selectedImages.forEach(image => dataTransfer.items.add(image));

            // 選択された画像を反映
            const inputElm = document.getElementById('inputElm');
            inputElm.files = dataTransfer.files;
        }

        // 選択している画像の枚数を表示する
        function countImages() {
            const count = document.getElementById('count');
            count.innerHTML = '';
            const countText = document.createElement('p');
            const ImageCount = selectedImages.length + existingImages.length;
            countText.textContent = `現在、${ImageCount}枚の画像を選択しています。`;
            count.appendChild(countText);
        }
    </script>

</x-app-layout>