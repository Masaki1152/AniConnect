<x-app-layout>
    <div id="like-message"
        class="hidden fixed top-[15%] left-1/2 transform -translate-x-1/2 bg-green-500/50 text-white px-6 py-3 rounded-lg shadow-lg flex items-center space-x-4 z-50">
    </div>

    <h1>「{{ $music_first->music->name }}」の感想投稿一覧</h1>
    <a href="{{ route('music_posts.create', ['music_id' => $music_first->music_id]) }}">新規投稿作成</a>
    <!-- 検索機能 -->
    <div class=serch>
        <form action="{{ route('music_posts.index', ['music_id' => $music_first->music_id]) }}" method="GET">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="キーワードを検索" aria-label="検索...">
            <input type="submit" value="キーワード検索">
        </form>
        <div class="cancel">
            <a href="{{ route('music_posts.index', ['music_id' => $music_first->music_id]) }}">キャンセル</a>
        </div>
    </div>
    <div class='music_posts'>
        @if ($music_posts->isEmpty())
            <h2 class='no_result'>結果がありません。</h2>
        @else
            <div class='music_post'>
                @foreach ($music_posts as $music_post)
                    <div class='music_post'>
                        <h2 class='title'>
                            <a
                                href="{{ route('music_posts.show', ['music_id' => $music_post->music_id, 'music_post_id' => $music_post->id]) }}">{{ $music_post->post_title }}</a>
                        </h2>
                        <p>{{ $music_post->user->name }}</p>
                        <div class="like">
                            <!-- ボタンの見た目は後のデザイン作成の際に設定する予定 -->
                            <button id="like_button" data-music-id="{{ $music_post->music_id }}"
                                data-post-id="{{ $music_post->id }}" type="submit">
                                {{ $music_post->users->contains(auth()->user()) ? 'いいね取り消し' : 'いいね' }}
                            </button>
                            <div class="like_user">
                                <a
                                    href="{{ route('music_post_like.index', ['music_id' => $music_post->music_id, 'music_post_id' => $music_post->id]) }}">
                                    <p id="like_count">{{ $music_post->users->count() }}</p>
                                </a>
                            </div>
                        </div>
                        <p class='body'>{{ $music_post->body }}</p>
                        <form
                            action="{{ route('music_posts.delete', ['music_id' => $music_post->music_id, 'music_post_id' => $music_post->id]) }}"
                            id="form_{{ $music_post->id }}" method="post">
                            @csrf
                            @method('DELETE')
                            <button type="button" data-post-id="{{ $music_post->id }}"
                                class="delete-button">投稿を削除する</button>
                        </form>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
    <div class="footer">
        <a href="{{ route('music.show', ['music_id' => $music_first->music_id]) }}">音楽詳細画面へ</a>
    </div>
    <div class='paginate'>
        {{ $music_posts->appends(request()->query())->links() }}
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
            const likeMessage = document.getElementById('like-message');
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
                        const response = await fetch(
                            `/music_posts/${musicId}/posts/${postId}/like`, {
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
                        // メッセージを表示
                        likeMessage.textContent = data.message;
                        likeMessage.classList.remove('hidden');
                        likeMessage.classList.add('block');

                        // 3秒後にメッセージを非表示
                        setTimeout(() => {
                            likeMessage.classList.add('hidden');
                            likeMessage.classList.remove('block');
                        }, 3000);
                    } catch (error) {
                        console.error('Error:', error);
                    }
                });
            });
        });
    </script>
</x-app-layout>
