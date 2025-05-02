<x-app-layout>
    <div class="container mx-auto pt-8">
        <h1 class="text-3xl font-bold text-center mb-6">登録メンバー</h1>
        <!-- 検索機能 -->
        <div class='flex justify-center mb-6 gap-2'>
            <form action="{{ route('users.index') }}" method="GET" class="flex items-center w-full max-w-md">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="キーワードを検索"
                    aria-label="検索..."
                    class="px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-blue-200 w-4/5">
                <button type="submit"
                    class="px-4 py-2 ml-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400 w-1/5">検索</button>
            </form>
            <div class="cancel mt-2">
                <a href="{{ route('users.index') }}" class="text-blue-500 hover:underline focus:outline-none">キャンセル</a>
            </div>
        </div>
    </div>
    <div class="container mx-auto px-16 py-4">
        @if ($users->isEmpty())
            <div class="flex justify-center items-center w-full h-[200px]">
                <h2 class='no_result text-center text-gray-500'>結果がありません。</h2>
            </div>
        @else
            <div class="lg:col space-y-2">
                <div class='users grid grid-cols-1 sm:grid-cols-2 gap-y-4 gap-x-0 place-items-center'>
                    @foreach ($users as $user)
                        <!-- ユーザーセルの表示 -->
                        <div
                            class='user_cell w-full sm:w-full lg:w-[96%] flex items-center p-1 gap-2 bg-white shadow rounded-lg h-[170px]'>
                            @include('user_interactions.users.user_cell', ['user' => $user])
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
    <div class='paginate flex justify-center pb-4'>
        {{ $users->appends(request()->query())->links('vendor.pagination.tailwind_custom') }}
    </div>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('/js/follow_user.js') }}"></script>
</x-app-layout>
