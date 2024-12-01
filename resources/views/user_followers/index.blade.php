<x-app-layout>
    <h1>「{{ $selected_user->name }}」をフォロー中のユーザー</h1>
    <div class='works'>
        @if ($users->count() == 0)
            <h2>フォローしているユーザーはいません。</h2>
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
                </div>
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
            @endforeach
        @endif
    </div>
</x-app-layout>
