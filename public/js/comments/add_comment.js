// document.addEventListener('DOMContentLoaded', function () {

//     // 投稿に対するコメントブロックの表示
//     toggleCommentsButton.addEventListener('click', () => {
//         addCommentBlock.style.display = 'block';
//         toggleCommentsButton.style.display = 'none';
//         closeCommentsButton.style.display = 'inline';
//     });

//     // 投稿に対するコメントブロックの非表示
//     closeCommentsButton.addEventListener('click', () => {
//         addCommentBlock.style.display = 'none';
//         toggleCommentsButton.style.display = 'inline';
//         closeCommentsButton.style.display = 'none';
//     });
// });

// 投稿のコメントフォームの表示/非表示を切り替える関数
function toggleCommentForm() {
    const toggleCommentButton = document.getElementById('toggleComments');
    const closeCommentButton = document.getElementById('closeComments');
    const addCommentBlock = document.getElementById('addCommentBlock');
    if (addCommentBlock.style.display === 'none' || addCommentBlock.style.display === '') {
        addCommentBlock.style.display = 'block';
        toggleCommentButton.style.display = 'none';
        closeCommentButton.style.display = 'inline';
    } else {
        addCommentBlock.style.display = 'none';
        toggleCommentButton.style.display = 'inline';
        closeCommentButton.style.display = 'none';
    }
}

// 各コメントのコメントフォームの表示/非表示を切り替える関数
function toggleChildCommentForm(replyId) {
    const addChildCommentBlock = document.getElementById(`addChildCommentBlock-${replyId}`);
    const toggleChildCommentButton = document.getElementById(`toggleChildComments-${replyId}`);
    const closeChildCommentButtton = document.getElementById(`closeChildComments-${replyId}`);
    if (addChildCommentBlock.style.display === 'none' || addChildCommentBlock.style.display === '') {
        addChildCommentBlock.style.display = 'block';
        toggleChildCommentButton.style.display = 'none';
        closeChildCommentButtton.style.display = 'inline';
    } else {
        addChildCommentBlock.style.display = 'none';
        toggleChildCommentButton.style.display = 'inline';
        closeChildCommentButtton.style.display = 'none';
    }
}

// ドロップダウン以外をクリックするとドロップダウンを閉じる
document.body.addEventListener('click', function (event) {
    const dropdowns = document.querySelectorAll('.dropdown');

    dropdowns.forEach(dropdown => {
        const button = dropdown.previousElementSibling;
        if (!dropdown.contains(event.target) && !button.contains(event.target)) {
            // クリックした場所がドロップダウンやボタンでなければ閉じる
            dropdown.classList.add('hidden');
        }
    });
});

// 各コメントの「管理する」ボタンのドロップダウンの表示・非表示を切り替える関数
function toggleDropdown(replyId) {
    const dropdown = document.getElementById(`dropdown-${replyId}`);
    if (dropdown) {
        const isHidden = dropdown.classList.contains('hidden');
        dropdown.classList.toggle('hidden', !isHidden);
        dropdown.classList.toggle('block', isHidden);
    }
}