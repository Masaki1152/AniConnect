<x-app-layout>
    @if (session('status'))
        <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)"
            class="fixed top-[15%] left-1/2 transform -translate-x-1/2 bg-red-500/50 text-white px-6 py-3 rounded-lg shadow-lg flex items-center space-x-4 z-50">
            <div class="text-white">
                {{ session('status') }}
            </div>
        </div>
    @endif
    <div id="like-message"
        class="hidden fixed top-[15%] left-1/2 transform -translate-x-1/2 bg-green-500/50 text-white px-6 py-3 rounded-lg shadow-lg flex items-center space-x-4 z-50">
    </div>

    <h1>お知らせ一覧（管理者用）</h1>
    <a href="{{ route('admin.notifications.create') }}">お知らせ作成</a>
    <!-- 検索機能 -->
    <div class='notifications'>
        <!-- 検索結果がない場合 -->
        <!-- 検索結果がある場合 -->
        <div class='notification_list'>
            @foreach ($notifications as $notification)
                <div class='notification_post'>
                    <h2 class='title'>
                        <a
                            href="{{ route('admin.notifications.show', ['notification_id' => $notification->id]) }}">{{ $notification->title }}</a>
                    </h2>
                    <div class='created_at'>
                        <p>{{ $notification->created_at->format('Y/m/d H:i') }}</p>
                    </div>
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
                    <h5 class='category flex gap-2'>
                        @foreach ($notification->categories as $category)
                            <span class="text-white px-2 py-1 rounded-full text-sm"
                                style="background-color: {{ getCategoryColor($category->name) }};">
                                {{ $category->name }}
                            </span>
                        @endforeach
                    </h5>
                    <form action="{{ route('admin.notifications.delete', ['notification_id' => $notification->id]) }}"
                        id="form_{{ $notification->id }}" method="post">
                        @csrf
                        @method('DELETE')
                        <button type="button" data-post-id="{{ $notification->id }}"
                            class="delete-button">お知らせを削除する</button>
                    </form>
                </div>
            @endforeach
        </div>
    </div>
    <div class='paginate'>

    </div>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('/js/delete_post.js') }}"></script>
    <script src="{{ asset('/js/like_notification.js') }}"></script>
</x-app-layout>
