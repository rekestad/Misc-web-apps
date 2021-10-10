<?php

namespace App\View\Components\Html\Table;

use Illuminate\Support\Collection;

class TableHeaderColumn
{
    public string $title;
    public ?string $class;

    public function __construct(
        string $title,
        ?string $class
    )
    {
        $this->title = $title;
        $this->class = $class;
    }

    public static function convertResultSet(Collection $resultSet) {
        // return a new collection where each row
        // has been converted to a TableHeaderColumn object
        return $resultSet->map(function ($item, $key) {
            return new TableHeaderColumn($item[0],$item[1]);
        });
    }
}
