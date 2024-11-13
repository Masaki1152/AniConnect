<x-app-layout>
    <h1>「{{ $character_first->character->name }}」の感想投稿一覧</h1>
    <a href="{{ route('character_posts.create', ['character_id' => $character_first->character_id]) }}">新規投稿作成</a>
    <!-- 検索機能 -->
    <div class=serch>
        <form action="{{ route('character_posts.index', ['character_id' => $character_first->character_id]) }}" method="GET">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="キーワードを検索" aria-label="検索...">
            <input type="submit" value="キーワード検索">
        </form>
        <div class="cancel">
            <a href="{{ route('character_posts.index', ['character_id' => $character_first->character_id]) }}">キャンセル</a>
        </div>
    </div>
    <div class='character_posts'>
        @if($character_posts->isEmpty())
        <h2 class='no_result'>結果がありません。</h2>
        @else
        <div class='character_post'>
            @foreach ($character_posts as $character_post)
            <div class='character_post'>
                <h2 class='title'>
                    <a href="{{ route('character_posts.show', ['character_id' => $character_post->character_id, 'character_post_id' => $character_post->id]) }}">{{ $character_post->post_title }}</a>
                </h2>
                <div class="like">
                    <!-- ボタンの見た目は後のデザイン作成の際に設定する予定 -->
                    <button id="like_button"
                        data-character-id="{{ $character_post->character_id }}"
                        data-post-id="{{ $character_post->id }}"
                        type="submit">
                        {{ $character_post->users->contains(auth()->user()) ? 'いいね取り消し' : 'いいね' }}
                    </button>
                    <div class="like_user">
                        <a href="{{ route('character_post_like.index', ['character_id' => $character_post->character_id, 'character_post_id' => $character_post->id]) }}">
                            <p id="like_count">{{ $character_post->users->count() }}</p>
                        </a>
                    </div>
                </div>

                <h5 class='category'>
                    @foreach($character_post->categories as $category)
                    {{ $category->name }}
                    @endforeach
                </h5>
                <p class='body'>{{ $character_post->body }}</p>
                @if($character_post->image1)
                <div>
                    <img src="{{ $character_post->image1 }}" alt="画像が読み込めません。">
                </div>
                @endif
                <form action="{{ route('character_posts.delete', ['character_id' => $character_post->character_id, 'character_post_id' => $character_post->id]) }}" id="form_{{ $character_post->id }}" method="post">
                    @csrf
                    @method('DELETE')
                    <button type="button" data-post-id="{{ $character_post->id }}" class="delete-button">投稿を削除する</button>
                </form>
            </div>
            @endforeach
        </div>
        @endif
    </div>
    <div class="footer">
        <a href="{{ route('characters.show', ['character_id' => $character_first->character_id]) }}">登場人物詳細画面へ</a>
    </div>
    <div class='paginate'>
        {{ $character_posts->appends(request()->query())->links() }}
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
                    const characterId = button.getAttribute('data-character-id');
                    const postId = button.getAttribute('data-post-id');
                    try {
                        const response = await fetch(`/character_posts/${characterId}/posts/${postId}/like`, {
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