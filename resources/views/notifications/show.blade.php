<x-app-layout>
    <div id="like-message"
        class="hidden fixed top-[15%] left-1/2 transform -translate-x-1/2 bg-green-500/50 text-white px-6 py-3 rounded-lg shadow-lg flex items-center space-x-4 z-50">
    </div>

    <div class="content">
        <div class="content__character_post">
            <h1 class="title">
                {{ $notification->title }}
            </h1>
            <div class="like">
                <!-- ボタンの見た目は後のデザイン作成の際に設定する予定 -->
                <button id="like_button" data-notification-id="{{ $notification->id }}" type="submit">
                    {{ $notification->users->contains(auth()->user()) ? 'いいね取り消し' : 'いいね' }}
                </button>
                <div class="like_user">
                    <a href="{{ route('notification_like.index', ['notification_id' => $notification->id]) }}">
                        <p id="like_count">{{ $notification->users->count() }}</p>
                    </a>
                </div>
            </div>
            <h3>カテゴリー</h3>
            <h5 class='category'>
                @foreach ($notification->categories as $category)
                    <span class="text-white px-2 py-1 rounded-full text-sm"
                        style="background-color: {{ getCategoryColor($category->name) }};">
                        {{ $category->name }}
                    </span>
                @endforeach
            </h5>
            <h3>本文</h3>
            <p>{{ $notification->body }}</p>
            <h3>作成日</h3>
            <p>{{ $notification->created_at->format('Y/m/d H:i') }}</p>
            @foreach ([1, 2, 3, 4] as $number)
                @php
                    $image = 'image' . $number;
                @endphp
                @if ($notification->$image)
                    <div>
                        <a href="{{ $notification->$image }}" data-lightbox="gallery"
                            data-title="{{ '画像' . $number }}">
                            <img src="{{ $notification->$image }}" alt="画像が読み込めません。"
                                class='w-36 h-36 object-cover rounded-md border border-gray-300 mb-2'>
                        </a>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
    <div class="footer">
        <a href="{{ route('notifications.index') }}">戻る</a>
    </div>
    <script src="{{ asset('/js/like_notification.js') }}"></script>
</x-app-layout>
