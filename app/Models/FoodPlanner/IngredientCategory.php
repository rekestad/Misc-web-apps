<?php

namespace App\Models\FoodPlanner;

use App\View\Components\Html\Form\Element\SelectOption;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Class IngredientCategory
 * @package App\Models\FoodPlanner
 */
class IngredientCategory extends Model
{
    protected $table = 'fp_ingredient_categories_fl';

    /**
     * returns default ingredient category
     * @return IngredientCategory
     */
    public static function getDefault(): IngredientCategory {
        return IngredientCategory::firstWhere('is_default', 1)->get();
    }

    /**
     * returns categories for the select input component
     * @return Collection
     */
    public static function getAsSelectOptions(): Collection {
        return (
        SelectOption::convertResultSet(
            DB::select('
                    SELECT
                        C.id AS value,
                        C.category_name AS label
                    FROM
                        fp_ingredient_categories_fl C
                    ORDER BY
                        C.category_name
                    '
            )
        )
        );
    }
}
