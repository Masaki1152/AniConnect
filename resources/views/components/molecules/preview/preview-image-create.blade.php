<div class="image">
    <label
        class="block font-medium text-sm text-gray-700 mb-2">{{ $isMultiple ? __('common.image_up_to_four') : __('common.image') }}</label>
    <label id="add-image-button"
        class="inline-flex items-center gap-2 cursor-pointer text-blue-500 bg-blue-20 border-2 border-gray-300 rounded-lg py-1 px-2 hover:bg-blue-50">
        <input id="inputElm" type="file" name="{{ $isMultiple ? 'images[]' : 'image' }}"
            {{ $isMultiple ? 'multiple' : '' }} class="hidden"
            onchange="loadImage(this, {{ json_encode($isVertical) }});"><span>{{ __('common.add_images') }}</span>
    </label>
    <div id="count" class="text-sm text-gray-600 mt-1">{{ $isMultiple ? __('common.image_num_zero') : '' }}</div>
    <x-molecules.preview.crop-image-modal />
    <!-- プレビュー画像の表示 -->
    <div id="preview" class="grid grid-cols-2 gap-4 mt-4"></div>
    <p class="image__error text-sm text-red-500 mt-1">{{ $errors->first('images') }}</p>
</div>
