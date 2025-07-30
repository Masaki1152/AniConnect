<?php

namespace App\View\Components\Organisms;

use Illuminate\View\Component;

class PopularThreeItemsSection extends Component
{
    public $popularItems;
    public $itemType;
    public $imageAspect;

    public function __construct($popularItems)
    {
        $this->popularItems = $popularItems;
        $this->itemType = $popularItems[0]['itemType'];
    }

    public function render()
    {
        return view('components.organisms.popular-three-items-section');
    }
}
