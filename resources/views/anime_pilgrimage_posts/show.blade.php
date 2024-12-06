<x-app-layout>
    <div id="like-message"
        class="hidden fixed top-[15%] left-1/2 transform -translate-x-1/2 bg-green-500/50 text-white px-6 py-3 rounded-lg shadow-lg flex items-center space-x-4 z-50">
    </div>

    <h1 class="title">
        {{ $pilgrimage_post->title }}
    </h1>
    <div class="like">
        <!-- ボタンの見た目は後のデザイン作成の際に設定する予定 -->
        <button id="like_button" data-pilgrimage-id="{{ $pilgrimage_post->anime_pilgrimage_id }}"
            data-post-id="{{ $pilgrimage_post->id }}" type="submit">
            {{ $pilgrimage_post->users->contains(auth()->user()) ? 'いいね取り消し' : 'いいね' }}
        </button>
        <div class="like_user">
            <a
                href="{{ route('pilgrimage_post_like.index', ['pilgrimage_id' => $pilgrimage_post->anime_pilgrimage_id, 'pilgrimage_post_id' => $pilgrimage_post->id]) }}">
                <p id="like_count">{{ $pilgrimage_post->users->count() }}</p>
            </a>
        </div>
    </div>
    <div class="content">
        <div class="content__character_post">
            <h3>聖地名</h3>
            <p>{{ $pilgrimage_post->animePilgrimage->name }}</p>
            <h3>投稿者</h3>
            <p>{{ $pilgrimage_post->user->name }}</p>
            <h3>タイトル</h3>
            <p>{{ $pilgrimage_post->post_title }}</p>
            <h3>シーン</h3>
            <p>{{ $pilgrimage_post->scene }}</p>
            <h3>本文</h3>
            <p>{{ $pilgrimage_post->body }}</p>
            <h3>作成日</h3>
            <p>{{ $pilgrimage_post->created_at }}</p>
            @php
                $numbers = [1, 2, 3, 4];
            @endphp
            @foreach ($numbers as $number)
                @php
                    $image = 'image' . $number;
                @endphp
                @if ($pilgrimage_post->$image)
                    <div>
                        <img src="{{ $pilgrimage_post->$image }}" alt="画像が読み込めません。">
                    </div>
                @endif
            @endforeach
        </div>
    </div>
    <div class="edit">
        <a
            href="{{ route('pilgrimage_posts.edit', ['pilgrimage_id' => $pilgrimage_post->anime_pilgrimage_id, 'pilgrimage_post_id' => $pilgrimage_post->id]) }}">編集する</a>
    </div>
    <form
        action="{{ route('pilgrimage_posts.delete', ['pilgrimage_id' => $pilgrimage_post->anime_pilgrimage_id, 'pilgrimage_post_id' => $pilgrimage_post->id]) }}"
        id="form_{{ $pilgrimage_post->id }}" method="post">
        @csrf
        @method('DELETE')
        <button type="button" data-post-id="{{ $pilgrimage_post->id }}" class="delete-button">投稿を削除する</button>
    </form>
    <div class="footer">
        <a
            href="{{ route('pilgrimage_posts.index', ['pilgrimage_id' => $pilgrimage_post->anime_pilgrimage_id]) }}">戻る</a>
    </div>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('/js/like_posts/like_anime_pilgrimage_post.js') }}"></script>

    <script>
        // DOMツリー読み取り完了後にイベント発火
        document.addEventListener('DOMContentLoaded', function() {
            // delete-buttonに一致するすべてのHTML要素を取得
            document.querySelectorAll('.delete-button').forEach(function(button) {
                button.addEventListener('click', function() {
                    const postId = button.getAttribute('data-post-id');
                    deletePost(postId);
                });
            });
        });

        // 削除処理を行う
        function deletePost(postId) {
            'use strict'

            if (confirm('削除すると復元できません。\n本当に削除しますか？')) {
                document.getElementById(`form_${postId}`).submit();
            }
        }
    </script>
</x-app-layout>
