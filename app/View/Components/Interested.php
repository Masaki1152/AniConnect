<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Interested extends Component
{
    public $type;
    public $root;
    public $path;
    public $prop;

    public function __construct($type, $root, $path, $prop)
    {
        $this->type = $type;
        $this->root = $root;
        $this->path = $path;
        $this->prop = $prop;
    }

    public function render()
    {
        return view('components.interested');
    }
}
