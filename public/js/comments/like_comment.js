// いいね処理を関数として定義
async function toggleLike(commentId, buttonId, userCountId, baseRoute) {
    const button = document.getElementById(buttonId);
    const userCount = document.getElementById(userCountId);
    try {
        const response = await fetch(
            `/${baseRoute}/comments/${commentId}/like`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
        });
        const data = await response.json();
        if (data.status === 'liked') {
            button.innerText = 'いいね取り消し';
            userCount.innerText = `${data.like_user}件`;
        } else if (data.status === 'unliked') {
            button.innerText = 'いいね';
            userCount.innerText = `${data.like_user}件`;
        }
        // メッセージを表示
        const likeMessage = document.getElementById('message');
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
}