function loadReplies(commentId) {
    const openRepliesButton = document.getElementById(`replies-button-${commentId}`);
    const closeRepliesButton = document.getElementById(`close-button-${commentId}`);
    const repliesContainer = document.getElementById(`replies-${commentId}`);
    // 子コメントのコンテナの表示状態で切り替え
    if (repliesContainer.style.display === 'none' || repliesContainer.style.display === '') {
        openRepliesButton.textContent = "読み込み中...";
        repliesContainer.style.display = 'block';
        const replyBlock = document.createElement('div');
        replyBlock.className = 'reply_block'
        // Ajax リクエスト
        fetch(`/work_reviews/comments/${commentId}/replies`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': `${csrfToken}`,
                'Accept': 'application/json',
            },
        })
            .then(response => response.json())
            .then(replies => {

                // 子コメントを表示
                replies.forEach(reply => {
                    const replyDiv = document.createElement('div');
                    replyDiv.classList.add('border-t', 'border-gray-200', 'pt-4', 'mt-4');

                    // コメントのヘッダー
                    const headerDiv = document.createElement('div');
                    headerDiv.classList.add('flex', 'items-center', 'justify-between');

                    const userDiv = document.createElement('div');
                    userDiv.classList.add('flex', 'items-center', 'space-x-4');
                    const userImage = document.createElement('img');
                    userImage.src = reply.user.image || 'https://res.cloudinary.com/dnumegejl/image/upload/v1732628038/No_User_Image_wulbjv.png';
                    userImage.alt = '画像が読み込めません。';
                    userImage.classList.add('w-16', 'h-16', 'rounded-full', 'object-cover');
                    const userInfoDiv = document.createElement('div');
                    const userLink = document.createElement('a');
                    userLink.href = `/users/${reply.user.id}`;
                    userLink.textContent = reply.user.name;
                    userLink.classList.add('font-medium');
                    const postDate = document.createElement('p');
                    postDate.textContent = reply.created_at;
                    postDate.classList.add('text-gray-500', 'text-sm');

                    userInfoDiv.appendChild(userLink);
                    userInfoDiv.appendChild(postDate);
                    userDiv.appendChild(userImage);
                    userDiv.appendChild(userInfoDiv);
                    headerDiv.appendChild(userDiv);

                    // コメント管理ボタン
                    const manageDiv = document.createElement('div');
                    manageDiv.classList.add('relative');
                    const manageButton = document.createElement('button');
                    manageButton.classList.add('p-1', 'bg-slate-400', 'text-white', 'rounded', 'hover:bg-slate-500');
                    manageButton.textContent = 'コメントを管理する';
                    manageButton.addEventListener('click', () => toggleDropdown(reply.id));

                    const dropdownDiv = document.createElement('div');
                    dropdownDiv.id = `dropdown-${reply.id}`;
                    dropdownDiv.classList.add('dropdown', 'hidden', 'absolute', 'right-0', 'mt-2', 'w-48', 'bg-white', 'rounded-md', 'shadow-lg', 'z-50');
                    const form = document.createElement('form');
                    form.action = `/work_reviews/comments/${reply.id}/delete`;
                    form.method = 'post';
                    form.id = `comment_${reply.id}`;
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = csrfToken;
                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'DELETE';
                    const deleteButton = document.createElement('button');
                    deleteButton.type = 'button';
                    deleteButton.textContent = 'コメントを削除する';
                    deleteButton.classList.add('block', 'w-full', 'text-left', 'px-4', 'py-2', 'text-sm', 'text-gray-700', 'hover:bg-gray-100');
                    deleteButton.addEventListener('click', () => deleteComment(reply.id));

                    form.appendChild(csrfInput);
                    form.appendChild(methodInput);
                    form.appendChild(deleteButton);
                    dropdownDiv.appendChild(form);
                    manageDiv.appendChild(manageButton);
                    manageDiv.appendChild(dropdownDiv);
                    headerDiv.appendChild(manageDiv);
                    replyDiv.appendChild(headerDiv);

                    // コメント本文
                    const bodyDiv = document.createElement('div');
                    bodyDiv.classList.add('flex', 'flex-col', 'md:flex-row', 'gap-4', 'mt-4');
                    const leftBlock = document.createElement('div');
                    leftBlock.classList.add('left_block', 'flex-1');
                    const bodyText = document.createElement('p');
                    bodyText.classList.add('text-gray-800');
                    bodyText.innerHTML = reply.body.replace(/\n/g, '<br>');
                    leftBlock.appendChild(bodyText);
                    bodyDiv.appendChild(leftBlock);

                    // 画像の処理
                    if (reply.image1 || reply.image2 || reply.image3 || reply.image4) {
                        const rightBlock = document.createElement('div');
                        rightBlock.classList.add('right_block', 'flex-1');
                        const gridDiv = document.createElement('div');
                        gridDiv.classList.add('grid', 'gap-4', reply.image2 ? 'grid-cols-2' : 'grid-cols-1');
                        if (!reply.image2) gridDiv.classList.add('place-items-center');

                        [reply.image1, reply.image2, reply.image3, reply.image4].forEach((image, index) => {
                            if (image) {
                                const link = document.createElement('a');
                                link.href = image;
                                link.dataset.lightbox = reply.id;
                                link.dataset.title = `画像${index + 1}`;
                                const img = document.createElement('img');
                                img.src = image;
                                img.alt = '画像が読み込めません。';
                                img.classList.add('w-full', 'object-cover', 'rounded-md', 'border', 'border-gray-300');
                                img.style.aspectRatio = '1 / 1';
                                link.appendChild(img);
                                gridDiv.appendChild(link);
                            }
                        });

                        rightBlock.appendChild(gridDiv);
                        bodyDiv.appendChild(rightBlock);
                    }

                    replyDiv.appendChild(bodyDiv);

                    // コメントのフッター
                    const footerDiv = document.createElement('div');
                    footerDiv.classList.add('flex', 'gap-4', 'items-center', 'justify-end', 'mt-4');
                    const commentFooterDiv = document.createElement('div');
                    commentFooterDiv.classList.add('content_footer_comment');
                    const toggleButton = document.createElement('button');
                    toggleButton.id = `toggleChildComments-${reply.id}`;
                    toggleButton.textContent = 'コメントする';
                    toggleButton.classList.add('px-2', 'py-1', 'bg-blue-500', 'text-white', 'rounded-lg', 'shadow-md', 'hover:bg-blue-600');
                    toggleButton.addEventListener('click', () => toggleChildCommentForm(reply.id));

                    const closeButton = document.createElement('button');
                    closeButton.id = `closeChildComments-${reply.id}`;
                    closeButton.textContent = '閉じる';
                    closeButton.classList.add('px-2', 'py-1', 'bg-gray-300', 'text-gray-700', 'rounded-lg', 'shadow-md', 'hover:bg-gray-400', 'hidden');
                    closeButton.addEventListener('click', () => toggleChildCommentForm(reply.id));

                    commentFooterDiv.appendChild(toggleButton);
                    commentFooterDiv.appendChild(closeButton);
                    footerDiv.appendChild(commentFooterDiv);

                    // コメントのいいねボタンセクション
                    const commentLikeSection = document.createElement('div');
                    commentLikeSection.classList.add('comment-like', 'flex', 'items-center', 'gap-2');

                    const likeButton = document.createElement('button');
                    likeButton.id = `comment-like_button-${reply.id}`;
                    likeButton.dataset.commentId = reply.id;
                    likeButton.className = 'comment-like_button px-2 py-1 bg-blue-500 text-white rounded-lg shadow-md hover:bg-blue-600';
                    likeButton.textContent = reply.is_liked_by_user ? 'いいね取り消し' : 'いいね';
                    likeButton.onclick = () => toggleLike(reply.id, `comment-like_button-${reply.id}`, `comment-like_count-${reply.id}`);
                    commentLikeSection.appendChild(likeButton);

                    const likeUserDiv = document.createElement('div');
                    likeUserDiv.className = 'comment-like_user';
                    const likeUserLink = document.createElement('a');
                    likeUserLink.href = `/work_reviews/comments/${reply.id}/like/index`;
                    likeUserLink.className = 'text-lg font-medium text-gray-700';
                    const likeUserCount = document.createElement('p');
                    likeUserCount.id = `comment-like_count-${reply.id}`;
                    likeUserCount.textContent = `${reply.like_user_count}件`;
                    likeUserLink.appendChild(likeUserCount);
                    likeUserDiv.appendChild(likeUserLink);
                    commentLikeSection.appendChild(likeUserDiv);
                    footerDiv.appendChild(commentLikeSection);
                    replyDiv.appendChild(footerDiv);

                    // 子コメント作成フォーム
                    const childCommentBlock = document.createElement('div');
                    childCommentBlock.id = `addChildCommentBlock-${reply.id}`;
                    childCommentBlock.className = 'w-full p-4 mt-4 border rounded-lg bg-gray-50';
                    childCommentBlock.style.display = 'none';

                    const commentTitle = document.createElement('p');
                    commentTitle.className = 'text-lg font-semibold mb-2';
                    commentTitle.textContent = 'コメントの作成';
                    childCommentBlock.appendChild(commentTitle);

                    const formDiv = document.createElement('div');
                    const workReviewInput = document.createElement('input');
                    workReviewInput.type = 'hidden';
                    workReviewInput.id = `work_review_id-${reply.id}`;
                    workReviewInput.value = reply.work_review_id;

                    const parentInput = document.createElement('input');
                    parentInput.type = 'hidden';
                    parentInput.id = `parent_id-${reply.id}`;
                    parentInput.value = reply.id;

                    const textarea = document.createElement('textarea');
                    textarea.id = `comment_body-${reply.id}`;
                    textarea.required = true;
                    textarea.className = 'w-full p-2 mb-2 border rounded-lg';
                    textarea.placeholder = 'コメントを入力してください';

                    const errorP = document.createElement('p');
                    errorP.id = `body_error-${reply.id}`;
                    errorP.className = 'text-red-500 text-sm hidden';
                    errorP.textContent = 'コメントを入力してください。';

                    const imageDiv = document.createElement('div');
                    imageDiv.className = 'image mb-4';
                    const imageTitle = document.createElement('h2');
                    imageTitle.className = 'text-sm font-medium mb-1';
                    imageTitle.textContent = '画像（4枚まで）';
                    const imageLabel = document.createElement('label');
                    const imageInput = document.createElement('input');
                    imageInput.id = `inputElm-${reply.id}`;
                    imageInput.type = 'file';
                    imageInput.style.display = 'none';
                    imageInput.name = 'images[]';
                    imageInput.multiple = true;
                    imageInput.onchange = () => loadImage(imageInput, reply.id);

                    const imageSpan = document.createElement('span');
                    imageSpan.className = 'text-blue-500 cursor-pointer';
                    imageSpan.textContent = '画像の追加';
                    const imageCount = document.createElement('div');
                    imageCount.id = `count-${reply.id}`;
                    imageCount.className = 'text-sm text-gray-600';
                    imageCount.textContent = '現在、0枚の画像を選択しています。';

                    imageLabel.appendChild(imageInput);
                    imageLabel.appendChild(imageSpan);
                    imageLabel.appendChild(imageCount);
                    imageDiv.appendChild(imageTitle);
                    imageDiv.appendChild(imageLabel);

                    const previewDiv = document.createElement('div');
                    previewDiv.id = `preview-${reply.id}`;
                    previewDiv.className = 'grid grid-cols-2 md:grid-cols-4 gap-2';

                    const submitDiv = document.createElement('div');
                    submitDiv.className = 'flex justify-center mt-4';
                    const submitButton = document.createElement('button');
                    submitButton.classList.add('submit_comment', 'px-2', 'py-1', 'bg-green-500', 'text-white', 'rounded-lg', 'shadow-md', 'hover:bg-green-600');
                    submitButton.textContent = 'コメントしろや';
                    submitButton.setAttribute('data-comment-id', reply.id);

                    submitButton.addEventListener('click', () => storeComment(reply.id));

                    submitDiv.appendChild(submitButton);

                    formDiv.append(workReviewInput, parentInput, textarea, errorP, imageDiv, previewDiv, submitDiv);
                    childCommentBlock.appendChild(formDiv);
                    replyDiv.appendChild(childCommentBlock);

                    // 子コメントセクション
                    if (reply.replies && reply.replies.length > 0) {
                        const childCommentDiv = document.createElement('div');
                        childCommentDiv.className = 'childComment';

                        const viewRepliesButton = document.createElement('button');
                        viewRepliesButton.onclick = () => loadReplies(reply.id);
                        viewRepliesButton.id = `replies-button-${reply.id}`;
                        viewRepliesButton.className = 'text-sm text-blue-500 hover:text-blue-600';
                        viewRepliesButton.textContent = '続きの返信を見る';
                        childCommentDiv.appendChild(viewRepliesButton);

                        const closeRepliesButton = document.createElement('button');
                        closeRepliesButton.onclick = () => loadReplies(reply.id);
                        closeRepliesButton.id = `close-button-${reply.id}`;
                        closeRepliesButton.className = 'text-sm text-gray-400 hover:text-gray-500 hidden';
                        closeRepliesButton.textContent = '続きの返信を閉じる';
                        childCommentDiv.appendChild(closeRepliesButton);

                        const repliesContainer = document.createElement('div');
                        repliesContainer.id = `replies-${reply.id}`;
                        repliesContainer.style.marginLeft = '40px';
                        childCommentDiv.appendChild(repliesContainer);

                        replyDiv.appendChild(childCommentDiv);
                    }

                    replyBlock.appendChild(replyDiv);
                    repliesContainer.appendChild(replyBlock);
                });

                // ボタンを切り替え
                // 再表示に備えて文言の変更
                openRepliesButton.textContent = "続きの返信を見る";
                openRepliesButton.style.display = 'none';
                closeRepliesButton.style.display = 'inline';

                repliesContainer.appendChild(replyBlock);
            })
            .catch(error => {
                console.error('エラーが発生しました:', error);
                // ボタンを元に戻す
                button.textContent = "続きの返信を見る";
                button.disabled = false;
            });

    } else {
        repliesContainer.style.display = 'none';
        // 既に表示している返信を削除
        repliesContainer.innerHTML = '';
        openRepliesButton.style.display = 'inline';
        closeRepliesButton.style.display = 'none';
    }
}