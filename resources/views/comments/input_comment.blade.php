<div id="comment-{{ $comment->id }}">
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
                <form action="{{ route('work_review.comments.delete', ['comment_id' => $comment->id]) }}"
                    id="comment_{{ $comment->id }}" method="post">
                    @csrf
                    @method('DELETE')
                    <button type="button" data-comment-id="{{ $comment->id }}"
                        class="delete-comment-button block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
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
</div>
