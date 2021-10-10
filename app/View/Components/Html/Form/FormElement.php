<?php

namespace App\View\Components\Html\Form;

use Illuminate\Support\Collection;
use Illuminate\View\Component;

class FormElement extends Component
{
    public string   $id;
    public ?string  $label;
    public bool     $isRequired;
    public ?string  $value;
    public ?string  $class;
    public string   $view;
    public ?string  $requiredText;
    public ?string  $name;
    public bool     $doDisplayAsInputGroup;
    public bool     $doDisplaySizeSmall;
    public ?Collection   $dataAttributes; // expected to be collection of DataAttribute objects
    public bool     $doAutoFocus;

    public function __construct (
        string  $id,
        ?string $label = null,
        bool    $isRequired = false,
        ?string $class = null,
        ?string $name = null,    /* if null then set to same as $id */
        bool    $doDisplayAsInputGroup = true,
        bool    $doDisplaySizeSmall = false,
        ?Collection  $dataAttributes = null,
        bool     $doAutoFocus = false
    )
    {
        $this->id           = $id;
        $this->label        = $label;
        $this->class        = $class;
        $this->isRequired   = (!empty($isRequired) && $isRequired === true);
        $this->class        = $class;
        $this->requiredText = ($isRequired ? 'required' : null);
        $this->name         = $name;
        $this->doDisplayAsInputGroup    = $doDisplayAsInputGroup;
        $this->doDisplaySizeSmall       = $doDisplaySizeSmall;
        $this->dataAttributes           = $dataAttributes;
        $this->doAutoFocus = $doAutoFocus;
    }

    public function setValue($value): void {
        $this->value = $value;
    }

    public function setView($view): void {
        $this->view = $view;
    }

    public function render()
    {
        return view($this->view); /* Implemented in child class */
    }
}
