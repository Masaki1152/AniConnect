document.addEventListener('DOMContentLoaded', function () {
    const toggleCommentsButton = document.getElementById('toggleComments');
    const closeCommentsButton = document.getElementById('closeComments');
    const addCommentBlock = document.getElementById('addCommentBlock');

    // 子コメントに関するコメント追加の表示
    const toggleChildCommentsButton = document.getElementById('toggleChildComments');
    const closeChildCommentsButton = document.getElementById('closeChildComments');
    const addChildCommentBlock = document.getElementById('addChildCommentBlock');

    // コメントブロックの表示
    toggleCommentsButton.addEventListener('click', () => {
        addCommentBlock.style.display = 'block';
        toggleCommentsButton.style.display = 'none';
        closeCommentsButton.style.display = 'inline';
    });

    // コメントブロックの非表示
    closeCommentsButton.addEventListener('click', () => {
        addCommentBlock.style.display = 'none';
        toggleCommentsButton.style.display = 'inline';
        closeCommentsButton.style.display = 'none';
    });

    // 子コメントブロックの表示
    toggleChildCommentsButton.addEventListener('click', () => {
        addChildCommentBlock.style.display = 'block';
        toggleChildCommentsButton.style.display = 'none';
        closeChildCommentsButton.style.display = 'inline';
    });

    // 子コメントブロックの非表示
    closeChildCommentsButton.addEventListener('click', () => {
        addChildCommentBlock.style.display = 'none';
        toggleChildCommentsButton.style.display = 'inline';
        closeChildCommentsButton.style.display = 'none';
    });
});