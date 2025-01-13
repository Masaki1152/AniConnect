<div id="comment-{{ $comment->id }}" data-parent-id="{{ $comment->parent_id ?? '' }}">
    @if ($status == 'child_comment_stored')
        <hr class="border-t my-4" id="border-{{ $comment->id }}">
    @endif
    <div class='flex items-center justify-between'>
        <div class="flex items-center space-x-4">
            <img src="{{ $comment->user->image ?? 'https://res.cloudinary.com/dnumegejl/image/upload/v1732628038/No_User_Image_wulbjv.png' }}"
                alt="画像が読み込めません。" class="w-16 h-16 rounded-full object-cover">
            <div>
                <!-- 自分のアカウントを選択した場合 -->
                @if (Auth::id() === $comment->user->id)
                    <a href="{{ route('profile.index') }}" class="font-medium">
                        {{ $comment->user->name }}
                    </a>
                @else
                    <a href="{{ route('users.show', ['user_id' => $comment->user->id]) }}" class="font-medium">
                        {{ $comment->user->name }}
                    </a>
                @endif
                <p class="text-gray-500 text-sm">
                    {{ $comment->created_at->format('Y/m/d H:i') }}</p>
            </div>
        </div>
        <!-- ドロップダウンメニュー -->
        <x-dropdown align="right" class='ml-auto'>
            <x-slot name="trigger">
                <button class="p-1 bg-slate-400 text-white rounded hover:bg-slate-500">
                    コメントを管理する
                </button>
            </x-slot>
            <x-slot name="content">
                <form action="{{ route($baseRoute . '.comments.delete', ['comment_id' => $comment->id]) }}"
                    id="comment_{{ $comment->id }}" method="post">
                    @csrf
                    @method('DELETE')
                    <button type="button" data-comment-id="{{ $comment->id }}"
                        class="delete-comment-button block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                        onclick="deleteComment({{ $comment->id }}, '{{ $baseRoute }}s')">
                        コメントを削除する
                    </button>
                </form>
            </x-slot>
        </x-dropdown>
    </div>
    <div class="flex flex-col md:flex-row gap-4">
        <div class="left_block flex-1">
            <p class="mt-4 text-gray-800">{!! nl2br(e($comment->body)) !!}</p>
        </div>
        <div class="right_block flex-1">
            @php
                $images = [];
                foreach ([1, 2, 3, 4] as $number) {
                    $image = 'image' . $number;
                    if ($comment->$image) {
                        $images[] = $comment->$image;
                    }
                }
            @endphp
            <div class="grid gap-4 {{ count($images) === 1 ? 'justify-items-center' : '' }}"
                style="grid-template-columns: repeat({{ count($images) > 1 ? 2 : 1 }}, 1fr);">
                @foreach ($images as $index => $image)
                    <a href="{{ $image }}" data-lightbox="{{ $comment->id }}"
                        data-title="{{ '画像' . ($index + 1) }}">
                        <img src="{{ $image }}" alt="画像が読み込めません。"
                            class="w-full object-cover rounded-md border border-gray-300 aspect-w-4 aspect-h-3">
                    </a>
                @endforeach
            </div>
        </div>
    </div>
    <div class='childComment flex items-center justify-between gap-4 mt-4'>
        <!-- 子コメントがあれば表示 -->
        <div class="flex items-center gap-4">
            @if ($comment->replies->where('parent_id', $comment->id)->count() > 0)
                <button onclick="loadReplies({{ $comment->id }}, '{{ $baseRoute }}s')"
                    id="replies-button-{{ $comment->id }}" class='text-sm text-blue-500 hover:text-blue-600'>
                    続きの返信を見る
                </button>
                <button onclick="loadReplies({{ $comment->id }}, '{{ $baseRoute }}s')"
                    id="close-button-{{ $comment->id }}" class='text-sm text-gray-400 hover:text-gray-500 hidden'>
                    続きの返信を閉じる
                </button>
            @endif
        </div>
        <div class='flex items-center gap-4'>
            <!-- コメントを追加したい場合 -->
            <button id='toggleChildComments-{{ $comment->id }}' type='button'
                class="px-2 py-1 bg-blue-500 text-white rounded-lg shadow-md hover:bg-blue-600"
                onclick="toggleChildCommentForm({{ $comment->id }})">コメントする</button>
            <button id='closeChildComments-{{ $comment->id }}' type='button'
                class="px-2 py-1 bg-gray-300 text-gray-700 rounded-lg shadow-md hover:bg-gray-400 hidden"
                onclick="toggleChildCommentForm({{ $comment->id }})">閉じる</button>
            <!-- いいねボタンといいね件数 -->
            <div class='comment-like flex items-center gap-2'>
                <!-- ボタンの見た目は後のデザイン作成の際に設定する予定 -->
                <button id="comment-like_button-{{ $comment->id }}" data-comment-id="{{ $comment->id }}"
                    onclick="toggleLike({{ $comment->id }}, 'comment-like_button-{{ $comment->id }}', 'comment-like_count-{{ $comment->id }}', '{{ $baseRoute }}s')"
                    class="comment-like_button px-2 py-1 bg-blue-500 text-white rounded-lg shadow-md hover:bg-blue-600">
                    {{ $comment->users->contains(auth()->user()) ? 'いいね取り消し' : 'いいね' }}
                </button>
                <div class="comment-like_user">
                    <a href="{{ route($baseRoute . '_comment.like.index', ['comment_id' => $comment->id]) }}"
                        class="text-lg font-medium text-gray-700">
                        <p id="comment-like_count-{{ $comment->id }}">
                            {{ $comment->users->count() }}件
                        </p>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- 子コメント作成フォーム -->
    <div id='addChildCommentBlock-{{ $comment->id }}' class="w-full p-4 mt-4 border rounded-lg bg-gray-50"
        style="display: none;">
        @include('comments.input_create_comment', [
            'comment' => $comment,
            'inputName' => $inputName,
            'inputPostIdName' => $inputPostIdName,
            'baseRoute' => $baseRoute,
            'postCommentId' => $postCommentId,
            'parentId' => $comment->id,
        ])
    </div>

    <!-- 続きの返信を見るボタンが表示された後にフォームを追加 -->
    <div id="replies-{{ $comment->id }}" style="margin-left: 40px;">
    </div>
</div>
