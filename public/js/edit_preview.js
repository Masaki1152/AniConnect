// 元々選択されている画像のリスト
let selectedImages = [];
// 既存の画像URLを保持
let existingImages = [];
// 既存の画像のうち、削除されていない画像のURLを保持
let remainedImages = [];
// 既存の画像のうち、削除された画像のURLを保持
let removedImages = [];
// HTMLのdata属性から既存画像のURLを取得
const existingImagePaths = document.getElementById('existing_image_paths');
const phpVariable = existingImagePaths.dataset.phpVariable;

// 画像トリミングの準備
const inputElm = document.getElementById('inputElm');
const cropModal = document.getElementById('crop-modal');
const cropPreview = document.getElementById('crop-preview');
const cropNextButton = document.getElementById('crop-next-button');
const cropCancelButton = document.getElementById('crop-cancel-button');
const preview = document.getElementById('preview');
let cropper;
let newImages = [];
let currentIndex = 0;

// 編集画面にて、以前画像が選択されていた場合、それらの画像を反映する
// DOMツリー読み取り完了後にイベント発火
document.addEventListener('DOMContentLoaded', function () {
    // 既存の画像を取得
    const ImagePaths = JSON.parse(phpVariable);
    ImagePaths.forEach((path, index) => {
        existingImages.push({
            id: index,
            url: path
        });

        // 既存画像をプレビューとして表示
        renderExistingImages();
    })
    // 削除されていない画像のURLをフォームに反映
    document.getElementById('remainedImages').value = JSON.stringify(existingImages);
});

// 既存画像をプレビューとして表示
function renderExistingImages() {
    // プレビューを初期化
    preview.innerHTML = '';
    // 選択している画像の枚数を表示する
    countImages(existingImages);

    existingImages.forEach(image => {
        const figure = document.createElement('figure');
        figure.setAttribute('id', `existing-img-${image.id}`);
        figure.className = 'relative flex flex-col items-center mb-4';

        const img = document.createElement('img');
        // サーバー上の画像URLを使用
        img.src = image.url;
        img.alt = 'existing preview';
        img.className = 'w-full h-full object-cover rounded-lg border border-gray-300 aspect-w-4 aspect-h-3';

        // 削除ボタン
        const rmBtn = document.createElement('button');
        rmBtn.type = 'button';
        rmBtn.textContent = '削除';
        rmBtn.className = 'bg-red-500 text-white mt-2 px-2 py-1 rounded hover:bg-red-600';
        rmBtn.onclick = function () {
            removeExistingImage(image.id);
        };

        figure.appendChild(img);
        figure.appendChild(rmBtn);
        preview.appendChild(figure);
    });
}

function loadImage(obj) {
    // 新しく選択された画像
    newImages = Array.from(obj.files);
    // ボタンの表示の初期化
    cropNextButton.innerText = window.Lang.common.next;
    // 合計が4枚を超える場合のチェック
    // 元々選択されていた画像と新しい画像、以前保存していた画像の合計を確認
    if (selectedImages.length + newImages.length + existingImages.length > 4) {
        alert(window.Lang.messages.enable_upload_four_images);
        // プレビューを更新し、以前選択していた画像を再表示する
        // 新しく選択していた方の画像は破棄
        renderPreviews();
        return;
    }

    currentIndex = 0;
    // ボタンのテキスト名の表示変更
    if (currentIndex === newImages.length - 1) {
        cropNextButton.innerText = window.Lang.messages.image_cropped_for_button;
    }
    // 新しい画像をトリミング
    if (newImages.length > 0) {
        cropImage(newImages[currentIndex]);
    }
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
            aspectRatio: 4 / 3,
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
        selectedImages.push(croppedImage);

        // 次の画像を読み込む
        currentIndex++;
        if (currentIndex < newImages.length) {
            // ボタンのテキスト名の表示変更
            if (currentIndex === newImages.length - 1) {
                cropNextButton.innerText = window.Lang.messages.image_cropped_for_button;
            }
            cropImage(newImages[currentIndex]);
        } else {
            // メッセージを表示
            const croppedMessage = document.getElementById('message');
            croppedMessage.textContent = window.Lang.messages.all_images_cropped;
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
            renderPreviews();
        }
    }
});

// キャンセルボタンの動作
cropCancelButton.addEventListener('click', () => {
    if (cropper) cropper.destroy();
    cropModal.classList.remove('show');
    // 新しく選択した画像の消去
    if (newImages) {
        newImages.forEach(newImage => {
            selectedImages = selectedImages.filter(selectedImage => selectedImage !== newImage);
        })
    }
    renderPreviews();
});

function renderPreviews() {
    // プレビューを取得後、クリア
    preview.innerHTML = '';

    // 既存画像を表示
    renderExistingImages();
    // 新規追加された画像を表示
    selectedImages.forEach((image, index) => {
        const figure = document.createElement('figure');
        figure.setAttribute('id', `img-${index}`);
        figure.className = 'relative flex flex-col items-center mb-4';

        const img = document.createElement('img');
        img.alt = 'preview';
        img.className = 'w-full h-full object-cover rounded-lg border border-gray-300 aspect-w-4 aspect-h-3';
        // ファイルがBase64文字列かFileオブジェクトかをチェック
        if (typeof image === 'string') {
            img.src = image;
        } else if (image instanceof File) {
            const reader = new FileReader();
            reader.onload = function (e) {
                img.src = e.target.result;
            };
            reader.readAsDataURL(image);
        }

        // 削除ボタン
        const rmBtn = document.createElement('button');
        rmBtn.type = 'button';
        rmBtn.textContent = window.Lang.common.delete;
        rmBtn.className = 'bg-red-500 text-white mt-2 px-2 py-1 rounded hover:bg-red-600';
        rmBtn.onclick = function () {
            removeImage(index);
        };

        figure.appendChild(img);
        figure.appendChild(rmBtn);
        preview.appendChild(figure);
    });

    // 選択しているファイルを反映
    updateInputElement();
};

// 既存画像リストから該当画像を削除
function removeExistingImage(id) {
    const index = existingImages.findIndex(img => img.id === id);
    removedImages.push(existingImages[index]);
    if (index !== -1) {
        existingImages.splice(index, 1);
    }
    // 削除されていない画像のURLをフォームに反映
    document.getElementById('remainedImages').value = JSON.stringify(existingImages);
    // 削除された画像のURLをフォームに反映
    document.getElementById('removedImages').value = JSON.stringify(removedImages);
    // プレビューを再描画
    renderPreviews();
}

function removeImage(index) {
    // 選択済み画像リストから該当インデックスの画像を削除
    selectedImages = selectedImages.filter((_, i) => i !== index);

    // プレビューを再描画
    renderPreviews();
}

function updateInputElement() {
    const dataTransfer = new DataTransfer();
    selectedImages.forEach((image, index) => {
        if (typeof image === 'string') {
            // Base64文字列をFileオブジェクトに変換
            const arr = image.split(',');
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
            const file = new File([u8arr], `cropped-image-${index + 1}.jpg`, { type: mime });
            dataTransfer.items.add(file);
        } else if (image instanceof File) {
            // File型の場合そのまま追加
            dataTransfer.items.add(image);
        }
    });

    // 選択されたファイルを反映
    inputElm.files = dataTransfer.files;
}

// 選択している画像の枚数を表示する
function countImages() {
    const count = document.getElementById('count');
    count.innerHTML = '';
    const countText = document.createElement('p');
    const imageCount = selectedImages.length + existingImages.length;
    countText.textContent = window.Lang.messages.image_count.replace(':count', imageCount);
    count.appendChild(countText);
}