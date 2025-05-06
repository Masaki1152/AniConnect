<x-app-layout>
    <div id="message"
        class="hidden fixed top-[15%] left-1/2 transform -translate-x-1/2 text-white px-6 py-3 rounded-lg shadow-lg flex items-center space-x-4 z-50">
    </div>
    <div class="container mx-auto px-4 py-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl lg:max-w-2xl">
                    <h2 class="text-lg font-medium text-gray-900">「{{ $character_post->character->name }}」への投稿編集画面</h2>
                    <p class="mt-1 text-sm text-gray-600">登場人物の感想を編集してみんなと共有しましょう！</p>
                    <form
                        action="{{ route('character_posts.update', ['character_id' => $character_post->character_id, 'character_post_id' => $character_post->id]) }}"
                        method="POST" enctype="multipart/form-data" class="mt-6 space-y-6">
                        @csrf
                        @method('PUT')
                        <div class="character_id">
                            <input type="hidden" name="character_post[character_id]"
                                value="{{ $character_post->character_id }}">
                        </div>
                        <div class="user_id">
                            <input type="hidden" name="character_post[user_id]" value="{{ $character_post->user_id }}">
                        </div>
                        <x-molecules.text-field.input-text :inputTextType="\App\Enums\InputTextType::Title" :postType="$character_post"
                            targetTableName="character_post" characterMaxLength="40" />
                        <x-molecules.select-box.star-num-select-box :postType="$character_post" targetTableName="character_post"
                            :isCreateType="false" />
                        <x-molecules.select-box.category-select-box :postType="$character_post" targetTableName="character_post"
                            :categories="$categories" />
                        <x-molecules.text-field.body-text-area :postType="$character_post" targetTableName="character_post" />
                        <x-molecules.preview.preview-image-edit :postType="$character_post" />
                        <!-- 投稿ボタン -->
                        <x-molecules.button.post-button buttonText="common.update_post" />
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
    <script src="{{ asset('/js/edit_preview.js') }}"></script>
    <script src="{{ asset('/js/select_multi_category.js') }}"></script>
    <script src="{{ asset('/js/count_character.js') }}"></script>
</x-app-layout>
