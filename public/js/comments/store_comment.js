// コメントの保存に関する処理
async function storeComment(dataCommentId) {
    const workReviewId = document.getElementById(`work_review_id-${dataCommentId}`).value;
    const parentId = document.getElementById(`parent_id-${dataCommentId}`).value;
    const commentBody = document.getElementById(`comment_body-${dataCommentId}`).value;
    const images = document.getElementById(`inputElm-${dataCommentId}`).files;
    const bodyError = document.getElementById(`body_error-${dataCommentId}`);

    // フォームデータ作成
    const formData = new FormData();
    formData.append('work_review_comment[work_review_id]', workReviewId);
    formData.append('work_review_comment[parent_id]', parentId);
    formData.append('work_review_comment[body]', commentBody);

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

    console.log("formDataは、");
    formData.forEach((form) => { console.log(form) });

    // 非同期リクエスト
    try {
        const response = await fetch('/work_reviews/comments/store', {
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
            console.log("タイプC");
            const childCommentContainer = document.createElement('div');
            childCommentContainer.id = `replies-${newCommentId}`;
            childCommentContainer.style.marginLeft = '40px';
            const parentCommentBlock = document.querySelector(`#replies-${parentId}`);
            console.log(`#replies-${parentId}`);
            console.log(parentCommentBlock);
            parentCommentBlock.appendChild(childCommentContainer);

            childCommentContainer.insertAdjacentHTML('beforeend', data.commentHtml);
            childCommentContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }

        // メッセージの表示
        const storeMessage = document.getElementById('store-message');
        storeMessage.textContent = data.message;
        storeMessage.classList.remove('hidden');
        storeMessage.classList.add('block');

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