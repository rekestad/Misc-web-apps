<?php

namespace App\View\Components\Html\Form\Element;

use App\View\Components\Html\Form\FormElement;

class Checkbox extends FormElement
{
    public string $checked;
    public string $currentStoredValue;
    public string $type;
    public bool $doFormatAsInline;
    public ?string $name;
    public ?string $value;

    function __construct(
        string      $id,
        string      $label,
        string      $currentStoredValue,
        ?string     $value = null,
        ?string     $class = null,
        string      $type = 'checkbox',
        bool        $doFormatAsInline = false,
        string      $name = null
    ) {
        // FormElement.construct
        parent::__construct(
            $id,
            $label,
            false,
            $class
        );

        // Element specific attributes
        $this->currentStoredValue = $currentStoredValue;
        $this->type = $type;
        $this->doFormatAsInline = $doFormatAsInline;
        $this->name = $name;

        $this->setValue($value ?? "1"); // default 1 for checkboxes
        $this->checked = (
            !empty(old($this->id)) || ($this->currentStoredValue ?? 0) == $this->value
                ? 'checked'
                : ''
        );
        $this->setView('Components.html.form.element.checkbox');

    }
}
