<p class="text-lg font-semibold mb-2">コメントの作成</p>
<div>
    <input type="hidden" id="post_comment_id-{{ $comment->id }}" value="{{ $postCommentId }}">
    <input type="hidden" id="parent_id-{{ $comment->id }}" value="{{ $parentId }}">
    <textarea id="comment_body-{{ $comment->id }}" required
        class="w-full p-2 border rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
        data-max-length="200" data-counter-id="commentCharacterCount-{{ $comment->id }}" oninput="countCharacter(this)"
        placeholder="コメントを入力してください"></textarea>
    <p id="commentCharacterCount-{{ $comment->id }}" class="text-sm text-gray-600">あと200文字入力できます。</p>
    <p id="body_error-{{ $comment->id }}" class="title__error text-sm text-red-500 mt-1">
        {{ $errors->first("{$inputName}.body") }}</p>
    <div class="image mt-2 mb-4">
        <h2 class="text-sm font-medium mb-1">画像（4枚まで）</h2>
        <label>
            <input id="inputElm-{{ $comment->id }}" type="file" name="images[]" class="hidden" multiple
                onchange="loadImage(this, {{ $comment->id }});">
            <span class="text-blue-500 cursor-pointer">画像を追加する</span>
        </label>
        <div id="count-{{ $comment->id }}" class="text-sm text-gray-600">
            現在、0枚の画像を選択しています。
        </div>
        <!-- 画像トリミング用のモーダルウィンドウ表示 -->
        <div id="crop-modal-{{ $comment->id }}"
            class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white p-4 rounded-lg shadow-lg relative w-full max-w-2xl">
                <div class="overflow-hidden w-full h-auto">
                    <img id="crop-preview-{{ $comment->id }}" class="w-full h-auto object-cover" />
                </div>
                <div class="flex justify-end gap-2 mt-4">
                    <button id="crop-cancel-button-{{ $comment->id }}" type="button"
                        class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                        キャンセル
                    </button>
                    <button id="crop-next-button-{{ $comment->id }}" type="button"
                        class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                        次へ
                    </button>
                </div>
            </div>
        </div>
        <!-- プレビュー画像の表示 -->
        <div id="preview-{{ $comment->id }}" class="grid grid-cols-2 md:grid-cols-4 gap-2"></div>
        <p id="image_error" class="text-red-500 text-sm hidden">画像が正しくありません。</p>
    </div>
    <div class="flex justify-center mt-4">
        <button id="submit_comment-{{ $comment->id }}" data-comment-id='{{ $comment->id }}' type='button'
            class="px-2 py-1 bg-green-500 text-white rounded-lg shadow-md hover:bg-green-600"
            onclick="storeComment({{ $comment->id }}, '{{ $inputName }}', '{{ $baseRoute }}s', '{{ $inputPostIdName }}')">
            コメントする
        </button>
    </div>
    <script src="{{ asset('/js/count_character.js') }}"></script>
</div>
