<?php

namespace App\View\Components;

use Illuminate\View\Component;

class PostButton extends Component
{
    public $buttonText;

    public function __construct($buttonText)
    {
        $this->buttonText = $buttonText;
    }

    public function render()
    {
        return view('components.post-button');
    }
}
