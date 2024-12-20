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
                replyDiv.innerHTML = `
                <p><strong>${reply.user.name}</strong>: ${reply.body}</p>
                <a href="${reply.image1}" data-lightbox="${reply.body}"
                                        data-title="画像1">
                ${reply.image1 ? `<img src='${reply.image1}' alt='画像が読み込めません。' class='w-36 h-36 object-cover rounded-md border border-gray-300 mb-2'>` : ''}</a>
                <a href="${reply.image2}" data-lightbox="${reply.body}"
                                        data-title="画像2">
                ${reply.image2 ? `<img src='${reply.image2}' alt='画像が読み込めません。' class='w-36 h-36 object-cover rounded-md border border-gray-300 mb-2'>` : ''}</a>
                <a href="${reply.image3}" data-lightbox="${reply.body}"
                                        data-title="画像3">
                ${reply.image3 ? `<img src='${reply.image3}' alt='画像が読み込めません。' class='w-36 h-36 object-cover rounded-md border border-gray-300 mb-2'>` : ''}</a>
                <a href="${reply.image4}" data-lightbox="${reply.body}"
                                        data-title="画像4">
                ${reply.image4 ? `<img src='${reply.image4}' alt='画像が読み込めません。' class='w-36 h-36 object-cover rounded-md border border-gray-300 mb-2'>` : ''}</a>
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