<?php

namespace App\View\Components\Html\Link;

class Delete extends Link
{
    public $doSuppressConfirmDialog;
    public $deleteFormClass;

    function __construct(
        $linkStyle,
        $route,
        $text                       = null,
        $linkClassAppend            = null,
        $deleteFormClass            = null,
        $doShowIcon                 = false,
        $doSuppressConfirmDialog    = false,
        $icon                       = null
    ) {
        parent::__construct(
            $linkStyle,
            $route,
            $text,
            $linkClassAppend
        );

        // Element specific attributes
        $this->doSuppressConfirmDialog = $doSuppressConfirmDialog;
        $this->deleteFormClass = $deleteFormClass;

        $this->setView('Components.html.link.delete');
        $this->setColor('danger');

        if($doShowIcon || $linkStyle == 'iconOnly') {
            $this->setIcon($icon ?? 'fas fa-trash');
        }

        // refresh linkClass
        $this->setLinkClass();
    }
}
