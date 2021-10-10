<?php

namespace App\View\Components\Html\Form\Element;

use App\View\Components\Html\Form\FormElement;
use Illuminate\Support\Collection;
use Symfony\Component\CssSelector\Node\SelectorNode;

class Select extends FormElement
{
    public Collection $options;
    public ?Collection $optionGroups;
    public bool $doDisplayAsInputGroup;
    public ?string $nullValueName;

    function __construct(
        string $id,
        Collection $options,            /* Collection of SelectOption objects */
        ?string $label                  = null,
        ?string $value                  = null,
        bool $isRequired                = false,
        ?string $class                  = null,
        ?string $name                   = null,
        bool $doDisplayAsInputGroup     = true,
        bool $doDisplaySizeSmall        = false,
        string $nullValueName           = 'none'
    ) {
        // FormElement.construct
        parent::__construct(
            $id,
            $label,
            $isRequired,
            $class,
            $name,
            $doDisplayAsInputGroup,
            $doDisplaySizeSmall
        );
        // Element specific attributes
        $this->options          = $options;
        $this->nullValueName    = $nullValueName;
        $this->optionGroups     = new Collection();
        $this->initOptionGroups();

        $this->setValue(old($this->id) ?? $value ?? null);
        $this->setView('Components.html.form.element.select');
    }

    public function initOptionGroups(): void {
        $defaultGroup = SelectOptionGroup::$defaultGroup;

        foreach($this->options->where('optionGroup','!=', $defaultGroup) AS $o) {
            if(!$this->optionGroups->contains('label', $o->optionGroup)) {
                $this->optionGroups->push(new SelectOptionGroup($o->optionGroup, $o->optionGroupSortOrder));
            }
        }
        $this->optionGroups->push(new SelectOptionGroup($defaultGroup,0));
    }
}
