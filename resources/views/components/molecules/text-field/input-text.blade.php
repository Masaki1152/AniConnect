@php
    $dataCounterId = $className . '.CharacterCount';
    $fieldName = $postTypeString . '.' . $column;
    $inputValue = is_null($postType) ? old($fieldName) : old($fieldName, $postType->$column);
@endphp

<div class="{{ $className }}">
    <label class="block font-medium text-sm text-gray-700 mb-2">{{ __($titleText) }}</label>
    <input type="text" name="{{ $postTypeString }}[{{ $column }}]"
        placeholder="{{ __($titleText) . __('common.text_placeholder') }}" value="{{ $inputValue }}"
        class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
        data-max-length="{{ $characterMaxLength }}" data-counter-id="{{ $dataCounterId }}"
        oninput="countCharacter(this)" />
    <p id="{{ $dataCounterId }}" class="mt-1 text-sm text-gray-500"></p>
    <p class="text-sm text-red-500 mt-1">
        {{ $errors->first($fieldName) }}
    </p>
</div>
