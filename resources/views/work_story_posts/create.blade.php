<x-app-layout>
    <h1>{{ $work_story->work->name }}</h1>
    <h1>{{ $work_story->episode }}</h1>
    <h1>「{{ $work_story->sub_title }}」への新規感想投稿</h1>
    <form
        action="{{ route('work_story_posts.store', ['work_id' => $work_story->work_id, 'work_story_id' => $work_story->id]) }}"
        method="POST" enctype="multipart/form-data">
        @csrf
        <div class="work_id">
            <input type="hidden" name="work_story_post[work_id]" value="{{ $work_story->work_id }}">
        </div>
        <div class="sub_title_id">
            <input type="hidden" name="work_story_post[sub_title_id]" value="{{ $work_story->id }}">
        </div>
        <div class="title">
            <h2>タイトル</h2>
            <input type="text" name="work_story_post[post_title]" placeholder="タイトル"
                value="{{ old('work_story_post.post_title') }}" />
            <p class="title__error" style="color:red">{{ $errors->first('work_story_post.post_title') }}</p>
        </div>
        <div class="body">
            <h2>内容</h2>
            <textarea name="work_story_post[body]" placeholder="内容を記入してください。">{{ old('work_story_post.body') }}</textarea>
            <p class="body__error" style="color:red">{{ $errors->first('work_story_post.body') }}</p>
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
        <a
            href="{{ route('work_story_posts.index', ['work_id' => $work_story->work_id, 'work_story_id' => $work_story->id]) }}">戻る</a>
    </div>
    <script src="{{ asset('/js/create_preview.js') }}"></script>

</x-app-layout>
