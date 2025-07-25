<x-app-layout>
    @if (session('message'))
        <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)"
            class="fixed top-[15%] left-1/2 transform -translate-x-1/2 text-white px-6 py-3 rounded-lg shadow-lg flex items-center space-x-4 z-50"
            style="background-color: {{ getCategoryColor(session('message')) }};">
            <div class="text-white">
                {{ session('message') }}
            </div>
        </div>
    @endif
    <div id="message"
        class="hidden fixed top-[15%] left-1/2 transform -translate-x-1/2 bg-green-500/50 text-white px-6 py-3 rounded-lg shadow-lg flex items-center space-x-4 z-50">
    </div>

    <div class="content">
        <a href="{{ route('admin.creators.edit', ['creator_id' => $creator->id]) }}">{{ __('common.edit') }}</a>
        <div class="content__post">
            <h3>会社名</h3>
            <p>{{ $creator->name }}</p>
            @if ($creator->image)
                <div class="relative w-full max-w-md aspect-[4/3] overflow-hidden rounded-md border border-gray-300">
                    <img src="{{ $creator->image }}" alt="画像が読み込めません。"
                        class="absolute inset-0 w-full h-full object-cover">
                </div>
                <p>{{ $creator->copyright }}</p>
            @endif
            <h3>公式サイトへのリンク</h3>
            <p>{{ $creator->official_site_link }}</p>
            <h3>Wikipediaへのリンク</h3>
            <p>{{ $creator->wiki_link }}</p>
            <h3>Twitterへのリンク</h3>
            <p>{{ $creator->twitter_link }}</p>
            <div class='works'>
                <h3>作品一覧</h3>
                @if ($creator->works->isEmpty())
                    <h3 class='no_work'>結果がありません。</h3>
                @else
                    @foreach ($creator->works as $work)
                        <div class='work_name'>
                            <a href="{{ route('works.show', ['work' => $work->id]) }}">
                                {{ $work->name }}
                            </a>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
    <form action="{{ route('admin.creators.delete', ['creator_id' => $creator->id]) }}" id="form_{{ $creator->id }}"
        method="post">
        @csrf
        @method('DELETE')
        <button type="button" data-post-id="{{ $creator->id }}"
            class="delete-button block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
            データを削除する
        </button>
    </form>
    <div class="footer">
        <a href="/admin/creator">制作会社一覧へ</a>
    </div>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('/js/delete_post.js') }}"></script>
</x-app-layout>
