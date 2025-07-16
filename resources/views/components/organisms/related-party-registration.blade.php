@php
    $isCreate = is_null($postType);
@endphp
@if ($isCreate)
    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
        <div class="max-w-xl lg:max-w-2xl">
            <h2 class="text-lg font-medium text-gray-900">{{ __('admin.' . $relatedPartyName . '_registration') }}</h2>
            <form action="{{ route('admin.' . $targetTableName . '.store') }}" method="POST" enctype="multipart/form-data"
                class="mt-6 space-y-6">
                @csrf
                <x-molecules.text-field.input-text :inputTextType="\App\Enums\InputTextType::Name" :postType="null"
                    targetTableName="{{ $targetTableName }}" characterMaxLength="200" />
                <x-molecules.preview.preview-image-create :isMultiple="false" :isVertical="false" />
                <x-molecules.text-field.input-text :inputTextType="\App\Enums\InputTextType::Copyright" :postType="null"
                    targetTableName="{{ $targetTableName }}" characterMaxLength="200" />
                <x-molecules.text-field.input-text :inputTextType="\App\Enums\InputTextType::OfficialSiteLink" :postType="null"
                    targetTableName="{{ $targetTableName }}" characterMaxLength="200" />
                <x-molecules.text-field.input-text :inputTextType="\App\Enums\InputTextType::WikiLink" :postType="null"
                    targetTableName="{{ $targetTableName }}" characterMaxLength="200" />
                <x-molecules.text-field.input-text :inputTextType="\App\Enums\InputTextType::TwitterLink" :postType="null"
                    targetTableName="{{ $targetTableName }}" characterMaxLength="200" />
                <!-- 登録ボタン -->
                <x-molecules.button.post-button buttonText="common.register" />
            </form>
        </div>
    </div>
@else
    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
        <div class="max-w-xl lg:max-w-2xl">
            <h2 class="text-lg font-medium text-gray-900">
                {{ __('common.target_edit', ['target' => $postType->name]) }}</h2>
            <form
                action="{{ route('admin.' . $targetTableName . '.update', [$relatedPartyName . '_id' => $postType->id]) }}"
                method="POST" enctype="multipart/form-data" class="mt-6 space-y-6">
                @csrf
                @method('PUT')
                <x-molecules.text-field.input-text :inputTextType="\App\Enums\InputTextType::Name" :postType="$postType"
                    targetTableName="{{ $targetTableName }}" characterMaxLength="200" />
                <x-molecules.preview.preview-image-edit :isMultiple="false" :postType="$postType" :isVertical="false" />
                <x-molecules.text-field.input-text :inputTextType="\App\Enums\InputTextType::Copyright" :postType="$postType"
                    targetTableName="{{ $targetTableName }}" characterMaxLength="200" />
                <x-molecules.text-field.input-text :inputTextType="\App\Enums\InputTextType::OfficialSiteLink" :postType="$postType"
                    targetTableName="{{ $targetTableName }}" characterMaxLength="200" />
                <x-molecules.text-field.input-text :inputTextType="\App\Enums\InputTextType::WikiLink" :postType="$postType"
                    targetTableName="{{ $targetTableName }}" characterMaxLength="200" />
                <x-molecules.text-field.input-text :inputTextType="\App\Enums\InputTextType::TwitterLink" :postType="$postType"
                    targetTableName="{{ $targetTableName }}" characterMaxLength="200" />
                <!-- 登録ボタン -->
                <x-molecules.button.post-button buttonText="common.edit" />
            </form>
        </div>
    </div>
@endif
