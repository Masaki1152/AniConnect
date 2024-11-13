<x-app-layout>
    <h1>いいねしたユーザー</h1>
    <div class='works'>
        @if($users->count() == 0)
            <h2>いいねしたユーザーはいません。</h2>
        @else
            @foreach ($users as $user)
            <div class='user'>
                <h2 class='name'>
                    <!-- あとでユーザー名タップでプロフィール画面に遷移するリンクを作成予定 -->
                    {{ $user->name }}
                </h2>
            </div>
            @endforeach
        @endif
    </div>
</x-app-layout>