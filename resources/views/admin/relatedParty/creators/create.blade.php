<x-app-layout>
    <div id="message"
        class="hidden fixed top-[15%] left-1/2 transform -translate-x-1/2 text-white px-6 py-3 rounded-lg shadow-lg flex items-center space-x-4 z-50">
    </div>

    <div class="container mx-auto px-4 py-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl lg:max-w-2xl">
                    <h2 class="text-lg font-medium text-gray-900">{{ __('admin.creator_registration') }}</h2>
                    <form action="{{ route('admin.creators.store') }}" method="POST" enctype="multipart/form-data"
                        class="mt-6 space-y-6">
                        @csrf
                        <x-molecules.text-field.input-text :inputTextType="\App\Enums\InputTextType::Name" :postType="null"
                            targetTableName="creators" characterMaxLength="200" />
                        <x-molecules.text-field.input-text :inputTextType="\App\Enums\InputTextType::OfficialSiteLink" :postType="null"
                            targetTableName="creators" characterMaxLength="200" />
                        <x-molecules.text-field.input-text :inputTextType="\App\Enums\InputTextType::WikiLink" :postType="null"
                            targetTableName="creators" characterMaxLength="200" />
                        <x-molecules.text-field.input-text :inputTextType="\App\Enums\InputTextType::TwitterLink" :postType="null"
                            targetTableName="creators" characterMaxLength="200" />
                        <!-- 登録ボタン -->
                        <x-molecules.button.post-button buttonText="common.register" />
                    </form>
                </div>
            </div>
        </div>
        <!-- 右側サイドバーブロック -->
        <div class="lg:col-span-1 bg-gray-100 rounded-lg shadow-md p-6">
            <h2 class="text-lg font-bold">サイドバーコンテンツ</h2>
            <ul class="space-y-2">
                <li><a href="#" class="text-blue-500 hover:underline">リンク1</a></li>
                <li><a href="#" class="text-blue-500 hover:underline">リンク2</a></li>
                <li><a href="#" class="text-blue-500 hover:underline">リンク3</a></li>
            </ul>
        </div>
    </div>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('/js/count_character.js') }}"></script>
</x-app-layout>
