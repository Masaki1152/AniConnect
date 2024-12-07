<x-app-layout>
    <h1>「{{ $work_story_model->work->name }}」のあらすじ一覧</h1>
    <!-- 検索機能 -->
    <div class=serch>
        <form action="{{ route('work_stories.index', ['work_id' => $work_story_model->work_id]) }}" method="GET">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="キーワードを検索" aria-label="検索...">
            <input type="submit" value="検索">
        </form>
        <div class="cancel">
            <a href="{{ route('work_stories.index', ['work_id' => $work_story_model->work_id]) }}">キャンセル</a>
        </div>
    </div>
    <div>
        <p>あらすじ、話数、内容など何でも検索してみましょう！</p>
    </div>
    <div class='work_stories'>
        @if ($work_stories->isEmpty())
            <h2 class='no_result'>結果がありません。</h2>
        @else
            @foreach ($work_stories as $work_story)
                <div class='work_story'>
                    <h2 class='episode'>
                        {{ $work_story->episode }}
                    </h2>
                    <p class='sub_title'>
                        <a
                            href="{{ route('work_stories.show', ['work_id' => $work_story->work_id, 'work_story_id' => $work_story->id]) }}">
                            {{ $work_story->sub_title }}
                        </a>
                    </p>
                </div>
            @endforeach
        @endif
    </div>
    <div class='paginate'>
        {{ $work_stories->appends(request()->query())->links() }}
    </div>
</x-app-layout>
