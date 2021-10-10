<?php

namespace App\View\Components\Html\Link;

class Edit extends Link
{
    function __construct(
        $linkStyle,
        $route,
        $text                       = null,
        $linkClassAppend            = null,
        $doShowIcon                 = false
    ) {
        // FormElement.construct
        parent::__construct(
            $linkStyle,
            $route,
            $text,
            $linkClassAppend
        );

        // Element specific attributes
        $this->setColor('secondary');

        if($doShowIcon || $linkStyle == 'iconOnly') {
            $this->setIcon('fas fa-pen');
        }

        // refresh linkClass
        $this->setLinkClass();
    }
}
