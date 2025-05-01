<x-app-layout>
    <h1 class="title">
        {{ $creator->name }}
    </h1>
    <div class="content">
        <div class="content__creator">
            <h3>会社名</h3>
            <p>{{ $creator->name }}</p>
            <h3>Wikipediaへのリンク</h3>
            <p>{{ $creator->wiki_link }}</p>
            <div class='works'>
                <h3>作品一覧</h3>
                @if($creator->works->isEmpty())
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
</x-app-layout>