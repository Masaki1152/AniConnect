<?php

namespace App\View\Components\Molecules\Button;

use Illuminate\View\Component;

class CommentLikeButton extends Component
{
    public $comment;
    public $baseRoute;

    public function __construct($comment, $baseRoute)
    {
        $this->comment = $comment;
        $this->baseRoute = $baseRoute;
    }

    public function render()
    {
        return view('components.molecules.button.comment-like-button');
    }
}
