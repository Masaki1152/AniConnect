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
                <a href="{{ route('characters.show', ['character_id' => $character_post->character_id]) }}"
                    class="text-blue-500 hover:text-blue-700 underline">
                    {{ $character_post->character->name }}
                </a>
                」への感想投稿
            </div>
            <!-- 感想詳細ブロック -->
            <div class="bg-white rounded-lg shadow-md">
                <div class="bg-amber-100 rounded-t-lg px-6 py-4">
                    <h1 class="text-2xl font-bold">{{ $character_post->post_title }}</h1>
                </div>
                <div class='p-6 space-y-4'>
                    <div class='flex items-center justify-between'>
                        <div class="flex gap-2">
                            @foreach ($character_post->categories as $category)
                                <span class="text-white px-2 py-1 rounded-full text-sm"
                                    style="background-color: {{ getCategoryColor($category->name) }};">
                                    {{ $category->name }}
                                </span>
                            @endforeach
                        </div>
                        <!-- ドロップダウンメニュー -->
                        <x-atom.dropdown align="right" class='ml-auto'>
                            <x-slot name="trigger">
                                <button class="p-1 bg-slate-400 text-white rounded hover:bg-slate-500">
                                    投稿を管理する
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <x-atom.dropdown-link
                                    href="{{ route('character_posts.edit', ['character_id' => $character_post->character_id, 'character_post_id' => $character_post->id]) }}">投稿を編集する</x-atom.dropdown-link>
                                <form
                                    action="{{ route('character_posts.delete', ['character_id' => $character_post->character_id, 'character_post_id' => $character_post->id]) }}"
                                    id="form_{{ $character_post->id }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" data-post-id="{{ $character_post->id }}"
                                        class="delete-button block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        投稿を削除する
                                    </button>
                                </form>
                            </x-slot>
                        </x-atom.dropdown>
                    </div>
                    <div class="flex flex-col md:flex-row gap-4">
                        <div class="left_block flex-1">
                            <div class="flex items-center gap-4">
                                <img src="{{ $character_post->user->image ?? 'https://res.cloudinary.com/dnumegejl/image/upload/v1732628038/No_User_Image_wulbjv.png' }}"
                                    alt="画像が読み込めません。" class="w-16 h-16 rounded-full object-cover">
                                <div>
                                    <!-- 自分のアカウントを選択した場合 -->
                                    @if (Auth::id() === $character_post->user->id)
                                        <a href="{{ route('profile.index') }}" class="font-medium">
                                            {{ $character_post->user->name }}
                                        </a>
                                    @else
                                        <a href="{{ route('users.show', ['user_id' => $character_post->user->id]) }}"
                                            class="font-medium">
                                            {{ $character_post->user->name }}
                                        </a>
                                    @endif
                                    <p class="text-gray-500 text-sm">
                                        {{ $character_post->created_at->format('Y/m/d H:i') }}</p>
                                </div>
                            </div>
                            <x-molecules.evaluation.star-num :starNum="$character_post->star_num" />
                            <p class="mt-4 text-gray-800">{!! nl2br(e($character_post->body)) !!}</p>
                        </div>
                        <div class="right_block flex-1">
                            @php
                                $images = [];
                                foreach ([1, 2, 3, 4] as $number) {
                                    $image = 'image' . $number;
                                    if ($character_post->$image) {
                                        $images[] = $character_post->$image;
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
                        <x-molecules.button.show-comment-form-button :comment="null" />
                        <x-molecules.button.like-post-button type="character" :post="$character_post" />
                    </div>
                    <!-- コメント作成フォーム -->
                    <div id='addCommentBlock' class="w-full p-4 border rounded-lg bg-gray-50" style="display: none;">
                        @include('user_interactions.comments.input_create_comment', [
                            'comment' => $character_post,
                            'inputName' => 'character_post_comment',
                            'inputPostIdName' => 'character_post_id',
                            'baseRoute' => 'character_post',
                            'postCommentId' => $character_post->id,
                            'parentId' => null,
                        ])
                    </div>
                </div>
            </div>
            <div class="text-lg font-semibold">
                「<span class="text-blue-500">{{ $character_post->post_title }}</span>」へのコメント：<span
                    id='comment_count'>{{ count($character_post->characterPostComments) }}</span>件
                </p>
            </div>
            <div id="comments-section">
                @if (!empty($character_post->characterPostComments) && $character_post->characterPostComments->isNotEmpty())
                    <!-- コメント表示 -->
                    <div id='comment_block' class='bg-white rounded-lg shadow-md p-6'>
                        @foreach ($character_post->characterPostComments->where('parent_id', null) as $comment)
                            <div id='replies-{{ $character_post->id }}'>
                                <!-- コメントの区切り線（ただし最後のコメントには表示しない） -->
                                @if (!$loop->first)
                                    <hr class="border-t my-4" id="border-{{ $comment->id }}">
                                @endif
                                @include('user_interactions.comments.input_comment', [
                                    'comment' => $comment,
                                    'status' => 'show',
                                    'inputName' => 'character_post_comment',
                                    'baseRoute' => 'character_post',
                                    'inputPostIdName' => 'character_post_id',
                                    'postCommentId' => $comment->character_post_id,
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
    <script src="{{ asset('/js/delete_post.js') }}"></script>
    <script src="{{ asset('/js/comments/like_comment.js') }}"></script>
    <script src="{{ asset('/js/comments/delete_comment.js') }}"></script>
    <script src="{{ asset('/js/comments/load_reply.js') }}"></script>
    <script src="{{ asset('/js/comments/add_comment.js') }}"></script>
    <script src="{{ asset('/js/comments/create_comment_preview.js') }}"></script>
    <script src="{{ asset('/js/comments/store_comment.js') }}"></script>
</x-app-layout>
