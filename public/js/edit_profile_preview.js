const preview = document.getElementById('preview');
// HTMLのdata属性から既存画像のURLを取得
const existingImagePath = document.getElementById('existing_image_path');
const phpVariable = existingImagePath.dataset.phpVariable;
// 既存の画像を取得
const existingImage = phpVariable;
// 画像がない場合の画像パス
const noImagePath = 'https://res.cloudinary.com/dnumegejl/image/upload/v1732628038/No_User_Image_wulbjv.png';
// 既存の画像を表示
let currentImage = existingImage != '' ? existingImage : noImagePath;

// 画像トリミングの準備
const cropModal = document.getElementById('crop-modal');
const cropPreview = document.getElementById('crop-preview');
const cropButton = document.getElementById('crop-button');
const cropCancelButton = document.getElementById('crop-cancel-button');
let cropper;

// 編集画面にて、以前画像が選択されていた場合、それらの画像を反映する
// DOMツリー読み取り完了後にイベント発火
document.addEventListener('DOMContentLoaded', function () {
    // 現在の画像のURLをフォームに反映
    document.getElementById('existingImage').value = existingImage != '' ? existingImage : null;

    // 現在の画像の表示
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
    img.className = 'w-40 h-40 rounded-full object-cover mr-1';

    const rmBtn = document.createElement('button');
    rmBtn.type = 'button';
    rmBtn.setAttribute('id', 'delete_button');
    rmBtn.textContent = window.Lang.common.delete;
    rmBtn.className = 'px-2 py-1 bg-red-500 text-white rounded hover:bg-red-600 mt-2';
    rmBtn.onclick = function () {
        removeExistingImage();
    };

    figure.appendChild(img);
    figure.appendChild(rmBtn);
    preview.appendChild(figure);
};

// 新しく画像を変えた場合の処理
document.getElementById('image').addEventListener('change', function (event) {

    const file = event.target.files[0];

    if (file) {
        cropImage(file);
        preview.innerHTML = '';
        const reader = new FileReader();

        //reader.onload = function (e) {
        const figure = document.createElement('figure');
        figure.setAttribute('id', 'preview_image');
        figure.className = 'relative flex flex-col items-center mb-4';
        // 画像の更新
        const img = document.createElement('img');
        img.src = currentImage;
        img.alt = 'preview';
        img.className = 'w-40 h-40 rounded-full object-contain mr-1';
        // 画像をリセットボタンの作成
        const rmBtn = document.createElement('button');
        rmBtn.type = 'button';
        rmBtn.textContent = window.Lang.messages.reset_image_change;
        rmBtn.className =
            'px-2 py-1 bg-red-500 text-white rounded hover:bg-red-600 mt-2';
        rmBtn.onclick = function () {
            resetImage();
        };

        figure.appendChild(img);
        figure.appendChild(rmBtn);
        preview.appendChild(figure);
        //};

        reader.readAsDataURL(file);
    }
    // 新規画像が追加されたことがわかるようにする
    document.getElementById('existingImage').value = 'new_Image';
});

// 画像の変更をリセットする
function resetImage() {
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
    // 再度、現在の画像のURLをフォームに反映
    document.getElementById('existingImage').value = existingImage != '' ? existingImage : null;
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
    // 現在の画像のURLを削除してフォームに反映
    document.getElementById('existingImage').value = null;
}

// 選択した画像をトリミング
function cropImage(file) {
    const reader = new FileReader();
    reader.onload = function (e) {
        cropPreview.src = e.target.result;

        // Cropper.jsを初期化
        if (cropper) cropper.destroy();
        cropper = new Cropper(cropPreview, {
            // 正方形のトリミング
            aspectRatio: 1,
            // トリミング領域を画像内に収める
            viewMode: 1,
            ready() {
                // ユーザーアイコン用の場合、circle-cropperクラスを追加
                this.cropper.container.classList.add('circle-cropper');
            }
        });
        // モーダルを表示
        cropModal.classList.add('show');
        // プレビュー画像の更新
        currentImage = e.target.result;
        renderExistingImages(currentImage);
    };
    reader.readAsDataURL(file);
}

// トリミングを完了する
cropButton.addEventListener('click', function (event) {
    event.preventDefault();
    if (cropper) {
        const croppedCanvas = cropper.getCroppedCanvas({
            width: 400,
            height: 400
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
        renderExistingImages(currentImage);

        // 既存のクラスをリセット
        var cropperContainer = document.querySelector('.cropper-container');
        if (cropperContainer) {
            cropperContainer.classList.remove('circle-cropper');
        }

        // トリミングした画像をファイル形式にしてフォームにセットする
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
        const inputImage = document.getElementById('image');
        inputImage.files = dataTransfer.files;
    }
});

// キャンセルボタンの動作
cropCancelButton.addEventListener('click', () => {
    if (cropper) cropper.destroy();
    cropModal.classList.remove('show');
    resetImage();

    // 既存のクラスをリセット
    var cropperContainer = document.querySelector('.cropper-container');
    if (cropperContainer) {
        cropperContainer.classList.remove('circle-cropper');
    }
});