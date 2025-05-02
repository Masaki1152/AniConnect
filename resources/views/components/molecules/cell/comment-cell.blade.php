<div id="cell" data-current-page="{{ $currentPage }}" data-last-page="{{ $lastPage }}">
    @if ($posts->isEmpty())
        <p class="text-gray-500">投稿がありません。</p>
    @else
        @foreach ($posts as $post)
            <div class="post p-4">
                <div class="flex flex-col">
                    <div class="flex flex-row items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <img src="{{ $post->user->image ?? 'https://res.cloudinary.com/dnumegejl/image/upload/v1732628038/No_User_Image_wulbjv.png' }}"
                                alt="画像が読み込めません。" class="w-16 h-16 rounded-full object-cover">
                            <div>
                                <!-- 自分のアカウントを選択した場合 -->
                                @if (Auth::id() === $post->user->id)
                                    <a href="{{ route('profile.index') }}" class="font-medium">
                                        {{ $post->user->name }}
                                    </a>
                                @else
                                    <a href="{{ route('users.show', ['user_id' => $post->user->id]) }}"
                                        class="font-medium">
                                        {{ $post->user->name }}
                                    </a>
                                @endif
                                <p class="text-gray-500 text-sm">
                                    {{ $post->created_at->format('Y/m/d H:i') }}</p>
                            </div>
                        </div>
                        <!-- ドロップダウンメニュー -->
                        <x-atom.dropdown align="right" class='ml-auto'>
                            <x-slot name="trigger">
                                <button class="p-1 bg-slate-400 text-white rounded hover:bg-slate-500">
                                    コメントを管理する
                                </button>
                            </x-slot>
                            <x-slot name="content">

                            </x-slot>
                        </x-atom.dropdown>
                    </div>
                </div>
                {{ $post->postType }}
                <p class="text-gray-700">{!! nl2br(e($post->body)) !!}</p>
            </div>
        @endforeach
    @endif
</div>
