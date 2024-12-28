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
        console.log(data);

        // 新しいコメントのidを取得
        const newCommentId = data.new_comment_id;

        const commentBlock = document.querySelector(`#replies-${parentId} .reply_block`);
        const repliesButton = document.querySelector(`#replies-button-${parentId}`);

        console.log(document.querySelector(`#replies-${parentId}`));
        console.log(document.querySelector(`#replies-button-${parentId}`));
        // 子コメントである場合
        if (commentBlock) {
            console.log("タイプはAかB");
            // 子コメントがすでに存在し、「続きの返信を見る」が未クリックの場合
            if (!commentBlock.innerHTML.trim() && repliesButton) {
                console.log("タイプA");
                // 「続きの返信を見る」をクリックして開く
                repliesButton.click();
                setTimeout(() => {
                    commentBlock.insertAdjacentHTML('beforeend', data.commentHtml);
                    // 新しいコメントの要素を取得
                    const newComment = commentBlock.lastElementChild;
                    // 新しいコメントまでスクロール
                    // ボタン処理の実行後を待つ
                    newComment.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }, 500);
            } else {
                console.log("タイプB");
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
            console.log("タイプC");
            const parentCommentBlock = document.querySelector(`#replies-${parentId}`);
            parentCommentBlock.appendChild(childCommentContainer);

            childCommentContainer.insertAdjacentHTML('beforeend', data.commentHtml);
            childCommentContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }

        // 新しいコメントを挿入
        // const commentBlock = parentId
        //     ? document.querySelector(`#replies-${parentId} .reply_block`)
        //     : document.querySelector('#comments-section #comment_block');
        // if (commentBlock) {
        //     commentBlock.insertAdjacentHTML('beforeend', data.commentHtml);
        //     // 新しいコメントの要素を取得
        //     const newComment = commentBlock.lastElementChild;
        //     // 新しいコメントまでスクロール
        //     newComment.scrollIntoView({ behavior: 'smooth', block: 'center' });
        // }

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