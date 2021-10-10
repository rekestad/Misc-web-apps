<?php

namespace App\View\Components\Html;

use Illuminate\Support\Collection;

class DataAttribute
{
    public string $name;
    public string $value;

    public function __construct(string $name, string $value) {
        $this->name = $name;
        $this->value = $value;
    }

    public function render(): string {
        return 'data-'.$this->name.'='.$this->value;
    }

    // for TableRowLink
    public static function convertJsonArray($json): Collection {
        $jsonDecoded = json_decode($json, true);
        $dataAttributes = new Collection();

        foreach($jsonDecoded AS $k => $v) {
            $dataAttributes->push(new DataAttribute($k,$v));
        }

        return $dataAttributes;
    }
}
