<?php

namespace App\View\Components;

use Illuminate\View\Component;

class BodyTextArea extends Component
{
    public $postType;
    public $postTypeString;

    public function __construct($postType = null, $postTypeString)
    {
        $this->postType = $postType;
        $this->postTypeString = $postTypeString;
    }

    public function render()
    {
        return view('components.body-text-area');
    }
}
