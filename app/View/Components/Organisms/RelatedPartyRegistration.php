<?php

namespace App\View\Components\Organisms;

use Illuminate\View\Component;

class RelatedPartyRegistration extends Component
{
    public $targetTableName;
    public $relatedPartyName;
    public $postType;

    public function __construct($targetTableType, $postType = null)
    {
        $this->targetTableName = $targetTableType->targetTableName();
        $this->relatedPartyName = $targetTableType->relatedPartyName();
        $this->postType = $postType;
    }

    public function render()
    {
        return view('components.organisms.related-party-registration');
    }
}
