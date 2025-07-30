<div class="sm:px-[124px] px-[40px]">
    <h3 class="mt-1 text-base font-semibold text-textColor text-center border-b-2 border-mainColor">
        {{ __('entity.main.popularity_' . $itemType . '_department') }}
    </h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-[72px] justify-items-center mt-3">
        @foreach ($popularItems as $popularItem)
            <div class="data-cell flex flex-col items-center w-[160px] h-full min-h-[280px] justify-start">
                <!-- あらすじの表示 -->
                @if ($itemType == 'work_story')
                    <a href="{{ route('work_stories.show', ['work_id' => $popularItem['item']->work_id, $itemType . '_id' => $popularItem['item']->id]) }}"
                        class="mb-3 flex items-center justify-center text-base text-textColor text-center h-[40px] overflow-hidden leading-tight hover:underline">
                        <span class="line-clamp-2">「{{ $popularItem['item']->sub_title }}」</span>
                    </a>
                    <a href="{{ route('works.show', ['work_id' => $popularItem['item']->work_id]) }}"
                        class="mb-1 flex items-center justify-center text-xs text-subTextColor text-center h-4 overflow-hidden leading-tight hover:underline">
                        <span class="line-clamp-1">{{ $popularItem['item']->work->name }}</span>
                    </a>
                    <p
                        class="mb-1 flex items-center justify-center text-xs text-subTextColor text-center h-4 overflow-hidden">
                        {{ $popularItem['item']->episode . ' ' . __('entity.episode_in_work') }}</p>
                @else
                    <a href="{{ route($itemType . 's.show', [$itemType . '_id' => $popularItem['item']->id]) }}"
                        class="mb-1 flex items-center justify-center text-base text-textColor text-center h-[40px] overflow-hidden leading-tight hover:underline">
                        <span class="line-clamp-2">{{ $popularItem['item']->name }}</span>
                    </a>
                    @if ($itemType == 'character')
                        <p class="mb-1 flex items-center justify-center text-xs text-subTextColor text-center h-4">
                            {{ __('entity.main.character_in_work') }}</p>
                        <a href="{{ route('works.show', ['work_id' => $popularItem['item']->works->first()->id]) }}"
                            class="mb-1 flex items-center justify-center text-xs text-subTextColor text-center h-4 overflow-hidden leading-tight hover:underline">
                            <span class="line-clamp-1">{{ $popularItem['item']->works->first()->name }}</span>
                        </a>
                    @endif
                @endif
                <x-molecules.evaluation.star-num-detail :starNum="$popularItem['item']->average_star_num" :postNum="null" />
                @if ($popularItem['item']->image)
                    <div class="mt-2 flex items-center justify-center overflow-hidden border border-gray-300">
                        <img src="{{ $popularItem['item']->image }}" alt="{{ __('common.not_reloaded_images') }}"
                            class="object-cover {{ $imageAspect }}">
                    </div>
                    <p class="mt-1 text-xs text-subTextColor">
                        {{ $popularItem['item']->copyright }}</p>
                @endif
            </div>
        @endforeach
    </div>
</div>
<div class="flex justify-end">
    <!-- あらすじの場合表示しない -->
    @if ($itemType != 'work_story')
        <a href="{{ route($itemType . 's.index') }}"
            class="my-2 sm:mr-[56px] mr-[40px] text-xs text-linkColor hover:underline">
            {{ __('entity.main.popularity_' . $itemType . '_detail') }}
        </a>
    @endif
</div>
