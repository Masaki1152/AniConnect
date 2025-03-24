<div class="star_num">
    <label class="block font-medium text-sm text-gray-700 mb-2">{{ __('common.evaluation') }}</label>
    <select name="{{ $postTypeString }}[star_num]"
        class="block w-1/3 px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
        @php
            $numbers = [1 => '★', 2 => '★★', 3 => '★★★', 4 => '★★★★', 5 => '★★★★★'];
        @endphp
        @foreach ($numbers as $num => $star)
            @php
                $isSelected = $isCreateType ? old("{$postType}.star_num") == $num : $postType->star_num == $num;
            @endphp

            <option value="{{ $num }}" @if ($isSelected) selected @endif>
                {{ $star }}
            </option>
        @endforeach
    </select>
    @if ($errors->has('{ $postTypeString }.star_num'))
        <p class="mt-2 text-sm text-red-600">{{ $errors->first('{$postTypeString}.star_num') }}
        </p>
    @endif
</div>
