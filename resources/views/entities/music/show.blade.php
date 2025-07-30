<x-app-layout>
    <div id="message"
        class="hidden fixed top-[15%] left-1/2 transform -translate-x-1/2 bg-green-500/50 text-white px-6 py-3 rounded-lg shadow-lg flex items-center space-x-4 z-50">
    </div>
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
                @if (!empty($categories))
                    @foreach ($categories as $category)
                        <span class="text-white px-2 py-1 rounded-full text-sm"
                            style="background-color: {{ getCategoryColor($category) }};">
                            {{ $category }}
                        </span>
                    @endforeach
                @else
                    <p>カテゴリー情報がありません。</p>
                @endif
            </h5>
            <div class='work'>
                <h3>登場作品</h3>
                <a href="{{ route('works.show', ['work_id' => $music->work_id]) }}">
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
            <iframe width="560" height="315" src="{{ $music->youtube_link }}" title="YouTube video player"
                frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
            <x-molecules.button.interested type="music" :root="$music" path="music.interested.index"
                :prop="['music_id' => $music->id]" isMultiple="false" />
        </div>
    </div>
    <div class="post_link">
        <a href="{{ route('music_posts.index', ['music_id' => $music->id]) }}">音楽感想一覧</a>
    </div>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('/js/interested_user.js') }}"></script>
</x-app-layout>
