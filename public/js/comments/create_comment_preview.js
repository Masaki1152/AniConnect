// 各コメントごとの選択画像を保持するマップ
let selectedImagesMap = new Map();

// コメント画像のプレビューを行う
function loadImage(obj, commentId) {

    // 新しく選択されたファイル
    const newImages = Array.from(obj.files);
    // コメントIDに対応する選択済み画像リストを取得または初期化
    const selectedImages = selectedImagesMap.get(commentId) || [];

    // 合計が4枚を超える場合のチェック
    // 元々選択されていたファイルと新しいファイルの合計を確認
    if (selectedImages.length + newImages.length > 4) {
        alert('画像は4枚までアップロード可能です');
        // プレビューを更新し、以前選択していたファイルを再表示する
        // 新しく選択していた方のファイルは破棄
        renderPreviews(commentId);
        return;
    }

    // 新しいファイルを選択済みリストに追加
    selectedImages.push(...newImages);
    // 更新後のリストをマップに保存
    selectedImagesMap.set(commentId, selectedImages);

    // プレビューの更新
    renderPreviews(commentId);
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
        const fileReader = new FileReader();

        fileReader.onload = function (e) {
            const figure = document.createElement('figure');
            figure.setAttribute('id', `img-${commentId}-${index}`);
            figure.className = 'relative flex flex-col items-center mb-4';

            const img = document.createElement('img');
            img.src = e.target.result;
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
        };

        fileReader.readAsDataURL(image);
    });

    // 選択しているファイルを反映
    updateInputElement(commentId);
}

function removeImage(commentId, index) {
    // コメントIDに対応する選択済み画像リストを取得
    const selectedImages = selectedImagesMap.get(commentId) || [];
    // 選択済みファイルリストから該当インデックスのファイルを削除
    selectedImages.splice(index, 1);

    // 更新後のリストをマップに保存
    selectedImagesMap.set(commentId, selectedImages);

    // プレビューを再描画
    renderPreviews(commentId);
}

function updateInputElement(commentId) {
    const selectedImages = selectedImagesMap.get(commentId) || [];
    const dataTransfer = new DataTransfer();
    selectedImages.forEach(image => dataTransfer.items.add(image));

    // 選択されたファイルを反映
    const inputElm = document.getElementById(`inputElm-${commentId}`);
    //console.log(inputElm.files.length);
    inputElm.files = dataTransfer.files;
}

// 選択している画像の枚数を表示する
function countImages(commentId) {
    const selectedImages = selectedImagesMap.get(commentId) || [];
    const count = document.getElementById(`count-${commentId}`);
    count.innerHTML = '';
    count.innerHTML = `現在、${selectedImages.length}枚の画像を選択しています。`;
}