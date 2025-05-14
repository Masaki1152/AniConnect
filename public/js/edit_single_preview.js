const inputElm = document.getElementById('inputElm');
const cropModal = document.getElementById('crop-modal');
const cropPreview = document.getElementById('crop-preview');
const cropNextButton = document.getElementById('crop-next-button');
const cropCancelButton = document.getElementById('crop-cancel-button');
const preview = document.getElementById('preview');

// HTMLのdata属性から既存画像のURLを取得
const existingImagePath = document.getElementById('existing_image_path');
const existingImage = existingImagePath.dataset.phpVariable;
// 画像がない場合の画像パス
const noImagePath = 'https://res.cloudinary.com/dnumegejl/image/upload/v1746976861/No_Image_udwwqo.png';
// 既存の画像
let currentImage = existingImage != '' ? existingImage : noImagePath;

let cropper;
let newImages = [];

// 編集画面にて、以前画像が選択されていた場合、それらの画像を反映する
// DOMツリー読み取り完了後にイベント発火
document.addEventListener('DOMContentLoaded', function () {
    // 現在の画像のURLをフォームに反映
    document.getElementById('existingImage').value = existingImage != '' ? existingImage : null;
    // 既存画像をプレビューとして表示
    renderImage(currentImage, removeExistingImage, false);

    if (currentImage == noImagePath) {
        // プレビューのうち、削除ボタンを削除する
        const deleteButton = document.getElementById('delete_button');
        deleteButton.remove();
    }
})

// 既存画像をプレビューとして表示
function renderImage(currentImage, changeImage, isNewImage) {
    // プレビューを初期化
    preview.innerHTML = '';

    const figure = document.createElement('figure');
    figure.setAttribute('id', `img-block`);
    figure.className = 'relative flex flex-col items-center mb-4';

    const img = document.createElement('img');
    // サーバー上の画像URLを使用
    img.src = currentImage;
    img.alt = 'preview';
    img.className = 'w-full h-full object-cover rounded-lg border border-gray-300 aspect-w-4 aspect-h-3';

    // 削除ボタンの文言のハンドリング
    const isNewImageSelected = currentImage.startsWith('data:image/');
    const hasExistingImage = existingImage !== '';
    const deleteLabel = (isNewImageSelected && hasExistingImage)
        ? window.Lang.messages.reset_image_change
        : window.Lang.common.delete;

    // 削除ボタン
    const rmBtn = document.createElement('button');
    rmBtn.type = 'button';
    rmBtn.setAttribute('id', 'delete_button');
    rmBtn.textContent = deleteLabel;
    rmBtn.className = 'px-2 py-1 bg-red-500 text-white rounded hover:bg-red-600 mt-2';
    rmBtn.onclick = function () {
        changeImage();
    };

    figure.appendChild(img);
    figure.appendChild(rmBtn);
    preview.appendChild(figure);

    // 画像追加ボタンの表示切替
    toggleAddImageButton();

    if (isNewImage) {
        updateInputElement();
    }
}

// 画像の削除
function removeExistingImage() {
    // 選択なし画像のパスを代入
    currentImage = noImagePath;
    // プレビューを再描画
    renderImage(currentImage, removeExistingImage, false);
    // プレビューのうち、削除ボタンを削除する
    const deleteButton = document.getElementById('delete_button');
    deleteButton.remove();
    // 現在の画像のURLを削除してフォームに反映
    document.getElementById('existingImage').value = null;
    // 画像追加ボタンの表示切替
    toggleAddImageButton();
}

// 画像の変更をリセットする
function resetImage() {
    currentImage = existingImage != '' ? existingImage : noImagePath;

    // プレビューを再描画
    renderImage(currentImage, removeExistingImage, false);
    if (currentImage == noImagePath) {
        // プレビューのうち、削除ボタンを削除する
        const deleteButton = document.getElementById('delete_button');
        deleteButton.remove();
    }
    // 選択した画像をinputから削除する
    inputElm.value = '';
    // 再度、現在の画像のURLをフォームに反映
    document.getElementById('existingImage').value = existingImage != '' ? existingImage : null;
    // 画像追加ボタンの表示切替
    toggleAddImageButton();
}

function loadImage(obj, isVertical) {
    // 新しく選択されたファイル
    newImages = Array.from(obj.files);
    // ボタンの表示の初期化
    cropNextButton.innerText = window.Lang.messages.image_cropped_for_button;

    // ファイルを選択しているかの確認
    if (newImages.length === 0) return;
    const selectedFile = newImages[0];

    // 新しい画像をトリミング
    cropImage(selectedFile, isVertical);
}

// 選択した画像をトリミング
function cropImage(file, isVertical) {
    const reader = new FileReader();
    reader.onload = function (e) {
        cropPreview.src = e.target.result;

        // Cropper.jsを初期化
        if (cropper) cropper.destroy();
        cropper = new Cropper(cropPreview, {
            // 長方形のトリミング
            aspectRatio: isVertical ? 3 / 4 : 4 / 3,
            // トリミング領域を画像内に収める
            viewMode: 1
        });
        // モーダルを表示
        cropModal.classList.add('show');
    };
    reader.readAsDataURL(file);
}

// トリミングして次の画像へ
cropNextButton.addEventListener('click', function (event) {
    event.preventDefault();

    if (cropper) {
        const croppedCanvas = cropper.getCroppedCanvas({
            width: 400,
            height: 300
        });

        // トリミング結果をBase64データとして取得
        const croppedImage = croppedCanvas.toDataURL('image/jpeg');
        currentImage = croppedImage;

        // メッセージを表示
        const croppedMessage = document.getElementById('message');
        croppedMessage.textContent = window.Lang.messages.image_cropped;
        croppedMessage.classList.remove('hidden');
        croppedMessage.classList.add('block');
        croppedMessage.style.backgroundColor = categoryColors[croppedMessage.textContent] || '#d1d5db';

        // 3秒後にメッセージを非表示
        setTimeout(() => {
            croppedMessage.classList.add('hidden');
            croppedMessage.classList.remove('block');
        }, 3000);

        // モーダルを閉じる
        cropModal.classList.remove('show');
        // トリミング後の画像をプレビューとして表示
        renderImage(currentImage, resetImage, true);
    }
});

// キャンセルボタンの動作
cropCancelButton.addEventListener('click', () => {
    if (cropper) cropper.destroy();
    cropModal.classList.remove('show');
    resetImage();
});

function updateInputElement() {
    const dataTransfer = new DataTransfer();
    if (typeof currentImage === 'string') {
        // Base64文字列をFileオブジェクトに変換
        const arr = currentImage.split(',');
        // MIMEタイプを取得
        const mime = arr[0].match(/:(.*?);/)[1];
        // Base64データをデコード
        const bstr = atob(arr[1]);
        let n = bstr.length;
        const u8arr = new Uint8Array(n);
        while (n--) {
            u8arr[n] = bstr.charCodeAt(n);
        }

        // Fileオブジェクトを作成
        const file = new File([u8arr], `cropped-image.jpg`, { type: mime });
        dataTransfer.items.add(file);
    } else if (currentImage instanceof File) {
        // File型の場合そのまま追加
        dataTransfer.items.add(currentImage);
    }

    // 選択されたファイルを反映
    inputElm.files = dataTransfer.files;
}

function toggleAddImageButton() {
    const addImageButton = document.getElementById('add-image-button');

    // 画像があるなら非表示、それ以外は表示
    if (currentImage != noImagePath) {
        addImageButton.style.display = 'none';
    } else {
        addImageButton.style.display = 'inline-flex';
    }
}