<x-app-layout>
    <h1>登場人物一覧</h1>
    <!-- 検索機能 -->
    <div class=serch>
        <form action="{{ route('characters.index') }}" method="GET">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="キーワードを検索" aria-label="検索...">
            <input type="submit" value="検索">
        </form>
        <div class="cancel">
            <a href="{{ route('characters.index') }}">キャンセル</a>
        </div>
    </div>
    <div>
        <p>人物名、作品名、声優、制作会社など何でも検索してみましょう！</p>
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
</x-app-layout>
