<x-app-layout>
    <h1>聖地一覧</h1>
    <!-- 検索機能 -->
    <div class='serch'>
        <form action="{{ route('pilgrimages.index') }}" method="GET">
            <div class=keword_serch>
                <p>聖地検索</p>
                <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="キーワードを検索"
                    aria-label="検索..."
                    class="px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-blue-200">
                <input type="submit" value="検索"
                    class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <div class='prefecture_serch'>
                <p>県名検索</p>
                <select name="prefecture_search" class="form-control" value="{{ request('prefecture_search') }}">
                    <option value="">未選択</option>
                    @foreach ($prefectures as $id => $prefecture_name)
                        <option value="{{ $id }}" @if ($prefecture_search == $id) selected @endif>
                            {{ $prefecture_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <!-- カテゴリー検索機能 -->
            <div class='category_search'>
                <button id='toggleCategories' type='button'
                    style="{{ count(request('checkedCategories', [])) > 0 ? 'display: none;' : 'display: inline;' }}">カテゴリーで絞り込む</button>
                <button id='closeCategories' type='button'
                    style="{{ count(request('checkedCategories', [])) > 0 ? 'display: inline;' : 'display: none;' }}">閉じる</button>
                <div id='categoryFilter' style="display: {{ request('checkedCategories') ? 'block' : 'none' }};">
                    <h2>カテゴリー</h2>
                    <ul id='categoryList'>
                        @foreach ($categories as $category)
                            <li>
                                <input type="checkbox" class="categoryCheckbox" name="checkedCategories[]"
                                    value="{{ $category->id }}"
                                    {{ in_array($category->id, request('checkedCategories', [])) ? 'checked' : '' }}>
                                <label>{{ $category->name }}</label>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </form>
        <div class="cancel">
            <a href="{{ route('pilgrimages.index') }}">キャンセル</a>
        </div>
    </div>
    <div>
        <p>聖地名、シーン、住所、作品名、関連人物名など何でも検索してみましょう！</p>
        <p>各聖地のカテゴリーは、登録メンバーの皆さんの投稿を元に随時更新されています！</p>
        @if (!empty($pilgrimage->top_categories_updated_at))
            <p>{{ $pilgrimage->top_categories_updated_at->format('Y/m/d H:i') }}更新</p>
        @endif
    </div>
    <div class='pilgrimages'>
        @if ($pilgrimages->isEmpty())
            <h2 class='no_result'>結果がありません。</h2>
        @else
            @foreach ($pilgrimages as $pilgrimage)
                <div class='pilgrimage'>
                    <h2 class='name'>
                        <a href="{{ route('pilgrimages.show', ['pilgrimage_id' => $pilgrimage->id]) }}">
                            {{ $pilgrimage->name }}
                        </a>
                    </h2>
                    <!-- 上位3カテゴリー -->
                    <h5 class='category flex gap-2'>
                        @if (!empty($pilgrimage->category_top_1))
                            @foreach ([$pilgrimage->category_top_1, $pilgrimage->category_top_2, $pilgrimage->category_top_3] as $categoryId)
                                @if (!empty($categoryId))
                                    <span class="bg-blue-500 text-white px-2 py-1 rounded-full text-sm">
                                        {{ \App\Models\AnimePilgrimagePostCategory::find($categoryId)->name }}
                                    </span>
                                @endif
                            @endforeach
                        @else
                            <p>カテゴリー情報がありません。</p>
                        @endif
                    </h5>
                    <p class='work'>
                        {{-- 関連する作品の数だけ繰り返す --}}
                        @foreach ($pilgrimage->works as $pilgrimage_work)
                            <a href="{{ route('works.show', ['work' => $pilgrimage_work->id]) }}">
                                {{ $pilgrimage_work->name }}
                            </a>
                        @endforeach
                    </p>
                    <p class='place'>
                        {{ $pilgrimage->place }}
                    </p>
                </div>
            @endforeach
        @endif
    </div>
    <div class='paginate'>
        {{ $pilgrimages->appends(request()->query())->links() }}
    </div>
    <script src="{{ asset('/js/search_category.js') }}"></script>
</x-app-layout>
