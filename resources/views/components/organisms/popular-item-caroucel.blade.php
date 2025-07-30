<div class="swiper">
    <div class="swiper-wrapper">
        <div class="swiper-slide mb-4">
            <x-organisms.popular-three-items-section :popularItems="$topPopularityWorks" itemType="work" />
        </div>
        <div class="swiper-slide">
            <x-organisms.popular-three-items-section :popularItems="$topPopularityWorkStories" itemType="work_story" />
        </div>
        <div class="swiper-slide">
            <x-organisms.popular-three-items-section :popularItems="$topPopularityCharacters" itemType="character" />
        </div>
    </div>
    <x-atom.swiper-button-prev />
    <x-atom.swiper-button-next />
    <div class="swiper-pagination"></div>
</div>
