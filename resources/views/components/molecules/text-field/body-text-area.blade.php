@php
    $fieldName = $targetTableName . '.body';
@endphp

<div class="body">
    <label class="block font-medium text-sm text-gray-700 mb-2">{{ __('common.content') }}</label>
    <textarea name="{{ $targetTableName }}[body]" placeholder="{{ __('common.content_placeholder') }}"
        class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 h-40"
        data-max-length="4000" data-counter-id="bodyCharacterCount" oninput="countCharacter(this)">{{ is_null($postType) ? old($fieldName) : old($fieldName, $postType->body) }}</textarea>
    <p id="bodyCharacterCount" class="mt-1 text-sm text-gray-500"></p>
    <p class="text-sm text-red-500 mt-1">{{ $errors->first($fieldName) }}</p>
</div>
