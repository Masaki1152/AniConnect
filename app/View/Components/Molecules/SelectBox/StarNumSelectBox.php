<?php

namespace App\View\Components\Molecules\SelectBox;

use Illuminate\View\Component;

class StarNumSelectBox extends Component
{
    public $postType;
    public $targetTableName;
    public $isCreateType;

    public function __construct($postType, $targetTableName, $isCreateType)
    {
        $this->postType = $postType;
        $this->targetTableName = $targetTableName;
        $this->isCreateType = $isCreateType;
    }

    public function render()
    {
        return view('components.molecules.select-box.star-num-select-box');
    }
}
