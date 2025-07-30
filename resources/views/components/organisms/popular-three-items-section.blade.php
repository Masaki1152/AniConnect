<div class="sm:px-[124px] px-[40px]">
    <h3 class="mt-1 text-base font-semibold text-textColor text-center border-b-2 border-mainColor">
        {{ __('entity.main.popularity_' . $itemType . '_department') }}
    </h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-[72px] justify-items-center mt-3">
        @foreach ($popularItems as $popularItem)
            <div class="data-cell flex flex-col items-center w-[160px] h-full min-h-[280px] justify-start">
                <x-molecules.cell.popular-item :popularItem="$popularItem" :imageAspect="$imageAspect" />
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
