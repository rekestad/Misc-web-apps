<?php

namespace App\View\Components\Html\Table;

use Illuminate\Support\Collection;
use Illuminate\View\Component;

class Table extends Component
{
    public Collection $tableRows;
    public ?TableHeaderRow $tableHeaderRow;
    public ?Collection $tableRowLinks;
    public bool $isSearchable;

    public function __construct(
        Collection      $tableRows,
        TableHeaderRow  $tableHeaderRow     = null,
        ?Collection     $tableRowLinks      = null,
        bool            $isSearchable       = false
    )
    {
        $this->tableRows        = $tableRows;
        $this->tableHeaderRow   = $tableHeaderRow;
        $this->tableRowLinks    = $tableRowLinks;
        $this->isSearchable     = $isSearchable;
    }

    public function render()
    {
        return view('Components.html.table.table');
    }
}
