<x-app-layout>
    <h1>音楽一覧</h1>
    <!-- 検索機能 -->
    <div class=serch>
        <form action="{{ route('music.index') }}" method="GET">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="キーワードを検索" aria-label="検索...">
            <input type="submit" value="検索">
        </form>
        <div class="cancel">
            <a href="{{ route('music.index') }}">キャンセル</a>
        </div>
    </div>
    <div>
        <p>楽曲名、歌手、作曲者、作詞者、作品名、制作会社など何でも検索してみましょう！</p>
    </div>
    <div class='music_collection'>
        @if ($music->isEmpty())
            <h2 class='no_result'>結果がありません。</h2>
        @else
            @foreach ($music as $music_model)
                <div class='music'>
                    <h2 class='name'>
                        <a href="{{ route('music.show', ['music_id' => $music_model->id]) }}">
                            {{ $music_model->name }}
                        </a>
                    </h2>
                    <p class='work'>
                        <a href="{{ route('works.show', ['work' => $music_model->work_id]) }}">
                            {{ $music_model->work->name }}
                        </a>
                    </p>
                    <p class='singer'>
                        歌手:
                        <a href="{{ route('singer.show', ['singer_id' => $music_model->singer_id]) }}">
                            {{ $music_model->singer->name }}
                        </a>
                    </p>
                </div>
            @endforeach
        @endif
    </div>
    <div class='paginate'>
        {{ $music->appends(request()->query())->links() }}
    </div>
</x-app-layout>
