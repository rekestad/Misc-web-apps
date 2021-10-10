<?php

namespace App\View\Components\FoodPlanner\WeeklyMenus;

use Illuminate\View\Component;
use Illuminate\View\View;

class Show extends Component
{
    public $weeklyMenu;
    public $weekDayNames;

    /**
     * Create a new component instance.
     *
     * @param $weeklyMenu
     */
    public function __construct($weeklyMenu)
    {
        $this->weeklyMenu = $weeklyMenu;
        $this->weekDayNames = util_getWeekDayNames(true);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View|string
     */
    public function render()
    {
        return view('FoodPlanner.weeklyMenus.components.show');
    }
}
