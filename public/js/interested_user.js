// csrfTokenの取得
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// 「気になる」登録処理を非同期で行う
document.addEventListener('DOMContentLoaded', function () {
    const interestedClasses = document.querySelectorAll('.interested');
    const interestedMessage = document.getElementById('message');
    interestedClasses.forEach(element => {
        // 「気になる」登録ボタンのクラスの取得
        let button = element.querySelector('#interested_button');
        // 「気になる」登録したユーザー数のクラス取得とpタグの取得
        let interestedClass = element.querySelector('.interested_user');
        let users = interestedClass.querySelector('#interested_count');

        //「気になる」登録ボタンクリックによる非同期処理
        button.addEventListener('click', async function () {
            const targetType = button.getAttribute('data-type');
            const targetFirstId = button.getAttribute('data-first-id');
            let path = '';
            if (button.getAttribute('data-second-id')) {
                const targetSecondId = button.getAttribute('data-second-id');
                path = createPath(targetType, targetFirstId, targetSecondId);
            } else {
                path = createPath(targetType, targetFirstId);
            }

            try {
                const response = await fetch(
                    path, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': `${csrfToken}`
                    },
                });
                const data = await response.json();
                if (data.status === 'interested') {
                    button.innerText = '★';
                    button.classList.remove('text-gray-400', 'hover:text-gray-600');
                    button.classList.add('text-yellow-400');
                    users.innerText = `${data.interested_user}件`;
                } else if (data.status === 'unInterested') {
                    button.innerText = '☆';
                    button.classList.remove('text-yellow-400');
                    button.classList.add('text-gray-400', 'hover:text-gray-600');
                    users.innerText = `${data.interested_user}件`;
                }
                // メッセージを表示
                interestedMessage.textContent = data.message;
                interestedMessage.classList.remove('hidden');
                interestedMessage.classList.add('block');
                interestedMessage.style.backgroundColor = categoryColors[data.message] || '#d1d5db';

                // 3秒後にメッセージを非表示
                setTimeout(() => {
                    interestedMessage.classList.add('hidden');
                    interestedMessage.classList.remove('block');
                }, 3000);
            } catch (error) {
                console.error('Error:', error);
            }
        });
    });
});

// 気になる登録をする対象の種類からパスを作成
function createPath(targetType, targetFirstId, targetSecondId = 0) {
    let path = "";
    switch (targetType) {
        case "works":
            path = `/works/${targetFirstId}/interested`;
            break;
        case "workStories":
            path = `/works/${targetSecondId}/stories/${targetFirstId}/interested`;
            break;
    }
    return path;
}