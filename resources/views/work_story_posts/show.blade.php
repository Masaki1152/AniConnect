<x-app-layout>
    @if (session('status'))
        <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)"
            class="fixed top-[15%] left-1/2 transform -translate-x-1/2 bg-green-500/50 text-white px-6 py-3 rounded-lg shadow-lg flex items-center space-x-4 z-50">
            <div class="text-white">
                {{ session('status') }}
            </div>
        </div>
    @endif
    <div id="like-message"
        class="hidden fixed top-[15%] left-1/2 transform -translate-x-1/2 bg-green-500/50 text-white px-6 py-3 rounded-lg shadow-lg flex items-center space-x-4 z-50">
    </div>

    <h1 class="title">
        {{ $work_story_post->post_title }}
    </h1>
    <div class="like">
        <!-- ボタンの見た目は後のデザイン作成の際に設定する予定 -->
        <button id="like_button" data-work-id="{{ $work_story_post->work_id }}"
            data-work_story-id="{{ $work_story_post->sub_title_id }}" data-post-id="{{ $work_story_post->id }}"
            type="submit">
            {{ $work_story_post->users->contains(auth()->user()) ? 'いいね取り消し' : 'いいね' }}
        </button>
        <div class="like_user">
            <a
                href="{{ route('work_story_post_like.index', ['work_id' => $work_story_post->work_id, 'work_story_id' => $work_story_post->sub_title_id, 'work_story_post_id' => $work_story_post->id]) }}">
                <p id="like_count">{{ $work_story_post->users->count() }}</p>
            </a>
        </div>
    </div>
    <div class="content">
        <div class="content__character_post">
            <h3>投稿者</h3>
            <p>{{ $work_story_post->user->name }}</p>
            <h3>作品名</h3>
            <p>{{ $work_story_post->work->name }}</p>
            <h3>話数</h3>
            <p>{{ $work_story_post->workStory->episode }}</p>
            <h3>タイトル</h3>
            <p>{{ $work_story_post->workStory->sub_title }}</p>
            <h3>本文</h3>
            <p>{{ $work_story_post->body }}</p>
            <h3>作成日</h3>
            <p>{{ $work_story_post->created_at }}</p>
            @php
                $numbers = [1, 2, 3, 4];
            @endphp
            @foreach ($numbers as $number)
                @php
                    $image = 'image' . $number;
                @endphp
                @if ($work_story_post->$image)
                    <div>
                        <img src="{{ $work_story_post->$image }}" alt="画像が読み込めません。">
                    </div>
                @endif
            @endforeach
        </div>
    </div>
    <div class="edit">
        <a
            href="{{ route('work_story_posts.edit', ['work_id' => $work_story_post->work_id, 'work_story_id' => $work_story_post->sub_title_id, 'work_story_post_id' => $work_story_post->id]) }}">編集する</a>
    </div>
    <form
        action="{{ route('work_story_posts.delete', ['work_id' => $work_story_post->work_id, 'work_story_id' => $work_story_post->sub_title_id, 'work_story_post_id' => $work_story_post->id]) }}"
        id="form_{{ $work_story_post->id }}" method="post">
        @csrf
        @method('DELETE')
        <button type="button" data-post-id="{{ $work_story_post->id }}" class="delete-button">投稿を削除する</button>
    </form>
    <div class="footer">
        <a
            href="{{ route('work_story_posts.index', ['work_id' => $work_story_post->work_id, 'work_story_id' => $work_story_post->sub_title_id]) }}">戻る</a>
    </div>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('/js/like_posts/like_work_story_post.js') }}"></script>
    <script src="{{ asset('/js/delete_post.js') }}"></script>
</x-app-layout>
