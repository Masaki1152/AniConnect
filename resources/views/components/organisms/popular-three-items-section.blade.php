<div class="sm:px-[124px] px-[40px]">
    <h3 class="mt-1 text-base font-semibold text-textColor text-center border-b-2 border-mainColor">
        {{ __('entity.main.popularity_work_department') }}
    </h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-[72px] justify-items-center mt-3">
        @foreach ($popularItems as $popularItem)
            <div class="data-cell flex flex-col items-center w-[160px] h-full min-h-[280px] justify-start">
                <a href="{{ route('works.show', ['work' => $popularItem['item']->id]) }}"
                    class="mb-1 flex items-center justify-center text-base text-textColor text-center h-[40px] overflow-hidden leading-tight hover:underline">
                    <span class="line-clamp-2">{{ $popularItem['item']->name }}</span>
                </a>
                <x-molecules.evaluation.star-num-detail :starNum="$popularItem['item']->average_star_num" :postNum="null" />
                @if ($popularItem['item']->image)
                    <div class="mt-1 mb-0 relative w-[123px] h-[164px] overflow-hidden border border-gray-300">
                        <img src="{{ $popularItem['item']->image }}" alt="{{ __('common.not_reloaded_images') }}"
                            class="w-full h-full object-cover">
                    </div>
                    <p class="mt-1 text-xs text-subTextColor">
                        {{ $popularItem['item']->copyright }}</p>
                @endif
            </div>
        @endforeach
    </div>
</div>
<div class="flex justify-end">
    <a href="{{ route('works.index') }}" class="my-2 sm:mr-[56px] mr-[40px] text-xs text-linkColor hover:underline">
        {{ __('entity.main.popularity_work_detail') }}
    </a>
</div>
