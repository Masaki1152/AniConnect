<x-app-layout>
    <div class="py-10">
        <div class="w-full max-w-[960px] mx-auto px-4 lg:px-0">
            <div class="bg-white main-content px-4 flex-col gap-6">
                <div class="introduction">
                    <h2 class="text-2xl font-bold text-textColor">{{ __('entity.main.introduction_title') }}</h2>
                    <p class="mt-4 text-base font-medium text-textColor border-2 border-mainColor px-6 py-3 rounded-xl">
                        {!! __('entity.main.introduction_description') !!}</p>
                    <div class="mt-2 flex justify-end">
                        <a href="{{ route('notifications.index') }}" class="text-xs text-linkColor hover:underline">
                            {{ __('entity.main.introduction_detail') }}
                        </a>
                    </div>
                </div>
                <div class="notifications">
                    <h2>お知らせ</h2>
                    <ul>
                        @foreach ($notifications as $notification)
                            <li><a
                                    href="{{ route('notifications.show', ['notification_id' => $notification->id]) }}">{{ $notification->title }}</a>
                            </li>
                            <li>{{ $notification->created_at->format('Y/m/d H:i') }}</li>
                        @endforeach
                    </ul>
                    <a href="{{ route('notifications.index') }}">お知らせ一覧へ</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
