<x-app-layout>
    <h1 class="title">
        {{ $work_story->sub_title }}
    </h1>
    <!-- 上位3カテゴリー -->
    <h5 class='category flex gap-2'>
        @if (!empty($work_story->category_top_1))
            @foreach ([$work_story->category_top_1, $work_story->category_top_2, $work_story->category_top_3] as $categoryId)
                @if (!empty($categoryId))
                    <span class="bg-blue-500 text-white px-2 py-1 rounded-full text-sm">
                        {{ \App\Models\WorkStoryPostCategory::find($categoryId)->name }}
                    </span>
                @endif
            @endforeach
        @else
            <p>カテゴリー情報がありません。</p>
        @endif
    </h5>
    <div class="content">
        <div class="content__work_story">
            <div class='episode'>
                <h3>話数</h3>
                <p>{{ $work_story->episode }}</p>
            </div>
            <div class='body'>
                <h3>あらすじ(公式サイトより)</h3>
                <a>{{ $work_story->body }}</a>
            </div>
            <div class='official_link'>
                <h3>公式サイトへのリンク</h3>
                <a>{{ $work_story->official_link }}</a>
            </div>
        </div>
    </div>
    <div class="post_link">
        <a
            href="{{ route('work_story_posts.index', ['work_id' => $work_story->work_id, 'work_story_id' => $work_story->id]) }}">あらすじ感想一覧</a>
    </div>
</x-app-layout>
