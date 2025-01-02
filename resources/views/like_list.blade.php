<x-app-layout>
    <div class="container mx-auto px-4 py-4">
        <div class="lg:col space-y-2">
            <div class="text-lg font-semibold mb-4">いいねしたメンバー</div>
            <div class='users space-y-4'>
                @if ($users->count() == 0)
                    <h2 class="text-lg text-gray-500">いいねしたメンバーはいません。</h2>
                @else
                    @foreach ($users as $user)
                        <!-- ユーザーセルの表示 -->
                        @include('users.user_cell', [
                            'user' => $user,
                        ])
                    @endforeach
                @endif
            </div>
        </div>
    </div>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('/js/follow_user.js') }}"></script>
</x-app-layout>
