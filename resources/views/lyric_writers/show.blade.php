<x-app-layout>
    <h1 class="title">
        {{ $lyric_writer->name }}
    </h1>
    <div class="content">
        <div class="content__creator">
            <h3>名前</h3>
            <p>{{ $lyric_writer->name }}</p>
            <h3>Wikipediaへのリンク</h3>
            <p>{{ $lyric_writer->wiki_link }}</p>
            <div class='music'>
                <h3>音楽一覧</h3>
                @if($lyric_writer->music->isEmpty())
                <h3 class='no_work'>結果がありません。</h3>
                @else
                @foreach ($lyric_writer->music as $music)
                <div class='music_name'>
                    <a href="{{ route('music.show', ['music_id' => $music->id]) }}">
                        {{ $music->name }}
                    </a>
                </div>
                @endforeach
                @endif
            </div>
        </div>
    </div>
</x-app-layout>