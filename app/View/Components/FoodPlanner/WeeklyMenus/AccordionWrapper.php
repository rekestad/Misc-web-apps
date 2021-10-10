<?php

namespace App\View\Components\FoodPlanner\WeeklyMenus;

use Illuminate\View\Component;

class AccordionWrapper extends Component
{
    public $weeklyMenus;

    public function __construct (
        $weeklyMenus
    )
    {
        $this->weeklyMenus = $weeklyMenus;
    }

    public function render()
    {
        return view('FoodPlanner.weeklyMenus.components.accordionWrapper');
    }
}
