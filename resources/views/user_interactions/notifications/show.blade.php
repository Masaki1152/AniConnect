<x-app-layout>
    <div id="message"
        class="hidden fixed top-[15%] left-1/2 transform -translate-x-1/2 text-white px-6 py-3 rounded-lg shadow-lg flex items-center space-x-4 z-50">
    </div>

    <div class="container mx-auto px-4 py-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column -->
        <div class="lg:col-span-2 space-y-6">
            <div class="text-lg font-semibold">
                お知らせ
            </div>
            <!-- お知らせ詳細ブロック -->
            <div class="bg-white rounded-lg shadow-md">
                <div class="bg-pink-100 rounded-t-lg px-6 py-4">
                    <h1 class="text-2xl font-bold">{{ $notification->title }}</h1>
                </div>
                <div class='p-6 space-y-4'>
                    <div class='flex items-center justify-between'>
                        <div class="flex gap-2">
                            @foreach ($notification->categories as $category)
                                <span class="text-white px-2 py-1 rounded-full text-sm"
                                    style="background-color: {{ getCategoryColor($category->name) }};">
                                    {{ $category->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                    <div class="flex flex-col md:flex-row gap-4">
                        <div class="left_block flex-1">
                            <div class="flex items-center gap-4">
                                <img src="{{ $operate->image ?? 'https://res.cloudinary.com/dnumegejl/image/upload/v1732628038/No_User_Image_wulbjv.png' }}"
                                    alt="画像が読み込めません。" class="w-16 h-16 rounded-full object-cover">
                                <div>
                                    <a href="{{ route('users.show', ['user_id' => $operate->id]) }}"
                                        class="font-medium">
                                        {{ $operate->name }}
                                    </a>
                                    <p class="text-gray-500 text-sm">
                                        {{ $operate->created_at->format('Y/m/d H:i') }}</p>
                                </div>
                            </div>
                            <p class="mt-4 text-gray-800">{!! nl2br(e($notification->body)) !!}</p>
                        </div>
                        <div class="right_block flex-1">
                            @php
                                $images = [];
                                foreach ([1, 2, 3, 4] as $number) {
                                    $image = 'image' . $number;
                                    if ($notification->$image) {
                                        $images[] = $notification->$image;
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
                        <x-molecules.button.like-post-button type="notification" :post="$notification" />
                    </div>
                    <!-- コメント作成フォーム -->
                    <div id='addCommentBlock' class="w-full p-4 border rounded-lg bg-gray-50" style="display: none;">
                        @include('user_interactions.comments.input_create_comment', [
                            'comment' => $notification,
                            'inputName' => 'notification_comment',
                            'inputPostIdName' => 'notification_id',
                            'baseRoute' => 'notification',
                            'postCommentId' => $notification->id,
                            'parentId' => null,
                        ])
                    </div>
                </div>
            </div>
            <div class="text-lg font-semibold">
                「<span class="text-blue-500">{{ $notification->title }}</span>」へのコメント：<span
                    id='comment_count'>{{ count($notification->notificationComments) }}</span>件
                </p>
            </div>
            <div id="comments-section">
                @if (!empty($notification->notificationComments) && $notification->notificationComments->isNotEmpty())
                    <!-- コメント表示 -->
                    <div id='comment_block' class='bg-white rounded-lg shadow-md p-6'>
                        @foreach ($notification->notificationComments->where('parent_id', null) as $comment)
                            <div id='replies-{{ $notification->id }}'>
                                <!-- コメントの区切り線（ただし最後のコメントには表示しない） -->
                                @if (!$loop->first)
                                    <hr class="border-t my-4" id="border-{{ $comment->id }}">
                                @endif
                                @include('user_interactions.comments.input_comment', [
                                    'comment' => $comment,
                                    'status' => 'show',
                                    'inputName' => 'notification_comment',
                                    'baseRoute' => 'notification',
                                    'inputPostIdName' => 'notification_id',
                                    'postCommentId' => $notification->id,
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
    <script src="{{ asset('/js/like_post.js') }}"></script>
    <script src="{{ asset('/js/comments/like_comment.js') }}"></script>
    <script src="{{ asset('/js/comments/delete_comment.js') }}"></script>
    <script src="{{ asset('/js/comments/load_reply.js') }}"></script>
    <script src="{{ asset('/js/comments/add_comment.js') }}"></script>
    <script src="{{ asset('/js/comments/create_comment_preview.js') }}"></script>
    <script src="{{ asset('/js/comments/store_comment.js') }}"></script>
</x-app-layout>
