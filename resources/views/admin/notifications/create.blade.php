<x-app-layout>
    <h1>新規お知らせ投稿</h1>
    <form action="{{ route('admin.notifications.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="title">
            <h2>タイトル</h2>
            <input type="text" name="notification[title]" placeholder="タイトル" value="{{ old('notification.title') }}" />
            <p class="title__error" style="color:red">{{ $errors->first('notification.title') }}</p>
        </div>
        <!-- カテゴリー -->
        <div class="body">
            <h2>内容</h2>
            <textarea name="notification[body]" placeholder="内容を記入してください。">{{ old('notification.body') }}</textarea>
            <p class="body__error" style="color:red">{{ $errors->first('notification.body') }}</p>
        </div>
        <div class="image">
            <h2>画像（4枚まで）</h2>
            <label>
                <input id="inputElm" type="file" style="display:none" name="images[]" multiple
                    onchange="loadImage(this);">画像の追加
                <div id="count">現在、0枚の画像を選択しています。</div>
            </label>
            <p class="image__error" style="color:red">{{ $errors->first('images') }}</p>
        </div>
        <!-- プレビュー画像の表示 -->
        <div id="preview" style="width: 300px;"></div>
        <button type="submit">投稿する</button>
    </form>
    <div class="footer">
        <a href="{{ route('admin.notifications.index') }}">戻る</a>
    </div>
    <script src="{{ asset('/js/create_preview.js') }}"></script>
</x-app-layout>
