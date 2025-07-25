// csrfTokenの取得
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// いいね処理を非同期で行う
document.addEventListener('DOMContentLoaded', function () {
    const likeClasses = document.querySelectorAll('.like');
    const likeMessage = document.getElementById('message');
    likeClasses.forEach(element => {
        // いいねボタンのクラスの取得
        let button = element.querySelector('#like_button');
        // いいねしたユーザー数のクラス取得とpタグの取得
        let likeClass = element.querySelector('.like_user');
        let users = likeClass.querySelector('#like_count');

        //いいねボタンクリックによる非同期処理
        button.addEventListener('click', async function () {

            // ログイン状態を確認
            if (!window.checkLoginAndShowDialog(this)) return;

            const url = handleLikeAction(button);
            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': `${csrfToken}`
                    },
                });
                const data = await response.json();
                if (data.status === 'liked') {
                    button.innerText = window.Lang.common.unlike_action;
                    users.innerText = `${data.like_user}${window.Lang.common.num}`;
                } else if (data.status === 'unliked') {
                    button.innerText = window.Lang.common.like_action;
                    users.innerText = `${data.like_user}${window.Lang.common.num}`;
                }
                // メッセージを表示
                likeMessage.textContent = data.message;
                likeMessage.classList.remove('hidden');
                likeMessage.classList.add('block');
                likeMessage.style.backgroundColor = categoryColors[data.message] || '#d1d5db';

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

function handleLikeAction(button) {
    let url = '';
    const workId = button.getAttribute('data-work-id');
    const workStoryId = button.getAttribute('data-work_story-id');
    const musicId = button.getAttribute('data-music-id');
    const characterId = button.getAttribute('data-character-id');
    const pilgrimageId = button.getAttribute('data-pilgrimage-id');
    const notificationId = button.getAttribute('data-notification-id');
    const postId = button.getAttribute('data-post-id');

    if (workId && workStoryId && postId) {
        // work_story_postの場合
        url = `/works/${workId}/stories/${workStoryId}/posts/${postId}/like`;
    } else if (workId && postId) {
        // work_postの場合
        url = `/work_posts/${workId}/posts/${postId}/like`;
    } else if (musicId && postId) {
        // music_postの場合
        url = `/music_posts/${musicId}/posts/${postId}/like`;
    } else if (characterId && postId) {
        // character_postの場合
        url = `/character_posts/${characterId}/posts/${postId}/like`;
    } else if (pilgrimageId && postId) {
        // pilgrimage_postの場合
        url = `/pilgrimage_posts/${pilgrimageId}/posts/${postId}/like`;
    } else if (notificationId) {
        // notification_postの場合
        url = `/notification/like/${notificationId}`;
    } else {
        return;
    }

    return url;
}