<x-app-layout>
    <div class="container mx-auto py-8">
        <h1 class="text-3xl font-bold text-center mb-6">作品一覧</h1>

        <!-- 検索機能 -->
        <div class="flex justify-center mb-6">
            <form action="{{ route('works.index') }}" method="GET" class="flex items-center space-x-2">
                <input type="text" id="search" name="search" value="{{ request('search') }}" placeholder="キーワードを検索"
                    aria-label="検索..."
                    class="px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-blue-200">
                <input type="submit" value="検索"
                    class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400">
            </form>
            <div class="ml-4">
                <a href="{{ route('works.index') }}" class="text-blue-500 hover:underline focus:outline-none">キャンセル</a>
            </div>
        </div>
        <div>
            <p>あらすじ、制作会社、登場人物、声優、音楽名、歌手、聖地など何でも検索してみましょう！</p>
            <p>各作品のカテゴリーは、登録メンバーの皆さんの投稿を元に随時更新されています！</p>
        </div>

        <!-- 作品リスト -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @if ($works->isEmpty())
                <h2 class="col-span-full text-center text-gray-500 text-xl">結果がありません。</h2>
            @else
                @foreach ($works as $work)
                    <div class="p-6 border border-gray-200 rounded-lg shadow-sm">
                        <h2 class="text-xl font-semibold mb-2 text-blue-600">
                            <a href="{{ route('works.show', ['work' => $work->id]) }}" class="hover:underline">
                                {{ $work->name }}
                            </a>
                        </h2>
                        <!-- 上位3カテゴリー -->
                        <h5 class='category flex gap-2'>
                            @if (!empty($work->topCategories))
                                @foreach ($work->topCategories as $category)
                                    <span class="bg-blue-500 text-white px-2 py-1 rounded-full text-sm">
                                        {{ $category['name'] }}
                                    </span>
                                @endforeach
                            @else
                                <p>カテゴリー情報がありません。</p>
                            @endif
                        </h5>
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
