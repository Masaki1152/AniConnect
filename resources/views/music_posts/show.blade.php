<x-app-layout>
    @if (session('status'))
        <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)"
            class="fixed top-[15%] left-1/2 transform -translate-x-1/2 bg-green-500/50 text-white px-6 py-3 rounded-lg shadow-lg flex items-center space-x-4 z-50">
            <div class="text-white">
                {{ session('status') }}
            </div>
        </div>
    @endif
    <div id="like-message"
        class="hidden fixed top-[15%] left-1/2 transform -translate-x-1/2 bg-green-500/50 text-white px-6 py-3 rounded-lg shadow-lg flex items-center space-x-4 z-50">
    </div>

    <h1 class="title">
        {{ $music_post->post_title }}
    </h1>
    <div class="like">
        <!-- ボタンの見た目は後のデザイン作成の際に設定する予定 -->
        <button id="like_button" data-music-id="{{ $music_post->music_id }}" data-post-id="{{ $music_post->id }}"
            type="submit">
            {{ $music_post->users->contains(auth()->user()) ? 'いいね取り消し' : 'いいね' }}
        </button>
        <div class="like_user">
            <a
                href="{{ route('music_post_like.index', ['music_id' => $music_post->music_id, 'music_post_id' => $music_post->id]) }}">
                <p id="like_count">{{ $music_post->users->count() }}</p>
            </a>
        </div>
    </div>
    <div class="content">
        <div class="content__character_post">
            <h3>音楽名</h3>
            <p>{{ $music_post->music->name }}</p>
            <h3>使用作品</h3>
            <p>{{ $music_post->work->name }}</p>
            <h3>投稿者</h3>
            <p>{{ $music_post->user->name }}</p>
            <h3>タイトル</h3>
            <p>{{ $music_post->post_title }}</p>
            <h3>カテゴリー</h3>
            <h5 class='category'>
                @foreach ($music_post->categories as $category)
                    {{ $category->name }}
                @endforeach
            </h5>
            <h3>評価</h3>
            @php
                $numbers = [1 => '★', 2 => '★★', 3 => '★★★', 4 => '★★★★', 5 => '★★★★★'];
            @endphp
            <p>{{ $numbers[$music_post->star_num] }}</p>
            <h3>本文</h3>
            <p>{{ $music_post->body }}</p>
            <h3>作成日</h3>
            <p>{{ $music_post->created_at }}</p>
        </div>
    </div>
    <div class="edit">
        <a
            href="{{ route('music_posts.edit', ['music_id' => $music_post->music_id, 'music_post_id' => $music_post->id]) }}">編集する</a>
    </div>
    <form
        action="{{ route('music_posts.delete', ['music_id' => $music_post->music_id, 'music_post_id' => $music_post->id]) }}"
        id="form_{{ $music_post->id }}" method="post">
        @csrf
        @method('DELETE')
        <button type="button" data-post-id="{{ $music_post->id }}" class="delete-button">投稿を削除する</button>
    </form>
    <div class="footer">
        <a href="{{ route('music_posts.index', ['music_id' => $music_post->music_id]) }}">戻る</a>
    </div>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('/js/like_posts/like_music_post.js') }}"></script>
    <script src="{{ asset('/js/delete_post.js') }}"></script>
</x-app-layout>
