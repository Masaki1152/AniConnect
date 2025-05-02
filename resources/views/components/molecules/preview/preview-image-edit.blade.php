@php
    // 既にファイルが選択されている場合はそれらを表示する
    $existingImages = [];
    $numbers = [1, 2, 3, 4];
    foreach ($numbers as $number) {
        $image = 'image' . $number;
        if ($postType->$image) {
            array_push($existingImages, $postType->$image);
        }
    }
    $existingImages = json_encode($existingImages);
@endphp

<div class="image">
    <label class="block font-medium text-sm text-gray-700 mb-2">{{ __('common.image_up_to_four') }}</label>
    <div id="existing_image_paths" data-php-variable="{{ $existingImages }}"></div>
    <label
        class="inline-flex items-center gap-2 cursor-pointer text-blue-500 bg-blue-20 border-2 border-gray-300 rounded-lg py-1 px-2 hover:bg-blue-50">
        <input id="inputElm" type="file" name="images[]" multiple class="hidden"
            onchange="loadImage(this);"><span>{{ __('common.add_images') }}</span>
    </label>
    <div id="count" class="text-sm text-gray-600 mt-1"></div>
    <x-molecules.preview.crop-image-modal />
    <!-- プレビュー画像の表示 -->
    <div id="preview" class="grid grid-cols-2 gap-4 mt-4"></div>
    <!-- 削除された既存画像のリスト -->
    <input type="hidden" name="removedImages[]" id="removedImages" value="">
    <!-- 削除されず残った既存画像のリスト -->
    <input type="hidden" name="remainedImages[]" id="remainedImages" value="">
    <p class="image__error text-sm text-red-500 mt-1">{{ $errors->first('images') }}</p>
</div>
