<!DOCTYPE HTML>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>登場人物の感想</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
</head>

<body>
    <h1 class="title">
        @php 
        //dd($character_post);
        @endphp
        {{ $character_post->post_title }}
    </h1>
    <div class="like">
        
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
                @foreach($character_post->categories as $category)
                {{ $category->name }}
                @endforeach
            </h5>
            <h3>評価</h3>
            <p>{{ $character_post->star_num }}</p>
            <h3>本文</h3>
            <p>{{ $character_post->body }}</p>
            <h3>作成日</h3>
            <p>{{ $character_post->created_at }}</p>
            @php
            $numbers = array(1, 2, 3, 4);
            @endphp
            @foreach($numbers as $number)
            @php
            $image = "image".$number;
            @endphp
            @if($character_post->$image)
            <div>
                <img src="{{ $character_post->$image }}" alt="画像が読み込めません。">
            </div>
            @endif
            @endforeach
        </div>
    </div>
    <div class="edit">
        <a href="{{ route('work_reviews.edit', ['work_id' => $character_post->work_id, 'work_review_id' => $character_post->id]) }}">編集する</a>
    </div>
    <form action="{{ route('work_reviews.delete', ['work_id' => $character_post->work_id, 'work_review_id' => $character_post->id]) }}" id="form_{{ $character_post->id }}" method="post">
        @csrf
        @method('DELETE')
        <button type="button" data-post-id="{{ $character_post->id }}" class="delete-button">投稿を削除する</button>
    </form>
    <div class="footer">
        <a href="{{ route('character_posts.index', ['character_id' => $character_post->character_id]) }}">戻る</a>
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
        //             const workId = button.getAttribute('data-work-id');
        //             const reviewId = button.getAttribute('data-review-id');
        //             try {
        //                 const response = await fetch(`/work_reviews/${workId}/reviews/${reviewId}/like`, {
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