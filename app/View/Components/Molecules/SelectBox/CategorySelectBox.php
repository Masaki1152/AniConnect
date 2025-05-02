<?php

namespace App\View\Components\Molecules\SelectBox;

use Illuminate\View\Component;

class CategorySelectBox extends Component
{
    public $postType;
    public $postTypeString;
    public $categories;

    public function __construct($postType = null, $postTypeString, $categories)
    {
        $this->postType = $postType;
        $this->postTypeString = $postTypeString;
        $this->categories = $categories;
    }

    public function render()
    {
        return view('components.molecules.select-box.category-select-box');
    }
}
