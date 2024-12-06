<x-app-layout>
    <div id="like-message"
        class="hidden fixed top-[15%] left-1/2 transform -translate-x-1/2 bg-green-500/50 text-white px-6 py-3 rounded-lg shadow-lg flex items-center space-x-4 z-50">
    </div>

    <h1 class="title">
        {{ $character_post->post_title }}
    </h1>
    <div class="like">
        <!-- ボタンの見た目は後のデザイン作成の際に設定する予定 -->
        <button id="like_button" data-character-id="{{ $character_post->character_id }}"
            data-post-id="{{ $character_post->id }}" type="submit">
            {{ $character_post->users->contains(auth()->user()) ? 'いいね取り消し' : 'いいね' }}
        </button>
        <div class="like_user">
            <a
                href="{{ route('character_post_like.index', ['character_id' => $character_post->character_id, 'character_post_id' => $character_post->id]) }}">
                <p id="like_count">{{ $character_post->users->count() }}</p>
            </a>
        </div>
    </div>
    <div class="content">
        <div class="content__character_post">
            <h3>登場人物名</h3>
            <p>{{ $character_post->character->name }}</p>
            <h3>投稿者</h3>
            <p>{{ $character_post->user->name }}</p>
            <h3>タイトル</h3>
            <p>{{ $character_post->post_title }}</p>
            <h3>カテゴリー</h3>
            <h5 class='category'>
                @foreach ($character_post->categories as $category)
                    {{ $category->name }}
                @endforeach
            </h5>
            <h3>評価</h3>
            @php
                $numbers = [1 => '★', 2 => '★★', 3 => '★★★', 4 => '★★★★', 5 => '★★★★★'];
            @endphp
            <p>{{ $numbers[$character_post->star_num] }}</p>
            <h3>本文</h3>
            <p>{{ $character_post->body }}</p>
            <h3>作成日</h3>
            <p>{{ $character_post->created_at }}</p>
            @php
                $numbers = [1, 2, 3, 4];
            @endphp
            @foreach ($numbers as $number)
                @php
                    $image = 'image' . $number;
                @endphp
                @if ($character_post->$image)
                    <div>
                        <img src="{{ $character_post->$image }}" alt="画像が読み込めません。">
                    </div>
                @endif
            @endforeach
        </div>
    </div>
    <div class="edit">
        <a
            href="{{ route('character_posts.edit', ['character_id' => $character_post->character_id, 'character_post_id' => $character_post->id]) }}">編集する</a>
    </div>
    <form
        action="{{ route('character_posts.delete', ['character_id' => $character_post->character_id, 'character_post_id' => $character_post->id]) }}"
        id="form_{{ $character_post->id }}" method="post">
        @csrf
        @method('DELETE')
        <button type="button" data-post-id="{{ $character_post->id }}" class="delete-button">投稿を削除する</button>
    </form>
    <div class="footer">
        <a href="{{ route('character_posts.index', ['character_id' => $character_post->character_id]) }}">戻る</a>
    </div>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('/js/like_posts/like_character_post.js') }}"></script>
    <script src="{{ asset('/js/delete_post.js') }}"></script>
</x-app-layout>
