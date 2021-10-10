<?php

namespace App\View\Components\Html\Table;

use Illuminate\Support\Collection;

class TableHeaderRow
{
    public Collection $tableHeaderColumns;
    public ?string $class;

    public function __construct(
        Collection $tableHeaderColumns, // collection of TableHeaderColumn objects
        ?string $class
    )
    {
        $this->tableHeaderColumns = $tableHeaderColumns;
        $this->class = $class;
    }


}
