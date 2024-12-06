// DOMツリー読み取り完了後にイベント発火
document.addEventListener('DOMContentLoaded', function () {
    // delete-buttonに一致するすべてのHTML要素を取得
    document.querySelectorAll('.delete-button').forEach(function (button) {
        button.addEventListener('click', function () {
            const postId = button.getAttribute('data-post-id');
            deletePost(postId);
        });
    });
});

// 削除処理を行う
function deletePost(postId) {
    'use strict'

    if (confirm('削除すると復元できません。\n本当に削除しますか？')) {
        document.getElementById(`form_${postId}`).submit();
    }
}