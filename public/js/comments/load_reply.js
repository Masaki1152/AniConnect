function loadReplies(commentId, baseRoute) {
    const openRepliesButton = document.getElementById(`replies-button-${commentId}`);
    const closeRepliesButton = document.getElementById(`close-button-${commentId}`);
    const repliesContainer = document.getElementById(`replies-${commentId}`);
    // 子コメントのコンテナの表示状態で切り替え
    if (repliesContainer.style.display === 'none' || repliesContainer.style.display === '') {
        openRepliesButton.textContent = "読み込み中...";
        repliesContainer.style.display = 'block';
        // Ajax リクエスト
        fetch(`/${baseRoute}/comments/${commentId}/replies`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': `${csrfToken}`,
                'Accept': 'application/json',
            },
        })
            .then(response => response.json())
            .then(data => {

                data.replies.forEach(reply => {

                    if (reply.html) {
                        // 区切り線を追加
                        const hr = document.createElement('hr');
                        hr.className = "border-t my-4 ";
                        hr.id = `border-${reply.id}`;
                        repliesContainer.appendChild(hr);

                        repliesContainer.insertAdjacentHTML('beforeend', reply.html);
                    }
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
                openRepliesButton.textContent = "続きの返信を見る";
                openRepliesButton.disabled = false;
            });

    } else {
        repliesContainer.style.display = 'none';
        // 既に表示している返信を削除
        repliesContainer.innerHTML = '';
        openRepliesButton.style.display = 'inline';
        closeRepliesButton.style.display = 'none';
    }
}