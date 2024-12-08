<x-app-layout>
    <h1>「{{ $work->name }}」への新規感想投稿</h1>
    <form action="{{ route('work_reviews.store', ['work_id' => $work->id]) }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        <div class="work_id">
            <input type="hidden" name="work_review[work_id]" value="{{ $work->id }}">
        </div>
        <div class="title">
            <h2>タイトル</h2>
            <input type="text" name="work_review[post_title]" placeholder="タイトル"
                value="{{ old('work_review.post_title') }}" />
            <p class="title__error" style="color:red">{{ $errors->first('work_review.post_title') }}</p>
        </div>
        <div class="category">
            <h2>カテゴリー（3個まで）</h2>
            <select name="work_review[categories_array][]" multiple>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @if (in_array($category->id, old('work_review.categories_array', []))) selected @endif>
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
            <textarea name="work_review[body]" placeholder="内容を記入してください。">{{ old('work_review.body') }}</textarea>
            <p class="body__error" style="color:red">{{ $errors->first('work_review.body') }}</p>
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
        <a href="{{ route('work_reviews.index', ['work_id' => $work->id]) }}">戻る</a>
    </div>
    <script src="{{ asset('/js/create_preview.js') }}"></script>

</x-app-layout>
