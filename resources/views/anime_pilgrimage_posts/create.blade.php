<x-app-layout>
    <h1>「{{ $pilgrimage->name }}」への新規感想投稿</h1>
    <form action="{{ route('pilgrimage_posts.store', ['pilgrimage_id' => $pilgrimage->id]) }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        <div class="anime_pilgrimage_id">
            <input type="hidden" name="pilgrimage_post[anime_pilgrimage_id]" value="{{ $pilgrimage->id }}">
        </div>
        <div class="title">
            <h2>タイトル</h2>
            <input type="text" name="pilgrimage_post[post_title]" placeholder="タイトル"
                value="{{ old('pilgrimage_post.post_title') }}" />
            <p class="title__error" style="color:red">{{ $errors->first('pilgrimage_post.post_title') }}</p>
        </div>
        <div class="scene">
            <h2>シーン</h2>
            <input type="text" name="pilgrimage_post[scene]" placeholder="シーン"
                value="{{ old('pilgrimage_post.scene') }}" />
            <p class="title__error" style="color:red">{{ $errors->first('pilgrimage_post.scene') }}</p>
        </div>
        <div class="body">
            <h2>内容</h2>
            <textarea name="pilgrimage_post[body]" placeholder="内容を記入してください。">{{ old('pilgrimage_post.body') }}</textarea>
            <p class="body__error" style="color:red">{{ $errors->first('pilgrimage_post.body') }}</p>
        </div>
        <div class="image">
            <h2>画像（4枚まで）</h2>
            <label>
                <input id="inputElm" type="file" style="display:none" name="images[]" multiple
                    onchange="loadImage(this);">画像の追加
                <div id="count">現在、0枚の画像を選択しています。</div>
            </label>
            <p class="image__error" style="color:red">{{ $errors->first('images') }}</p>
        </div>
        <!-- プレビュー画像の表示 -->
        <div id="preview" style="width: 300px;"></div>
        <button type="submit">投稿する</button>
    </form>
    <div class="footer">
        <a href="{{ route('pilgrimage_posts.index', ['pilgrimage_id' => $pilgrimage->id]) }}">戻る</a>
    </div>
    <script src="{{ asset('/js/create_preview.js') }}"></script>
</x-app-layout>
