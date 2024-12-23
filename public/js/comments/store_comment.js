// コメントの保存に関する処理
document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    document.querySelectorAll('#submit_comment').forEach(function (button) {
        button.addEventListener('click', async function (event) {
            event.preventDefault();

            const dataCommentId = button.getAttribute('data-comment-id');
            const workReviewId = document.getElementById(`work_review_id-${dataCommentId}`).value;
            const parentId = document.getElementById(`parent_id-${dataCommentId}`).value;
            const commentBody = document.getElementById(`comment_body-${dataCommentId}`).value;
            const images = document.getElementById(`inputElm-${dataCommentId}`).files;
            const bodyError = document.getElementById('body_error');

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
                console.log(parentId);

                // 新しいコメントを挿入
                const commentBlock = parentId
                    ? document.querySelector(`#replies-${parentId} .reply_block`)
                    : document.querySelector('#comments-section #comment_block');

                console.log(document.querySelector(`#replies-${parentId}`));
                if (commentBlock) {
                    commentBlock.insertAdjacentHTML('beforeend', data.commentHtml);
                    // 新しいコメントの要素を取得
                    const newComment = commentBlock.lastElementChild;
                    // 新しいコメントまでスクロール
                    newComment.scrollIntoView({ behavior: 'smooth', block: 'center' });
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
                toggleCommentForm();

            } catch (error) {
                console.error('Error:', error);
            }
        });
    });
});