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
    const preview = document.getElementById('preview');
    // プレビューを初期化
    preview.innerHTML = '';
    // 選択している画像の枚数を表示する
    countImages(existingImages);

    existingImages.forEach(image => {
        const figure = document.createElement('figure');
        figure.setAttribute('id', `existing-img-${image.id}`);
        figure.className = 'relative flex flex-col items-center mb-4';

        // 画像部分の背景
        const imageWrapper = document.createElement('div');
        imageWrapper.className = 'image-wrapper';

        const img = document.createElement('img');
        // サーバー上の画像URLを使用
        img.src = image.url;
        img.alt = 'existing preview';
        img.className = 'img-preview';

        // 画像の比率を計算
        img.onload = function () {
            const imgRatio = img.naturalWidth / img.naturalHeight;
            // 正方形 9rem x 9rem の比率
            const wrapperRatio = 1;

            if (imgRatio > wrapperRatio) {
                // 横長の画像
                img.style.width = '100%';
                img.style.height = 'auto';
            } else {
                // 縦長の画像、または正方形
                img.style.height = '100%';
                img.style.width = 'auto';
            }
        };

        imageWrapper.appendChild(img);
        figure.appendChild(imageWrapper);

        // 削除ボタン
        const rmBtn = document.createElement('button');
        rmBtn.type = 'button';
        rmBtn.textContent = '削除';
        rmBtn.className = 'px-2 py-1 bg-red-500 text-white text-sm rounded hover:bg-red-600';
        rmBtn.onclick = function () {
            removeExistingImage(image.id);
        };

        figure.appendChild(rmBtn);
        preview.appendChild(figure);
    });
}

function loadImage(obj) {
    // 新しく選択された画像
    const newImages = Array.from(obj.files);
    // 合計が4枚を超える場合のチェック
    // 元々選択されていた画像と新しい画像、以前保存していた画像の合計を確認
    if (selectedImages.length + newImages.length + existingImages.length > 4) {
        alert('画像は4枚までアップロード可能です');
        // プレビューを更新し、以前選択していた画像を再表示する
        // 新しく選択していた方の画像は破棄
        renderPreviews();
        return;
    }

    // 新しい画像を選択済みリストに追加
    selectedImages.push(...newImages);

    // プレビューの更新
    renderPreviews();
}

function renderPreviews() {
    // プレビューを取得後、クリア
    const preview = document.getElementById('preview');
    preview.innerHTML = '';

    // 既存画像を表示
    renderExistingImages();
    // 新規追加された画像を表示
    selectedImages.forEach((image, index) => {
        const fileReader = new FileReader();

        fileReader.onload = function (e) {
            const figure = document.createElement('figure');
            figure.setAttribute('id', `img-${index}`);
            figure.className = 'relative flex flex-col items-center mb-4';

            // 画像部分の背景
            const imageWrapper = document.createElement('div');
            imageWrapper.className = 'image-wrapper';

            const img = document.createElement('img');
            img.src = e.target.result;
            img.alt = 'preview';
            img.className = 'img-preview';

            // 画像の比率を計算
            img.onload = function () {
                const imgRatio = img.naturalWidth / img.naturalHeight;
                // 正方形 9rem x 9rem の比率
                const wrapperRatio = 1;

                if (imgRatio > wrapperRatio) {
                    // 横長の画像
                    img.style.width = '100%';
                    img.style.height = 'auto';
                } else {
                    // 縦長の画像、または正方形
                    img.style.height = '100%';
                    img.style.width = 'auto';
                }
            };

            imageWrapper.appendChild(img);
            figure.appendChild(imageWrapper);

            // 削除ボタン
            const rmBtn = document.createElement('button');
            rmBtn.type = 'button';
            rmBtn.textContent = '削除';
            rmBtn.className = 'px-2 py-1 bg-red-500 text-white text-sm rounded hover:bg-red-600';
            rmBtn.onclick = function () {
                removeImage(index);
            };

            figure.appendChild(rmBtn);
            preview.appendChild(figure);
        };

        fileReader.readAsDataURL(image);
    });

    // 選択している画像を反映
    updateInputElement();
}

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
    selectedImages.splice(index, 1);

    // プレビューを再描画
    renderPreviews();
}

function updateInputElement() {
    const dataTransfer = new DataTransfer();
    selectedImages.forEach(image => dataTransfer.items.add(image));

    // 選択された画像を反映
    const inputElm = document.getElementById('inputElm');
    inputElm.files = dataTransfer.files;
}

// 選択している画像の枚数を表示する
function countImages() {
    const count = document.getElementById('count');
    count.innerHTML = '';
    const countText = document.createElement('p');
    const ImageCount = selectedImages.length + existingImages.length;
    countText.textContent = `現在、${ImageCount}枚の画像を選択しています。`;
    count.appendChild(countText);
}