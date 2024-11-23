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
                    <img src="{{ $user->image }}" alt="画像が読み込めません。">
                </div>
            @else
                <div>
                    <img src="https://res.cloudinary.com/dnumegejl/image/upload/v1732344378/No_User_Image_genl0i.png"
                        alt="画像が読み込めません。">
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
