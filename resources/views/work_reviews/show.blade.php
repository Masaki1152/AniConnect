<x-app-layout>
    <div id="like-message"
        class="hidden fixed top-[15%] left-1/2 transform -translate-x-1/2 bg-green-500/50 text-white px-6 py-3 rounded-lg shadow-lg flex items-center space-x-4 z-50">
    </div>

    <h1 class="title">
        {{ $work_review->post_title }}
    </h1>
    <div class="like">
        <!-- ボタンの見た目は後のデザイン作成の際に設定する予定 -->
        <button id="like_button" data-work-id="{{ $work_review->work_id }}" data-review-id="{{ $work_review->id }}"
            type="submit">
            {{ $work_review->users->contains(auth()->user()) ? 'いいね取り消し' : 'いいね' }}
        </button>
        <div class="like_user">
            <a
                href="{{ route('work_review_like.index', ['work_id' => $work_review->work_id, 'work_review_id' => $work_review->id]) }}">
                <p id="like_count">{{ $work_review->users->count() }}</p>
            </a>
        </div>
    </div>
    <div class="content">
        <div class="content__work_review">
            <h3>作品名</h3>
            <p>{{ $work_review->work->name }}</p>
            <h3>投稿者</h3>
            <p>{{ $work_review->user->name }}</p>
            <h3>カテゴリー</h3>
            <h5 class='category'>
                @foreach ($work_review->categories as $category)
                    {{ $category->name }}
                @endforeach
            </h5>
            <h3>本文</h3>
            <p>{{ $work_review->body }}</p>
            <h3>作成日</h3>
            <p>{{ $work_review->created_at }}</p>
            @php
                $numbers = [1, 2, 3, 4];
            @endphp
            @foreach ($numbers as $number)
                @php
                    $image = 'image' . $number;
                @endphp
                @if ($work_review->$image)
                    <div>
                        <img src="{{ $work_review->$image }}" alt="画像が読み込めません。">
                    </div>
                @endif
            @endforeach
        </div>
    </div>
    <div class="edit">
        <a
            href="{{ route('work_reviews.edit', ['work_id' => $work_review->work_id, 'work_review_id' => $work_review->id]) }}">編集する</a>
    </div>
    <form
        action="{{ route('work_reviews.delete', ['work_id' => $work_review->work_id, 'work_review_id' => $work_review->id]) }}"
        id="form_{{ $work_review->id }}" method="post">
        @csrf
        @method('DELETE')
        <button type="button" data-post-id="{{ $work_review->id }}" class="delete-button">投稿を削除する</button>
    </form>
    <div class="footer">
        <a href="{{ route('work_reviews.index', ['work_id' => $work_review->work_id]) }}">戻る</a>
    </div>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('/js/like_posts/like_work_post.js') }}"></script>
    <script src="{{ asset('/js/delete_post.js') }}"></script>
</x-app-layout>
