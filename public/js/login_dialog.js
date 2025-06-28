function showLoginDialog(messageText) {
    const dialog = document.getElementById('login-dialog');
    const messageParagraph = dialog ? dialog.querySelector('p') : null;

    if (dialog) {
        if (messageParagraph) {
            messageParagraph.textContent = `${messageText}にはログインが必要です。`;
        }

        dialog.classList.remove('hidden');
        dialog.classList.add('flex');

        // クローズボタンのイベントリスナーを設定
        const closeButton = document.getElementById('close-login-dialog');
        if (closeButton) {
            closeButton.onclick = () => {
                dialog.classList.add('hidden');
                dialog.classList.remove('flex');
            };
        }

        // モーダルの外側をクリックで閉じる
        dialog.onclick = (e) => {
            if (e.target === dialog) {
                dialog.classList.add('hidden');
                dialog.classList.remove('flex');
            }
        };
    }
}

function checkLoginAndShowDialog(element) {
    if (window.App && !window.App.userLoggedIn) {
        const actionText = element.getAttribute('data-login-required-action');
        if (!window.showLoginDialog) return false;
        showLoginDialog(actionText);
        return false;
    }
    return true;
}

