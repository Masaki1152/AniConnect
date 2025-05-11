@php
    // 既にファイルが選択されている場合はそれらを表示する
    $existingImage = $isMultiple ? [] : '';
    // 選択する画像が1枚か複数か
    if ($isMultiple) {
        $numbers = [1, 2, 3, 4];
        foreach ($numbers as $number) {
            $image = 'image' . $number;
            if ($postType->$image) {
                array_push($existingImage, $postType->$image);
            }
        }
        $existingImage = json_encode($existingImage);
    } else {
        if ($postType->image) {
            $existingImage = $postType->image;
        }
    }
@endphp

<div class="image">
    <label
        class="block font-medium text-sm text-gray-700 mb-2">{{ $isMultiple ? __('common.image_up_to_four') : __('common.image') }}</label>
    <div id="existing_image_path" data-php-variable="{{ $existingImage }}"></div>
    <label id="add-image-button"
        class="inline-flex items-center gap-2 cursor-pointer text-blue-500 bg-blue-20 border-2 border-gray-300 rounded-lg py-1 px-2 hover:bg-blue-50">
        <input id="inputElm" type="file" name="{{ $isMultiple ? 'images[]' : 'image' }}"
            {{ $isMultiple ? 'multiple' : '' }} class="hidden"
            onchange="loadImage(this, {{ json_encode($isVertical) }});">
        <span>{{ __('common.add_images') }}</span>
    </label>
    <div id="count" class="text-sm text-gray-600 mt-1"></div>
    <x-molecules.preview.crop-image-modal />
    <!-- プレビュー画像の表示 -->
    <div id="preview" class="grid grid-cols-2 gap-4 mt-4"></div>
    <input type="hidden" name="existingImage" id="existingImage" value="">
    <!-- 削除された既存画像のリスト -->
    <input type="hidden" name="removedImages[]" id="removedImages" value="">
    <!-- 削除されず残った既存画像のリスト -->
    <input type="hidden" name="remainedImages[]" id="remainedImages" value="">
    <p class="image__error text-sm text-red-500 mt-1">{{ $errors->first('images') }}</p>
</div>
