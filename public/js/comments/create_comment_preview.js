// 各コメントごとの選択画像を保持するマップ
let selectedImagesMap = new Map();
let cropper;
let newImages = [];
let currentIndex = 0;

// コメント画像のプレビューを行う
function loadImage(obj, commentId) {
    // 新しい選択を開始するために、マップのクリア
    selectedImagesMap.set(commentId, []);

    // 新しく選択されたファイル
    newImages = Array.from(obj.files);
    // コメントIDに対応する選択済み画像リストを取得または初期化
    const selectedImages = selectedImagesMap.get(commentId) || [];

    // 合計が4枚を超える場合のチェック
    // 元々選択されていたファイルと新しいファイルの合計を確認
    if (selectedImages.length + newImages.length > 4) {
        alert('画像は4枚までアップロード可能です');
        return;
    }

    currentIndex = 0;
    const cropNextButton = document.getElementById(`crop-next-button-${commentId}`);
    // ボタンのテキスト名の表示変更
    if (currentIndex === newImages.length - 1) {
        cropNextButton.innerText = "トリミング完了";
    }

    // 新しい画像をトリミング
    if (newImages.length > 0) {
        cropImage(newImages[currentIndex], commentId);
    }
}

// 選択した画像をトリミング
function cropImage(file, commentId) {
    const reader = new FileReader();
    reader.onload = function (e) {
        // プレビュー画像に表示
        const cropPreview = document.getElementById(`crop-preview-${commentId}`);
        cropPreview.src = e.target.result;

        // Cropper.jsを初期化
        if (cropper) cropper.destroy();
        cropper = new Cropper(cropPreview, {
            aspectRatio: 4 / 3,
            viewMode: 1,
        });

        // トリミングモーダルを表示
        const cropModal = document.getElementById(`crop-modal-${commentId}`);
        cropModal.classList.remove('hidden');

        // トリミング後の画像を次の画像として処理
        document.getElementById(`crop-next-button-${commentId}`).onclick = () => {
            cropNextButtonClicked(commentId);
        };
        // キャンセルボタン押下時の処理
        document.getElementById(`crop-cancel-button-${commentId}`).onclick = () => {
            cropCancelButtonClicked(commentId);
        };
    };
    reader.readAsDataURL(file);
}

// トリミングして次の画像へ
function cropNextButtonClicked(commentId) {
    if (cropper) {
        const croppedCanvas = cropper.getCroppedCanvas({
            width: 400,
            height: 300,
        });

        // トリミング結果をBase64データとして取得
        const croppedImage = croppedCanvas.toDataURL('image/jpeg');
        // コメントIDに対応する画像リストに保存
        const selectedImages = selectedImagesMap.get(commentId) || [];
        selectedImages.push(croppedImage);
        // 更新後のリストをマップに保存
        selectedImagesMap.set(commentId, selectedImages);

        // 次の画像を処理
        currentIndex++;
        if (currentIndex < newImages.length) {
            // ボタンのテキスト名の表示変更
            if (currentIndex === newImages.length - 1) {
                document.getElementById(`crop-next-button-${commentId}`).innerText = "トリミング完了";
            }
            cropImage(newImages[currentIndex], commentId);
        } else {
            // すべての画像のトリミングが完了した場合
            const croppedMessage = document.getElementById('message');
            croppedMessage.textContent = 'すべての画像のトリミングが完了しました';
            croppedMessage.classList.remove('hidden');
            croppedMessage.classList.add('block');
            croppedMessage.style.backgroundColor = categoryColors[croppedMessage.textContent] || '#d1d5db';

            // 3秒後にメッセージを非表示
            setTimeout(() => {
                croppedMessage.classList.add('hidden');
                croppedMessage.classList.remove('block');
            }, 3000);

            // モーダルを閉じる
            const cropModal = document.getElementById(`crop-modal-${commentId}`);
            cropModal.classList.add('hidden');
            // プレビューを再描画
            renderPreviews(commentId);
        }
    }
}

// キャンセルボタンの動作
function cropCancelButtonClicked(commentId) {
    if (cropper) cropper.destroy();
    const cropModal = document.getElementById(`crop-modal-${commentId}`);
    cropModal.classList.add('hidden');
    // 新しく選択した画像を消去
    newImages = [];
}

function renderPreviews(commentId) {
    // コメントIDに対応する選択済み画像リストを取得
    const selectedImages = selectedImagesMap.get(commentId) || [];

    // プレビューを取得後、クリア
    const preview = document.getElementById(`preview-${commentId}`);
    preview.innerHTML = '';
    // 選択している画像の枚数を表示する
    countImages(commentId);

    selectedImages.forEach((image, index) => {
        const figure = document.createElement('figure');
        figure.setAttribute('id', `img-${commentId}-${index}`);
        figure.className = 'relative flex flex-col items-center mb-4';

        const img = document.createElement('img');
        img.src = image;
        img.alt = 'preview';
        img.className = 'w-full h-full object-cover rounded-lg border border-gray-300 aspect-square';

        // 削除ボタン
        const rmBtn = document.createElement('button');
        rmBtn.type = 'button';
        rmBtn.textContent = '削除';
        rmBtn.className = 'bg-red-500 text-white text-xs mt-2 px-2 py-1 rounded hover:bg-red-600';
        rmBtn.onclick = function () {
            removeImage(commentId, index);
        };

        figure.appendChild(img);
        figure.appendChild(rmBtn);
        preview.appendChild(figure);
    });

    // 選択しているファイルを反映
    updateInputElement(commentId);
}

function removeImage(commentId, index) {
    // コメントIDに対応する選択済み画像リストを取得
    let selectedImages = selectedImagesMap.get(commentId) || [];
    // 選択済みファイルリストから該当インデックスのファイルを削除
    selectedImages = selectedImages.filter((_, i) => i !== index);

    // 更新後のリストをマップに保存
    selectedImagesMap.set(commentId, selectedImages);

    // プレビューを再描画
    renderPreviews(commentId);
}

function updateInputElement(commentId) {
    const selectedImages = selectedImagesMap.get(commentId) || [];
    const dataTransfer = new DataTransfer();
    //selectedImages.forEach(file => dataTransfer.items.add(file));
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
    const inputElm = document.getElementById(`inputElm-${commentId}`);
    inputElm.files = dataTransfer.files;
}

// 選択している画像の枚数を表示する
function countImages(commentId) {
    const selectedImages = selectedImagesMap.get(commentId) || [];
    const count = document.getElementById(`count-${commentId}`);
    count.innerHTML = '';
    count.innerHTML = `現在、${selectedImages.length}枚の画像を選択しています。`;
}