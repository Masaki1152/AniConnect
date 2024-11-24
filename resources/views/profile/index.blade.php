<x-app-layout>
    <h1 class="title">
        プロフィール
    </h1>
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
                    <img src="{{ $user->image }}" alt="画像が読み込めません。" style="width: 300px;">
                </div>
            @else
                <div>
                    <img src="https://res.cloudinary.com/dnumegejl/image/upload/v1732344378/No_User_Image_genl0i.png"
                        alt="画像が読み込めません。" style="width: 300px;">
                </div>
            @endif
        </div>
    </div>
    <div>
        <a href="{{ route('profile.edit') }}">プロフィールの編集</a>
        <a href="{{ route('profile.password') }}">パスワードの更新</a>
        <a href="{{ route('profile.delete') }}" class="text-red-500">アカウント削除</a>
    </div>
    @if (session('status'))
        <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)"
            class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50 z-50">
            <div class="bg-gray-300 text-white text-lg font-bold py-4 px-6 rounded-lg shadow-lg">
                {{ session('status') }}
            </div>
        </div>
    @endif
</x-app-layout>
