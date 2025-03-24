<?php

namespace App\View\Components;

use Illuminate\View\Component;

class StarNumSelectBox extends Component
{
    public $postType;
    public $postTypeString;
    public $isCreateType;

    public function __construct($postType, $postTypeString, $isCreateType)
    {
        $this->postType = $postType;
        $this->postTypeString = $postTypeString;
        $this->isCreateType = $isCreateType;
    }

    public function render()
    {
        return view('components.star-num-select-box');
    }
}
