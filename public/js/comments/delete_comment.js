// コメントの削除処理を行う
function deleteComment(commentId) {
    'use strict'

    if (confirm('削除すると復元できません。\n同時にこのコメントへの返信も削除されます。\n本当に削除しますか？')) {
        //document.getElementById(`comment_${commentId}`).submit();
        // コメント削除のリクエストを送信
        fetch(`/work_reviews/comments/${commentId}/delete`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
            },
        })
            .then((response) => {
                if (!response.ok) {
                    throw new Error('削除リクエストが失敗しました');
                }
                // JSON パースを待つ
                return response.json();
            })
            .then((data) => {
                // 削除成功時の処理
                // DOM から削除
                document.getElementById(`comment-${commentId}`).remove();

                // メッセージを表示
                const deleteMessage = document.getElementById('message');
                deleteMessage.textContent = data.message;
                deleteMessage.classList.remove('hidden');
                deleteMessage.classList.add('block');
                deleteMessage.style.backgroundColor = categoryColors[data.message] || '#d1d5db';

                // コメント数の表示変更
                const commentCountSpan = document.getElementById('comment_count');
                const commentCount = Number(commentCountSpan.innerHTML) - data.commentCount;
                commentCountSpan.innerHTML = commentCount;
                // コメント数が0の場合はcomment_blockを非表示にする
                if (commentCount == 0) {
                    const commentSection = document.getElementById('comment_block');
                    commentSection.remove();
                }

                // 3秒後にメッセージを非表示
                setTimeout(() => {
                    deleteMessage.classList.add('hidden');
                    deleteMessage.classList.remove('block');
                }, 3000);
            })
            .catch((error) => {
                console.error('エラー:', error);
            });
    }
}