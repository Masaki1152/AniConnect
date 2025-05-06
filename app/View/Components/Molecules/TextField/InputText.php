<?php

namespace App\View\Components\Molecules\TextField;

use Illuminate\View\Component;

class InputText extends Component
{
    public $postType;
    public $targetTableName;
    public $characterMaxLength;
    public $className;
    public $column;
    public $titleText;

    public function __construct($inputTextType, $postType = null, $targetTableName, $characterMaxLength)
    {
        $this->postType = $postType;
        $this->targetTableName = $targetTableName;
        $this->characterMaxLength = $characterMaxLength;

        $this->className = $inputTextType->className();
        $this->column = $inputTextType->targetColumn();
        $this->titleText = $inputTextType->localizedText();
    }

    public function render()
    {
        return view('components.molecules.text-field.input-text');
    }
}
