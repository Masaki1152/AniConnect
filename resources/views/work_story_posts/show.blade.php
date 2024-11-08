<!DOCTYPE HTML>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>あらすじの感想</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
</head>

<body>
    <h1 class="title">
        {{ $work_story_post->post_title }}
    </h1>
    <div class="like">

    </div>
    <div class="content">
        <div class="content__character_post">
            <h3>投稿者</h3>
            <p>{{ $work_story_post->user->name }}</p>
            <h3>作品名</h3>
            <p>{{ $work_story_post->work->name }}</p>
            <h3>話数</h3>
            <p>{{ $work_story_post->workStory->episode }}</p>
            <h3>タイトル</h3>
            <p>{{ $work_story_post->workStory->sub_title }}</p>
            <h3>本文</h3>
            <p>{{ $work_story_post->body }}</p>
            <h3>作成日</h3>
            <p>{{ $work_story_post->created_at }}</p>
            @php
            $numbers = array(1, 2, 3, 4);
            @endphp
            @foreach($numbers as $number)
            @php
            $image = "image".$number;
            @endphp
            @if($work_story_post->$image)
            <div>
                <img src="{{ $work_story_post->$image }}" alt="画像が読み込めません。">
            </div>
            @endif
            @endforeach
        </div>
    </div>
    <div class="edit">
        <a href="{{ route('character_posts.edit', ['character_id' => $work_story_post->id, 'character_post_id' => $work_story_post->id]) }}">編集する</a>
    </div>
    <form action="{{ route('character_posts.delete', ['character_id' => $work_story_post->id, 'character_post_id' => $work_story_post->id]) }}" id="form_{{ $work_story_post->id }}" method="post">
        @csrf
        @method('DELETE')
        <button type="button" data-post-id="{{ $work_story_post->id }}" class="delete-button">投稿を削除する</button>
    </form>
    <div class="footer">
        <a href="{{ route('work_story_posts.index', ['work_id' => $work_story_post->work_id, 'work_story_id' => $work_story_post->sub_title_id]) }}">戻る</a>
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