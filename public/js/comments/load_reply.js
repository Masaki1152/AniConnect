function loadReplies(commentId) {
    // ボタンの無効化
    const button = document.getElementById(`replies-button-${commentId}`);
    button.disabled = true;
    button.textContent = "読み込み中...";

    // Ajax リクエスト
    fetch(`/work_reviews/comments/${commentId}/replies`, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': `${csrfToken}`,
            'Accept': 'application/json',
        },
    })
        .then(response => response.json())
        .then(replies => {
            const repliesContainer = document.getElementById(`replies-${commentId}`);
            console.log(replies);

            // 子コメントを表示
            replies.forEach(reply => {
                const replyDiv = document.createElement('div');
                replyDiv.classList.add('border-b', 'border-gray-200', 'pb-4', 'mb-4');
                replyDiv.innerHTML = `
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <img src="${reply.user.image || 'https://res.cloudinary.com/dnumegejl/image/upload/v1732628038/No_User_Image_wulbjv.png'}"
                            alt="画像が読み込めません。" class="w-16 h-16 rounded-full object-cover">
                        <div>
                            <a href="/users/${reply.user.id}" class="font-medium">${reply.user.name}</a>
                            <p class="text-gray-500 text-sm">${reply.created_at}</p>
                        </div>
                    </div>
                    <div class="relative">
                        <button class="p-1 bg-slate-400 text-white rounded hover:bg-slate-500" onclick="toggleDropdown(${reply.id})">
                            コメントを管理する
                        </button>
                        <div id="dropdown-${reply.id}" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-50">
                            <form action="/work_review/comments/${reply.id}/delete" method="post">
                                <input type="hidden" name="_token" value="${csrfToken}">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    コメントを削除する
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col md:flex-row gap-4">
                    <div class="left_block flex-1">
                        <p class="mt-4 text-gray-800">${reply.body.replace(/\n/g, '<br>')}</p>
                    </div>
                    <div class="right_block flex-1">
                        <a href="${reply.image1}" data-lightbox="${reply.id}"
                                        data-title="画像1">
                ${reply.image1 ? `<img src='${reply.image1}' alt='画像が読み込めません。' class='w-36 h-36 object-cover rounded-md border border-gray-300 mb-2'>` : ''}</a>
                <a href="${reply.image2}" data-lightbox="${reply.id}"
                                        data-title="画像2">
                ${reply.image2 ? `<img src='${reply.image2}' alt='画像が読み込めません。' class='w-36 h-36 object-cover rounded-md border border-gray-300 mb-2'>` : ''}</a>
                <a href="${reply.image3}" data-lightbox="${reply.id}"
                                        data-title="画像3">
                ${reply.image3 ? `<img src='${reply.image3}' alt='画像が読み込めません。' class='w-36 h-36 object-cover rounded-md border border-gray-300 mb-2'>` : ''}</a>
                <a href="${reply.image4}" data-lightbox="${reply.id}"
                                        data-title="画像4">
                ${reply.image4 ? `<img src='${reply.image4}' alt='画像が読み込めません。' class='w-36 h-36 object-cover rounded-md border border-gray-300 mb-2'>` : ''}</a>
                    </div>
                </div>
                <div class='flex gap-4 items-center justify-end mt-4'>
                    <div class='content_footer_comment'>
                        <button id='toggleChildComments-${reply.id}' type='button'
                            class="px-2 py-1 bg-blue-500 text-white rounded-lg shadow-md hover:bg-blue-600">コメントする</button>
                        <button id='closeChildComments-${reply.id}' type='button'
                            class="px-2 py-1 bg-gray-300 text-gray-700 rounded-lg shadow-md hover:bg-gray-400 hidden">閉じる</button>
                    </div>
                    <div class='comment-like flex items-center gap-2'>
                        <button id="comment-like_button-${reply.id}" data-comment-id="${reply.id}"
                            type="button"
                            class="px-2 py-1 bg-blue-500 text-white rounded-lg shadow-md hover:bg-blue-600">
                            ${reply.is_liked_by_user ? 'いいね取り消し' : 'いいね'}
                        </button>
                        <div class="comment-like_user">
                            <a href="/work_review_comment/like/index/${reply.id}" class="text-lg font-medium text-gray-700">
                                <p id="comment-like_count-${reply.id}">${reply.like_count}件</p>
                            </a>
                        </div>
                    </div>
                </div>
                <div id='addChildCommentBlock-${reply.id}' class="w-full p-4 mt-4 border rounded-lg bg-gray-50 hidden">
                    <p class="text-lg font-semibold mb-2">コメントの作成</p>
                    <form action="/work_review/comments/store" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="${csrfToken}">
                        <input type="hidden" name="work_review_comment[work_review_id]" value="${reply.work_review_id}">
                        <input type="hidden" name="work_review_comment[parent_id]" value="${reply.id}">
                        <textarea name="work_review_comment[body]" required class="w-full p-2 mb-2 border rounded-lg"
                            placeholder="コメントを入力してください"></textarea>
                        <div class="image mb-4">
                            <h2 class="text-sm font-medium mb-1">画像（4枚まで）</h2>
                            <label>
                                <input id="inputElm-${reply.id}" type="file" style="display:none"
                                    name="images[]" multiple onchange="loadImage(this, ${reply.id});">
                                <span class="text-blue-500 cursor-pointer">画像の追加</span>
                                <div id="count-${reply.id}" class="text-sm text-gray-600">現在、0枚の画像を選択しています。</div>
                            </label>
                        </div>
                        <div id="preview-${reply.id}" class="grid grid-cols-2 lg:grid-cols-4 gap-2"></div>
                        <div class="flex justify-center mt-4">
                            <button type="submit"
                                class="px-2 py-1 bg-green-500 text-white rounded-lg shadow-md hover:bg-green-600">コメントする</button>
                        </div>
                    </form>
                </div>
                <div class="childComment">
                    ${reply.replies ? `
                        <button onclick="loadReplies(${reply.id})" id="replies-button-${reply.id}">
                            続きの返信を見る
                        </button>
                        <div id="replies-${reply.id}" style="margin-left: 20px;"></div>
                    ` : ''}
                </div>
            `;
                repliesContainer.appendChild(replyDiv);
            });

            // ボタンを削除
            button.remove();
        })
        .catch(error => {
            console.error('エラーが発生しました:', error);
            button.textContent = "続きの返信を見る"; // ボタンを元に戻す
            button.disabled = false;
        });
}

// ドロップダウンの表示・非表示を切り替える関数
function toggleDropdown(replyId) {
    const dropdown = document.getElementById(`dropdown-${replyId}`);
    if (dropdown) {
        const isHidden = dropdown.classList.contains('hidden');
        dropdown.classList.toggle('hidden', !isHidden);
        dropdown.classList.toggle('block', isHidden);
    }
}