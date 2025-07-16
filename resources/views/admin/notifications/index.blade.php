<x-app-layout>
    @if (session('status'))
        <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)"
            class="fixed top-[15%] left-1/2 transform -translate-x-1/2 bg-red-500/50 text-white px-6 py-3 rounded-lg shadow-lg flex items-center space-x-4 z-50">
            <div class="text-white">
                {{ session('status') }}
            </div>
        </div>
    @endif
    <div id="message"
        class="hidden fixed top-[15%] left-1/2 transform -translate-x-1/2 bg-green-500/50 text-white px-6 py-3 rounded-lg shadow-lg flex items-center space-x-4 z-50">
    </div>

    <h1>{{ __('admin.notification_list') }}</h1>
    <a href="{{ route('admin.notifications.create') }}">{{ __('admin.create_notification') }}</a>
    <!-- 検索機能 -->
    <div class=serch>
        <form action="{{ route('admin.notifications.index') }}" method="GET">
            <!-- キーワード検索 -->
            <input type="text" name="search" id="search", value="{{ request('search') }}"
                placeholder="{{ __('common.search_keyword') }}">
            <!-- カテゴリー検索機能 -->
            <div>
                <button id='toggleCategories' type='button'
                    style="{{ count(request('checkedCategories', [])) > 0 ? 'display: none;' : 'display: inline;' }}">{{ __('common.filter_category') }}</button>
                <button id='closeCategories' type='button'
                    style="{{ count(request('checkedCategories', [])) > 0 ? 'display: inline;' : 'display: none;' }}">{{ __('common.close') }}</button>
                <div id='categoryFilter' style="display: {{ request('checkedCategories') ? 'block' : 'none' }};">
                    <h2>{{ __('common.category') }}</h2>
                    <ul id='categoryList'>
                        @foreach ($categories as $category)
                            <li>
                                <input type="checkbox" class="categoryCheckbox" name="checkedCategories[]"
                                    value="{{ $category->id }}"
                                    {{ in_array($category->id, request('checkedCategories', [])) ? 'checked' : '' }}>
                                <label>{{ $category->name }}</label>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <input type="submit" value="{{ __('common.search') }}">
        </form>
        <div class="cancel">
            <a href="{{ route('admin.notifications.index') }}">{{ __('common.cancel') }}</a>
        </div>
    </div>
    <div class='notifications'>
        <!-- 検索結果がない場合 -->
        @if ($notifications->isEmpty())
            <h2 class="col-span-full text-center text-gray-500 text-lg font-semibold">
                @if (!empty($search))
                    {{ __('common.keyword') }} 「{{ $search }}」
                @endif
                @if (!empty($search) && !empty($selectedCategories))
                    {{ __('common.comma') }}
                @endif
                @if (!empty($selectedCategories))
                    {{ __('common.category') }} 「{{ implode('、', $selectedCategories) }}」
                @endif
                {{ __('common.no_result') }}</p>
            </h2>
        @else
            <!-- 検索結果がある場合 -->
            @if (!empty($search) || !empty($selectedCategories))
                <p class="col-span-full text-center text-gray-700 text-lg font-semibold">
                    @if (!empty($search))
                        {{ __('common.keyword') }} 「{{ $search }}」
                    @endif
                    @if (!empty($search) && !empty($selectedCategories))
                        {{ __('common.comma') }}
                    @endif
                    @if (!empty($selectedCategories))
                        {{ __('common.category') }} 「{{ implode('、', $selectedCategories) }}」
                    @endif
                    {{ __('common.search_result') }}<span
                        class="text-blue-500">{{ $totalResults }}</span>{{ __('common.num') }}
                </p>
            @else
                <p class="col-span-full text-center text-gray-700 text-lg font-semibold">
                    {{ __('common.all_post') }}<span
                        class="text-blue-500">{{ $totalResults }}</span>{{ __('common.num') }}
                </p>
            @endif
            <div class='notification_list'>
                @foreach ($notifications as $notification)
                    <div class='notification_post'>
                        <h2 class='title'>
                            <a
                                href="{{ route('admin.notifications.show', ['notification_id' => $notification->id]) }}">{{ $notification->title }}</a>
                        </h2>
                        <h5 class='category flex gap-2'>
                            @foreach ($notification->categories as $category)
                                <span class="text-white px-2 py-1 rounded-full text-sm"
                                    style="background-color: {{ getCategoryColor($category->name) }};">
                                    {{ $category->name }}
                                </span>
                            @endforeach
                        </h5>
                        <div class='created_at'>
                            <p>{{ $notification->created_at->format('Y/m/d H:i') }}</p>
                        </div>
                        <div class="like">
                            <!-- ボタンの見た目は後のデザイン作成の際に設定する予定 -->
                            <button id="like_button" data-notification-id="{{ $notification->id }}" type="submit">
                                {{ $notification->users->contains(auth()->user()) ? __('common.unlike_action') : __('common.like_action') }}
                            </button>
                            <div class="like_user">
                                <a
                                    href="{{ route('notification_like.index', ['notification_id' => $notification->id]) }}">
                                    <p id="like_count">{{ $notification->users->count() }}</p>
                                </a>
                            </div>
                        </div>
                        <form
                            action="{{ route('admin.notifications.delete', ['notification_id' => $notification->id]) }}"
                            id="form_{{ $notification->id }}" method="post">
                            @csrf
                            @method('DELETE')
                            <button type="button" data-post-id="{{ $notification->id }}"
                                class="delete-button">{{ __('common.delete_notification') }}</button>
                        </form>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
    <div class='paginate'>
        {{ $notifications->appends(request()->query())->links() }}
    </div>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('/js/delete_post.js') }}"></script>
    <script src="{{ asset('/js/admin/like_notification.js') }}"></script>
    <script src="{{ asset('/js/search_category.js') }}"></script>
</x-app-layout>
