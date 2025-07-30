<?php

namespace App\View\Components\Organisms;

use Illuminate\View\Component;

class PopularItemCaroucel extends Component
{
    public $topPopularityWorks;
    public $topPopularityWorkStories;
    public $topPopularityCharacters;
    public $topPopularityMusic;
    public $topPopularityPilgrimages;

    public function __construct($topPopularityWorks, $topPopularityWorkStories, $topPopularityCharacters, $topPopularityMusic, $topPopularityPilgrimages)
    {
        $this->topPopularityWorks = $topPopularityWorks;
        $this->topPopularityWorkStories = $topPopularityWorkStories;
        $this->topPopularityCharacters = $topPopularityCharacters;
        $this->topPopularityMusic = $topPopularityMusic;
        $this->topPopularityPilgrimages = $topPopularityPilgrimages;
    }

    public function render()
    {
        return view('components.organisms.popular-item-caroucel');
    }
}
