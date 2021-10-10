<?php

namespace App\View\Components\FoodPlanner\Dishes;

use Illuminate\View\Component;
use Illuminate\View\View;

class Show extends Component
{
    public $dish;
    public $isPublicUser;
    public $doShowHeading;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($dish, $isPublicUser = true, $doShowHeading = true)
    {
        $this->dish = $dish;
        $this->isPublicUser = $isPublicUser;
        $this->doShowHeading = $doShowHeading;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View|string
     */
    public function render()
    {
        return view('FoodPlanner.dishes.components.show');
    }
}
