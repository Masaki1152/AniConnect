<?php

namespace App\View\Components\Molecules\TextField;

use Illuminate\View\Component;

class BodyTextArea extends Component
{
    public $postType;
    public $targetTableName;

    public function __construct($postType = null, $targetTableName)
    {
        $this->postType = $postType;
        $this->targetTableName = $targetTableName;
    }

    public function render()
    {
        return view('components.molecules.text-field.body-text-area');
    }
}
