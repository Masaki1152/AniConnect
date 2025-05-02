<?php

namespace App\View\Components\Molecules\Evaluation;

use Illuminate\View\Component;

class StarNumDetail extends Component
{
    public $starNum;
    public $postNum;

    public function __construct($starNum, $postNum)
    {
        $this->starNum = $starNum;
        $this->postNum = $postNum;
    }

    public function render()
    {
        return view('components.molecules.evaluation.star-num-detail');
    }
}
