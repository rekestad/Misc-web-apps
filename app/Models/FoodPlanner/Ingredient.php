<?php

namespace App\Models\FoodPlanner;

use App\View\Components\Html\Form\Element\SelectOption;
use App\View\Components\Html\Table\TableRow;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Class Ingredient
 * @package App\Models\FoodPlanner
 */
class Ingredient extends Model
{
    protected $table = 'fp_ingredients';
    protected $fillable = [
        'ingredient_name',
        'unit_type_id',
        'category_id',
        'user_id_insert',
        'user_id_update'
    ];

    /**
     * returns all ingredients for the html table component
     * @return Collection
     */
    public static function getAsHtmlTableRows(): Collection {
        return TableRow::convertResultSet(collect(
            DB::select('
                SELECT
                    I.id,
                    1 AS isEditable,
                    I.ingredient_name,
                    I.unit_type_abbr,
                    I.category_name
                FROM
                    view_ingredients I
                ORDER BY
                    I.ingredient_name COLLATE utf8mb4_swedish_ci
                '
            )
        ));
    }

    /**
     * returns all ingredients for the select input component
     * @return Collection
     */
    public static function getAsSelectOptions(): Collection {
        return (
        SelectOption::convertResultSet(
            DB::select('
                    SELECT
                        I.id AS value,
                        I.ingredient_name AS label,
                        JSON_OBJECT(\'default-unit\',I.unit_type_id) AS dataAttributesJson
                    FROM
                        fp_ingredients I
                    ORDER BY
                        I.ingredient_name COLLATE utf8mb4_swedish_ci
                    '
            )
        )
        );
    }

    /**
     * insert new ingredients from new dish form
     * @param $newIngredients
     * @param $userId
     * @return array
     */
    public static function createNewIngredientsForDish($newIngredients, $userId): array {
        // declare
        $insertedIngredients = array();
        $ingredient = null;

        // remove all incomplete ingredients
        $newIngredients = array_filter(
            $newIngredients,
            function ($item) {
                return
                    !empty(strip_tags($item['quantity'])) &&
                    (round(floatval(strip_tags($item['quantity'])), 1) > 0.0) &&
                    !empty(strip_tags($item['ingredient_name'])) &&
                    !empty(strip_tags($item['unit_type_id'])) &&
                    !empty(strip_tags($item['category_id']));
            }
        );

        // create new ingredients
        foreach ($newIngredients as $ni) {
            $ingredient = Ingredient::create([
                'ingredient_name' => strtolower(strip_tags($ni['ingredient_name'])),
                'unit_type_id' => strip_tags($ni['unit_type_id']),
                'category_id' => strip_tags($ni['category_id']),
                'user_id_insert' => $userId,
                'user_id_update' => $userId
            ]);

            // save inserted id and quantity in return-array for
            // many-to-many-table
            $insertedIngredients[] = array(
                'quantity' => $ni['quantity'],
                'ingredient_id' => $ingredient->id,
                'unit_type_id' => $ingredient->unit_type_id
            );

            $ingredient = null;
        }

        return $insertedIngredients;
    }
}
