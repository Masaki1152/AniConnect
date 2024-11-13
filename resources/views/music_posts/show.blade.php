<x-app-layout>
    <h1 class="title">
        {{ $music_post->post_title }}
    </h1>
    <div class="like">
        <!-- ボタンの見た目は後のデザイン作成の際に設定する予定 -->
        <button id="like_button"
            data-music-id="{{ $music_post->music_id }}"
            data-post-id="{{ $music_post->id }}"
            type="submit">
            {{ $music_post->users->contains(auth()->user()) ? 'いいね取り消し' : 'いいね' }}
        </button>
        <div class="like_user">
            <a href="{{ route('music_post_like.index', ['music_id' => $music_post->music_id, 'music_post_id' => $music_post->id]) }}">
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
            <h3>評価</h3>
            @php
            $numbers = array(1 => '★', 2 => '★★', 3 => '★★★', 4 => '★★★★', 5 => '★★★★★');
            @endphp
            <p>{{ $numbers[$music_post->star_num] }}</p>
            <h3>本文</h3>
            <p>{{ $music_post->body }}</p>
            <h3>作成日</h3>
            <p>{{ $music_post->created_at }}</p>
        </div>
    </div>
    <div class="edit">
        <a href="{{ route('music_posts.edit', ['music_id' => $music_post->music_id, 'music_post_id' => $music_post->id]) }}">編集する</a>
    </div>
    <form action="{{ route('music_posts.delete', ['music_id' => $music_post->music_id, 'music_post_id' => $music_post->id]) }}" id="form_{{ $music_post->id }}" method="post">
        @csrf
        @method('DELETE')
        <button type="button" data-post-id="{{ $music_post->id }}" class="delete-button">投稿を削除する</button>
    </form>
    <div class="footer">
        <a href="{{ route('music_posts.index', ['music_id' => $music_post->music_id]) }}">戻る</a>
    </div>

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

        // いいね処理を非同期で行う
        document.addEventListener('DOMContentLoaded', function() {
            const likeClasses = document.querySelectorAll('.like');
            likeClasses.forEach(element => {
                // いいねボタンのクラスの取得
                let button = element.querySelector('#like_button');
                // いいねしたユーザー数のクラス取得とpタグの取得
                let likeClass = element.querySelector('.like_user');
                let users = likeClass.querySelector('#like_count');

                //いいねボタンクリックによる非同期処理
                button.addEventListener('click', async function() {
                    const musicId = button.getAttribute('data-music-id');
                    const postId = button.getAttribute('data-post-id');
                    try {
                        const response = await fetch(`/music_posts/${musicId}/posts/${postId}/like`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                        });
                        const data = await response.json();
                        if (data.status === 'liked') {
                            button.innerText = 'いいね取り消し';
                            users.innerText = data.like_user;
                        } else if (data.status === 'unliked') {
                            button.innerText = 'いいね';
                            users.innerText = data.like_user;
                        }
                    } catch (error) {
                        console.error('Error:', error);
                    }
                });
            });
        });
    </script>
</x-app-layout>