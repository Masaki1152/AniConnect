<?php

namespace App\View\Components\Organisms;

use Illuminate\View\Component;

class PopularThreeItemsSection extends Component
{
    public $popularItems;
    public $itemType;
    public $imageAspect;

    public function __construct($popularItems, $itemType)
    {
        $this->popularItems = $popularItems;
        $this->itemType = $itemType;

        $vertical = "w-[123px] h-[164px]";
        $horizontal = "w-[164px] h-[123px]";
        $square = "w-[123px] h-[123px]";
        switch ($itemType) {
            case "work":
                $this->imageAspect = $vertical;
                break;
            case "work_story":
            case "character":
            case "pilgrimage":
                $this->imageAspect = $horizontal;
                break;
            case "music":
                $this->imageAspect = $square;
                break;
        }
    }

    public function render()
    {
        return view('components.organisms.popular-three-items-section');
    }
}
