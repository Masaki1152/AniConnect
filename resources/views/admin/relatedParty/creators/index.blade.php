<x-app-layout>
    @if (session('message'))
        <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)"
            class="fixed top-[15%] left-1/2 transform -translate-x-1/2 bg-red-500/50 text-white px-6 py-3 rounded-lg shadow-lg flex items-center space-x-4 z-50">
            <div class="text-white">
                {{ session('message') }}
            </div>
        </div>
    @endif
    <div id="message"
        class="hidden fixed top-[15%] left-1/2 transform -translate-x-1/2 bg-green-500/50 text-white px-6 py-3 rounded-lg shadow-lg flex items-center space-x-4 z-50">
    </div>
    <div class="container mx-auto py-8">
        <h1 class="text-3xl font-bold text-center mb-6">制作会社一覧（管理者用）</h1>
        <a href="{{ route('admin.creators.create') }}">{{ __('admin.creator_registration') }}</a>
        <!-- 検索機能 -->
        <div class="flex justify-center mb-6">
            <form action="{{ route('admin.creators.index') }}" method="GET" class="flex items-center space-x-2">
                <!-- キーワード検索 -->
                <input type="text" name="search" id="search", value="{{ request('search') }}"
                    placeholder="キーワードを検索" aria-label="検索..."
                    class="px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-blue-200">
                <input type="submit" value="検索"
                    class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400">
            </form>
            <div class="ml-4">
                <a href="{{ route('admin.creators.index') }}"
                    class="text-blue-500 hover:underline focus:outline-none">キャンセル</a>
            </div>
        </div>

        <!-- 制作会社リスト -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- 検索結果がない場合 -->
            @if ($creators->isEmpty())
                <h2 class="col-span-full text-center text-gray-500 text-lg font-semibold">
                    @if (!empty($search))
                        キーワード 「{{ $search }}」
                    @endif
                    に一致する結果はありませんでした。</p>
                </h2>
            @else
                <!-- 検索結果がある場合 -->
                @if (!empty($search))
                    <p class="col-span-full text-center text-gray-700 text-lg font-semibold">
                        @if (!empty($search))
                            キーワード 「{{ $search }}」
                        @endif
                        の検索結果：<span class="text-blue-500">{{ $totalResults }}</span>件
                    </p>
                @else
                    <p class="col-span-full text-center text-gray-700 text-lg font-semibold">
                        全作品：<span class="text-blue-500">{{ $totalResults }}</span>件
                    </p>
                @endif
                @foreach ($creators as $creator)
                    <div class="p-6 border border-gray-200 rounded-lg shadow-sm">
                        <h2 class="text-xl font-semibold mb-2 text-blue-600">
                            <a href="{{ route('admin.creators.show', ['creator_id' => $creator->id]) }}"
                                class="hover:underline">
                                {{ $creator->name }}
                            </a>
                        </h2>
                    </div>
                @endforeach
            @endif
        </div>

        <!-- ページネーション -->
        <div class="mt-8">
            {{ $creators->appends(request()->query())->links() }}
        </div>
    </div>
</x-app-layout>
