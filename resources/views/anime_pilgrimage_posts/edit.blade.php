<x-app-layout>
    <h1 class="title">{{ $pilgrimage_post->animePilgrimage->name }}への投稿編集画面</h1>
    <div class="content">
        <form
            action="{{ route('pilgrimage_posts.update', ['pilgrimage_id' => $pilgrimage_post->anime_pilgrimage_id, 'pilgrimage_post_id' => $pilgrimage_post->id]) }}"
            method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="user_id">
                <input type="hidden" name="pilgrimage_post[user_id]" value="{{ $pilgrimage_post->user_id }}">
            </div>
            <div class="anime_pilgrimage_id">
                <input type="hidden" name="pilgrimage_post[anime_pilgrimage_id]"
                    value="{{ $pilgrimage_post->anime_pilgrimage_id }}">
            </div>
            <div class="title">
                <h2>タイトル</h2>
                <input type="text" name="pilgrimage_post[title]" placeholder="タイトル"
                    value="{{ $pilgrimage_post->title }}" />
                <p class="title__error" style="color:red">{{ $errors->first('pilgrimage_post.title') }}</p>
            </div>
            <div class="scene">
                <h2>シーン</h2>
                <input type="text" name="pilgrimage_post[scene]" placeholder="シーン"
                    value="{{ $pilgrimage_post->scene }}" />
                <p class="title__error" style="color:red">{{ $errors->first('pilgrimage_post.scene') }}</p>
            </div>
            <div class="body">
                <h2>内容</h2>
                <textarea name="pilgrimage_post[body]" placeholder="内容を記入してください。">{{ $pilgrimage_post->body }}</textarea>
                <p class="body__error" style="color:red">{{ $errors->first('pilgrimage_post.body') }}</p>
            </div>
            <div class="image">
                <h2>画像（4枚まで）</h2>
                @php
                    // 既にファイルが選択されている場合はそれらを表示する
                    $existingImages = [];
                    $numbers = [1, 2, 3, 4];
                    foreach ($numbers as $number) {
                        $image = 'image' . $number;
                        if ($pilgrimage_post->$image) {
                            array_push($existingImages, $pilgrimage_post->$image);
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
            href="{{ route('pilgrimage_posts.show', ['pilgrimage_id' => $pilgrimage_post->anime_pilgrimage_id, 'pilgrimage_post_id' => $pilgrimage_post->id]) }}">保存しないで戻る</a>
    </div>
    <script src="{{ asset('/js/edit_preview.js') }}"></script>
</x-app-layout>
