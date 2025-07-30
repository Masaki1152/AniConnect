<div class="swiper swiper-desktop hidden md:block">
    <div class="swiper-wrapper">
        <div class="swiper-slide mb-4">
            <x-organisms.popular-three-items-section :popularItems="$topPopularityWorks" />
        </div>
        <div class="swiper-slide">
            <x-organisms.popular-three-items-section :popularItems="$topPopularityWorkStories" />
        </div>
        <div class="swiper-slide">
            <x-organisms.popular-three-items-section :popularItems="$topPopularityCharacters" />
        </div>
    </div>
    <x-atom.swiper-button-prev />
    <x-atom.swiper-button-next />
    <div class="swiper-pagination"></div>
</div>
<!-- モバイルでの表示 -->
<div class="swiper swiper-mobile md:hidden">
    <div class="swiper-wrapper">
        @foreach ($allPopularItems as $popularItem)
            <div class="swiper-slide mb-6">
                <div
                    class="data-cell flex flex-col items-center w-[160px] min-h-[310px] border-2 border-mainColor rounded-xl p-2">
                    <x-molecules.cell.popular-item :popularItem="$popularItem" />
                </div>
            </div>
        @endforeach
    </div>
</div>
