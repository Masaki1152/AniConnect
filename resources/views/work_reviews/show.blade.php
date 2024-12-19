<x-app-layout>
    @if (session('status'))
        <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)"
            class="fixed top-[15%] left-1/2 transform -translate-x-1/2 bg-green-500/50 text-white px-6 py-3 rounded-lg shadow-lg flex items-center space-x-4 z-50">
            <div class="text-white">
                {{ session('status') }}
            </div>
        </div>
    @endif
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
            <h5 class='category flex gap-2'>
                @foreach ($work_review->categories as $category)
                    <span class="text-white px-2 py-1 rounded-full text-sm"
                        style="background-color: {{ getCategoryColor($category->name) }};">
                        {{ $category->name }}
                    </span>
                @endforeach
            </h5>
            <h3>本文</h3>
            <p>{{ $work_review->body }}</p>
            <h3>作成日</h3>
            <p>{{ $work_review->created_at }}</p>
            @foreach ([1, 2, 3, 4] as $number)
                @php
                    $image = 'image' . $number;
                @endphp
                @if ($work_review->$image)
                    <div>
                        <a href="{{ $work_review->$image }}" data-lightbox="gallery"
                            data-title="{{ '画像' . $number }}">
                            <img src="{{ $work_review->$image }}" alt="画像が読み込めません。"
                                class='w-36 h-36 object-cover rounded-md border border-gray-300 mb-2'>
                        </a>
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
    @if (!empty($work_review->workReviewComments))
        <div class="comment">
            <p>コメント:{{ count($work_review->workReviewComments) }}件</p>
            @foreach ($work_review->workReviewComments->where('parent_id', null) as $comment)
                <div>
                    <p>{{ $comment->user->name }}</p>
                    <p>{{ $comment->body }}</p>
                    <div class="comment-image">
                        @foreach ([1, 2, 3, 4] as $number)
                            @php
                                $image = 'image' . $number;
                            @endphp
                            @if ($comment->$image)
                                <div>
                                    <a href="{{ $comment->$image }}" data-lightbox="comment_gallery"
                                        data-title="{{ '画像' . $number }}">
                                        <img src="{{ $comment->$image }}" alt="画像が読み込めません。"
                                            class='w-36 h-36 object-cover rounded-md border border-gray-300 mb-2'>
                                    </a>
                                </div>
                            @endif
                        @endforeach
                    </div>
                    <div class="comment-like">
                        <!-- ボタンの見た目は後のデザイン作成の際に設定する予定 -->
                        <button id="comment-like_button" data-comment-id="{{ $comment->id }}" type="submit">
                            {{ $comment->users->contains(auth()->user()) ? 'いいね取り消し' : 'いいね' }}
                        </button>
                        <div class="comment-like_user">
                            <a href="{{ route('work_review_comment.like.index', ['comment_id' => $comment->id]) }}">
                                <p id="comment-like_count">{{ $comment->users->count() }}</p>
                            </a>
                        </div>
                    </div>
                    <form action="{{ route('work_review.comments.delete', ['comment_id' => $comment->id]) }}"
                        id="comment_{{ $comment->id }}" method="post">
                        @csrf
                        @method('DELETE')
                        <button type="button" data-comment-id="{{ $comment->id }}"
                            class="delete-comment-button">コメントを削除する</button>
                    </form>

                    <!-- 子コメント表示 -->
                    @foreach ($comment->replies as $reply)
                        <div style="margin-left: 20px;">
                            <p>{{ $reply->body }}</p>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    @else
        <p>コメント:0件</p>
    @endif
    <p>コメントの作成</p>
    <form action="{{ route('work_review.comments.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="work_review_comment[work_review_id]" value="{{ $work_review->id }}">
        <input type="hidden" name="work_review_comment[parent_id]" value="">
        <textarea name="work_review_comment[body]" required placeholder="コメントを入力してください">{{ old('work_review_comment.body') }}</textarea>
        <p class="body__error" style="color:red">{{ $errors->first('work_review_comment.body') }}</p>
        <div class="image">
            <h2>画像（4枚まで）</h2>
            <label>
                <input id="inputElm" type="file" style="display:none" name="images[]" multiple
                    onchange="loadImage(this);">画像の追加
                <div id="count">現在、0枚の画像を選択しています。</div>
            </label>
            <p class="image__error" style="color:red">{{ $errors->first('images') }}</p>
        </div>
        <!-- プレビュー画像の表示 -->
        <div id="preview" style="width: 300px;"></div>
        <button type="submit">コメントする</button>
    </form>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('/js/like_posts/like_work_post.js') }}"></script>
    <script src="{{ asset('/js/like_comments/like_wr_comment.js') }}"></script>
    <script src="{{ asset('/js/delete_post.js') }}"></script>
    <script src="{{ asset('/js/delete_comment.js') }}"></script>
    <script src="{{ asset('/js/create_preview.js') }}"></script>
</x-app-layout>
