<h3 class="my-1 text-base font-semibold text-textColor text-center border-b-2 border-mainColor md:hidden">
    {{ __('entity.main.popularity_' . $itemType . '_department') }}
</h3>
<!-- あらすじの表示 -->
@if ($popularItem['itemType'] == 'work_story')
    <a href="{{ route('work_stories.show', ['work_id' => $popularItem['item']->work_id, $popularItem['itemType'] . '_id' => $popularItem['item']->id]) }}"
        class="mb-3 flex items-center justify-center text-base text-textColor text-center h-[40px] overflow-hidden leading-tight hover:underline">
        <span class="line-clamp-2">「{{ $popularItem['item']->sub_title }}」</span>
    </a>
    <a href="{{ route('works.show', ['work_id' => $popularItem['item']->work_id]) }}"
        class="mb-1 flex items-center justify-center text-xs text-subTextColor text-center h-4 overflow-hidden leading-tight hover:underline">
        <span class="line-clamp-1">{{ $popularItem['item']->work->name }}</span>
    </a>
    <p class="mb-1 flex items-center justify-center text-xs text-subTextColor text-center h-4 overflow-hidden">
        {{ $popularItem['item']->episode . ' ' . __('entity.episode_in_work') }}</p>
@else
    <a href="{{ route($popularItem['itemType'] . 's.show', [$popularItem['itemType'] . '_id' => $popularItem['item']->id]) }}"
        class="mb-1 flex items-center justify-center text-base text-textColor text-center h-[40px] overflow-hidden leading-tight hover:underline">
        <span class="line-clamp-2">{{ $popularItem['item']->name }}</span>
    </a>
    @if ($popularItem['itemType'] == 'character')
        <p class="mb-1 flex items-center justify-center text-xs text-subTextColor text-center h-4">
            {{ __('entity.main.character_in_work') }}</p>
        <a href="{{ route('works.show', ['work_id' => $popularItem['item']->works->first()->id]) }}"
            class="mb-1 flex items-center justify-center text-xs text-subTextColor text-center h-4 overflow-hidden leading-tight hover:underline">
            <span class="line-clamp-1">{{ $popularItem['item']->works->first()->name }}</span>
        </a>
    @endif
@endif
<div class="flex justify-center">
    <x-molecules.evaluation.star-num-detail :starNum="$popularItem['item']->average_star_num" :postNum="null" />
</div>
@if ($popularItem['item']->image)
    <div class="flex flex-col items-center justify-center">
        <div class="mt-2 w-[112px] md:w-[123px] {{ $imageAspect }} overflow-hidden border border-gray-300">
            <img src="{{ $popularItem['item']->image }}" alt="{{ __('common.not_reloaded_images') }}"
                class="w-full h-full object-cover">
        </div>
        <div class="mt-1 text-[8px] md:text-xs text-subTextColor">
            {{ $popularItem['item']->copyright }}
        </div>
    </div>
@endif
