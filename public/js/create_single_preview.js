// 元々選択されているファイルのリスト
let selectedImages = [];
const inputElm = document.getElementById('inputElm');
const cropModal = document.getElementById('crop-modal');
const cropPreview = document.getElementById('crop-preview');
const cropNextButton = document.getElementById('crop-next-button');
const cropCancelButton = document.getElementById('crop-cancel-button');
const preview = document.getElementById('preview');
let cropper;
let newImages = [];
let currentIndex = 0;

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
        selectedImages.push(croppedImage);

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
        renderPreviews();
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
    // 選択している画像の枚数を表示する
    countImages();

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
}

function removeImage(index) {
    // 選択済みファイルリストから該当インデックスのファイルを削除
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
    const imageCount = selectedImages.length;
    countText.textContent = window.Lang.messages.image_count.replace(':count', imageCount);
    count.appendChild(countText);
}