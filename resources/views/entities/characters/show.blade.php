<x-app-layout>
    <div id="message"
        class="hidden fixed top-[15%] left-1/2 transform -translate-x-1/2 bg-green-500/50 text-white px-6 py-3 rounded-lg shadow-lg flex items-center space-x-4 z-50">
    </div>
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
            <h3>登場作品</h3>
            <div class='work'>
                {{-- 関連する登場作品の数だけ繰り返す --}}
                @foreach ($character->works as $character_work)
                    <a href="{{ route('works.show', ['work' => $character_work->id]) }}">
                        {{ $character_work->name }}
                    </a>
                @endforeach
            </div>
            <h3>pixivへのリンク</h3>
            <p>{{ $character->wiki_link }}</p>
            <x-interested type="characters" :root="$character" path="characters.interested.index" :prop="['character_id' => $character->id]"
                isMultiple="false" />
        </div>
    </div>
    <div class="post_link">
        <a href="{{ route('character_posts.index', ['character_id' => $character->id]) }}">登場人物感想一覧</a>
    </div>
    <div class="footer">
        <a href="/works">作品一覧へ</a>
    </div>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('/js/interested_user.js') }}"></script>
</x-app-layout>
