<div class="flex flex-row gap-4 items-stretch">
    <!-- 左側のブロック -->
    <div class="left_block w-1/3 h-full pl-2 flex flex-col justify-center items-center gap-4">
        <!-- ユーザー画像 -->
        <div
            class='user_image flex-grow w-[280px] h-[280px] rounded-full overflow-hidden flex items-center justify-center border border-gray-400'>
            @if ($user->image)
                <div>
                    <img src="{{ $user->image }}" alt="画像が読み込めません。" class="object-cover w-full h-full">
                </div>
            @else
                <div>
                    <img src="https://res.cloudinary.com/dnumegejl/image/upload/v1732628038/No_User_Image_wulbjv.png"
                        alt="画像が読み込めません。" class="object-cover w-full h-full">
                </div>
            @endif
        </div>
        <div class="flex flex-col gap-2">
            <span
                class="user_recommended_button py-2 rounded-full text-base bg-yellow-500 hover:bg-yellow-600 text-white inline-block min-w-[180px] text-center">
                イチオシを確認！
            </span>
            <span
                class="watch_history_button py-2 rounded-full text-base bg-green-500 hover:bg-green-600 text-white inline-block min-w-[180px] text-center">
                視聴リストを確認！
            </span>
        </div>
    </div>
    <!-- 右側のブロック -->
    <div class="right_block follow w-2/3 px-4 py-2 flex flex-col justify-between">
        <div class="pt-2 flex flex-row items-center justify-between">
            <h2 class='name text-3xl font-semibold'>
                <p class="text-black-500">
                    {{ $user->name }}
                </p>
            </h2>
            <!-- フォロー機能 -->
            <!-- 自分のアカウント以外のみフォローボタンの表示 -->
            <div class="follow_button ml-auto">
                @if (Auth::id() !== $user->id)
                    <!-- ボタンの見た目は後のデザイン作成の際に設定する予定 -->
                    <button id="follow_button" user-id="{{ $user->id }}" type="submit"
                        class="bg-blue-500 text-white text-lg px-4 py-2 rounded hover:bg-blue-600">
                        {{ Auth::user()->followings->contains($user->id) ? 'フォロー解除' : 'フォローする' }}
                    </button>
                @endif
            </div>
        </div>
        <!-- フォローされているかの表示 -->
        <div class="isFollowed px-2 my-4">
            @if ($user->followings->contains(Auth::id()))
                <span class="px-2 py-1 rounded-full text-sm bg-gray-200 text-gray-500">
                    フォローされています
                </span>
            @endif
        </div>
        <!-- イチオシの表示 -->
        <div class="user_recommended px-3 my-2">
            <p>イチオシ！：</p>
        </div>
        <div class="user_introduction mt-4 ml-2 flex-grow">
            <p class="text-lg">
                {!! nl2br(e($user->introduction)) !!}
            </p>
        </div>
        <div class="follow_status">
            @if (Auth::id() !== $user->id)
                <div class="follow_user mt-auto flex justify-end text-lg text-black-500 space-x-6">
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
                <div class="auth_follow_user mt-auto flex justify-end text-lg text-black-500 space-x-6">
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
