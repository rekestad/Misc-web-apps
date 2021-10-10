<?php

namespace App\View\Components\Html\Card;

use Illuminate\View\Component;

class CardComponent extends Component
{
    public string $type;
    public string $cardId;
    public ?string $class;
    public ?string $style;
    public bool $isCollapsible;
    public bool $doShowExpanded;

    public function __construct(
        string $type,
        string $cardId,
        ?string $class = null,
        ?string $style = null,
        bool $isCollapsible = false,
        bool $doShowExpanded = false
    )
    {
        $this->type = $type;
        $this->cardId = $cardId;
        $this->class = $class;
        $this->style = $style;
        $this->isCollapsible = $isCollapsible;
        $this->doShowExpanded = $isCollapsible && $doShowExpanded;
    }

    public function render()
    {
        return view('Components.html.card.' . $this->type);
    }
}
