// コメントの保存に関する処理
async function storeComment(dataCommentId, inputName, baseRoute, inputPostIdName) {
    const postId = document.getElementById(`post_comment_id-${dataCommentId}`).value;
    const parentId = document.getElementById(`parent_id-${dataCommentId}`).value;
    const commentBody = document.getElementById(`comment_body-${dataCommentId}`).value;
    const images = document.getElementById(`inputElm-${dataCommentId}`).files;
    const bodyError = document.getElementById(`body_error-${dataCommentId}`);

    // フォームデータ作成
    const formData = new FormData();
    formData.append(`${inputName}[${inputPostIdName}]`, postId);
    formData.append(`${inputName}[parent_id]`, parentId);
    formData.append(`${inputName}[body]`, commentBody);

    // 画像を追加
    Array.from(images).forEach((image, index) => {
        formData.append(`images[]`, image);
    });
    // フィールドのバリデーションチェック
    if (!commentBody.trim()) {
        bodyError.classList.remove('hidden');
        return;
    } else {
        bodyError.classList.add('hidden');
    }

    // 非同期リクエスト
    try {
        const response = await fetch(`/${baseRoute}/comments/store`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
            },
            body: formData,
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();

        // 新しいコメントのidを取得
        const newCommentId = data.new_comment_id;

        // parentIdがnull、つまり親コメントの場合
        if (!parentId) {
            const parentCommentSection = document.getElementById('comments-section');

            let parentCommentBlock = document.getElementById('comment_block');
            // 他のコメントが存在する場合
            if (parentCommentBlock) {
                // 枠線のHTMLを定義
                const borderLineHtml = `<hr class="border-t my-4" id="border-${newCommentId}">`;
                parentCommentBlock.insertAdjacentHTML('beforeend', borderLineHtml + data.commentHtml);
                const newParentComment = parentCommentBlock.lastElementChild;
                newParentComment.scrollIntoView({ behavior: 'smooth', block: 'center' });
            } else {
                // 他のコメントがない場合、comment_blockを新規作成
                parentCommentBlock = document.createElement('div');
                parentCommentBlock.id = 'comment_block';
                parentCommentBlock.classList.add('bg-white', 'rounded-lg', 'shadow-md', 'p-6');

                // 子コメントのコンテナを先に生成
                const repliesContainer = document.createElement('div');
                repliesContainer.id = `replies-${newCommentId}`;
                repliesContainer.style.marginLeft = '40px';

                parentCommentBlock.appendChild(repliesContainer);
                parentCommentBlock.insertAdjacentHTML('afterbegin', data.commentHtml);
                parentCommentSection.appendChild(parentCommentBlock);

                repliesContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }

            // フォームのリセット
            document.getElementById(`comment_body-${dataCommentId}`).value = '';
            document.getElementById(`inputElm-${dataCommentId}`).value = '';
            document.getElementById(`preview-${dataCommentId}`).innerHTML = '';

            // メッセージの表示
            const storeMessage = document.getElementById('message');
            storeMessage.textContent = data.message;
            storeMessage.classList.remove('hidden');
            storeMessage.classList.add('block');
            storeMessage.style.backgroundColor = categoryColors[data.message] || '#d1d5db';
            // 3秒後にメッセージを非表示
            setTimeout(() => {
                storeMessage.classList.add('hidden');
                storeMessage.classList.remove('block');
            }, 3000);

            // コメント数の表示変更
            const commentCount = document.getElementById('comment_count');
            commentCount.innerHTML = Number(commentCount.innerHTML) + 1;
            // フォームを閉じる
            toggleCommentForm();
            return;
        }

        const commentBlock = document.querySelector(`#replies-${parentId}`);
        const repliesButton = document.querySelector(`#replies-button-${parentId}`);

        // 子コメントである場合
        if (commentBlock) {
            // 子コメントがすでに存在し、「続きの返信を見る」が未クリックの場合
            if (!commentBlock.innerHTML.trim() && repliesButton) {
                // 「続きの返信を見る」をクリックして開く
                // 「続きの返信を見る」をクリックした時点で新しいコメントが追加される
                repliesButton.click();

                // MutationObserverでDOM の変化を監視
                const targetNode = document.querySelector(`#replies-${parentId}`);
                if (targetNode) {
                    const observer = new MutationObserver((mutationsList, observer) => {
                        const reply_block = targetNode.lastElementChild;
                        if (reply_block) {
                            // 新しいコメントが追加されたらスクロール
                            reply_block.lastElementChild.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            // 監視を停止
                            observer.disconnect();
                        }
                    });

                    observer.observe(targetNode, { childList: true, subtree: true });
                } else {
                    console.error(`#replies-${parentId} が見つかりませんでした。`);
                }
            } else {
                // 子コメントが存在し、表示済みの場合
                commentBlock.insertAdjacentHTML('beforeend', data.commentHtml);
                const newComment = commentBlock.lastElementChild;
                newComment.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        } else {
            // 子コメントセクションがない場合、まず開く
            const childCommentContainer = document.createElement('div');
            childCommentContainer.id = `replies-${newCommentId}`;
            childCommentContainer.style.marginLeft = '40px';

            const parentCommentBlock = document.querySelector(`#replies-${parentId}`);
            if (!parentCommentBlock) {
                const newParentBlock = document.createElement('div');
                newParentBlock.id = `replies-${parentId}`;
                newParentBlock.style.marginLeft = '40px';
                newParentBlock.insertAdjacentHTML('beforeend', data.commentHtml);
                document.getElementById('comments-section').appendChild(newParentBlock);
                newParentBlock.scrollIntoView({ behavior: 'smooth', block: 'center' });
            } else {
                parentCommentBlock.appendChild(childCommentContainer);
                childCommentContainer.insertAdjacentHTML('beforeend', data.commentHtml);
                childCommentContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }

        // メッセージの表示
        const storeMessage = document.getElementById('message');
        storeMessage.textContent = data.message;
        storeMessage.classList.remove('hidden');
        storeMessage.classList.add('block');
        storeMessage.style.backgroundColor = categoryColors[data.message] || '#d1d5db';
        // コメント数の表示変更
        const commentCount = document.getElementById('comment_count');
        commentCount.innerHTML = Number(commentCount.innerHTML) + 1;

        // 3秒後にメッセージを非表示
        setTimeout(() => {
            storeMessage.classList.add('hidden');
            storeMessage.classList.remove('block');
        }, 3000);

        // フォームのリセット
        document.getElementById(`comment_body-${dataCommentId}`).value = '';
        document.getElementById(`inputElm-${dataCommentId}`).value = '';
        document.getElementById(`preview-${dataCommentId}`).innerHTML = '';

        // フォームを閉じる
        if (parentId) {
            toggleChildCommentForm(parentId);
        } else {
            toggleCommentForm();
        }

    } catch (error) {
        console.error('Error:', error);
    }
}