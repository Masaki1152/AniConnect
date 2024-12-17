<x-app-layout>
    @if (session('status'))
        <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)"
            class="fixed top-[15%] left-1/2 transform -translate-x-1/2 bg-green-500/50 text-white px-6 py-3 rounded-lg shadow-lg flex items-center space-x-4 z-50">
            <div class="text-white">
                {{ session('status') }}
            </div>
        </div>
    @endif

    <div class="content">
        <div class="content__character_post">
            <h1 class="title">
                {{ $notification->title }}
            </h1>
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
                        <a href="{{ $notification->$image }}" data-lightbox="gallery" data-title="{{ '画像' . $number }}">
                            <img src="{{ $notification->$image }}" alt="画像が読み込めません。"
                                class='w-36 h-36 object-cover rounded-md border border-gray-300 mb-2'>
                        </a>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
    <div class="edit">
        <a href="{{ route('admin.notifications.edit', ['notification_id' => $notification->id]) }}">編集する</a>
    </div>
    <form action="{{ route('admin.notifications.delete', ['notification_id' => $notification->id]) }}"
        id="form_{{ $notification->id }}" method="post">
        @csrf
        @method('DELETE')
        <button type="button" data-post-id="{{ $notification->id }}" class="delete-button">お知らせを削除する</button>
    </form>
    <div class="footer">
        <a href="{{ route('admin.notifications.index') }}">戻る</a>
    </div>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('/js/delete_post.js') }}"></script>
</x-app-layout>
