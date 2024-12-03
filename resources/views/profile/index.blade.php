<x-app-layout>
    <h1 class="title">
        プロフィール
    </h1>
    @if (session('status'))
        <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)"
            class="fixed top-[15%] left-1/2 transform -translate-x-1/2 bg-green-500/50 text-white px-6 py-3 rounded-lg shadow-lg flex items-center space-x-4 z-50">
            <div class="text-white">
                {{ session('status') }}
            </div>
        </div>
    @endif
    <div class="content">
        <div class="content__user_profile">
            <h3>ユーザー名</h3>
            <p>{{ $user->name }}</p>
            <h3>年齢</h3>
            <p>{{ $user->age }}歳</p>
            <h3>性別</h3>
            <p>{{ $user->sex }}</p>
            <h3>紹介文</h3>
            <p>{{ $user->introduction }}</p>
            @if ($user->image)
                <div>
                    <img src="{{ $user->image }}" alt="画像が読み込めません。" class="w-40 h-40 rounded-full object-cover mr-1">
                </div>
            @else
                <div>
                    <img src="https://res.cloudinary.com/dnumegejl/image/upload/v1732628038/No_User_Image_wulbjv.png"
                        alt="画像が読み込めません。" class="w-40 h-40 rounded-full object-cover mr-1">
                </div>
            @endif
        </div>
        <!-- 自分のアカウントの場合 -->
        <div class="auth_follow_user">
            <a href="{{ route('user_follows.indexFollowingUser', ['user_id' => $user->id]) }}">
                <p id="auth_following_count">
                    {{ $user->followings->count() }}
                    フォロー中</p>
            </a>
            <a href="{{ route('user_follows.indexFollowedUser', ['user_id' => $user->id]) }}">
                <p id="auth_followers_count">
                    {{ $user->followers->count() }}
                    フォロワー</p>
            </a>
        </div>
    </div>
    <div>
        <a href="{{ route('profile.edit') }}">プロフィールの編集</a>
        <a href="{{ route('profile.password') }}">パスワードの更新</a>
        <a href="{{ route('profile.delete') }}" class="text-red-500">アカウント削除</a>
    </div>
    <div class="post-buttons flex space-x-4">
        <button class="post-button bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
            data-type="work">作品感想</button>
        <button class="post-button bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
            data-type="workStory">あらすじ感想</button>
        <button class="post-button bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
            data-type="character">登場人物感想</button>
        <button class="post-button bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
            data-type="music">音楽感想</button>
        <button class="post-button bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
            data-type="animePilgrimage">聖地感想</button>
    </div>
    <div id="post-container" class="mt-4">
        <!-- 投稿データの表示 -->
    </div>
    <div id="user_id" data-user-id="{{ $user->id }}"></div>
    <script src="{{ asset('/js/fetch_post.js') }}"></script>
</x-app-layout>
