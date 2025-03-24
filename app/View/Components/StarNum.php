<?php

namespace App\View\Components;

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
        return view('components.star-num');
    }
}
