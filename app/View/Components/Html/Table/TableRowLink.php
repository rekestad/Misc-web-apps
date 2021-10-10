<?php

namespace App\View\Components\Html\Table;

class TableRowLink
{
    public ?string $route;
    public ?int $sortOrder;
    public bool $isDelete;
    public bool $isEdit;
    public bool $isShow;
    public ?string $icon;
    public string $linkStyle;
    public ?string $class;

    public function __construct(
        ?string $route = null,
        ?int $sortOrder = 1,
        bool $isDelete = false,
        bool $isEdit = false,
        bool $isShow = false,
        ?string $icon = null,
        ?string $class = null
    )
    {
        $this->route = $route;
        $this->sortOrder = $sortOrder;
        $this->isDelete = $isDelete;
        $this->isEdit = $isEdit;
        $this->isShow = $isShow;
        $this->icon = $icon;
        $this->linkStyle = 'iconOnly';
        $this->class = $class;
    }
}
