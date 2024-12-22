// ドロップダウン以外をクリックするとドロップダウンを閉じる
document.body.addEventListener('click', function (event) {
    const dropdowns = document.querySelectorAll('.dropdown');

    dropdowns.forEach(dropdown => {
        const button = dropdown.previousElementSibling;
        if (!dropdown.contains(event.target) && !button.contains(event.target)) {
            // クリックした場所がドロップダウンやボタンでなければ閉じる
            dropdown.classList.add('hidden');
        }
    });
});

function loadReplies(commentId) {
    const openRepliesButton = document.getElementById(`replies-button-${commentId}`);
    const closeRepliesButton = document.getElementById(`close-button-${commentId}`);
    const repliesContainer = document.getElementById(`replies-${commentId}`);
    // 子コメントのコンテナの表示状態で切り替え
    if (repliesContainer.style.display === 'none' || repliesContainer.style.display === '') {
        openRepliesButton.textContent = "読み込み中...";
        repliesContainer.style.display = 'block';
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

                // 子コメントを表示
                replies.forEach(reply => {
                    const replyDiv = document.createElement('div');
                    replyDiv.classList.add('border-t', 'border-gray-200', 'pt-4', 'mt-4');
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
                        <div id="dropdown-${reply.id}" class="dropdown hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-50">
                            <form action="/work_reviews/comments/${reply.id}/delete" method="post" id="comment_${reply.id}">
                                <input type="hidden" name="_token" value="${csrfToken}">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="button" onclick="deleteComment(${reply.id})" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    コメントを削除する
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col md:flex-row gap-4 mt-4">
                    <div class="left_block flex-1">
                        <p class="text-gray-800">${reply.body.replace(/\n/g, '<br>')}</p>
                    </div>
                    <div class="right_block flex-1">
                    <div class="grid gap-4 ${reply.image2 ? 'grid-cols-2' : 'grid-cols-1'} ${!reply.image2 ? 'place-items-center' : ''}"
                 style="grid-template-columns: repeat(${reply.image2 ? 2 : 1}, 1fr);">
                ${reply.image1 ? `
                    <a href="${reply.image1}" data-lightbox="${reply.id}" data-title="画像1">
                        <img src="${reply.image1}" alt="画像が読み込めません。"
                            class="w-full object-cover rounded-md border border-gray-300"
                            style="aspect-ratio: 1 / 1;">
                    </a>` : ''}
                ${reply.image2 ? `
                    <a href="${reply.image2}" data-lightbox="${reply.id}" data-title="画像2">
                        <img src="${reply.image2}" alt="画像が読み込めません。"
                            class="w-full object-cover rounded-md border border-gray-300"
                            style="aspect-ratio: 1 / 1;">
                    </a>` : ''}
                ${reply.image3 ? `
                    <a href="${reply.image3}" data-lightbox="${reply.id}" data-title="画像3">
                        <img src="${reply.image3}" alt="画像が読み込めません。"
                            class="w-full object-cover rounded-md border border-gray-300"
                            style="aspect-ratio: 1 / 1;">
                    </a>` : ''}
                ${reply.image4 ? `
                    <a href="${reply.image4}" data-lightbox="${reply.id}" data-title="画像4">
                        <img src="${reply.image4}" alt="画像が読み込めません。"
                            class="w-full object-cover rounded-md border border-gray-300"
                            style="aspect-ratio: 1 / 1;">
                    </a>` : ''}
            </div>
                </div>
                </div>
                <div class='flex gap-4 items-center justify-end mt-4'>
                    <div class='content_footer_comment'>
                        <button id='toggleChildComments-${reply.id}'
                            class="px-2 py-1 bg-blue-500 text-white rounded-lg shadow-md hover:bg-blue-600" onclick="toggleChildCommentForm(${reply.id})">コメントする</button>
                        <button id='closeChildComments-${reply.id}'
                            class="px-2 py-1 bg-gray-300 text-gray-700 rounded-lg shadow-md hover:bg-gray-400 hidden" onclick="toggleChildCommentForm(${reply.id})">閉じる</button>
                    </div>
                    <div class='comment-like flex items-center gap-2'>
                        <button id="comment-like_button-${reply.id}"
                        data-comment-id="${reply.id}"
                        onclick="toggleLike(${reply.id}, 'comment-like_button-${reply.id}', 'comment-like_count-${reply.id}')"
                            class="comment-like_button px-2 py-1 bg-blue-500 text-white rounded-lg shadow-md hover:bg-blue-600">
                            ${reply.is_liked_by_user ? 'いいね取り消し' : 'いいね'}
                        </button>
                        <div class="comment-like_user">
                            <a href="/work_reviews/comments/${reply.id}/like/index" class="text-lg font-medium text-gray-700">
                                <p id="comment-like_count-${reply.id}">${reply.like_user_count}件</p>
                            </a>
                        </div>
                    </div>
                </div>
                <div id='addChildCommentBlock-${reply.id}' class="w-full p-4 mt-4 border rounded-lg bg-gray-50" style="display: none;">
                    <p class="text-lg font-semibold mb-2">コメントの作成</p>
                    <form action="/work_reviews/comments/store" method="POST" enctype="multipart/form-data">
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
                        <div id="preview-${reply.id}" class="grid grid-cols-2 md:grid-cols-4 gap-2"></div>
                        <div class="flex justify-center mt-4">
                            <button type="submit"
                                class="px-2 py-1 bg-green-500 text-white rounded-lg shadow-md hover:bg-green-600">コメントする</button>
                        </div>
                    </form>
                </div>
                <div class="childComment">
                    ${reply.replies && reply.replies.length > 0 ? `
                        <button onclick="loadReplies(${reply.id})" id="replies-button-${reply.id}" class='text-sm text-blue-500 hover:text-blue-600'>
                            続きの返信を見る
                        </button>
                        <button onclick="loadReplies(${reply.id})" id="close-button-${reply.id}" class='text-sm text-gray-400 hover:text-gray-500 hidden'>
                            続きの返信を閉じる
                        </button>
                        <div id="replies-${reply.id}" style="margin-left: 40px;"></div>
                    ` : ''}
                </div>
            `;
                    repliesContainer.appendChild(replyDiv);
                });

                // ボタンを切り替え
                // 再表示に備えて文言の変更
                openRepliesButton.textContent = "続きの返信を見る";
                openRepliesButton.style.display = 'none';
                closeRepliesButton.style.display = 'inline';
            })
            .catch(error => {
                console.error('エラーが発生しました:', error);
                // ボタンを元に戻す
                button.textContent = "続きの返信を見る";
                button.disabled = false;
            });

    } else {
        repliesContainer.style.display = 'none';
        // 既に表示している返信を削除
        repliesContainer.innerHTML = '';
        openRepliesButton.style.display = 'inline';
        closeRepliesButton.style.display = 'none';
    }
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

// 追加コメントフォームの表示/非表示を切り替える関数
function toggleChildCommentForm(replyId) {
    const addChildCommentBlock = document.getElementById(`addChildCommentBlock-${replyId}`);
    const toggleChildCommentButton = document.getElementById(`toggleChildComments-${replyId}`);
    const closeChildCommentButtton = document.getElementById(`closeChildComments-${replyId}`);
    if (addChildCommentBlock.style.display === 'none' || addChildCommentBlock.style.display === '') {
        addChildCommentBlock.style.display = 'block';
        toggleChildCommentButton.style.display = 'none';
        closeChildCommentButtton.style.display = 'inline';
    } else {
        addChildCommentBlock.style.display = 'none';
        toggleChildCommentButton.style.display = 'inline';
        closeChildCommentButtton.style.display = 'none';
    }
}

// 各コメントごとの選択画像を保持するマップ
let selectedImagesMap = new Map();

// コメント画像のプレビューを行う
function loadImage(obj, commentId) {

    // 新しく選択されたファイル
    const newImages = Array.from(obj.files);
    // コメントIDに対応する選択済み画像リストを取得または初期化
    const selectedImages = selectedImagesMap.get(commentId) || [];

    // 合計が4枚を超える場合のチェック
    // 元々選択されていたファイルと新しいファイルの合計を確認
    if (selectedImages.length + newImages.length > 4) {
        alert('画像は4枚までアップロード可能です');
        // プレビューを更新し、以前選択していたファイルを再表示する
        // 新しく選択していた方のファイルは破棄
        renderPreviews(commentId);
        return;
    }

    // 新しいファイルを選択済みリストに追加
    selectedImages.push(...newImages);
    // 更新後のリストをマップに保存
    selectedImagesMap.set(commentId, selectedImages);

    // プレビューの更新
    renderPreviews(commentId);
}

function renderPreviews(commentId) {
    // コメントIDに対応する選択済み画像リストを取得
    const selectedImages = selectedImagesMap.get(commentId) || [];

    // プレビューを取得後、クリア
    const preview = document.getElementById(`preview-${commentId}`);
    preview.innerHTML = '';
    // 選択している画像の枚数を表示する
    countImages(commentId);

    selectedImages.forEach((image, index) => {
        const fileReader = new FileReader();

        fileReader.onload = function (e) {
            const figure = document.createElement('figure');
            figure.setAttribute('id', `img-${commentId}-${index}`);
            figure.className = 'relative flex flex-col items-center mb-4';

            const img = document.createElement('img');
            img.src = e.target.result;
            img.alt = 'preview';
            img.className = 'w-full h-full object-cover rounded-lg border border-gray-300 aspect-square';

            // 削除ボタン
            const rmBtn = document.createElement('button');
            rmBtn.type = 'button';
            rmBtn.textContent = '削除';
            rmBtn.className = 'bg-red-500 text-white text-xs mt-2 px-2 py-1 rounded hover:bg-red-600';
            rmBtn.onclick = function () {
                removeImage(commentId, index);
            };

            figure.appendChild(img);
            figure.appendChild(rmBtn);
            preview.appendChild(figure);
        };

        fileReader.readAsDataURL(image);
    });

    // 選択しているファイルを反映
    updateInputElement(commentId);
}

function removeImage(commentId, index) {
    // コメントIDに対応する選択済み画像リストを取得
    const selectedImages = selectedImagesMap.get(commentId) || [];
    // 選択済みファイルリストから該当インデックスのファイルを削除
    selectedImages.splice(index, 1);

    // 更新後のリストをマップに保存
    selectedImagesMap.set(commentId, selectedImages);

    // プレビューを再描画
    renderPreviews(commentId);
}

function updateInputElement(commentId) {
    const selectedImages = selectedImagesMap.get(commentId) || [];
    const dataTransfer = new DataTransfer();
    selectedImages.forEach(image => dataTransfer.items.add(image));

    // 選択されたファイルを反映
    const inputElm = document.getElementById(`inputElm-${commentId}`);
    //console.log(inputElm.files.length);
    inputElm.files = dataTransfer.files;
}

// 選択している画像の枚数を表示する
function countImages(commentId) {
    const selectedImages = selectedImagesMap.get(commentId) || [];
    const count = document.getElementById(`count-${commentId}`);
    count.innerHTML = '';
    count.innerHTML = `現在、${selectedImages.length}枚の画像を選択しています。`;
}