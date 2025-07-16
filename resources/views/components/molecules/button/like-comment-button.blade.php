<div class='comment-like flex items-center gap-2'>
    <!-- ボタンの見た目は後のデザイン作成の際に設定する予定 -->
    <button id="comment-like_button-{{ $comment->id }}" data-comment-id="{{ $comment->id }}"
        data-login-required-action="{{ \App\Enums\LoginPromptActionType::Like->label() }}"
        onclick="toggleLike(this, {{ $comment->id }}, 'comment-like_button-{{ $comment->id }}', 'comment-like_count-{{ $comment->id }}', '{{ $baseRoute }}s')"
        class="comment-like_button px-2 py-1 bg-blue-500 text-white rounded-lg shadow-md hover:bg-blue-600">
        {{ $comment->users->contains(auth()->user()) ? __('common.unlike_action') : __('common.like_action') }}
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
