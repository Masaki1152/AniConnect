<?php

namespace App\View\Components;

use Illuminate\View\Component;

class PreviewImageEdit extends Component
{

    public $postType;

    public function __construct($postType)
    {
        $this->postType = $postType;
    }

    public function render()
    {
        return view('components.preview-image-edit');
    }
}
