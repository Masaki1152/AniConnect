<?php

namespace App\View\Components;

use Illuminate\View\Component;

class InputText extends Component
{
    public $postType;
    public $postTypeString;
    public $characterMaxLength;
    public $className;
    public $column;
    public $titleText;

    public function __construct($inputTextType, $postType = null, $postTypeString, $characterMaxLength)
    {
        $this->postType = $postType;
        $this->postTypeString = $postTypeString;
        $this->characterMaxLength = $characterMaxLength;

        $this->className = $inputTextType->className();
        $this->column = $inputTextType->targetColumn();
        $this->titleText = $inputTextType->localizedText();
    }

    public function render()
    {
        return view('components.input-text');
    }
}
