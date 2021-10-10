<?php

namespace App\View\Components\Html\Form\Element;

use App\View\Components\Html\Form\FormElement;

class Input extends FormElement
{
    public string $type;
    public ?string $placeholder;
    public ?float $step;
    public ?int $maxlength;
    public ?float $minValue;

    function __construct(
        $id,
        $type,
        $label          = null,
        $isRequired     = false,
        $value          = null,
        $class          = null,
        $placeholder    = null,
        $step           = null,
        $maxlength      = null,
        $doDisplayAsInputGroup = true,
        $doDisplaySizeSmall    = false,
        $minValue       = null, /* only set if type = number */
        $doAutoFocus = false
    ) {
        // FormElement.construct
        parent::__construct(
            $id,
            $label,
            $isRequired,
            $class,
            $id,
            $doDisplayAsInputGroup,
            $doDisplaySizeSmall,
            null,
            $doAutoFocus
        );

        // Element specific attributes
        $this->type         = $type;
        $this->placeholder  = $placeholder;
        $this->step         = $step;
        $this->maxlength    = $maxlength;
        $this->minValue     = $minValue;

        $this->setValue(old($this->id) ?? $value ?? null);
        $this->setView('Components.html.form.element.input');
    }
}
