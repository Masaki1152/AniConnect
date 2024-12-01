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
            <!-- フォロー機能 -->
            <!-- 自分のアカウント以外のみフォローボタンの表示 -->
            <div class="follow">
                @if ($auth_user_id !== $user->id)
                    <!-- ボタンの見た目は後のデザイン作成の際に設定する予定 -->
                    <button id="follow_button" user-id="{{ $user->id }}" type="submit">
                        {{ Auth::user()->followings->contains($user->id) ? 'フォロー解除' : 'フォローする' }}
                    </button>
                    <div class="follow_user">
                        <a href="{{ route('user_follows.indexFollowingUser', ['user_id' => $user->id]) }}">
                            <p id="following_count">
                                {{ $user->followings->count() }}
                                フォロー中</p>
                        </a>
                        <a href="{{ route('user_follows.indexFollowedUser', ['user_id' => $user->id]) }}">
                            <p id="followers_count">
                                {{ $user->followers->count() }}
                                フォロワー</p>
                        </a>
                    </div>
                @else
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
                @endif
            </div>
        </div>
    </div>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('/js/follow_user.js') }}"></script>
</x-app-layout>
