<?php

namespace App\View\Components\Html\Form\Element;

use App\View\Components\Html\DataAttribute;
use Illuminate\Support\Collection;

class SelectOption
{
    public string   $value;
    public string   $label;
    public ?string  $style;
    public ?string  $optionGroup;
    public int      $optionGroupSortOrder;
    public ?Collection $dataAttributes; // expects array of DataAttribute objects

    function __construct(
        object $option
    ) {
        $this->value            = $option->value;
        $this->label            = $option->label ?? $option->value;
        $this->style            = $option->style ?? null;
        $this->optionGroup      = $option->optionGroup ?? SelectOptionGroup::$defaultGroup;
        $this->optionGroupSortOrder = $option->optionGroupSortOrder ?? 0;
        $this->dataAttributes   = (
        !empty($option->dataAttributesJson)
            ? DataAttribute::convertJsonArray($option->dataAttributesJson)
            : null
        );
    }

    public static function convertResultSet(array $resultSet): Collection {
        // return a new collection where each row
        // has been converted to a SelectOption object
        return collect($resultSet)->map(function ($value, $key) {
            return new SelectOption($value);
        });
    }
}
