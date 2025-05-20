<?php

namespace App\View\Components\Molecules\Preview;

use Illuminate\View\Component;

class PreviewImageEdit extends Component
{
    public $isMultiple;
    public $postType;
    public $isVertical;

    public function __construct($isMultiple, $postType, $isVertical = false)
    {
        $this->isMultiple = $isMultiple;
        $this->postType = $postType;
        $this->isVertical = $isVertical;
    }

    public function render()
    {
        return view('components.molecules.preview.preview-image-edit');
    }
}
