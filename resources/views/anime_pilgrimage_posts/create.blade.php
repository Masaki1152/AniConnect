<x-app-layout>
    <div id="message"
        class="hidden fixed top-[15%] left-1/2 transform -translate-x-1/2 text-white px-6 py-3 rounded-lg shadow-lg flex items-center space-x-4 z-50">
    </div>
    <div class="container mx-auto px-4 py-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl lg:max-w-2xl">
                    <h2 class="text-lg font-medium text-gray-900">「{{ $pilgrimage->name }}」への新規感想投稿</h2>
                    <p class="mt-1 text-sm text-gray-600">聖地の感想を書いてみんなと共有しましょう！</p>
                    <form action="{{ route('pilgrimage_posts.store', ['pilgrimage_id' => $pilgrimage->id]) }}"
                        method="POST" enctype="multipart/form-data" class="mt-6 space-y-6">
                        @csrf
                        <div class="anime_pilgrimage_id">
                            <input type="hidden" name="pilgrimage_post[anime_pilgrimage_id]"
                                value="{{ $pilgrimage->id }}">
                        </div>
                        <x-input-text :inputTextType="\App\Enums\InputTextType::Title" :postType="null" postTypeString="pilgrimage_post"
                            characterMaxLength="40" />
                        <x-star-num-select-box :postType="$pilgrimage" postTypeString="pilgrimage_post" :isCreateType="true" />
                        <x-input-text :inputTextType="\App\Enums\InputTextType::Scene" :postType="null" postTypeString="pilgrimage_post"
                            characterMaxLength="40" />
                        <x-category-select-box :postType="null" postTypeString="pilgrimage_post" :categories="$categories" />
                        <x-body-text-area :postType="null" postTypeString="pilgrimage_post" />
                        <x-preview-image-create />
                        <!-- 投稿ボタン -->
                        <x-post-button buttonText="common.post" />
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
    <script src="{{ asset('/js/create_preview.js') }}"></script>
    <script src="{{ asset('/js/select_multi_category.js') }}"></script>
    <script src="{{ asset('/js/count_character.js') }}"></script>
</x-app-layout>
