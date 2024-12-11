<x-app-layout>
    <h1 class="title">「{{ $work_story_post->workStory->sub_title }}」への投稿編集画面</h1>
    <div class="content">
        <form
            action="{{ route('work_story_posts.update', ['work_id' => $work_story_post->work_id, 'work_story_id' => $work_story_post->sub_title_id, 'work_story_post_id' => $work_story_post->id]) }}"
            method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="work_id">
                <input type="hidden" name="work_story_post[work_id]" value="{{ $work_story_post->work_id }}">
            </div>
            <div class="user_id">
                <input type="hidden" name="work_story_post[user_id]" value="{{ $work_story_post->user_id }}">
            </div>
            <div class="sub_title_id">
                <input type="hidden" name="work_story_post[sub_title_id]" value="{{ $work_story_post->sub_title_id }}">
            </div>
            <div class="title">
                <h2>タイトル</h2>
                <input type="text" name="work_story_post[post_title]" placeholder="タイトル"
                    value="{{ $work_story_post->post_title }}" />
                <p class="title__error" style="color:red">{{ $errors->first('work_story_post.post_title') }}</p>
            </div>
            <div class="category">
                <h2>カテゴリー（3個まで）</h2>
                @php
                    // old() が存在すればそれを使用し、なければ過去の保存値を使用
                    $existingCategories = $work_story_post->categories->pluck('id')->toArray();
                    $selectedCategories = old('work_story_post.categories_array', $existingCategories);
                @endphp
                <select name="work_story_post[categories_array][]" multiple>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ in_array($category->id, $selectedCategories) ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>

                @if ($errors->has('work_story_post.categories_array'))
                    <p class="category__error" style="color:red">
                        {{ $errors->first('work_story_post.categories_array') }}</p>
                @endif
            </div>
            <div class="body">
                <h2>内容</h2>
                <textarea name="work_story_post[body]" placeholder="内容を記入してください。">{{ $work_story_post->body }}</textarea>
                <p class="body__error" style="color:red">{{ $errors->first('work_story_post.body') }}</p>
            </div>
            <div class="image">
                <h2>画像（4枚まで）</h2>
                @php
                    // 既にファイルが選択されている場合はそれらを表示する
                    $existingImages = [];
                    $numbers = [1, 2, 3, 4];
                    foreach ($numbers as $number) {
                        $image = 'image' . $number;
                        if ($work_story_post->$image) {
                            array_push($existingImages, $work_story_post->$image);
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
            href="{{ route('work_story_posts.show', ['work_id' => $work_story_post->work_id, 'work_story_id' => $work_story_post->sub_title_id, 'work_story_post_id' => $work_story_post->id]) }}">保存しないで戻る</a>
    </div>
    <script src="{{ asset('/js/edit_preview.js') }}"></script>
</x-app-layout>
