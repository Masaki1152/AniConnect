// DOMツリー読み取り完了後にイベント発火
document.addEventListener('DOMContentLoaded', function () {
    // delete-comment-buttonに一致するすべてのHTML要素を取得
    document.querySelectorAll('.delete-comment-button').forEach(function (button) {
        button.addEventListener('click', function () {
            const commentId = button.getAttribute('data-comment-id');
            deletePost(commentId);
        });
    });
});

// 削除処理を行う
function deletePost(commentId) {
    'use strict'

    if (confirm('削除すると復元できません。\n本当に削除しますか？')) {
        document.getElementById(`comment_${commentId}`).submit();
    }
}