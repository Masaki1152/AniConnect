<?php

namespace App\View\Components\Molecules\TextField;

use Illuminate\View\Component;

class SearchRelatedParty extends Component
{
    public $targetTableName;
    public $relatedPartyName;
    public $column;
    public $titleText;

    public function __construct($targetTableName, $relatedPartyType)
    {
        $this->targetTableName = $targetTableName;
        $this->relatedPartyName = $relatedPartyType->relatedPartyName();
        $this->column = $relatedPartyType->targetColumn();
        $this->titleText = $relatedPartyType->localizedText();
    }

    public function render()
    {
        return view('components.molecules.text-field.search-related-party');
    }
}
