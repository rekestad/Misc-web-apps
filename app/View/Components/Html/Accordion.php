<?php

namespace App\View\Components\Html;

use Illuminate\View\Component;
use Illuminate\View\View;

class Accordion extends Component
{
    public string $type;
    public ?string $accordionId;
    public ?string $itemId;
    public bool $doStartExpanded;

    public function __construct(
        string $type,
        ?string $accordionId = null,
        ?string $itemId = null,
        bool $doStartExpanded = false
    )
    {
        $this->type = $type;
        $this->accordionId = $accordionId;
        $this->itemId = $itemId;
        $this->doStartExpanded = $doStartExpanded;
    }

    public function render()
    {
        return view('Components.html.accordion.' . $this->type);
    }
}
