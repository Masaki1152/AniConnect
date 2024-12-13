<x-app-layout>
    <h1 class="title">
        {{ $music->name }}
    </h1>
    <div class="content">
        <div class="content__music">
            <h3>名前</h3>
            <p>{{ $music->name }}</p>
            <div class='singer'>
                <h3>歌手</h3>
                <a href="{{ route('singer.show', ['singer_id' => $music->singer_id]) }}">
                    {{ $music->singer->name }}
                </a>
            </div>
            <!-- 上位3カテゴリー -->
            <h5 class='category flex gap-2'>
                @if (!empty($music->category_top_1))
                    @foreach ([$music->category_top_1, $music->category_top_2, $music->category_top_3] as $categoryId)
                        @if (!empty($categoryId))
                            <span class="bg-blue-500 text-white px-2 py-1 rounded-full text-sm">
                                {{ \App\Models\MusicPostCategory::find($categoryId)->name }}
                            </span>
                        @endif
                    @endforeach
                @else
                    <p>カテゴリー情報がありません。</p>
                @endif
            </h5>
            <div class='work'>
                <h3>登場作品</h3>
                <a href="{{ route('works.show', ['work' => $music->work_id]) }}">
                    {{ $music->work->name }}
                </a>
            </div>
            <div class='lyric_writer'>
                <h3>作詞者</h3>
                <a href="{{ route('lyric_writer.show', ['lyric_writer_id' => $music->lyric_writer_id]) }}">
                    {{ $music->lyricWriter->name }}
                </a>
            </div>
            <div class='composer'>
                <h3>作曲者</h3>
                <a href="{{ route('composer.show', ['composer_id' => $music->composer_id]) }}">
                    {{ $music->composer->name }}
                </a>
            </div>
            <h3>Wikipediaへのリンク</h3>
            <p>{{ $music->wiki_link }}</p>
            <h3>YouTubeのリンク</h3>
            <p>{{ $music->youtube_link }}</p>
        </div>
    </div>
    <div class="post_link">
        <a href="{{ route('music_posts.index', ['music_id' => $music->id]) }}">音楽感想一覧</a>
    </div>
</x-app-layout>
