<?php

namespace App\View\Components\Html\Link;

use App\View\Components\Html\DataAttribute;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class Link extends Component
{
    public string   $linkStyle;
    public ?string  $route;
    public ?string  $text;
    public ?string  $icon;
    public ?string  $linkClass;
    public ?string  $color;
    public bool     $isBlockBtn;
    public ?string  $linkClassAppend;
    public ?string  $view = 'Components.html.link.link';
    public bool     $doOpenInNewWindow;
    public ?string  $javaScript;
    public ?string  $id;
    public ?Collection $dataAttributes;

    public function __construct(
        string  $linkStyle,
        ?string  $route                 = null,
        ?string  $text                  = null,
        ?string  $linkClassAppend       = null,
        ?string  $icon                  = null,
        ?string  $color                 = null,
        bool    $doOpenInNewWindow      = false,
        ?string  $javaScript            = null,
        ?string  $id                    = null,
        ?Collection $dataAttributes     = null
    )
    {
        $this->linkStyle            = $linkStyle;
        $this->route                = $route;
        $this->text                 = $text;
        $this->icon                 = $icon;
        $this->color                = $color;
        $this->linkClassAppend      = $linkClassAppend;
        $this->isBlockBtn           = ($linkStyle === 'block');
        $this->doOpenInNewWindow    = $doOpenInNewWindow;
        $this->javaScript           = $javaScript;
        $this->id                   = $id;
        $this->dataAttributes       = $dataAttributes;
        $this->setLinkClass();
    }

    public function setLinkClass(): void {
        $class = null;

        switch($this->linkStyle) {
            case 'block':
                $class = 'btn btn-block btn-'.$this->color;
                break;
            case 'iconOnly':
                $class = 'border-0 p-0 m-0 bg-transparent text-decoration-none';
                break;
        }

        $this->linkClass = $class . ' ' . $this->linkClassAppend;
    }

    public function render()
    {
        return view($this->view);
    }

    public function setView(string $view): void {
        $this->view = $view;
    }

    public function setColor($color): void {
        $this->color = $color;
    }

    public function setIcon($icon): void {
        $this->icon = $icon;
    }
}
