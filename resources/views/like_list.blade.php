<x-app-layout>
    <div class="container mx-auto px-4 py-4">
        <div class="lg:col space-y-2">
            <div class="text-lg font-semibold mb-4">いいねしたメンバー</div>
            <div class='users space-y-4'>
                @if ($users->count() == 0)
                    <h2 class="text-lg text-gray-500">いいねしたメンバーはいません。</h2>
                @else
                    @foreach ($users as $user)
                        <div
                            class='user_cell w-full sm:w-full lg:w-[50%] flex items-start p-1 gap-2 bg-white shadow rounded-lg h-[170px]'>
                            <!-- 左側のブロック -->
                            <div class="left_block w-1/4 h-full pl-2 flex justify-center items-center">
                                <!-- ユーザー画像 -->
                                <div
                                    class='user_image w-34 h-34 rounded-full overflow-hidden flex items-center justify-center'>
                                    @if ($user->image)
                                        <div>
                                            <img src="{{ $user->image }}" alt="画像が読み込めません。"
                                                class="object-cover w-full h-full">
                                        </div>
                                    @else
                                        <div>
                                            <img src="https://res.cloudinary.com/dnumegejl/image/upload/v1732628038/No_User_Image_wulbjv.png"
                                                alt="画像が読み込めません。" class="object-cover w-full h-full">
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <!-- 右側のブロック -->
                            <div class="follow w-3/4 h-full px-4 py-2 flex flex-col justify-between">
                                <!-- 上部のブロック -->
                                <!-- ユーザー名とフォローボタン -->
                                <div class="top_block flex items-center justify-between">
                                    <h2 class='name text-lg font-semibold'>
                                        <!-- 自分のアカウントを選択した場合 -->
                                        @if (Auth::id() === $user->id)
                                            <a href="{{ route('profile.index') }}"
                                                class="text-blue-500 hover:underline">
                                                {{ $user->name }}
                                            </a>
                                        @else
                                            <a href="{{ route('users.show', ['user_id' => $user->id]) }}"
                                                class="text-blue-500 hover:underline">
                                                {{ $user->name }}
                                            </a>
                                        @endif
                                    </h2>
                                    <!-- フォロー機能 -->
                                    <!-- 自分のアカウント以外のみフォローボタンの表示 -->
                                    <div class="ml-auto">
                                        @if (Auth::id() !== $user->id)
                                            <!-- ボタンの見た目は後のデザイン作成の際に設定する予定 -->
                                            <button id="follow_button" user-id="{{ $user->id }}" type="submit"
                                                class="bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600">
                                                {{ Auth::user()->followings->contains($user->id) ? 'フォロー解除' : 'フォローする' }}
                                            </button>
                                        @endif
                                    </div>
                                </div>
                                <!-- フォローされているかの表示 -->
                                <div class="isFollowed">
                                    @if ($user->followings->contains(Auth::id()))
                                        <span class="px-1 py-1 rounded-full text-sm bg-gray-200 text-gray-500">
                                            フォローされています
                                        </span>
                                    @endif
                                </div>
                                <!-- ユーザーの説明文 -->
                                <div class="middle_block overflow-hidden text-ellipsis line-clamp-3 text-sm mt-1"
                                    style="height: 4.5em; line-height: 1.5em;">
                                    {{ $user->introduction }}
                                </div>
                                <!-- 下部のブロック -->
                                <div class="bottom_block mt-2">
                                    <!-- 自分のアカウント以外のみ -->
                                    @if (Auth::id() !== $user->id)
                                        <div class="follow_user flex justify-end text-sm text-black-500 space-x-6">
                                            <a href="{{ route('user_follows.indexFollowingUser', ['user_id' => $user->id]) }}"
                                                class="hover:underline">
                                                <p id="following_count">
                                                    {{ $user->followings->count() }}
                                                    フォロー中</p>
                                            </a>
                                            <a href="{{ route('user_follows.indexFollowedUser', ['user_id' => $user->id]) }}"
                                                class="hover:underline">
                                                <p id="followers_count">
                                                    {{ $user->followers->count() }}
                                                    フォロワー</p>
                                            </a>
                                        </div>
                                    @else
                                        <!-- 自分のアカウントの場合 -->
                                        <div class="auth_follow_user flex justify-end text-sm text-black-500 space-x-6">
                                            <a
                                                href="{{ route('user_follows.indexFollowingUser', ['user_id' => $user->id]) }}">
                                                <p id="auth_following_count">
                                                    {{ $user->followings->count() }}
                                                    フォロー中</p>
                                            </a>
                                            <a
                                                href="{{ route('user_follows.indexFollowedUser', ['user_id' => $user->id]) }}">
                                                <p id="auth_followers_count">
                                                    {{ $user->followers->count() }}
                                                    フォロワー</p>
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('/js/follow_user.js') }}"></script>
</x-app-layout>
