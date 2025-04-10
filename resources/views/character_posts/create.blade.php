<x-app-layout>
    <div id="message"
        class="hidden fixed top-[15%] left-1/2 transform -translate-x-1/2 text-white px-6 py-3 rounded-lg shadow-lg flex items-center space-x-4 z-50">
    </div>
    <div class="container mx-auto px-4 py-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl lg:max-w-2xl">
                    <h2 class="text-lg font-medium text-gray-900">「{{ $character->name }}」への新規感想投稿</h2>
                    <p class="mt-1 text-sm text-gray-600">登場人物の感想を書いてみんなと共有しましょう！</p>
                    <form action="{{ route('character_posts.store', ['character_id' => $character->id]) }}" method="POST"
                        enctype="multipart/form-data" class="mt-6 space-y-6">
                        @csrf
                        <div class="character_id">
                            <input type="hidden" name="character_post[character_id]" value="{{ $character->id }}">
                        </div>
                        <x-input-text :inputTextType="\App\Enums\InputTextType::Title" :postType="null" postTypeString="character_post"
                            characterMaxLength="40" />
                        <x-star-num-select-box :postType="$character" postTypeString="character_post" :isCreateType="true" />
                        <div id="custom-multi-select-container" class="category relative">
                            <label class="block font-medium text-sm text-gray-700 mb-2">カテゴリー（3個まで）</label>
                            <div id="custom-multi-select" tabindex="0" class="w-1/3">
                                <div id="custom-multi-select-list" class="max-h-48 overflow-y-auto">
                                    @php
                                        $selectedCategories = old('character_post.categories_array', []);
                                    @endphp
                                    @foreach ($categories as $category)
                                        <div class="custom-option p-2 cursor-pointer @if (in_array($category->id, $selectedCategories)) bg-gray-500 text-white @endif"
                                            data-value="{{ $category->id }}" data-post-type="character_post">
                                            {{ $category->name }}
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <!-- 選択された値を格納する -->
                            <div id="selected-categories-container">
                                @foreach ($selectedCategories as $selectedCategory)
                                    <input type="hidden" name="character_post[categories_array][]"
                                        value="{{ $selectedCategory }}">
                                @endforeach
                            </div>
                            @if ($errors->has('character_post.categories_array'))
                                <p class="category__error text-sm text-red-500 mt-1">
                                    {{ $errors->first('character_post.categories_array') }}</p>
                            @endif
                        </div>
                        <x-body-text-area :postType="null" postTypeString="character_post" />
                        <div class="image">
                            <label class="block font-medium text-sm text-gray-700 mb-2">画像（4枚まで）</label>
                            <label
                                class="inline-flex items-center gap-2 cursor-pointer text-blue-500 bg-blue-20 border-2 border-gray-300 rounded-lg py-1 px-2 hover:bg-blue-50">
                                <input id="inputElm" type="file" name="images[]" multiple class="hidden"
                                    onchange="loadImage(this);"><span>画像を追加する</span>
                            </label>
                            <div id="count" class="text-sm text-gray-600 mt-1">現在、0枚の画像を選択しています。</div>
                            <!-- 画像トリミング用のモーダルウィンドウ表示 -->
                            <div id="crop-modal"
                                class="crop-modal hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                                <div class="bg-white p-4 rounded-lg shadow-lg relative w-full max-w-2xl">
                                    <div class="overflow-hidden w-full h-auto">
                                        <img id="crop-preview" class="crop-preview w-full h-auto object-cover" />
                                    </div>
                                    <div class="flex justify-end gap-2 mt-4">
                                        <button id="crop-cancel-button" type="button"
                                            class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                                            キャンセル
                                        </button>
                                        <button id="crop-next-button" type="button"
                                            class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                                            次へ
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <!-- プレビュー画像の表示 -->
                            <div id="preview" class="grid grid-cols-2 gap-4 mt-4"></div>
                            <p class="image__error text-sm text-red-500 mt-1">{{ $errors->first('images') }}</p>
                        </div>
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
