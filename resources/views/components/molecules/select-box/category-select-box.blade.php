@php
    $existingCategories =
        !empty($postType) && $postType->categories ? $postType->categories->pluck('id')->toArray() : [];
    $selectedCategories = old("{$postTypeString}.categories_array", $existingCategories);
@endphp
<div id="custom-multi-select-container" class="category relative">
    <label class="block font-medium text-sm text-gray-700 mb-2">{{ __('common.select_category') }}</label>
    <div id="custom-multi-select" tabindex="0" class="w-1/3">
        <div id="custom-multi-select-list" class="max-h-48 overflow-y-auto">
            @foreach ($categories as $category)
                <div class="custom-option p-2 cursor-pointer @if (in_array($category->id, $selectedCategories)) bg-gray-500 text-white @endif"
                    data-value="{{ $category->id }}" data-post-type="{{ $postTypeString }}">
                    {{ $category->name }}
                </div>
            @endforeach
        </div>
    </div>
    <!-- 選択された値を格納する -->
    <div id="selected-categories-container">
        @foreach ($selectedCategories as $selectedCategory)
            <input type="hidden" name="{{ $postTypeString }}[categories_array][]" value="{{ $selectedCategory }}">
        @endforeach
    </div>
    @if ($errors->has("{$postTypeString}.categories_array"))
        <p class="category__error text-sm text-red-500 mt-1">
            {{ $errors->first("{$postTypeString}.categories_array") }}</p>
    @endif
</div>
