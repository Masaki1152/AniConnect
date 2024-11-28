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
                        <a href="{{ route('users.show', ['user_id' => $user->id]) }}">
                            {{ $user->name }}
                        </a>
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
                </div>
            @endforeach
        @endif
    </div>
    <div class='paginate'>
        {{ $users->appends(request()->query())->links() }}
    </div>
</x-app-layout>
