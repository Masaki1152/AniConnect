// 投稿のコメントフォームの表示/非表示を切り替える関数
function toggleCommentForm(buttonElement) {

    // ログイン状態を確認
    if (!window.checkLoginAndShowDialog(buttonElement)) return;

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
function toggleChildCommentForm(buttonElement, replyId) {

    // ログイン状態を確認
    if (!window.checkLoginAndShowDialog(buttonElement)) return;

    const addChildCommentBlock = document.getElementById(`addChildCommentBlock-${replyId}`);
    const toggleChildCommentButton = document.getElementById(`toggleChildComments-${replyId}`);
    const closeChildCommentButtton = document.getElementById(`closeChildComments-${replyId}`);
    if (addChildCommentBlock.style.display === 'none' || addChildCommentBlock.style.display === '') {
        addChildCommentBlock.style.display = 'block';
        toggleChildCommentButton.style.display = 'none';
        closeChildCommentButtton.style.display = 'inline';
        if (document.getElementById(`commentCharacterCount-${replyId}`)) {
            document.getElementById(`commentCharacterCount-${replyId}`).innerText = "あと200文字入力できます。";
            document.getElementById(`comment_body-${replyId}`).value = "";
        }
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