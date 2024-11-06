<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <title>Blog</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
</head>

<body>
    <h1>「{{ $pilgrimage_first->animePilgrimage->name }}」の感想投稿一覧</h1>
    <a href="{{ route('character_posts.create', ['character_id' => $pilgrimage_first->anime_pilgrimage_id]) }}">新規投稿作成</a>
    <!-- 検索機能 -->
    <div class=serch>
        <form action="{{ route('pilgrimage_posts.index', ['pilgrimage_id' => $pilgrimage_first->anime_pilgrimage_id]) }}" method="GET">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="キーワードを検索" aria-label="検索...">
            <input type="submit" value="キーワード検索">
        </form>
        <div class="cancel">
            <a href="{{ route('pilgrimage_posts.index', ['pilgrimage_id' => $pilgrimage_first->anime_pilgrimage_id]) }}">キャンセル</a>
        </div>
    </div>
    <div class='pilgrimage_posts'>
        @if($pilgrimage_posts->isEmpty())
        <h2 class='no_result'>結果がありません。</h2>
        @else
        <div class='character_post'>
            @foreach ($pilgrimage_posts as $pilgrimage_post)
            <div class='pilgrimage_post'>
                <h2 class='title'>
                    <a href="{{ route('pilgrimage_posts.show', ['pilgrimage_id' => $pilgrimage_post->anime_pilgrimage_id, 'pilgrimage_post_id' => $pilgrimage_post->id]) }}">{{ $pilgrimage_post->title }}</a>
                </h2>
                <h3 class='user'>
                    {{ $pilgrimage_post->user->name }}
                </h3>
                <h3 class='scene'>
                    {{ $pilgrimage_post->scene }}
                </h3>
                <div class="like">
                    
                </div>
                <p class='body'>{{ $pilgrimage_post->body }}</p>
                @if($pilgrimage_post->image1)
                <div>
                    <img src="{{ $pilgrimage_post->image1 }}" alt="画像が読み込めません。">
                </div>
                @endif
                <form action="{{ route('character_posts.delete', ['character_id' => $pilgrimage_post->anime_pilgrimage_id, 'character_post_id' => $pilgrimage_post->id]) }}" id="form_{{ $pilgrimage_post->id }}" method="post">
                    @csrf
                    @method('DELETE')
                    <button type="button" data-post-id="{{ $pilgrimage_post->id }}" class="delete-button">投稿を削除する</button>
                </form>
            </div>
            @endforeach
        </div>
        @endif
    </div>
    <div class="footer">
        <a href="{{ route('pilgrimages.show', ['pilgrimage_id' => $pilgrimage_first->anime_pilgrimage_id]) }}">聖地詳細画面へ</a>
    </div>
    <div class='paginate'>
        {{ $pilgrimage_posts->appends(request()->query())->links() }}
    </div>

    <script>
        // // DOMツリー読み取り完了後にイベント発火
        // document.addEventListener('DOMContentLoaded', function() {
        //     // delete-buttonに一致するすべてのHTML要素を取得
        //     document.querySelectorAll('.delete-button').forEach(function(button) {
        //         button.addEventListener('click', function() {
        //             const postId = button.getAttribute('data-post-id');
        //             deletePost(postId);
        //         });
        //     });
        // });

        // // 削除処理を行う
        // function deletePost(postId) {
        //     'use strict'

        //     if (confirm('削除すると復元できません。\n本当に削除しますか？')) {
        //         document.getElementById(`form_${postId}`).submit();
        //     }
        // }

        // // いいね処理を非同期で行う
        // document.addEventListener('DOMContentLoaded', function() {
        //     const likeClasses = document.querySelectorAll('.like');
        //     likeClasses.forEach(element => {
        //         // いいねボタンのクラスの取得
        //         let button = element.querySelector('#like_button');
        //         // いいねしたユーザー数のクラス取得とpタグの取得
        //         let likeClass = element.querySelector('.like_user');
        //         let users = likeClass.querySelector('#like_count');

        //         //いいねボタンクリックによる非同期処理
        //         button.addEventListener('click', async function() {
        //             const characterId = button.getAttribute('data-character-id');
        //             const postId = button.getAttribute('data-post-id');
        //             try {
        //                 const response = await fetch(`/character_posts/${characterId}/posts/${postId}/like`, {
        //                     method: 'POST',
        //                     headers: {
        //                         'Content-Type': 'application/json',
        //                         'X-CSRF-TOKEN': '{{ csrf_token() }}'
        //                     },
        //                 });
        //                 const data = await response.json();
        //                 if (data.status === 'liked') {
        //                     button.innerText = 'いいね取り消し';
        //                     users.innerText = data.like_user;
        //                 } else if (data.status === 'unliked') {
        //                     button.innerText = 'いいね';
        //                     users.innerText = data.like_user;
        //                 }
        //             } catch (error) {
        //                 console.error('Error:', error);
        //             }
        //         });
        //     });
        // });
    </script>
</body>

</html>