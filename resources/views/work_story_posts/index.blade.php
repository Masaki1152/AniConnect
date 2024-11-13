<x-app-layout>
    @if(is_null($work_story_post_first))
    <h2 class='no_post_result'>投稿はまだありません。</h2>
    @else
    <h1>{{ $work_story_post_first->work->name}}</h1>
    <h1>{{ $work_story_post_first->workStory->episode}}</h1>
    <h1>「{{ $work_story_post_first->workStory->sub_title }}」の感想投稿一覧</h1>
    <a href="{{ route('work_story_posts.create', ['work_id' => $work_story_post_first->work_id, 'work_story_id' => $work_story_post_first->sub_title_id]) }}">新規投稿作成</a>
    <!-- 検索機能 -->
    <div class=serch>
        <form action="{{ route('work_story_posts.index', ['work_id' => $work_story_post_first->work_id, 'work_story_id' => $work_story_post_first->sub_title_id]) }}" method="GET">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="キーワードを検索" aria-label="検索...">
            <input type="submit" value="キーワード検索">
        </form>
        <div class="cancel">
            <a href="{{ route('work_story_posts.index', ['work_id' => $work_story_post_first->work_id, 'work_story_id' => $work_story_post_first->sub_title_id]) }}">キャンセル</a>
        </div>
    </div>
    <div class='work_story_posts'>
        @if($work_story_posts->isEmpty())
        <h2 class='no_result'>結果がありません。</h2>
        @else
        <div class='work_story_post'>
            @foreach ($work_story_posts as $work_story_post)
            <div class='work_story_post'>
                <h2 class='title'>
                    <a href="{{ route('work_story_posts.show', ['work_id' => $work_story_post->work_id, 'work_story_id' => $work_story_post->sub_title_id, 'work_story_post_id' => $work_story_post->id]) }}">{{ $work_story_post->post_title }}</a>
                </h2>
                <div class='user'>
                    <p>{{ $work_story_post->user->name }}</p>
                </div>
                <div class="like">
                    <!-- ボタンの見た目は後のデザイン作成の際に設定する予定 -->
                    <button id="like_button"
                        data-work-id="{{ $work_story_post->work_id }}"
                        data-work_story-id="{{ $work_story_post->sub_title_id }}"
                        data-post-id="{{ $work_story_post->id }}"
                        type="submit">
                        {{ $work_story_post->users->contains(auth()->user()) ? 'いいね取り消し' : 'いいね' }}
                    </button>
                    <div class="like_user">
                        <a href="{{ route('work_story_post_like.index', ['work_id' => $work_story_post->work_id, 'work_story_id' => $work_story_post->sub_title_id, 'work_story_post_id' => $work_story_post->id]) }}">
                            <p id="like_count">{{ $work_story_post->users->count() }}</p>
                        </a>
                    </div>
                </div>
                <div class='image'>
                    @if($work_story_post->image1)
                    <div>
                        <img src="{{ $work_story_post->image1 }}" alt="画像が読み込めません。">
                    </div>
                    @endif
                </div>
                <form action="{{ route('work_story_posts.delete', ['work_id' => $work_story_post->work_id, 'work_story_id' => $work_story_post->sub_title_id, 'work_story_post_id' => $work_story_post->id]) }}" id="form_{{ $work_story_post->id }}" method="post">
                    @csrf
                    @method('DELETE')
                    <button type="button" data-post-id="{{ $work_story_post->id }}" class="delete-button">投稿を削除する</button>
                </form>
            </div>
            @endforeach
        </div>
        @endif
    </div>
    <div class="footer">
        <a href="{{ route('work_stories.show', ['work_id' => $work_story_post_first->work_id, 'work_story_id' => $work_story_post_first->sub_title_id]) }}">あらすじ詳細画面へ</a>
    </div>
    <div class='paginate'>
        {{ $work_story_posts->appends(request()->query())->links() }}
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
                    const workId = button.getAttribute('data-work-id');
                    const workStoryId = button.getAttribute('data-work_story-id');
                    const postId = button.getAttribute('data-post-id');
                    try {
                        const response = await fetch(`/works/${workId}/stories/${workStoryId}/posts/${postId}/like`, {
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
    @endif
</x-app-layout>