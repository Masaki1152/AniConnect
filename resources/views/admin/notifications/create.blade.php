<x-app-layout>
    <h1>{{ __('common.new_notification_post') }}</h1>
    <form action="{{ route('admin.notifications.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="title">
            <h2>{{ __('common.title') }}</h2>
            <input type="text" name="notification[title]" placeholder="{{ __('common.title') }}"
                value="{{ old('notification.title') }}" />
            <p class="title__error" style="color:red">{{ $errors->first('notification.title') }}</p>
        </div>
        <!-- カテゴリー -->
        <div class="category">
            <h2>{{ __('common.select_category') }}</h2>
            <select name="notification[categories_array][]" multiple>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @if (in_array($category->id, old('notification.categories_array', []))) selected @endif>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            @if ($errors->has('notification.categories_array'))
                <p class="category__error" style="color:red">{{ $errors->first('notification.categories_array') }}
                </p>
            @endif
        </div>
        <div class="body">
            <h2>{{ __('common.content') }}</h2>
            <textarea name="notification[body]" placeholder="{{ __('common.content_placeholder') }}">{{ old('notification.body') }}</textarea>
            <p class="body__error" style="color:red">{{ $errors->first('notification.body') }}</p>
        </div>
        <div class="image">
            <h2>{{ __('common.image_up_to_four') }}</h2>
            <label>
                <input id="inputElm" type="file" style="display:none" name="images[]" multiple
                    onchange="loadImage(this);">{{ __('common.add_image') }}
            </label>
            <div id="count">{{ __('common.image_num_zero') }}</div>
            <p class="image__error" style="color:red">{{ $errors->first('images') }}</p>
        </div>
        <!-- プレビュー画像の表示 -->
        <div id="preview" style="width: 300px;"></div>
        <button type="submit">{{ __('common.post') }}</button>
    </form>
    <div class="footer">
        <a href="{{ route('admin.notifications.index') }}">{{ __('common.back') }}</a>
    </div>
    <script src="{{ asset('/js/create_preview.js') }}"></script>
</x-app-layout>
