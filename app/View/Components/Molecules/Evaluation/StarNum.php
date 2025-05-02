<?php

namespace App\View\Components\Molecules\Evaluation;

use Illuminate\View\Component;

class StarNum extends Component
{
    public $starNum;

    public function __construct($starNum)
    {
        $this->starNum = (int) $starNum;
    }

    public function render()
    {
        return view('components.molecules.evaluation.star-num');
    }
}
