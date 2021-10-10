<?php

namespace App\View\Components\FoodPlanner\Dishes;

use Illuminate\View\Component;

class AccordionWrapper extends Component
{
    public $dishes;

    public function __construct (
        $dishes
    )
    {
        $this->dishes = $dishes;
    }

    public function render()
    {
        return view('FoodPlanner.dishes.components.accordionWrapper');
    }
}
