<x-app-layout>
    <h1>「{{ $music->name }}」への新規感想投稿</h1>
    <form action="{{ route('music_posts.store', ['music_id' => $music->id]) }}" method="POST">
        @csrf
        <div class="work_id">
            <input type="hidden" name="music_post[work_id]" value="{{ $music->work_id }}">
        </div>
        <div class="music_id">
            <input type="hidden" name="music_post[music_id]" value="{{ $music->id }}">
        </div>
        <div class="title">
            <h2>タイトル</h2>
            <input type="text" name="music_post[post_title]" placeholder="タイトル"
                value="{{ old('music_post.post_title') }}" />
            <p class="title__error" style="color:red">{{ $errors->first('music_post.post_title') }}</p>
        </div>
        <div class="star_num">
            <h2>星の数</h2>
            <select name="music_post[star_num]">
                @php
                    $numbers = [1 => '★', 2 => '★★', 3 => '★★★', 4 => '★★★★', 5 => '★★★★★'];
                @endphp
                @foreach ($numbers as $num => $star)
                    <option value="{{ $num }}" @if (old('music_post.star_num') == $num) selected @endif>
                        {{ $star }}
                    </option>
                @endforeach
            </select>
            <p class="title__star_num" style="color:red">{{ $errors->first('music_post.star_num') }}</p>
        </div>
        <div class="body">
            <h2>内容</h2>
            <textarea name="music_post[body]" placeholder="内容を記入してください。">{{ old('music_post.body') }}</textarea>
            <p class="body__error" style="color:red">{{ $errors->first('music_post.body') }}</p>
        </div>
        <button type="submit">投稿する</button>
    </form>
    <div class="footer">
        <a href="{{ route('music_posts.index', ['music_id' => $music->id]) }}">戻る</a>
    </div>
</x-app-layout>
