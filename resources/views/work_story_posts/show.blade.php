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
                <a href="{{ route('works.show', ['work' => $work_story_post->work_id]) }}"
                    class="text-blue-500 hover:text-blue-700 underline">
                    {{ $work_story_post->workStory->work->name }}
                </a>
                」<br>
                {{ $work_story_post->workStory->episode }}
                「
                <a href="{{ route('work_stories.show', ['work_id' => $work_story_post->work_id, 'work_story_id' => $work_story_post->sub_title_id]) }}"
                    class="text-blue-500 hover:text-blue-700 underline">
                    {{ $work_story_post->workStory->sub_title }}
                </a>
                」への感想投稿
            </div>
            <!-- 感想詳細ブロック -->
            <div class="bg-white rounded-lg shadow-md">
                <div class="bg-green-100 rounded-t-lg px-6 py-4">
                    <h1 class="text-2xl font-bold">{{ $work_story_post->post_title }}</h1>
                </div>
                <div class='p-6 space-y-4'>
                    <div class='flex items-center justify-between'>
                        <div class="flex gap-2">
                            @foreach ($work_story_post->categories as $category)
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
                                    href="{{ route('work_story_posts.edit', ['work_id' => $work_story_post->work_id, 'work_story_id' => $work_story_post->sub_title_id, 'work_story_post_id' => $work_story_post->id]) }}">投稿を編集する</x-dropdown-link>
                                <form
                                    action="{{ route('work_story_posts.delete', ['work_id' => $work_story_post->work_id, 'work_story_id' => $work_story_post->sub_title_id, 'work_story_post_id' => $work_story_post->id]) }}"
                                    id="form_{{ $work_story_post->id }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" data-post-id="{{ $work_story_post->id }}"
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
                                <img src="{{ $work_story_post->user->image ?? 'https://res.cloudinary.com/dnumegejl/image/upload/v1732628038/No_User_Image_wulbjv.png' }}"
                                    alt="画像が読み込めません。" class="w-16 h-16 rounded-full object-cover">
                                <div>
                                    <!-- 自分のアカウントを選択した場合 -->
                                    @if (Auth::id() === $work_story_post->user->id)
                                        <a href="{{ route('profile.index') }}" class="font-medium">
                                            {{ $work_story_post->user->name }}
                                        </a>
                                    @else
                                        <a href="{{ route('users.show', ['user_id' => $work_story_post->user->id]) }}"
                                            class="font-medium">
                                            {{ $work_story_post->user->name }}
                                        </a>
                                    @endif
                                    <p class="text-gray-500 text-sm">
                                        {{ $work_story_post->created_at->format('Y/m/d H:i') }}</p>
                                </div>
                            </div>
                            <p class="mt-4 text-gray-800">{!! nl2br(e($work_story_post->body)) !!}</p>
                        </div>
                        <div class="right_block flex-1">
                            @php
                                $images = [];
                                foreach ([1, 2, 3, 4] as $number) {
                                    $image = 'image' . $number;
                                    if ($work_story_post->$image) {
                                        $images[] = $work_story_post->$image;
                                    }
                                }
                            @endphp
                            <div class="grid gap-4 {{ count($images) === 1 ? 'justify-items-center' : '' }}"
                                style="grid-template-columns: repeat({{ count($images) > 1 ? 2 : 1 }}, 1fr);">
                                @foreach ($images as $index => $image)
                                    <a href="{{ $image }}" data-lightbox="gallery"
                                        data-title="{{ '画像' . ($index + 1) }}">
                                        <img src="{{ $image }}" alt="画像が読み込めません。"
                                            class="w-full object-cover rounded-md border border-gray-300"
                                            style="aspect-ratio: 1/1;">
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
                            <button id="like_button" data-work-id="{{ $work_story_post->work_id }}"
                                data-work_story-id="{{ $work_story_post->sub_title_id }}"
                                data-post-id="{{ $work_story_post->id }}" type="submit"
                                class="px-2 py-1 bg-blue-500 text-white rounded-lg shadow-md hover:bg-blue-600">
                                {{ $work_story_post->users->contains(auth()->user()) ? 'いいね取り消し' : 'いいね' }}
                            </button>
                            <div class="like_user">
                                <a href="{{ route('work_story_post_like.index', ['work_id' => $work_story_post->work_id, 'work_story_id' => $work_story_post->sub_title_id, 'work_story_post_id' => $work_story_post->id]) }}"
                                    class="text-lg font-medium text-gray-700">
                                    <p id="like_count">{{ $work_story_post->users->count() }}件
                                    </p>
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- コメント作成フォーム -->
                    <div id='addCommentBlock' class="w-full p-4 border rounded-lg bg-gray-50" style="display: none;">
                        @include('comments.input_create_comment', [
                            'comment' => $work_story_post,
                            'inputName' => 'work_story_post_comment',
                            'inputPostIdName' => 'work_story_post_id',
                            'baseRoute' => 'work_story_post',
                            'postCommentId' => $work_story_post->id,
                            'parentId' => null,
                        ])
                    </div>
                </div>
            </div>
            <div class="text-lg font-semibold">
                「<span class="text-blue-500">{{ $work_story_post->post_title }}</span>」へのコメント：<span
                    id='comment_count'>{{ count($work_story_post->workStoryPostComments) }}</span>件
                </p>
            </div>
            <div id="comments-section">
                @if (!empty($work_story_post->workStoryPostComments) && $work_story_post->workStoryPostComments->isNotEmpty())
                    <!-- コメント表示 -->
                    <div id='comment_block' class='bg-white rounded-lg shadow-md p-6'>
                        @foreach ($work_story_post->workStoryPostComments->where('parent_id', null) as $comment)
                            <div id='replies-{{ $work_story_post->id }}'>
                                <!-- コメントの区切り線（ただし最後のコメントには表示しない） -->
                                @if (!$loop->first)
                                    <hr class="border-t my-4" id="border-{{ $comment->id }}">
                                @endif
                                @include('comments.input_comment', [
                                    'comment' => $comment,
                                    'status' => 'show',
                                    'inputName' => 'work_story_post_comment',
                                    'baseRoute' => 'work_story_post',
                                    'inputPostIdName' => 'work_story_post_id',
                                    'postCommentId' => $comment->work_story_post_id,
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
    <script src="{{ asset('/js/like_posts/like_work_story_post.js') }}"></script>
    <script src="{{ asset('/js/delete_post.js') }}"></script>
    <script src="{{ asset('/js/comments/like_comment.js') }}"></script>
    <script src="{{ asset('/js/comments/delete_comment.js') }}"></script>
    <script src="{{ asset('/js/comments/load_reply.js') }}"></script>
    <script src="{{ asset('/js/comments/add_comment.js') }}"></script>
    <script src="{{ asset('/js/comments/create_comment_preview.js') }}"></script>
    <script src="{{ asset('/js/comments/store_comment.js') }}"></script>
    <script>
        // PHP の Helper 関数で定義した色データを JavaScript に渡す
        const categoryColors = {!! json_encode([
            'コメントと関連するすべての返信を削除しました' => getCategoryColor('コメントと関連するすべての返信を削除しました'),
            'コメントの削除に失敗しました' => getCategoryColor('コメントの削除に失敗しました'),
            'コメントを投稿しました。' => getCategoryColor('コメントを投稿しました'),
            'いいねしました' => getCategoryColor('いいねしました'),
            'いいねを解除しました' => getCategoryColor('いいねを解除しました'),
        ]) !!};
    </script>
</x-app-layout>
