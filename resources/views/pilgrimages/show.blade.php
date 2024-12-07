<x-app-layout>
    <h1 class="title">
        {{ $pilgrimage->name }}
    </h1>
    <div class="content">
        <div class="content__pilgrimage">
            <h3>名前</h3>
            <p>{{ $pilgrimage->name }}</p>
            <div class='work'>
                <h3>登場作品</h3>
                {{-- 関連する作品の数だけ繰り返す --}}
                @foreach ($pilgrimage->works as $pilgrimage_work)
                    <a href="{{ route('works.show', ['work' => $pilgrimage_work->id]) }}">
                        {{ $pilgrimage_work->name }}
                    </a>
                @endforeach
            </div>
            <h3>Google Mapsへのリンク</h3>
            <p>{{ $pilgrimage->map_link }}</p>
            <h3>Wikipediaへのリンク</h3>
            <p>{{ $pilgrimage->wiki_link }}</p>
        </div>
    </div>
    <div class="post_link">
        <a href="{{ route('pilgrimage_posts.index', ['pilgrimage_id' => $pilgrimage->id]) }}">聖地投稿一覧</a>
    </div>
    <div class="footer">
        <a href="/works">作品一覧へ</a>
    </div>
</x-app-layout>
