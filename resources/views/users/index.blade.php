<x-app-layout>
    <h1>登録メンバー</h1>
    <!-- 検索機能 -->
    <div class=serch>
        <form action="{{ route('users.index') }}" method="GET">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="キーワードを検索" aria-label="検索...">
            <input type="submit" value="検索">
        </form>
        <div class="cancel">
            <a href="{{ route('users.index') }}">キャンセル</a>
        </div>
    </div>
    <div class='users'>
        @if ($users->isEmpty())
            <h2 class='no_result'>結果がありません。</h2>
        @else
            @foreach ($users as $user)
                <div class='user'>
                    <h2 class='name'>
                        <!-- 自分のアカウントを選択した場合 -->
                        @if ($auth_user_id === $user->id)
                            <a href="{{ route('profile.index') }}">
                                {{ $user->name }}
                            </a>
                        @else
                            <a href="{{ route('users.show', ['user_id' => $user->id]) }}">
                                {{ $user->name }}
                            </a>
                        @endif
                    </h2>
                    <div class='user_image'>
                        @if ($user->image)
                            <div>
                                <img src="{{ $user->image }}" alt="画像が読み込めません。"
                                    class="w-40 h-40 rounded-full object-cover mr-1">
                            </div>
                        @else
                            <div>
                                <img src="https://res.cloudinary.com/dnumegejl/image/upload/v1732628038/No_User_Image_wulbjv.png"
                                    alt="画像が読み込めません。" class="w-40 h-40 rounded-full object-cover mr-1">
                            </div>
                        @endif
                    </div>
                    <!-- フォロー機能 -->
                    <!-- ログインしているアカウントのid取得 -->
                    <div id="auth_user_id" auth-user-id="{{ Auth::id() }}"></div>
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
            @endforeach
        @endif
    </div>
    <div class='paginate'>
        {{ $users->appends(request()->query())->links() }}
    </div>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('/js/follow_user.js') }}"></script>
</x-app-layout>
