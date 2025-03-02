// csrfTokenの取得
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// いいね処理を非同期で行う
document.addEventListener('DOMContentLoaded', function () {
    const likeClasses = document.querySelectorAll('.like');
    const likeMessage = document.getElementById('like-message');
    likeClasses.forEach(element => {
        // いいねボタンのクラスの取得
        let button = element.querySelector('#like_button');
        // いいねしたユーザー数のクラス取得とpタグの取得
        let likeClass = element.querySelector('.like_user');
        let users = likeClass.querySelector('#like_count');

        //いいねボタンクリックによる非同期処理
        button.addEventListener('click', async function () {
            const notificationId = button.getAttribute('data-notification-id');
            try {
                const response = await fetch(
                    `/notification/like/${notificationId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': `${csrfToken}`
                    },
                });
                const data = await response.json();
                if (data.status === 'liked') {
                    button.innerText = window.Lang.common.unlike_action;
                    users.innerText = `${data.like_user}${window.Lang.common.liked_num}`;
                } else if (data.status === 'unliked') {
                    button.innerText = window.Lang.common.like_action;
                    users.innerText = `${data.like_user}${window.Lang.common.liked_num}`;
                }
                // メッセージを表示
                likeMessage.textContent = data.message;
                likeMessage.classList.remove('hidden');
                likeMessage.classList.add('block');

                // 3秒後にメッセージを非表示
                setTimeout(() => {
                    likeMessage.classList.add('hidden');
                    likeMessage.classList.remove('block');
                }, 3000);
            } catch (error) {
                console.error('Error:', error);
            }
        });
    });
});