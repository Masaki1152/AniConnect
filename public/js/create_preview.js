//document.addEventListener('DOMContentLoaded', () => {
// 元々選択されているファイルのリスト
let selectedImages = [];
const inputElm = document.getElementById('inputElm');
const cropContainer = document.getElementById('crop-container');
const cropPreview = document.getElementById('crop-preview');
const cropNextButton = document.getElementById('crop-next-button');
const preview = document.getElementById('preview');
let cropper;
let newImages = [];
let currentIndex = 0;

function loadImage(obj) {
    // 新しく選択されたファイル
    newImages = Array.from(obj.files);

    // 合計が4枚を超える場合のチェック
    // 元々選択されていたファイルと新しいファイルの合計を確認
    if (selectedImages.length + newImages.length > 4) {
        alert('画像は4枚までアップロード可能です');
        // プレビューを更新し、以前選択していたファイルを再表示する
        // 新しく選択していた方のファイルは破棄
        //renderPreviews();
        return;
    }

    currentIndex = 0;
    // 新しいファイルを選択済みリストに追加
    selectedImages.push(...newImages);
    // 新しい画像をトリミング
    if (newImages.length > 0) {
        cropImage(newImages[currentIndex]);
    }
    // プレビューの更新
    //renderPreviews();
}

// 選択した画像をトリミング
function cropImage(file) {
    const reader = new FileReader();
    reader.onload = function (e) {
        cropPreview.src = e.target.result;
        cropContainer.style.display = 'block';

        // Cropper.jsを初期化
        if (cropper) cropper.destroy();
        cropper = new Cropper(cropPreview, {
            aspectRatio: 1, // 正方形のトリミング
            viewMode: 1,    // トリミング領域を画像内に収める
        });
    };
    reader.readAsDataURL(file);
}

// トリミングして次の画像へ
cropNextButton.addEventListener('click', function (event) {
    event.preventDefault();
    if (cropper) {
        const croppedCanvas = cropper.getCroppedCanvas({
            width: 300,
            height: 300
        });

        // トリミング結果を保存
        const croppedImage = croppedCanvas.toDataURL('image/jpeg');
        selectedImages[currentIndex] = croppedImage;
        console.log('トリミング結果:', croppedImage);

        // 次の画像を読み込む
        currentIndex++;
        if (currentIndex < newImages.length) {
            cropImage(newImages[currentIndex]);
        } else {
            alert('すべての画像のトリミングが完了しました！');
            cropContainer.style.display = 'none';
            renderPreviews();
        }
    }
});

function renderPreviews() {
    // プレビューを取得後、クリア
    preview.innerHTML = '';
    // 選択している画像の枚数を表示する
    countImages();

    selectedImages.forEach((image, index) => {
        //const fileReader = new FileReader();

        //fileReader.onload = function (e) {
        const figure = document.createElement('figure');
        figure.setAttribute('id', `img-${index}`);
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
            removeImage(index);
        };

        figure.appendChild(img);
        figure.appendChild(rmBtn);
        preview.appendChild(figure);
        //};

        //fileReader.readAsDataURL(image);
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
    const ImageCount = selectedImages.length;
    countText.textContent = `現在、${ImageCount}枚の画像を選択しています。`;
    count.appendChild(countText);
}
//});