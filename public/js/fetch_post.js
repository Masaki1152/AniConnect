// HTMLのdata属性から表示しているユーザーのidを取得
const userIdTag = document.getElementById('user_id');
const userId = userIdTag.dataset.userId;

document.addEventListener('DOMContentLoaded', function () {
    const postButtons = document.querySelectorAll('.post-button');
    const postContainer = document.getElementById('post-container');

    postButtons.forEach(button => {
        button.addEventListener('click', async function () {
            // ボタンに設定した data-type 属性の値を取得
            const type = this.dataset.type;

            try {
                // データ取得
                const response = await fetch(`/users/${userId}/posts/${type}`);
                const posts = await response.json();

                // 表示を更新
                postContainer.innerHTML = '';
                if (posts.length > 0) {
                    posts.forEach(post => {
                        const postElement = document.createElement('div');
                        postElement.className = 'post-item p-4 mb-4 bg-gray-100 rounded shadow';
                        postElement.innerHTML = `
                            <h3 class="text-lg font-bold">${post.post_title || 'No Title'}</h3>
                            <p class="text-gray-700">${post.body || 'No Content'}</p>
                        `;
                        postContainer.appendChild(postElement);
                    });
                } else {
                    postContainer.innerHTML = '<p class="text-gray-500">投稿がありません。</p>';
                }
            } catch (error) {
                console.error('Error fetching posts:', error);
                postContainer.innerHTML = '<p class="text-red-500">投稿の取得に失敗しました。</p>';
            }
        });
    });
});
