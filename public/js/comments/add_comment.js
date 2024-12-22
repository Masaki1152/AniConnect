document.addEventListener('DOMContentLoaded', function () {

    // 最後のコメントからborederを削除する
    const commentBlock = document.getElementById('comment_block');
    const lastComment = commentBlock.lastElementChild;
    if (lastComment) {
        lastComment.classList.remove('border-b', 'border-gray-200', 'pb-4', 'mb-4');
    }

    const toggleCommentsButton = document.getElementById('toggleComments');
    const closeCommentsButton = document.getElementById('closeComments');
    const addCommentBlock = document.getElementById('addCommentBlock');

    // コメントブロックの表示
    toggleCommentsButton.addEventListener('click', () => {
        addCommentBlock.style.display = 'block';
        toggleCommentsButton.style.display = 'none';
        closeCommentsButton.style.display = 'inline';
        // 最後のコメントから `with-border` クラスを削除
        const lastReply = repliesContainer.lastElementChild;
        if (lastReply) {
            lastReply.classList.remove('with-border');
            lastReply.classList.add('no-border');
        }
    });

    // コメントブロックの非表示
    closeCommentsButton.addEventListener('click', () => {
        addCommentBlock.style.display = 'none';
        toggleCommentsButton.style.display = 'inline';
        closeCommentsButton.style.display = 'none';
    });
});