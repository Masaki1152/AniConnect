<?php

namespace App\View\Components\Organisms;

use Illuminate\View\Component;

class PopularThreeItemsSection extends Component
{
    public $popularItems;

    public function __construct($popularItems)
    {
        $this->popularItems = $popularItems;
    }

    public function render()
    {
        return view('components.organisms.popular-three-items-section');
    }
}
