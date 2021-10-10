<?php

namespace App\View\Components\Html\Form;

use Illuminate\View\Component;
use Illuminate\View\View;

class Form extends Component
{
    public $action;
    public $isEdit;
    public $title;
    public $submitBtnTxt;
    public $doWrapInCard;

    /**
     * Create a new component instance.
     *
     * @param $action
     * @param bool $isEdit
     * @param $title
     * @param $submitBtnTxt
     * @param bool $doWrapInCard
     */
    public function __construct(
        $action,
        $isEdit,
        $title = null, // card title
        $submitBtnTxt = null,
        $doWrapInCard = true
    )
    {
        $this->action = $action;
        $this->isEdit = $isEdit;
        $this->title = $title;
        $this->submitBtnTxt = $submitBtnTxt;
        $this->doWrapInCard = $doWrapInCard;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View|string
     */
    public function render()
    {
        return view('Components.html.form.form');
    }
}
