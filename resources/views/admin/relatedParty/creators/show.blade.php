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
            <h3>Wikipediaへのリンク</h3>
            <p>{{ $creator->wiki_link }}</p>
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
            投稿を削除する
        </button>
    </form>
    <div class="footer">
        <a href="/admin/creator">制作会社一覧へ</a>
    </div>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('/js/delete_post.js') }}"></script>
</x-app-layout>
