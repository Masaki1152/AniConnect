<?php

namespace App\View\Components\Molecules\SelectBox;

use Illuminate\View\Component;

class CategorySelectBox extends Component
{
    public $postType;
    public $targetTableName;
    public $categories;

    public function __construct($postType = null, $targetTableName, $categories)
    {
        $this->postType = $postType;
        $this->targetTableName = $targetTableName;
        $this->categories = $categories;
    }

    public function render()
    {
        return view('components.molecules.select-box.category-select-box');
    }
}
