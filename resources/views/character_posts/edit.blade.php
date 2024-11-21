<x-app-layout>
    <h1 class="title">{{ $character_post->character->name }}への投稿編集画面</h1>
    <div class="content">
        <form
            action="{{ route('character_posts.update', ['character_id' => $character_post->character_id, 'character_post_id' => $character_post->id]) }}"
            method="POST" enctype="multipart/form-data">
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
                <input type="text" name="character_post[post_title]" placeholder="タイトル"
                    value="{{ $character_post->post_title }}" />
                <p class="title__error" style="color:red">{{ $errors->first('character_post.post_title') }}</p>
            </div>
            <div class="star_num">
                <h2>星の数</h2>
                <select name="character_post[star_num]">
                    @php
                        $numbers = [1 => '★', 2 => '★★', 3 => '★★★', 4 => '★★★★', 5 => '★★★★★'];
                    @endphp
                    @foreach ($numbers as $num => $star)
                        <option value="{{ $num }}" @if ($character_post->star_num == $num) selected @endif>
                            {{ $star }}
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
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ in_array($category->id, $selectedCategories) ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>

                @if ($errors->has('character_post.categories_array'))
                    <p class="category__error" style="color:red">
                        {{ $errors->first('character_post.categories_array') }}</p>
                @endif
            </div>
            <div class="body">
                <h2>内容</h2>
                <textarea name="character_post[body]" placeholder="内容を記入してください。">{{ $character_post->body }}</textarea>
                <p class="body__error" style="color:red">{{ $errors->first('character_post.body') }}</p>
            </div>
            <div class="image">
                <h2>画像（4枚まで）</h2>
                @php
                    // 既にファイルが選択されている場合はそれらを表示する
                    $existingImages = [];
                    $numbers = [1, 2, 3, 4];
                    foreach ($numbers as $number) {
                        $image = 'image' . $number;
                        if ($character_post->$image) {
                            array_push($existingImages, $character_post->$image);
                        }
                    }
                    $existingImages = json_encode($existingImages);
                @endphp
                <div id="existing_image_paths" data-php-variable="{{ $existingImages }}"></div>
                <label>
                    <input id="inputElm" type="file" style="display:none" name="images[]" multiple
                        onchange="loadImage(this);">画像の追加
                    <div id="count"></div>
                </label>
                <!-- 削除された既存画像のリスト -->
                <input type="hidden" name="removedImages[]" id="removedImages" value="">
                <!-- 削除されず残った既存画像のリスト -->
                <input type="hidden" name="remainedImages[]" id="remainedImages" value="">
                <p class="image__error" style="color:red">{{ $errors->first('images') }}</p>
            </div>
            <!-- プレビュー画像の表示 -->
            <div id="preview" style="width: 300px;"></div>
            <button type="submit">変更を保存する</button>
        </form>
    </div>
    <div class="footer">
        <a
            href="{{ route('character_posts.show', ['character_id' => $character_post->character_id, 'character_post_id' => $character_post->id]) }}">保存しないで戻る</a>
    </div>
    <script src="{{ asset('/js/edit_preview.js') }}"></script>
</x-app-layout>
