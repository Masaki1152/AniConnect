<?php

namespace App\View\Components\Molecules\Button;

use Illuminate\View\Component;

class ShowCommentFormButton extends Component
{
    public $comment;

    public function __construct($comment)
    {
        $this->comment = $comment;
    }

    public function render()
    {
        return view('components.molecules.button.show-comment-form-button');
    }
}
