<x-app-layout>
    <h1>「{{ $character_post->character->name }}」への新規感想投稿</h1>
    <form action="{{ route('character_posts.store', ['character_id' => $character_post->character_id]) }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        <div class="work_id">
            <input type="hidden" name="character_post[work_id]" value="{{ $character_post->work_id }}">
        </div>
        <div class="character_id">
            <input type="hidden" name="character_post[character_id]" value="{{ $character_post->character_id }}">
        </div>
        <div class="title">
            <h2>タイトル</h2>
            <input type="text" name="character_post[post_title]" placeholder="タイトル"
                value="{{ old('character_post.post_title') }}" />
            <p class="title__error" style="color:red">{{ $errors->first('character_post.post_title') }}</p>
        </div>
        <div class="star_num">
            <h2>星の数</h2>
            <select name="character_post[star_num]">
                @php
                    $numbers = [1 => '★', 2 => '★★', 3 => '★★★', 4 => '★★★★', 5 => '★★★★★'];
                @endphp
                @foreach ($numbers as $num => $star)
                    <option value="{{ $num }}" @if (old('character_post.star_num') == $num) selected @endif>
                        {{ $star }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="category">
            <h2>カテゴリー（3個まで）</h2>
            <select name="character_post[categories_array][]" multiple>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @if (in_array($category->id, old('character_post.categories_array', []))) selected @endif>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            @if ($errors->has('character_post.categories_array'))
                <p class="category__error" style="color:red">{{ $errors->first('character_post.categories_array') }}
                </p>
            @endif
        </div>
        <div class="body">
            <h2>内容</h2>
            <textarea name="character_post[body]" placeholder="内容を記入してください。">{{ old('character_post.body') }}</textarea>
            <p class="body__error" style="color:red">{{ $errors->first('character_post.body') }}</p>
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
        <a href="{{ route('character_posts.index', ['character_id' => $character_post->character_id]) }}">戻る</a>
    </div>
    <script src="{{ asset('/js/create_preview.js') }}"></script>
</x-app-layout>
