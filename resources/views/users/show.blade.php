<x-app-layout>
    @if (session('status'))
        <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)"
            class="fixed top-[15%] left-1/2 transform -translate-x-1/2 bg-green-500/50 text-white px-6 py-3 rounded-lg shadow-lg flex items-center space-x-4 z-50">
            <div class="text-white">
                {{ session('status') }}
            </div>
        </div>
    @endif

    <div class="px-4 py-6 flex flex-row gap-6">
        <div class="basis-2/3">
            <!-- プロフィールブロック -->
            <div class="flex flex-col gap-4">
                <div class="title text-xl font-semibold ml-4">
                    プロフィール
                </div>
                <!-- プロフィール詳細ブロック -->
                <div class="bg-white rounded-lg shadow-md">
                    <div class="w-full p-4 border rounded-lg bg-gray-50">
                        @include('users.input_profile', ['user' => $user])
                    </div>
                </div>
                <div class="post_type_button py-2 flex flex-row justify-evenly items-center w-full">
                    <button
                        class="switch-button active rounded-full text-base bg-blue-500 hover:bg-blue-600 text-white inline-block min-w-[180px] text-center py-2 h-10 min-h-[40px]"
                        type="impressions">感想投稿</button>
                    <button
                        class="switch-button rounded-full text-base bg-blue-300 hover:bg-blue-400 text-white inline-block min-w-[180px] text-center py-2 h-10 min-h-[40px]"
                        type="comments">コメント投稿</button>
                    <button
                        class="switch-button rounded-full text-base bg-blue-300 hover:bg-blue-400 text-white inline-block min-w-[180px] text-center py-2 h-10 min-h-[40px]"
                        type="likes">いいねした投稿</button>
                </div>
                <!-- 検索機能 -->
                <div class='search flex flex-row justify-center items-center w-full gap-4'>
                    <div class="search_bar w-full max-w-lg">
                        <form method="GET" onsubmit="return false;" class="flex items-center w-full gap-2">
                            <!-- 検索バー -->
                            <div class="relative w-4/5">
                                <input type="text" id="search-input" name="search" value="{{ request('search') }}"
                                    placeholder="キーワードを入力..." aria-label="検索..."
                                    class="px-4 border border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-blue-200 h-[44px] min-w-[300px] text-base pr-10"
                                    oninput="toggleClearButton()">
                                <button type="button" id="clear-button"
                                    class="absolute right-4 top-1/2 transform -translate-y-1/2  flex items-center justify-center text-gray-600 hover:text-gray-400 focus:outline-none hidden"
                                    onclick="clearSearch()">
                                    ✕
                                </button>
                            </div>
                            <!-- 検索ボタン -->
                            <button type="submit" id="search-button"
                                class="bg-blue-500 text-white rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400 h-[30px] min-w-[60px] text-base"
                                onclick="searchPosts()">検索</button>
                            <select name="post_type" id="select_box"
                                class="px-4 ml-4 border border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 h-[44px] min-w-[200px] text-base"
                                onchange="changePostType(this)">
                                <option value='none'>投稿の種類で絞り込む</option>
                                @php
                                    $numbers = [
                                        'work' => '作品感想',
                                        'workStory' => 'あらすじ感想',
                                        'character' => '登場人物感想',
                                        'music' => '音楽感想',
                                        'animePilgrimage' => '聖地感想',
                                    ];
                                @endphp
                                @foreach ($numbers as $type => $name)
                                    <option value="{{ $type }}"
                                        {{ request('post_type') == $type ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                </div>
                <div id="post-container" class="mt-4 bg-white rounded-lg shadow-md">

                </div>
                <!-- ペジネーション -->
                <div id="pagination-container" class="mt-4 flex justify-center space-x-2">
                    <!-- ページナビゲーションボタンはJavaScriptで動的に生成 -->
                </div>
                <div id="user_id" data-user-id="{{ $user->id }}"></div>
                <div class="cancel mt-2">
                    <a href="{{ route('users.index') }}"
                        class="text-blue-500 hover:underline focus:outline-none">キャンセル</a>
                </div>
                <div class="bg-sky-100">曜ちゃん！</div>
                <div class="bg-sky-200">テキストが入ります</div>
                <div class="bg-sky-500">テキストが入ります</div>
            </div>
        </div>
        <div class="bg-sky-400 basis-1/3">凛ちゃん</div>
    </div>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('/js/fetch_post.js') }}"></script>
    <script src="{{ asset('/js/follow_user.js') }}"></script>
    <script src="{{ asset('/js/search_bar.js') }}"></script>
</x-app-layout>
