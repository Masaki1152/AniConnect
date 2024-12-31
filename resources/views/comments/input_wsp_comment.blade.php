<div id="comment-{{ $comment->id }}">
    @if ($status == 'child_comment_stored')
        <hr class="border-t my-4">
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
                <form action="{{ route('work_story_post.comments.delete', ['comment_id' => $comment->id]) }}"
                    id="comment_{{ $comment->id }}" method="post">
                    @csrf
                    @method('DELETE')
                    <button type="button" data-comment-id="{{ $comment->id }}"
                        class="delete-comment-button block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                        onclick="deleteComment({{ $comment->id }})">
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
                            class="w-full object-cover rounded-md border border-gray-300" style="aspect-ratio: 1/1;">
                    </a>
                @endforeach
            </div>
        </div>
    </div>
    <div class='childComment flex items-center justify-between gap-4 mt-4'>
        <!-- 子コメントがあれば表示 -->
        <div class="flex items-center gap-4">
            @if ($comment->replies->where('parent_id', $comment->id)->count() > 0)
                <button onclick="loadReplies({{ $comment->id }})" id="replies-button-{{ $comment->id }}"
                    class='text-sm text-blue-500 hover:text-blue-600'>
                    続きの返信を見る
                </button>
                <button onclick="loadReplies({{ $comment->id }})" id="close-button-{{ $comment->id }}"
                    class='text-sm text-gray-400 hover:text-gray-500 hidden'>
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
                    onclick="toggleLike({{ $comment->id }}, 'comment-like_button-{{ $comment->id }}', 'comment-like_count-{{ $comment->id }}')"
                    class="comment-like_button px-2 py-1 bg-blue-500 text-white rounded-lg shadow-md hover:bg-blue-600">
                    {{ $comment->users->contains(auth()->user()) ? 'いいね取り消し' : 'いいね' }}
                </button>
                <div class="comment-like_user">
                    <a href="{{ route('work_story_post_comment.like.index', ['comment_id' => $comment->id]) }}"
                        class="text-lg font-medium text-gray-700">
                        <p id="comment-like_count-{{ $comment->id }}">
                            {{ $comment->users->count() }}件
                        </p>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div id='addChildCommentBlock-{{ $comment->id }}' class="w-full p-4 mt-4 border rounded-lg bg-gray-50"
        style="display: none;">
        <p class="text-lg font-semibold mb-2">コメントの作成</p>
        <div>
            <input type="hidden" id="work_review_id-{{ $comment->id }}" value="{{ $comment->work_story_post_id }}">
            <input type="hidden" id="parent_id-{{ $comment->id }}" value="{{ $comment->id }}">
            <textarea id="comment_body-{{ $comment->id }}" required class="w-full p-2 mb-2 border rounded-lg"
                placeholder="コメントを入力してください"></textarea>
            <p id="body_error-{{ $comment->id }}" class="text-red-500 text-sm hidden">コメントを入力してください。</p>
            <div class="image mb-4">
                <h2 class="text-sm font-medium mb-1">画像（4枚まで）</h2>
                <label>
                    <input id="inputElm-{{ $comment->id }}" type="file" style="display:none" multiple
                        onchange="loadImage(this, {{ $comment->id }});">
                    <span class="text-blue-500 cursor-pointer">画像の追加</span>
                    <div id="count-{{ $comment->id }}" class="text-sm text-gray-600">
                        現在、0枚の画像を選択しています。</div>
                </label>
                <p id="image_error" class="text-red-500 text-sm hidden">画像が正しくありません。
                </p>
            </div>
            <!-- プレビュー画像の表示 -->
            <div id="preview-{{ $comment->id }}" class="grid grid-cols-2 md:grid-cols-4 gap-2">
            </div>
            <div class="flex justify-center mt-4">
                <button id="submit_comment" data-comment-id='{{ $comment->id }}'
                    class="px-2 py-1 bg-green-500 text-white rounded-lg shadow-md hover:bg-green-600"
                    onclick="storeComment({{ $comment->id }})">
                    コメントする
                </button>
            </div>
        </div>
    </div>

    <!-- 続きの返信を見るボタンが表示された後にフォームを追加 -->
    <div id="replies-{{ $comment->id }}" style="margin-left: 40px;"></div>
</div>
