<x-app-layout>
    <div class="py-10">
        <div class="w-full max-w-[960px] mx-auto px-4 lg:px-0">
            <div class="main-content px-4 flex flex-col gap-6">
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
                <div class="popularity">
                    <div class="flex lg:gap-4 gap-2 items-end">
                        <h2 class="text-[22px] font-bold text-textColor">{{ __('entity.main.popularity_title') }}</h2>
                        <p class="mb-1 text-xs text-subTextColor">
                            {{ __('common.updated_at') . (count($topPopularityWorks) > 0 ? $topPopularityWorks[0]['item']->updated_at->format('Y/m/d H:i') : 'N/A') }}
                        </p>
                    </div>
                    <div class="mt-4 border-2 border-mainColor rounded-xl lg:mx-14">
                        <x-organisms.popular-three-items-section :popularItems="$topPopularityWorks" itemType="work" />
                        <x-organisms.popular-three-items-section :popularItems="$topPopularityWorkStories" itemType="work_story" />
                        <x-organisms.popular-three-items-section :popularItems="$topPopularityCharacters" itemType="character" />
                    </div>
                </div>
                <div class="notifications">
                    <h2 class="text-[22px] font-bold text-textColor">{{ __('entity.main.notification_title') }}</h2>
                    <div
                        class="notification_list mt-4 lg:mx-14 border-2 border-mainColor p-4 rounded-xl divide-y divide-mainColor">
                        @foreach ($notifications as $notification)
                            <div class="flex flex-wrap items-center gap-2 py-2">
                                <p class="text-xs text-textColor">
                                    {{ $notification->created_at->format('Y/m/d') }}
                                </p>
                                <a href="{{ route('notifications.show', ['notification_id' => $notification->id]) }}"
                                    class="ml-4 text-xs text-textColor">{{ $notification->title }}
                                </a>
                                @foreach ($notification->categories as $category)
                                    <span class="text-xs text-white px-2 py-1 rounded-full"
                                        style="background-color: {{ getCategoryColor($category->name) }};">
                                        {{ $category->name }}
                                    </span>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-2 lg:mx-14 flex justify-end">
                        <a href="{{ route('notifications.index') }}" class="text-xs text-linkColor hover:underline">
                            {{ __('entity.main.notification_detail') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
