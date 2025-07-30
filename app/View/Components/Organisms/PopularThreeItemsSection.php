<?php

namespace App\View\Components\Organisms;

use Illuminate\View\Component;
use Illuminate\Support\Facades\Log;

class PopularThreeItemsSection extends Component
{
    public $popularItems;
    public $itemType;
    public $imageAspect;

    public function __construct($popularItems)
    {
        $this->popularItems = $popularItems;
        //$this->itemType = $popularItems[0]['itemType'];

        Log::info('PopularThreeItemsSection: Constructor called.');
        Log::info('PopularThreeItemsSection: $popularItems received:', ['data' => $popularItems]);

        if (!empty($popularItems) && isset($popularItems[0]['itemType'])) {
            $this->itemType = $popularItems[0]['itemType'];
            Log::info('PopularThreeItemsSection: itemType successfully extracted: ' . $this->itemType);
        } else {
            $this->itemType = null;
            Log::warning('PopularThreeItemsSection: $popularItems is empty or "itemType" is missing for the first item. Setting itemType to null.', ['received_popularItems' => $popularItems]);
        }
    }

    public function render()
    {
        return view('components.organisms.popular-three-items-section');
    }
}
