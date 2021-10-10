<?php

namespace App\View\Components\Html\Table;

use App\View\Components\Html\DataAttribute;
use Illuminate\Support\Collection;

class TableRow
{
    public int $id;
    public array $tableColumns;
    public bool $isDeletable;
    public bool $isEditable;
    public ?string $class;
    public ?Collection $rowLinkDataAttributes; // expected to be collection of DataAttribute objects
    public static array $sysColumnNames = [
        'id',
        'isDeletable',
        'class',
        'isEditable',
        'rowLinkDataAttributesJson'
    ];

    public function __construct(
        object $tableColumns
    )
    {
        $this->id           = $tableColumns->id;
        $this->isDeletable  = ($tableColumns->isDeletable ?? 0) == 1;
        $this->isEditable   = ($tableColumns->isEditable ?? 0) == 1;
        $this->class        = ($tableColumns->class ?? null);
        $this->rowLinkDataAttributes = (
            !empty($tableColumns->rowLinkDataAttributesJson)
            ? DataAttribute::convertJsonArray($tableColumns->rowLinkDataAttributesJson)
            : null
        );

        // remove all system columns
        $this->tableColumns = array_filter(
            get_object_vars($tableColumns),
            fn ($key) => !in_array($key, self::$sysColumnNames),
            ARRAY_FILTER_USE_KEY
        );
    }

    public static function convertResultSet(Collection $resultSet): Collection {
        // return a new collection where each row
        // has been converted to a TableRow object
        return $resultSet->map(function ($item, $key) {
            return new TableRow($item);
        });
    }
}
