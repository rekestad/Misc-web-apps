<?php

namespace App\Models\FoodPlanner;

use App\View\Components\Html\Form\Element\SelectOption;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Class UnitType - UnitType for ingredients
 * @package App\Models\FoodPlanner
 */
class UnitType extends Model
{
    protected $table = 'fp_unit_types_fl';

    /**
     * returns default ingredient unit type
     * @return UnitType
     */
    public static function getDefault(): UnitType {
        return UnitType::firstWhere('is_default', 1)->get();
    }

    /**
     * returns unit types for the select input component
     * @return Collection
     */
    public static function getAsSelectOptions(): Collection {
        return (
        SelectOption::convertResultSet(
            DB::select('
                    SELECT
                        UT.id AS value,
                        UT.unit_type_abbr AS label,
                        UTC.category_name AS optionGroup,
                        UTC.id AS optionGroupSortOrder
                    FROM
                        fp_unit_types_fl UT
                        JOIN fp_unit_type_categories_fl UTC ON
                            UTC.id = UT.category_id
                    ORDER BY
                        UT.sort_order
                    '
            )
        )
        );
    }
}
