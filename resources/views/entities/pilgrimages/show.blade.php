<x-app-layout>
    <div id="message"
        class="hidden fixed top-[15%] left-1/2 transform -translate-x-1/2 bg-green-500/50 text-white px-6 py-3 rounded-lg shadow-lg flex items-center space-x-4 z-50">
    </div>
    <h1 class="title">
        {{ $pilgrimage->name }}
    </h1>
    <div class="content">
        <div class="content__pilgrimage">
            <h3>名前</h3>
            <p>{{ $pilgrimage->name }}</p>
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
                {{-- 関連する作品の数だけ繰り返す --}}
                @foreach ($pilgrimage->works as $pilgrimage_work)
                    <a href="{{ route('works.show', ['work_id' => $pilgrimage_work->id]) }}">
                        {{ $pilgrimage_work->name }}
                    </a>
                @endforeach
            </div>
            <h3>Google Mapsへのリンク</h3>
            <p>{{ $pilgrimage->map_link }}</p>
            <h3>Wikipediaへのリンク</h3>
            <p>{{ $pilgrimage->wiki_link }}</p>
            <x-molecules.button.interested type="animePilgrimage" :root="$pilgrimage" path="pilgrimages.interested.index"
                :prop="['pilgrimage_id' => $pilgrimage->id]" isMultiple="false" />
        </div>
    </div>
    <div class="post_link">
        <a href="{{ route('pilgrimage_posts.index', ['pilgrimage_id' => $pilgrimage->id]) }}">聖地投稿一覧</a>
    </div>
    <div class="footer">
        <a href="/works">作品一覧へ</a>
    </div>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('/js/interested_user.js') }}"></script>
</x-app-layout>
