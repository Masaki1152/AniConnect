<x-app-layout>
    <h1>聖地一覧</h1>
    <!-- 検索機能 -->
    <div class=serch>
        <form action="{{ route('pilgrimages.index') }}" method="GET">
            <div class=keword_serch>
                <p>聖地検索</p>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="キーワードを検索"
                    aria-label="検索...">
                <input type="submit" value="検索">
            </div>
            <div class=prefecture_serch>
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
        </form>
        <div class="cancel">
            <a href="{{ route('pilgrimages.index') }}">キャンセル</a>
        </div>
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
</x-app-layout>
