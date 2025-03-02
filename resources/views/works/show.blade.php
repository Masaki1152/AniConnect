<x-app-layout>
    <div id="message"
        class="hidden fixed top-[15%] left-1/2 transform -translate-x-1/2 bg-green-500/50 text-white px-6 py-3 rounded-lg shadow-lg flex items-center space-x-4 z-50">
    </div>

    <h1 class="title">
        {{ $work->name }}
    </h1>
    <div class="content">
        <div class="content__post">
            <h3>作品名</h3>
            <p>{{ $work->name }}</p>
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
            <h3>放映期間</h3>
            <p>{{ $work->term }}</p>
            <div class='creator'>
                <h3>制作会社</h3>
                <a
                    href="{{ route('creator.show', ['creator_id' => $work->creator->id]) }}">{{ $work->creator->name }}</a>
            </div>
            <div class='music'>
                <h3>楽曲</h3>
                @if ($work->music->isEmpty())
                    <h3 class='no_music'>結果がありません。</h3>
                @else
                    @foreach ($work->music as $music)
                        <div class='music_name'>
                            <a href="{{ route('music.show', ['music_id' => $music->id]) }}">
                                {{ $music->name }}
                            </a>
                        </div>
                    @endforeach
                @endif
            </div>
            <div class='character'>
                <h3>登場人物</h3>
                @if ($work->characters->isEmpty())
                    <h3 class='no_character'>結果がありません。</h3>
                @else
                    @foreach ($work->characters as $character)
                        <div class='character_name'>
                            <a href="{{ route('characters.show', ['character_id' => $character->id]) }}">
                                {{ $character->name }}
                            </a>
                        </div>
                    @endforeach
                @endif
            </div>
            <div class='anime_pilgrimage'>
                <h3>聖地</h3>
                @if ($work->animePilgrimages->isEmpty())
                    <h3 class='no_anime_pilgrimage'>結果がありません。</h3>
                @else
                    @foreach ($work->animePilgrimages as $anime_pilgrimage)
                        <div class='anime_pilgrimage_name'>
                            <a href="{{ route('pilgrimages.show', ['pilgrimage_id' => $anime_pilgrimage->id]) }}">
                                {{ $anime_pilgrimage->name }}
                            </a>
                        </div>
                    @endforeach
                @endif
            </div>
            <h3>公式サイトへのリンク</h3>
            <p>{{ $work->official_site_link }}</p>
            <h3>Wikipediaへのリンク</h3>
            <p>{{ $work->wiki_link }}</p>
            <h3>Twitterへのリンク</h3>
            <p>{{ $work->twitter_link }}</p>
            <x-interested type="works" :root="$work" path="work.interested.index" :prop="['work_id' => $work->id]" />
            <a href="{{ route('work_reviews.index', ['work_id' => $work->id]) }}">作品感想一覧</a>
        </div>
    </div>
    <div class="work_story_link">
        <a href="{{ route('work_stories.index', ['work_id' => $work->id]) }}">あらすじ一覧</a>
    </div>
    <div class="footer">
        <a href="/works">作品一覧へ</a>
    </div>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('/js/interested_user.js') }}"></script>
</x-app-layout>
