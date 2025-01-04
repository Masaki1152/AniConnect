<x-app-layout>
    <div class="container mx-auto px-4 py-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl lg:max-w-2xl">
                    <h2 class="text-lg font-medium text-gray-900">「{{ $work->name }}」への新規感想投稿</h2>
                    <p class="mt-1 text-sm text-gray-600">作品の感想を書いてみんなと共有しましょう！</p>
                    <form action="{{ route('work_reviews.store', ['work_id' => $work->id]) }}" method="POST"
                        enctype="multipart/form-data" class="mt-6 space-y-6">
                        @csrf
                        <div class="work_id">
                            <input type="hidden" name="work_review[work_id]" value="{{ $work->id }}">
                        </div>
                        <div class="title">
                            <label class="block font-medium text-sm text-gray-700 mb-2">タイトル</label>
                            <input type="text" name="work_review[post_title]" placeholder="タイトル"
                                value="{{ old('work_review.post_title') }}"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
                                data-max-length="25" data-counter-id="titleCharacterCount"
                                oninput="countCharacter(this)" />
                            <p id="titleCharacterCount" class="mt-1 text-sm text-gray-500"></p>
                            <p class="title__error text-sm text-red-500 mt-1">
                                {{ $errors->first('work_review.post_title') }}
                            </p>
                        </div>
                        <div id="custom-multi-select-container" class="category relative">
                            <label class="block font-medium text-sm text-gray-700 mb-2">カテゴリー（3個まで）</label>
                            <div id="custom-multi-select" tabindex="0" class="w-1/3">
                                <div id="custom-multi-select-list" class="max-h-48 overflow-y-auto">
                                    @php
                                        $selectedCategories = old('work_review.categories_array', []);
                                    @endphp
                                    @foreach ($categories as $category)
                                        <div class="custom-option p-2 cursor-pointer @if (in_array($category->id, $selectedCategories)) bg-gray-500 text-white @endif"
                                            data-value="{{ $category->id }}">
                                            {{ $category->name }}
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <!-- 選択された値を格納する -->
                            <div id="selected-categories-container">
                                @foreach ($selectedCategories as $selectedCategory)
                                    <input type="hidden" name="work_review[categories_array][]"
                                        value="{{ $selectedCategory }}">
                                @endforeach
                            </div>
                            @if ($errors->has('work_review.categories_array'))
                                <p class="category__error text-sm text-red-500 mt-1">
                                    {{ $errors->first('work_review.categories_array') }}</p>
                            @endif
                        </div>
                        <div class="body">
                            <label class="block font-medium text-sm text-gray-700 mb-2">内容</label>
                            <textarea name="work_review[body]" placeholder="内容を記入してください。"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 h-40"
                                data-max-length="4000" data-counter-id="bodyCharacterCount" oninput="countCharacter(this)">{{ old('work_review.body') }}</textarea>
                            <p id="bodyCharacterCount" class="mt-1 text-sm text-gray-500"></p>
                            <p class="text-sm text-red-500 mt-1">{{ $errors->first('work_review.body') }}</p>
                        </div>
                        <div class="image">
                            <label class="block font-medium text-sm text-gray-700 mb-2">画像（4枚まで）</label>
                            <label class="flex items-center gap-2 cursor-pointer text-blue-500 hover:underline">
                                <input id="inputElm" type="file" name="images[]" multiple class="hidden"
                                    onchange="loadImage(this);"><span>画像の追加</span>
                            </label>
                            <div id="count" class="text-sm text-gray-600 mt-1">現在、0枚の画像を選択しています。</div>
                            <!-- プレビュー画像の表示 -->
                            <div id="preview" class="grid grid-cols-2 gap-4 mt-4"></div>
                            <p class="image__error text-sm text-red-500 mt-1">{{ $errors->first('images') }}</p>
                        </div>
                        <!-- 投稿ボタン -->
                        <div class="flex items-center  justify-center">
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
</x-app-layout>
