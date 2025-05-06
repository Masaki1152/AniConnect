<x-app-layout>
    <div id="message"
        class="hidden fixed top-[15%] left-1/2 transform -translate-x-1/2 bg-green-500/50 text-white px-6 py-3 rounded-lg shadow-lg flex items-center space-x-4 z-50">
    </div>
    <div class="container mx-auto py-8">
        <h1 class="text-3xl font-bold text-center mb-6">作品一覧（管理者用）</h1>
        <a href="{{ route('admin.works.create') }}">新規作品登録</a>
        <!-- 検索機能 -->
        <div class="flex justify-center mb-6">
            <form action="{{ route('admin.works.index') }}" method="GET" class="flex items-center space-x-2">
                <!-- キーワード検索 -->
                <input type="text" name="search" id="search", value="{{ request('search') }}"
                    placeholder="キーワードを検索" aria-label="検索..."
                    class="px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-blue-200">
                <input type="submit" value="検索"
                    class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400">
            </form>
            <div class="ml-4">
                <a href="{{ route('admin.works.index') }}"
                    class="text-blue-500 hover:underline focus:outline-none">キャンセル</a>
            </div>
        </div>

        <!-- 作品リスト -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- 検索結果がない場合 -->
            @if ($works->isEmpty())
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
                @foreach ($works as $work)
                    <div class="p-6 border border-gray-200 rounded-lg shadow-sm">
                        <h2 class="text-xl font-semibold mb-2 text-blue-600">
                            <a href="{{ route('admin.works.show', ['work_id' => $work->id]) }}" class="hover:underline">
                                {{ $work->name }}
                            </a>
                        </h2>
                        @if ($work->image)
                            <div
                                class="relative w-full max-w-md aspect-[3/4] overflow-hidden rounded-md border border-gray-300">
                                <img src="{{ $work->image }}" alt="画像が読み込めません。"
                                    class="absolute inset-0 w-full h-full object-cover">
                            </div>
                            <p>{{ $work->copyright }}</p>
                        @endif
                        <x-molecules.evaluation.star-num-detail :starNum="$work->average_star_num" :postNum="$work->post_num" />
                        <p class="text-gray-600">{{ $work->term }}</p>
                    </div>
                @endforeach
            @endif
        </div>

        <!-- ページネーション -->
        <div class="mt-8">
            {{ $works->appends(request()->query())->links() }}
        </div>
    </div>
</x-app-layout>
