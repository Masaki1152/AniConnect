// HTMLのdata属性から表示しているユーザーのidを取得
const userIdTag = document.getElementById('user_id');
const userId = userIdTag.dataset.userId;

document.addEventListener('DOMContentLoaded', async function () {
    const postButtons = document.querySelectorAll('.post-button');
    const postContainer = document.getElementById('post-container');
    // デフォルトの投稿の種類
    const defaultType = 'work';

    // デフォルトの投稿を読み込む
    async function fetchAndDisplayPosts(type) {
        try {
            // データの取得
            const response = await fetch(`/users/${userId}/posts/${type}`);
            const posts = await response.json();

            // 表示を更新
            postContainer.innerHTML = '';
            if (posts.length > 0) {
                posts.forEach(post => {

                    // 投稿の種類に応じたURLを取得
                    const postDetailUrl = createTypeToURL(type, post);

                    const postElement = document.createElement('div');
                    postElement.className = 'post-item p-4 mb-4 bg-gray-100 rounded';
                    postElement.innerHTML = `
                        <div class="post-header flex items-center mb-4">
                            <img src="${post.user.image || 'https://res.cloudinary.com/dnumegejl/image/upload/v1732628038/No_User_Image_wulbjv.png'}" 
                                alt="${post.user.name}'s avatar" 
                                class="w-10 h-10 rounded-full mr-4">
                                <a href="/users/${post.user.id}" class="text-lg font-bold">${post.user.name || '名無し'}</a>
                        </div>
                        <h3 class="text-lg font-bold">
                            <a href="${postDetailUrl}" class="hover:underline">
                                ${post.post_title || 'No Title'}
                            </a>
                        </h3>
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
    }

    // 初期の読み込み
    await fetchAndDisplayPosts(defaultType);

    // ボタンの切り替えイベント
    postButtons.forEach(button => {
        button.addEventListener('click', async function () {
            // 押下したボタンの種類を取得
            const type = this.dataset.type;

            // ボタンのスタイルを更新
            postButtons.forEach(btn => {
                btn.classList.remove('active', 'bg-blue-500', 'text-white');
                btn.classList.add('bg-blue-300', 'text-white');
            });
            this.classList.add('active', 'bg-blue-500', 'text-white');

            // 投稿データを更新
            await fetchAndDisplayPosts(type);
        });
    });

    function createTypeToURL(type, post) {
        // 各投稿の種類のURL
        const typeToUrlMap = {
            // 作品感想の詳細ページ
            work: `work_reviews/${post.work_id}/reviews`,
            // あらすじ感想の詳細ページ
            workStory: `works/${post.work_id}/stories/${post.sub_title_id}/posts`,
            // 登場人物感想の詳細ページ
            character: `character_posts/${post.character_id}/posts`,
            // 音楽感想の詳細ページ
            music: `music_posts/${post.music_id}/posts`,
            // 聖地感想の詳細ページ
            animePilgrimage: `pilgrimage_posts/${post.anime_pilgrimage_id}/posts`,
        };

        // 投稿の種類に応じたURLを取得
        const typeUrl = typeToUrlMap[type];
        return `/${typeUrl}/${post.id}`;
    }
});
