<?php

namespace App\View\Components\Html\Card;

use Illuminate\View\Component;

class Card extends Component
{
    public ?string $cardId;
    public string $title;
    public ?string $cardClass;
    public ?string $cardStyle;
    public ?string $headerClass;
    public ?string $headerStyle;
    public ?string $bodyClass;
    public ?string $bodyStyle;
    public string $bodyView;
    public bool $isListGroup;
    public bool $isCollapsible;

    public function __construct(
        ?string $cardId,
        string $title,
        ?string $cardClass = null,
        ?string $cardStyle = null,
        ?string $headerClass = null,
        ?string $headerStyle = null,
        ?string $bodyClass = null,
        ?string $bodyStyle = null,
        bool $isListGroup = false,
        bool $isCollapsible = false
    )
    {
        $this->cardId = $cardId;
        $this->title = $title;
        $this->cardClass = $cardClass;
        $this->cardStyle = $cardStyle;
        $this->headerClass = $headerClass;
        $this->headerStyle = $headerStyle;
        $this->bodyClass = $bodyClass;
        $this->bodyStyle = $bodyStyle;
        $this->isListGroup = $isListGroup;
        $this->bodyView = ($this->isListGroup ? 'listGroup' : 'body');
        $this->isCollapsible = $isCollapsible;
    }

    public function render()
    {
        return view('Components.html.card.card');
    }
}
