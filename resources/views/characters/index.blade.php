<x-app-layout>
    <h1>登場人物一覧</h1>
    <!-- 検索機能 -->
    <div class=serch>
        <form action="{{ route('characters.index') }}" method="GET">
            <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="キーワードを検索"
                aria-label="検索..."
                class="px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-blue-200">
            <!-- カテゴリー検索機能 -->
            <div>
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
            <input type="submit" value="検索"
                class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400">
        </form>
        <div class="cancel">
            <a href="{{ route('characters.index') }}">キャンセル</a>
        </div>
    </div>
    <div>
        <p>人物名、作品名、声優、制作会社など何でも検索してみましょう！</p>
        <p>各登場人物のカテゴリーは、登録メンバーの皆さんの投稿を元に随時更新されています！</p>
        @if (!empty($character->top_categories_updated_at))
            <p>{{ $character->top_categories_updated_at->format('Y/m/d H:i') }}更新</p>
        @endif
    </div>
    <div class='characters'>
        @if ($characters->isEmpty())
            <h2 class='no_result'>結果がありません。</h2>
        @else
            @foreach ($characters as $character)
                <div class='character'>
                    <h2 class='name'>
                        <a href="{{ route('characters.show', ['character_id' => $character->id]) }}">
                            {{ $character->name }}
                        </a>
                    </h2>
                    <!-- 上位3カテゴリー -->
                    <h5 class='category flex gap-2'>
                        @if (!empty($character->category_top_1))
                            @foreach ([$character->category_top_1, $character->category_top_2, $character->category_top_3] as $categoryId)
                                @if (!empty($categoryId))
                                    <span class="bg-blue-500 text-white px-2 py-1 rounded-full text-sm">
                                        {{ \App\Models\CharacterPostCategory::find($categoryId)->name }}
                                    </span>
                                @endif
                            @endforeach
                        @else
                            <p>カテゴリー情報がありません。</p>
                        @endif
                    </h5>
                    <p class='work'>
                        {{-- 関連する登場作品の数だけ繰り返す --}}
                        @foreach ($character->works as $character_work)
                            <a href="{{ route('works.show', ['work' => $character_work->id]) }}">
                                {{ $character_work->name }}
                            </a>
                        @endforeach
                    </p>
                    <p class='voice_artist'>
                        <a href="{{ route('voice_artist.show', ['voice_artist_id' => $character->voiceArtist->id]) }}">
                            CV:{{ $character->voiceArtist->name }}
                        </a>
                    </p>
                </div>
            @endforeach
        @endif
    </div>
    <div class='paginate'>
        {{ $characters->appends(request()->query())->links() }}
    </div>
    <script src="{{ asset('/js/search_category.js') }}"></script>
</x-app-layout>
