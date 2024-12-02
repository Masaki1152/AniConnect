// フォローを非同期で行う
document.addEventListener('DOMContentLoaded', function () {
    const followClasses = document.querySelectorAll('.follow');
    followClasses.forEach(element => {
        // フォローボタンのクラスの取得
        const button = element.querySelector('#follow_button');
        // ボタンが存在しない場合、ログインしているユーザーのためスキップ
        if (!button) return;

        // フォローしたユーザー数のクラス取得とpタグの取得
        const followClass = element.querySelector('.follow_user');
        const followingUsers = followClass ? followClass.querySelector('#following_count') : null;
        const followersUsers = followClass ? followClass.querySelector('#followers_count') : null;

        // ログインしているユーザーのフォロー数取得
        const authFollowClass = document.querySelector('.auth_follow_user');
        const authFollowingUsers = authFollowClass ? authFollowClass.querySelector(
            '#auth_following_count') : null;
        const authFollowersUsers = authFollowClass ? authFollowClass.querySelector(
            '#auth_followers_count') : null;
        //フォローボタンクリックによる非同期処理
        button.addEventListener('click', async function () {
            const userId = button.getAttribute('user-id');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            try {
                const response = await fetch(
                    `/users/${userId}/follow`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': `${csrfToken}`
                    },
                });
                const data = await response.json();
                if (data.status === 'followed') {
                    button.innerText = 'フォロー解除';
                    if (followingUsers) followingUsers.innerText =
                        `${data.followingCount} フォロー中`;
                    if (followersUsers) followersUsers.innerText =
                        `${data.followersCount} フォロワー`;
                } else if (data.status === 'unfollowed') {
                    button.innerText = 'フォローする';
                    if (followingUsers) followingUsers.innerText =
                        `${data.followingCount} フォロー中`;
                    if (followersUsers) followersUsers.innerText =
                        `${data.followersCount} フォロワー`;
                }
                // ログインしているユーザーのフォロー数の更新
                if (authFollowingUsers) authFollowingUsers.innerText =
                    `${data.authFollowingCount} フォロー中`;
                if (authFollowersUsers) authFollowersUsers.innerText =
                    `${data.authFollowersCount} フォロワー`;
            } catch (error) {
                console.error('Error:', error);
            }
        });
    });
});