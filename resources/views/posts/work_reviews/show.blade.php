<x-app-layout>
    @if (session('message'))
        <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)"
            class="fixed top-[15%] left-1/2 transform -translate-x-1/2 text-white px-6 py-3 rounded-lg shadow-lg flex items-center space-x-4 z-50"
            style="background-color: {{ getCategoryColor(session('message')) }};">
            <div class="text-white">
                {{ session('message') }}
            </div>
        </div>
    @endif
    <div id="message"
        class="hidden fixed top-[15%] left-1/2 transform -translate-x-1/2 text-white px-6 py-3 rounded-lg shadow-lg flex items-center space-x-4 z-50">
    </div>

    <div class="container mx-auto px-4 py-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column -->
        <div class="lg:col-span-2 space-y-6">
            <div class="text-lg font-semibold">
                「
                <a href="{{ route('works.show', ['work' => $work_review->work_id]) }}"
                    class="text-blue-500 hover:text-blue-700 underline">
                    {{ $work_review->work->name }}
                </a>
                」への感想投稿
            </div>
            <!-- 感想詳細ブロック -->
            <div class="bg-white rounded-lg shadow-md">
                <div class="bg-pink-100 rounded-t-lg px-6 py-4">
                    <h1 class="text-2xl font-bold">{{ $work_review->post_title }}</h1>
                </div>
                <div class='p-6 space-y-4'>
                    <div class='flex items-center justify-between'>
                        <div class="flex gap-2">
                            @foreach ($work_review->categories as $category)
                                <span class="text-white px-2 py-1 rounded-full text-sm"
                                    style="background-color: {{ getCategoryColor($category->name) }};">
                                    {{ $category->name }}
                                </span>
                            @endforeach
                        </div>
                        <!-- ドロップダウンメニュー -->
                        <x-dropdown align="right" class='ml-auto'>
                            <x-slot name="trigger">
                                <button class="p-1 bg-slate-400 text-white rounded hover:bg-slate-500">
                                    投稿を管理する
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <x-dropdown-link
                                    href="{{ route('work_reviews.edit', ['work_id' => $work_review->work_id, 'work_review_id' => $work_review->id]) }}">投稿を編集する</x-dropdown-link>
                                <form
                                    action="{{ route('work_reviews.delete', ['work_id' => $work_review->work_id, 'work_review_id' => $work_review->id]) }}"
                                    id="form_{{ $work_review->id }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" data-post-id="{{ $work_review->id }}"
                                        class="delete-button block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        投稿を削除する
                                    </button>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>
                    <div class="flex flex-col md:flex-row gap-4">
                        <div class="left_block flex-1">
                            <div class="flex items-center gap-4">
                                <img src="{{ $work_review->user->image ?? 'https://res.cloudinary.com/dnumegejl/image/upload/v1732628038/No_User_Image_wulbjv.png' }}"
                                    alt="画像が読み込めません。" class="w-16 h-16 rounded-full object-cover">
                                <div>
                                    <!-- 自分のアカウントを選択した場合 -->
                                    @if (Auth::id() === $work_review->user->id)
                                        <a href="{{ route('profile.index') }}" class="font-medium">
                                            {{ $work_review->user->name }}
                                        </a>
                                    @else
                                        <a href="{{ route('users.show', ['user_id' => $work_review->user->id]) }}"
                                            class="font-medium">
                                            {{ $work_review->user->name }}
                                        </a>
                                    @endif
                                    <p class="text-gray-500 text-sm">
                                        {{ $work_review->created_at->format('Y/m/d H:i') }}</p>
                                </div>
                            </div>
                            <x-star-num :starNum="$work_review->star_num" />
                            <p class="mt-4 text-gray-800">{!! nl2br(e($work_review->body)) !!}</p>
                        </div>
                        <div class="right_block flex-1">
                            @php
                                $images = [];
                                foreach ([1, 2, 3, 4] as $number) {
                                    $image = 'image' . $number;
                                    if ($work_review->$image) {
                                        $images[] = $work_review->$image;
                                    }
                                }
                            @endphp
                            <div class="grid gap-4 {{ count($images) === 1 ? 'justify-items-center' : '' }}"
                                style="grid-template-columns: repeat({{ count($images) > 1 ? 2 : 1 }}, 1fr);">
                                @foreach ($images as $index => $image)
                                    <a href="{{ $image }}" data-lightbox="gallery"
                                        data-title="{{ '画像' . ($index + 1) }}">
                                        <img src="{{ $image }}" alt="画像が読み込めません。"
                                            class="w-full object-cover rounded-md border border-gray-300 aspect-w-4 aspect-h-3">
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class='flex gap-4 items-center justify-end'>
                        <div class='content_fotter_comment'>
                            <!-- コメントを追加したい場合 -->
                            <button id='toggleComments' type='button'
                                class="px-2 py-1 bg-blue-500 text-white rounded-lg shadow-md hover:bg-blue-600"
                                onclick="toggleCommentForm()">コメントする</button>
                            <button id='closeComments' type='button'
                                class="px-2 py-1 bg-gray-300 text-gray-700 rounded-lg shadow-md hover:bg-gray-400 hidden"
                                onclick="toggleCommentForm()">閉じる</button>
                        </div>
                        <div class='like flex items-center gap-2'>
                            <!-- ボタンの見た目は後のデザイン作成の際に設定する予定 -->
                            <button id="like_button" data-work-id="{{ $work_review->work_id }}"
                                data-review-id="{{ $work_review->id }}" type="submit"
                                class="px-2 py-1 bg-blue-500 text-white rounded-lg shadow-md hover:bg-blue-600">
                                {{ $work_review->users->contains(auth()->user()) ? 'いいね取り消し' : 'いいね' }}
                            </button>
                            <div class="like_user">
                                <a href="{{ route('work_review_like.index', ['work_id' => $work_review->work_id, 'work_review_id' => $work_review->id]) }}"
                                    class="text-lg font-medium text-gray-700">
                                    <p id="like_count">{{ $work_review->users->count() }}件
                                    </p>
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- コメント作成フォーム -->
                    <div id='addCommentBlock' class="w-full p-4 border rounded-lg bg-gray-50" style="display: none;">
                        @include('user_interactions.comments.input_create_comment', [
                            'comment' => $work_review,
                            'inputName' => 'work_review_comment',
                            'inputPostIdName' => 'work_review_id',
                            'baseRoute' => 'work_review',
                            'postCommentId' => $work_review->id,
                            'parentId' => null,
                        ])
                    </div>
                </div>
            </div>
            <div class="text-lg font-semibold">
                「<span class="text-blue-500">{{ $work_review->post_title }}</span>」へのコメント：<span
                    id='comment_count'>{{ count($work_review->workReviewComments) }}</span>件
                </p>
            </div>
            <div id="comments-section">
                @if (!empty($work_review->workReviewComments) && $work_review->workReviewComments->isNotEmpty())
                    <!-- コメント表示 -->
                    <div id='comment_block' class='bg-white rounded-lg shadow-md p-6'>
                        @foreach ($work_review->workReviewComments->where('parent_id', null) as $comment)
                            <div id='replies-{{ $work_review->id }}'>
                                <!-- コメントの区切り線（ただし最後のコメントには表示しない） -->
                                @if (!$loop->first)
                                    <hr class="border-t my-4" id="border-{{ $comment->id }}">
                                @endif
                                @include('user_interactions.comments.input_comment', [
                                    'comment' => $comment,
                                    'status' => 'show',
                                    'inputName' => 'work_review_comment',
                                    'baseRoute' => 'work_review',
                                    'inputPostIdName' => 'work_review_id',
                                    'postCommentId' => $comment->work_review_id,
                                    'parentId' => $comment->id,
                                ])
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- 右側サイドバーブロック -->
        <div class="lg:col-span-1 bg-gray-100 rounded-lg shadow-md p-6">
            <h2 class="text-lg font-bold">サイドバーコンテンツ</h2>
            <ul class="space-y-2">
                <li><a href="#" class="text-blue-500 hover:underline">リンク1</a></li>
                <li><a href="#" class="text-blue-500 hover:underline">リンク2</a></li>
                <li><a href="#" class="text-blue-500 hover:underline">リンク3</a></li>
            </ul>
        </div>
    </div>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('/js/like_posts/like_work_post.js') }}"></script>
    <script src="{{ asset('/js/delete_post.js') }}"></script>
    <script src="{{ asset('/js/comments/like_comment.js') }}"></script>
    <script src="{{ asset('/js/comments/delete_comment.js') }}"></script>
    <script src="{{ asset('/js/comments/load_reply.js') }}"></script>
    <script src="{{ asset('/js/comments/add_comment.js') }}"></script>
    <script src="{{ asset('/js/comments/create_comment_preview.js') }}"></script>
    <script src="{{ asset('/js/comments/store_comment.js') }}"></script>
</x-app-layout>
