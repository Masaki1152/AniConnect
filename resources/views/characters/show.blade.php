<x-app-layout>
    <h1 class="title">
        {{ $character->name }}
    </h1>
    <div class="content">
        <div class="content__character">
            <h3>名前</h3>
            <p>{{ $character->name }}</p>
            <h3>声優</h3>
            <a href="{{ route('voice_artist.show', ['voice_artist_id' => $character->voiceArtist->id]) }}">
                CV:{{ $character->voiceArtist->name }}
            </a>
            <h3>登場作品</h3>
            <div class='work'>
                <a href="{{ route('works.show', ['work' => $character->work->id]) }}">
                    {{ $character->work->name }}
                </a>
            </div>
            <h3>pixivへのリンク</h3>
            <p>{{ $character->wiki_link }}</p>
        </div>
    </div>
    <div class="post_link">
        <a href="{{ route('character_posts.index', ['character_id' => $character->id]) }}">登場人物感想一覧</a>
    </div>
    <div class="footer">
        <a href="/works">作品一覧へ</a>
    </div>
</x-app-layout>