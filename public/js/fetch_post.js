// HTMLのdata属性から表示しているユーザーのidを取得
const userIdTag = document.getElementById('user_id');
const userId = userIdTag.dataset.userId;
const postContainer = document.getElementById('post-container');

document.addEventListener('DOMContentLoaded', async function () {
    const switchButtons = document.querySelectorAll('.switch-button');
    // デフォルトの投稿の種類
    const defaultPostType = 'none';
    const defaultSwitchType = 'impressions';

    // 投稿の更新処理
    function updatePostContainer(type, html) {
        // 表示を更新
        postContainer.innerHTML = html;
        // 投稿の表示
        // if (posts.length > 0) {
        //     posts.forEach(post => {
        //         // 投稿の種類に応じたURLを取得
        //         const postDetailUrl = createTypeToURL(type, post);
        //         // 投稿の種類に応じた文言を取得
        //         const typeDescription = describeGroup(type, post);

        //         const postElement = document.createElement('div');
        //         postElement.className = 'post-item p-4 mb-4 bg-gray-100 rounded max-w-3xl text-left';
        //         postElement.innerHTML = `
        //                 <div class="post-header flex items-center mb-4">
        //                     <img src="${post.user.image || 'https://res.cloudinary.com/dnumegejl/image/upload/v1732628038/No_User_Image_wulbjv.png'}" 
        //                         alt="${post.user.name}のアバター" 
        //                         class="w-10 h-10 rounded-full mr-4">
        //                         <a href="/users/${post.user.id}" class="text-lg font-bold">${post.user.name || '名無し'}</a>
        //                 </div>
        //                 <p>${post.postType}</p>
        //                 <p>${post.created_at}</p>
        //                 <p>${typeDescription}</p>
        //                 <div class="post-content flex items-start gap-4 max-w-[800px] mx-auto">
        //                     <div class="flex-1">
        //                         <h3 class="text-lg font-bold">
        //                             <a href="${postDetailUrl}" class="hover:underline">
        //             ${post.post_title || 'タイトルがありません'}
        //                             </a>
        //                         </h3>
        //                         <p class="text-gray-700">${post.body || '内容がありません'}</p>
        //                     </div>
        //                     ${post.image1 ? `
        //                     <div class="flex-shrink-0">
        //                         <img src="${post.image1}" 
        //          alt="投稿画像"
        //          class="w-24 h-24 object-cover rounded border border-gray-300">
        //                     </div>` : ''}
        //                 </div>
        //             `;
        //         postContainer.appendChild(postElement);
        //     });
        // } else {
        //     postContainer.innerHTML = '<p class="text-gray-500">投稿がありません。</p>';
        // }
    }

    // 初期の読み込み
    await fetchAndDisplayPosts(defaultSwitchType, defaultPostType, 1);

    // ボタンの切り替えイベント（感想投稿・コメント・いいね）
    switchButtons.forEach(button => {
        button.addEventListener('click', async function () {
            // 押下したボタンの種類を取得
            const switchType = this.getAttribute('switch-type');
            console.log(switchType);

            // ボタンのスタイルを更新
            switchButtons.forEach(btn => {
                btn.classList.remove('active', 'bg-blue-500', 'text-white', 'hover:bg-blue-400', 'hover:bg-blue-600');
                btn.classList.add('bg-blue-300', 'text-white', 'hover:bg-blue-400');
            });
            this.classList.add('active', 'bg-blue-500', 'text-white', 'hover:bg-blue-600');
            // 検索状態をリセット
            document.getElementById('search-input').value = '';
            // 投稿データを更新
            await fetchAndDisplayPosts(switchType, defaultPostType, 1);
        });
    });
});

// 投稿の検索を行う
function searchPosts() {
    const switchType = document.querySelector('.switch-button.active').getAttribute('switch-type');
    const selectElement = document.getElementById("select_box");
    const postType = selectElement.value;
    fetchAndDisplayPosts(switchType, postType, page = 1);
}

// 投稿の種類別の検索を可能にする
function changePostType(selectElement) {
    let selectedType = selectElement.value;
    const switchType = document.querySelector('.switch-button.active').getAttribute('switch-type');
    fetchAndDisplayPosts(switchType, selectedType, page = 1);
}

async function fetchAndDisplayPosts(switchType, postType, page = 1) {
    try {
        // 検索キーワードを取得
        const searchInput = document.getElementById('search-input').value.trim();

        // ローディング表示
        postContainer.innerHTML = '<p>読み込み中...</p>';

        // データの取得
        const response = await fetch(`/users/${userId}/posts/${postType}?page=${page}`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content') // CSRF対策
            },
            body: JSON.stringify({ keyword: searchInput, switchType: switchType })
        });
        const postCellHtml = await response.text();
        postContainer.innerHTML = postCellHtml;

        // ページネーション情報を取得
        //const postContainerDiv = document.getElementById('post-cell');
        //const currentPage = parseInt(postContainerDiv.dataset.currentPage);
        //const lastPage = parseInt(postContainerDiv.dataset.lastPage);

        // ページネーションの更新
        //updatePagination(postType, currentPage, lastPage);

    } catch (error) {
        postContainer.innerHTML = '<p class="text-gray-500">投稿がありません。</p>';
    }
}

// ページネーションの更新
function updatePagination(postType, currentPage, lastPage) {
    const paginationContainer = document.getElementById('pagination-container');
    paginationContainer.innerHTML = '';
    const switchType = document.querySelector('.switch-button.active').getAttribute('switch-type');

    // 「前へ」ボタン
    if (currentPage > 1) {

        const prevButton = document.createElement('button');
        prevButton.className = 'bg-blue-500 text-white px-2 py-2 rounded mx-1';
        prevButton.addEventListener('click', () => {
            fetchAndDisplayPosts(switchType, postType, currentPage - 1);
        });
        const leftArrows = createPaginationButton("left");
        prevButton.appendChild(leftArrows);
        paginationContainer.appendChild(prevButton);
    }

    let pages = [];

    // 最初のページ
    if (currentPage > 3) {
        pages.push(1);
        if (currentPage > 4) pages.push('...');
    }
    // 現在のページの前後を表示
    let startPage = Math.max(1, currentPage - 2);
    let endPage = Math.min(lastPage, currentPage + 2);
    for (let i = startPage; i <= endPage; i++) {
        pages.push(i);
    }
    // 最後のページ
    if (currentPage < lastPage - 2) {
        if (currentPage < lastPage - 3) pages.push('...');
        pages.push(lastPage);
    }
    // ページボタンを作成
    pages.forEach(page => {
        if (page === '...') {
            const dots = document.createElement('span');
            dots.textContent = '...';
            dots.className = 'px-3 py-2 text-gray-500';
            paginationContainer.appendChild(dots);
        } else {
            const pageButton = document.createElement('button');
            pageButton.textContent = page;
            pageButton.className = `px-3 py-2 rounded mx-1 ${page === currentPage ? 'bg-blue-700 text-white' : 'bg-gray-300 text-black'}`;
            if (page !== currentPage) {
                pageButton.addEventListener('click', () => fetchAndDisplayPosts(switchType, postType, page));
            }
            paginationContainer.appendChild(pageButton);
        }
    });
    // 「次へ」ボタン
    if (currentPage < lastPage) {
        const nextButton = document.createElement('button');
        nextButton.className = 'bg-blue-500 text-white px-2 py-2 rounded mx-1';
        nextButton.addEventListener('click', () => fetchAndDisplayPosts(switchType, postType, currentPage + 1));
        const rightArrows = createPaginationButton("right");
        nextButton.appendChild(rightArrows);
        paginationContainer.appendChild(nextButton);
    }
}

// ペジネーションボタンを作成する関数
function createPaginationButton(arrowType) {
    // SVG要素の作成
    const svgNS = "http://www.w3.org/2000/svg";
    const svg = document.createElementNS(svgNS, "svg");
    svg.setAttribute("class", "w-5 h-5");
    svg.setAttribute("fill", "currentColor");
    svg.setAttribute("viewBox", "0 0 20 20");

    // Path要素の作成
    const path = document.createElementNS(svgNS, "path");
    path.setAttribute("fill-rule", "evenodd");
    let arrowIcon = arrowType === "right" ? "M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" : "M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z";
    path.setAttribute("d", arrowIcon);
    path.setAttribute("clip-rule", "evenodd");

    // SVGにPathを追加
    svg.appendChild(path);
    return svg;
}
