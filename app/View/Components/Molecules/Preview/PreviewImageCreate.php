<?php

namespace App\View\Components\Molecules\Preview;

use Illuminate\View\Component;

class PreviewImageCreate extends Component
{
    public $isMultiple;
    public $isVertical;

    public function __construct($isMultiple, $isVertical)
    {
        $this->isMultiple = $isMultiple;
        $this->isVertical = $isVertical;
    }

    public function render()
    {
        return view('components.molecules.preview.preview-image-create');
    }
}
