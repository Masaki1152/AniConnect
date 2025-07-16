<x-app-layout>
    <h1 class="title">{{ __('common.edit_notification_post') }}</h1>
    <div class="content">
        <form action="{{ route('admin.notifications.update', ['notification_id' => $notification->id]) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="title">
                <h2>{{ __('common.title') }}</h2>
                <input type="text" name="notification[title]" placeholder="{{ __('common.title') }}"
                    value="{{ $notification->title }}" />
                <p class="title__error" style="color:red">{{ $errors->first('notification.title') }}</p>
            </div>
            <!-- カテゴリー -->
            <div class="category">
                <h2>{{ __('common.select_category') }}</h2>
                @php
                    // old() が存在すればそれを使用し、なければ過去の保存値を使用
                    $existingCategories = $notification->categories->pluck('id')->toArray();
                    $selectedCategories = old('notification.categories_array', $existingCategories);
                @endphp
                <select name="notification[categories_array][]" multiple>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ in_array($category->id, $selectedCategories) ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @if ($errors->has('notification.categories_array'))
                    <p class="category__error" style="color:red">
                        {{ $errors->first('notification.categories_array') }}</p>
                @endif
            </div>
            <div class="body">
                <h2>{{ __('common.content') }}</h2>
                <textarea name="notification[body]" placeholder="内容を記入してください。">{{ $notification->body }}</textarea>
                <p class="body__error" style="color:red">{{ $errors->first('notification.body') }}</p>
            </div>
            <div class="image">
                <h2>{{ __('common.image_up_to_four') }}</h2>
                @php
                    // 既にファイルが選択されている場合はそれらを表示する
                    $existingImages = [];
                    $numbers = [1, 2, 3, 4];
                    foreach ($numbers as $number) {
                        $image = 'image' . $number;
                        if ($notification->$image) {
                            array_push($existingImages, $notification->$image);
                        }
                    }
                    $existingImages = json_encode($existingImages);
                @endphp
                <div id="existing_image_path" data-php-variable="{{ $existingImages }}"></div>
                <label>
                    <input id="inputElm" type="file" style="display:none" name="images[]" multiple
                        onchange="loadImage(this);">{{ __('common.add_image') }}
                    <div id="count"></div>
                </label>
                <!-- 削除された既存画像のリスト -->
                <input type="hidden" name="removedImages[]" id="removedImages" value="">
                <!-- 削除されず残った既存画像のリスト -->
                <input type="hidden" name="remainedImages[]" id="remainedImages" value="">
                <p class="image__error" style="color:red">{{ $errors->first('images') }}</p>
            </div>
            <!-- プレビュー画像の表示 -->
            <div id="preview" style="width: 300px;"></div>
            <button type="submit">{{ __('common.update_post') }}</button>
        </form>
    </div>
    <div class="footer">
        <a
            href="{{ route('admin.notifications.show', ['notification_id' => $notification->id]) }}">{{ __('common.back_without_update') }}</a>
    </div>
    <script src="{{ asset('/js/edit_preview.js') }}"></script>
</x-app-layout>
