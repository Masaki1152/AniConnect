<div class="like">
    <!-- ボタンの見た目は後のデザイン作成の際に設定する予定 -->
    <button id="like_button" type="submit"
        @foreach ($dataTypeAttributes as $key => $value)
        data-{{ $key }}="{{ $value }}" @endforeach>
        {{ $post->users->contains(auth()->user()) ? __('common.unlike_action') : __('common.like_action') }}
    </button>
    <div class="like_user">
        <a href="{{ $likeCountUrl }}">
            <p id="like_count">{{ $post->users->count() }}</p>
        </a>
    </div>
</div>
