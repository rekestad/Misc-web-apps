<?php

namespace App\View\Components\Html\Form\Element;

use App\View\Components\Html\Form\FormElement;

class Textarea extends FormElement
{
    public ?int $rows;
    public ?string $placeholder;
    public ?int $maxlength;
    public bool $doAllowSpecialChars;

    function __construct(
        $id,
        $label,
        $isRequired     = null,
        $value          = null,
        $class          = null,
        ?string $placeholder    = null,
        ?int $maxlength      = null,
        ?int $rows           = null,
        bool $doAllowSpecialChars = false
    ) {
        // FormElement.construct
        parent::__construct(
            $id,
            $label,
            $isRequired,
            $class
        );

        // Element specific attributes
        $this->placeholder  = $placeholder;
        $this->maxlength    = $maxlength;
        $this->rows         = $rows;
        $this->doAllowSpecialChars = $doAllowSpecialChars;

        $this->setValue(old($this->id) ?? $value ?? null);
        $this->setView('Components.html.form.element.textarea');
    }
}
