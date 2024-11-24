const preview = document.getElementById('preview');
// HTMLのdata属性から既存画像のURLを取得
const existingImagePath = document.getElementById('existing_image_path');
const phpVariable = existingImagePath.dataset.phpVariable;
// 既存の画像を取得
const existingImage = phpVariable;
// 画像がない場合の画像パス
const noImagePath = 'https://res.cloudinary.com/dnumegejl/image/upload/v1732344378/No_User_Image_genl0i.png';
// 既存の画像を表示
const currentImage = existingImage != '' ? existingImage : noImagePath;

// 編集画面にて、以前画像が選択されていた場合、それらの画像を反映する
// DOMツリー読み取り完了後にイベント発火
document.addEventListener('DOMContentLoaded', function() {
    renderExistingImages(currentImage)
    if (currentImage == noImagePath) {
        // プレビューのうち、削除ボタンを削除する
        const deleteButton = document.getElementById('delete_button');
        deleteButton.remove();
    }
});

// 既存画像をプレビューとして表示
function renderExistingImages(currentImage) {
    // プレビューを初期化
    preview.innerHTML = '';

    const figure = document.createElement('figure');
    figure.setAttribute('id', 'preview_image');
    figure.className = 'relative flex flex-col items-center mb-4';

    const img = document.createElement('img');
    img.src = currentImage;
    img.alt = 'existing preview';
    img.className = 'w-36 h-36 object-cover rounded-md border border-gray-300 mb-2';

    const rmBtn = document.createElement('button');
    rmBtn.type = 'button';
    rmBtn.setAttribute('id', 'delete_button');
    rmBtn.textContent = '削除';
    rmBtn.className = 'px-2 py-1 bg-red-500 text-white text-sm rounded hover:bg-red-600';
    rmBtn.onclick = function() {
        removeExistingImage();
    };

    figure.appendChild(img);
    figure.appendChild(rmBtn);
    preview.appendChild(figure);
};

// 新しく画像を変えた場合の処理
document.getElementById('image').addEventListener('change', function(event) {

    const file = event.target.files[0];

    if (file) {
        preview.innerHTML = '';
        const reader = new FileReader();

        reader.onload = function(e) {
            const figure = document.createElement('figure');
            figure.setAttribute('id', 'preview_image');
            figure.className = 'relative flex flex-col items-center mb-4';
            // 画像の更新
            const img = document.createElement('img');
            img.src = e.target.result;
            img.alt = 'preview';
            img.className = 'w-36 h-36 object-cover rounded-md border border-gray-300 mb-2';
            // 画像をリセットボタンの作成
            const rmBtn = document.createElement('button');
            rmBtn.type = 'button';
            rmBtn.textContent = '画像変更のリセット';
            rmBtn.className =
                'px-2 py-1 bg-red-500 text-white text-sm rounded hover:bg-red-600';
            rmBtn.onclick = function() {
                resetImage();
            };

            figure.appendChild(img);
            figure.appendChild(rmBtn);
            preview.appendChild(figure);
        };

        reader.readAsDataURL(file);
    }
});

// 画像の変更をリセットする
function resetImage(index) {
    const currentImage = existingImage != '' ? existingImage : noImagePath;

    // プレビューを再描画
    renderExistingImages(currentImage);
    if (currentImage == noImagePath) {
        // プレビューのうち、削除ボタンを削除する
        const deleteButton = document.getElementById('delete_button');
        deleteButton.remove();
    }
    // 選択した画像をinputから削除する
    const selectedImage = document.getElementById('image');
    selectedImage.value = '';
}

// 画像の削除
function removeExistingImage() {
    // 選択なし画像のパスを代入
    const currentImage = noImagePath;
    // プレビューを再描画
    renderExistingImages(currentImage);
    // プレビューのうち、削除ボタンを削除する
    const deleteButton = document.getElementById('delete_button');
    deleteButton.remove();
}