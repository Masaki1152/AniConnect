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
                        <div class="title">
                            <label class="block font-medium text-sm text-gray-700 mb-2">タイトル</label>
                            <input type="text" name="pilgrimage_post[post_title]" placeholder="タイトル"
                                value="{{ old('pilgrimage_post.post_title') }}"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
                                data-max-length="40" data-counter-id="titleCharacterCount"
                                oninput="countCharacter(this)" />
                            <p id="titleCharacterCount" class="mt-1 text-sm text-gray-500"></p>
                            <p class="title__error text-sm text-red-500 mt-1">
                                {{ $errors->first('pilgrimage_post.post_title') }}
                            </p>
                        </div>
                        <div class="star_num">
                            <label class="block font-medium text-sm text-gray-700 mb-2">評価</label>
                            <select name="pilgrimage_post[star_num]"
                                class="block w-1/3 px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                                @php
                                    $numbers = [1 => '★', 2 => '★★', 3 => '★★★', 4 => '★★★★', 5 => '★★★★★'];
                                @endphp
                                @foreach ($numbers as $num => $star)
                                    <option value="{{ $num }}"
                                        @if (old('pilgrimage_post.star_num') == $num) selected @endif>
                                        {{ $star }}
                                    </option>
                                @endforeach
                            </select>
                            @if ($errors->has('pilgrimage_post.star_num'))
                                <p class="mt-2 text-sm text-red-600">{{ $errors->first('pilgrimage_post.star_num') }}
                                </p>
                            @endif
                        </div>
                        <div class="scene">
                            <label class="block font-medium text-sm text-gray-700 mb-2">シーン</label>
                            <input type="text" name="pilgrimage_post[scene]" placeholder="シーン"
                                value="{{ old('pilgrimage_post.scene') }}"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
                                data-max-length="40" data-counter-id="sceneCharacterCount"
                                oninput="countCharacter(this)" />
                            <p id="sceneCharacterCount" class="mt-1 text-sm text-gray-500"></p>
                            <p class="title__error text-sm text-red-500 mt-1">
                                {{ $errors->first('pilgrimage_post.scene') }}
                            </p>
                        </div>
                        <div id="custom-multi-select-container" class="category relative">
                            <label class="block font-medium text-sm text-gray-700 mb-2">カテゴリー（3個まで）</label>
                            <div id="custom-multi-select" tabindex="0" class="w-1/3">
                                <div id="custom-multi-select-list" class="max-h-48 overflow-y-auto">
                                    @php
                                        $selectedCategories = old('pilgrimage_post.categories_array', []);
                                    @endphp
                                    @foreach ($categories as $category)
                                        <div class="custom-option p-2 cursor-pointer @if (in_array($category->id, $selectedCategories)) bg-gray-500 text-white @endif"
                                            data-value="{{ $category->id }}" data-post-type="pilgrimage_post">
                                            {{ $category->name }}
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <!-- 選択された値を格納する -->
                            <div id="selected-categories-container">
                                @foreach ($selectedCategories as $selectedCategory)
                                    <input type="hidden" name="pilgrimage_post[categories_array][]"
                                        value="{{ $selectedCategory }}">
                                @endforeach
                            </div>
                            @if ($errors->has('pilgrimage_post.categories_array'))
                                <p class="category__error text-sm text-red-500 mt-1">
                                    {{ $errors->first('pilgrimage_post.categories_array') }}</p>
                            @endif
                        </div>
                        <div class="body">
                            <label class="block font-medium text-sm text-gray-700 mb-2">内容</label>
                            <textarea name="pilgrimage_post[body]" placeholder="内容を記入してください。"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 h-40"
                                data-max-length="4000" data-counter-id="bodyCharacterCount" oninput="countCharacter(this)">{{ old('pilgrimage_post.body') }}</textarea>
                            <p id="bodyCharacterCount" class="mt-1 text-sm text-gray-500"></p>
                            <p class="text-sm text-red-500 mt-1">{{ $errors->first('pilgrimage_post.body') }}</p>
                        </div>
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
                                class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                                <div class="bg-white p-4 rounded-lg shadow-lg relative w-full max-w-2xl">
                                    <div class="overflow-hidden w-full h-auto">
                                        <img id="crop-preview" class="w-full h-auto object-cover" />
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
                        <div class="flex items-center justify-center">
                            <button type="submit"
                                class="bg-blue-500 text-white py-2 px-4 rounded-lg shadow hover:bg-blue-600 focus:outline-none focus:ring focus:ring-blue-300">投稿する</button>
                        </div>
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
    <script>
        // PHP の Helper 関数で定義した色データを JavaScript に渡す
        const categoryColors = {!! json_encode([
            'すべての画像のトリミングが完了しました' => getCategoryColor('すべての画像のトリミングが完了しました'),
        ]) !!};
    </script>
</x-app-layout>
