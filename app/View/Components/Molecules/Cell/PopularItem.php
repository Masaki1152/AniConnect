<?php

namespace App\View\Components\Molecules\Cell;

use Illuminate\View\Component;

class PopularItem extends Component
{
    public $popularItem;
    public $itemType;
    public $imageAspect;

    public function __construct($popularItem)
    {
        $this->popularItem = $popularItem;
        $this->itemType = $popularItem['itemType'];

        $vertical = "aspect-[3/4]";
        $horizontal = "aspect-[4/3]";
        $square = "aspect-square";
        switch ($this->itemType) {
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
        return view('components.molecules.cell.popular-item');
    }
}
