// 元々選択されているファイルのリスト
let selectedImages = [];

function loadImage(obj) {
    // 新しく選択されたファイル
    const newImages = Array.from(obj.files);

    // 合計が4枚を超える場合のチェック
    // 元々選択されていたファイルと新しいファイルの合計を確認
    if (selectedImages.length + newImages.length > 4) {
        alert('画像は4枚までアップロード可能です');
        // プレビューを更新し、以前選択していたファイルを再表示する
        // 新しく選択していた方のファイルは破棄
        renderPreviews();
        return;
    }

    // 新しいファイルを選択済みリストに追加
    selectedImages.push(...newImages);

    // プレビューの更新
    renderPreviews();
}

function renderPreviews() {
    // プレビューを取得後、クリア
    const preview = document.getElementById('preview');
    preview.innerHTML = '';
    // 選択している画像の枚数を表示する
    countImages(selectedImages);

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
            rmBtn.className = 'rm-btn';
            rmBtn.onclick = function () {
                removeImage(index);
            };

            figure.appendChild(rmBtn);
            preview.appendChild(figure);
        };

        fileReader.readAsDataURL(image);
    });

    // 選択しているファイルを反映
    updateInputElement();
}

function removeImage(index) {
    // 選択済みファイルリストから該当インデックスのファイルを削除
    selectedImages.splice(index, 1);

    // プレビューを再描画
    renderPreviews();
}

function updateInputElement() {
    const dataTransfer = new DataTransfer();
    selectedImages.forEach(image => dataTransfer.items.add(image));

    // 選択されたファイルを反映
    const inputElm = document.getElementById('inputElm');
    inputElm.files = dataTransfer.files;
}

// 選択している画像の枚数を表示する
function countImages() {
    const count = document.getElementById('count');
    count.innerHTML = '';
    const countText = document.createElement('p');
    const ImageCount = selectedImages.length;
    countText.textContent = `現在、${ImageCount}枚の画像を選択しています。`;
    count.appendChild(countText);
}