<div class="{{ $relatedPartyName }} relative">
    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __($titleText) }}</label>
    <input type="text" id="{{ $relatedPartyName }}-name" name="{{ $relatedPartyName }}_name"
        placeholder="{{ __($titleText) . __('common.text_placeholder') }}"autocomplete="off"
        class="w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2">
    <ul id="{{ $relatedPartyName }}-suggestions"
        class="absolute z-10 mt-1 w-full bg-white border border-gray-300 rounded-md shadow-lg hidden max-h-60 overflow-auto">
    </ul>
    <span class="hidden hover:bg-blue-100"></span>
    <input type="hidden" name="{{ $targetTableName }}[{{ $column }}]" id="{{ $relatedPartyName }}-id">
    <div id="{{ $relatedPartyName }}-not-found" class="mt-2 text-sm text-red-600 hidden">
        {{ __('common.creator') . __('common.not_found') }}
        <a id="{{ $relatedPartyName }}-create-link" href="#"
            class="text-blue-600 underline ml-2">{{ __('common.creator') . __('common.add_suggestion') }}</a>
    </div>
</div>
