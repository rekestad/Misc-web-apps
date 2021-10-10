<?php

namespace App\View\Components\Html\Link;

class Show extends Link
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
        $this->setColor('success');

        if($doShowIcon || $linkStyle == 'iconOnly') {
            $this->setIcon('fas fa-eye');
        }

        // refresh linkClass
        $this->setLinkClass();
    }
}
