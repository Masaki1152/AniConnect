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
            // 検索キーワードを取得
            const searchInput = document.getElementById('search-input').value.trim();

            // データの取得
            const response = await fetch(`/users/${userId}/posts/${type}?keyword=${encodeURIComponent(searchInput)}`);
            const posts = await response.json();

            // 表示を更新
            postContainer.innerHTML = '';
            // 投稿の表示
            if (posts.length > 0) {
                posts.forEach(post => {
                    // 投稿の種類に応じたURLを取得
                    const postDetailUrl = createTypeToURL(type, post);
                    // 投稿の種類に応じた文言を取得
                    const typeDescription = describeGroup(type, post);

                    const postElement = document.createElement('div');
                    postElement.className = 'post-item p-4 mb-4 bg-gray-100 rounded max-w-3xl text-left';
                    postElement.innerHTML = `
                        <div class="post-header flex items-center mb-4">
                            <img src="${post.user.image || 'https://res.cloudinary.com/dnumegejl/image/upload/v1732628038/No_User_Image_wulbjv.png'}" 
                                alt="${post.user.name}のアバター" 
                                class="w-10 h-10 rounded-full mr-4">
                                <a href="/users/${post.user.id}" class="text-lg font-bold">${post.user.name || '名無し'}</a>
                        </div>
                        <p>${typeDescription}</p>
                        <div class="post-content flex items-start gap-4 max-w-[800px] mx-auto">
                            <div class="flex-1">
                                <h3 class="text-lg font-bold">
                                    <a href="${postDetailUrl}" class="hover:underline">
                    ${post.post_title || 'タイトルがありません'}
                                    </a>
                                </h3>
                                <p class="text-gray-700">${post.body || '内容がありません'}</p>
                            </div>
                            ${post.image1 ? `
                            <div class="flex-shrink-0">
                                <img src="${post.image1}" 
                 alt="投稿画像"
                 class="w-24 h-24 object-cover rounded border border-gray-300">
                            </div>` : ''}
                        </div>
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
            // 検索状態をリセット
            document.getElementById('search-input').value = '';
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

    function describeGroup(type, post) {
        // 各投稿の種類に追加する文言
        const typeToGroup = {
            // 作品感想の文言
            work: `「${post.work?.name || '不明な作品'}」への感想投稿`,
            // あらすじ感想の文言
            workStory: `「${post.work?.name || '不明な作品'}」${post.work_story?.episode || '不明な話数'}「${post.work_story?.sub_title || '不明なサブタイトル'}」への感想投稿`,
            // 登場人物感想の文言
            character: `「${post.character?.name || '不明なキャラクター'}」への感想投稿`,
            // 音楽感想の文言
            music: `「${post.music?.name || '不明な音楽'}」への感想投稿`,
            // 聖地感想の文言
            animePilgrimage: `「${post.anime_pilgrimage?.name || '不明な聖地'}」への感想投稿`,
        };

        // 投稿の種類に応じたURLを取得
        const typeGroup = typeToGroup[type];
        return typeGroup;
    }

    // 検索を行うメソッド
    document.getElementById('search-button').addEventListener('click', async function () {
        const activeButton = document.querySelector('.post-button.active');
        const type = activeButton ? activeButton.dataset.type : 'work';
        await fetchAndDisplayPosts(type);
    });

    // キャンセルを行うメソッド
    document.getElementById('cancel-button').addEventListener('click', async function () {
        // 検索状態をリセット
        document.getElementById('search-input').value = '';
        const activeButton = document.querySelector('.post-button.active');
        const type = activeButton ? activeButton.dataset.type : 'work';
        await fetchAndDisplayPosts(type);
    });
});
