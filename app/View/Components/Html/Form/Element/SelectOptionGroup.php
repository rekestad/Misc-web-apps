<?php

namespace App\View\Components\Html\Form\Element;

use Illuminate\Support\Collection;

class SelectOptionGroup
{
    public string $label;
    public int $sortOrder;
    public string $json;
    public static string $defaultGroup = 'default';

    function __construct($label,$sortOrder) {
        $this->label        = $label;
        $this->sortOrder    = $sortOrder;
    }
}
