<div id='addCommentBlock' class="w-full p-4 border rounded-lg bg-gray-50" style="display: none;">
    <p class="text-lg font-semibold mb-2">コメントの作成</p>
    <div>
        <input type="hidden" id="post_comment_id-{{ $comment->id }}" value="{{ $comment->id }}">
        <input type="hidden" id="parent_id-{{ $comment->id }}" value="">
        <textarea id="comment_body-{{ $comment->id }}" required class="w-full p-2 mb-2 border rounded-lg"
            placeholder="コメントを入力してください"></textarea>
        <p id="body_error-{{ $comment->id }}" class="text-red-500 text-sm hidden">
            コメントを入力してください。</p>
        <div class="image mb-4">
            <h2 class="text-sm font-medium mb-1">画像（4枚まで）</h2>
            <label>
                <input id="inputElm-{{ $comment->id }}" type="file" style="display:none" multiple
                    onchange="loadImage(this, {{ $comment->id }});">
                <span class="text-blue-500 cursor-pointer">画像の追加</span>
                <div id="count-{{ $comment->id }}" class="text-sm text-gray-600">
                    現在、0枚の画像を選択しています。
                </div>
            </label>
            <p id="image_error" class="text-red-500 text-sm hidden">画像が正しくありません。</p>
        </div>
        <!-- プレビュー画像の表示 -->
        <div id="preview-{{ $comment->id }}" class="grid grid-cols-2 md:grid-cols-4 gap-2">
        </div>
        <div class="flex justify-center mt-4">
            <button id="submit_comment" data-comment-id='{{ $comment->id }}' type='button'
                class="px-2 py-1 bg-green-500 text-white rounded-lg shadow-md hover:bg-green-600"
                onclick="storeComment({{ $comment->id }})">
                コメントする
            </button>
        </div>
    </div>
</div>
