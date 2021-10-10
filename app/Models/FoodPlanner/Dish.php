<?php

namespace App\Models\FoodPlanner;

use App\View\Components\Html\Form\Element\SelectOptionGroup;
use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Class Dish
 * @package App\Models\FoodPlanner
 */
class Dish extends Model
{
    use SoftDeletes;

    protected $table = 'fp_dishes';
    protected $fillable = [
        'dish_name',
        'dish_description',
        'dish_rating',
        'dish_difficulty',
        'portions',
        'url_recipe',
        'household_id',
        'user_id_insert',
        'user_id_update'
    ];

    /**
     * returns dishes to populate select input
     * @param $householdId
     * @return Collection
     */
    public static function getDishesAsSelectOptions($householdId): Collection {
        return collect(DB::select('
            SELECT
                DT.dish_id AS value,
                CONCAT(DT.dish_name,\' (\',DT.ingredient_count,\')\') AS label,
                :defaultGroup AS optionGroup
            FROM
                view_dishesIngredientCount DT
            WHERE
                DT.household_id = :householdId
            ORDER BY
                DT.dish_name
            ',
            array(
                'defaultGroup' => SelectOptionGroup::$defaultGroup,
                'householdId' => $householdId
            )
        ));
    }

    /**
     * returns top list for food planner dashboard
     * @param $householdId
     * @return array
     */
    public static function getDishTopList($householdId): array {
        return DB::select('
            SELECT
                DT.menu_count AS total_count,
                GROUP_CONCAT(
                    DT.dish_name
                    ORDER BY DT.dish_name ASC
                    SEPARATOR \'<br>\'
                ) AS dish_name
            FROM
                view_dishesMenuCount DT
            WHERE
                DT.household_id = :householdId AND
                DT.menu_count > 1
            GROUP BY
                DT.menu_count
            ORDER BY
                DT.menu_count DESC
            LIMIT 10
            ',
            array(
                'householdId' => $householdId
            )
        );
    }

    /**
     * returns list of dishes never eaten for food planner dashboard
     * @param $householdId
     * @return array
     */
    public static function getDishesNeverEaten($householdId): array {
        return DB::select('
            SELECT
                DT.menu_count AS total_count,
                GROUP_CONCAT(
                    DT.dish_name
                    ORDER BY DT.dish_name ASC
                    SEPARATOR \'<br>\'
                ) AS dish_name
            FROM
                view_dishesMenuCount DT
            WHERE
                DT.household_id = :householdId AND
                DT.menu_count = 0
            GROUP BY
                DT.menu_count
            ',
            array(
                'householdId' => $householdId
            )
        );
    }

    /**
     * Returns all dish ingredients
     * @return Collection
     */
    public function getIngredients(): Collection {
        return (
        DB::table('fp_dish_ingredient AS DI')
            ->select(
                'DI.quantity AS qty',
                'I.ingredient_name AS name',
                //'UT.unit_type_abbr AS unit',
                'UT.unit_type_abbr AS unit',
                'I.id',
                'DI.unit_type_id',
                DB::raw('ROW_NUMBER() OVER(ORDER BY C.category_name, I.ingredient_name) AS sort_order'),
            )
            ->join('fp_ingredients AS I', 'I.id', '=', 'DI.ingredient_id')
            ->join('fp_ingredient_categories_fl AS C', 'C.id', '=', 'I.category_id')
            ->join('fp_unit_types_fl AS UT', 'UT.id', '=', 'DI.unit_type_id')
            ->where('DI.dish_id', '=', $this->id)
            ->orderBy('C.category_name')
            ->orderBy('I.ingredient_name')
            ->get()
        );
    }

    /**
     * sync ingredients connected to dish
     * @param $ingredients
     */
    public function syncIngredients($ingredients) {
        // Remove all empty values from array before insert
        $ingredients = array_filter(
            $ingredients,
            function ($item) {
                return
                    !empty(strip_tags($item['quantity'])) &&
                    (round(floatval(strip_tags($item['quantity'])), 1) > 0.0) &&
                    !empty(strip_tags($item['ingredient_id'])) &&
                    !empty(strip_tags($item['unit_type_id']));
            }
        );

        // group array to distinct ingredients with quantity sum
        $groupedIngredients = array();

        foreach ($ingredients as $i) {
            if (isset($groupedIngredients[$i['ingredient_id']])) {
                if ($groupedIngredients[$i['ingredient_id']]['unit_type_id'] === $i['unit_type_id']) {
                    $groupedIngredients[$i['ingredient_id']]['quantity'] += $i['quantity'];
                }
            } else {
                $groupedIngredients[$i['ingredient_id']]['quantity'] = 0 + $i['quantity'];
                $groupedIngredients[$i['ingredient_id']]['unit_type_id'] = $i['unit_type_id'];
            }
        }

        $ingredients = null;

        foreach ($groupedIngredients as $key => $value) {
            $ingredients[strip_tags($key)] = [
                'quantity' => strip_tags($value['quantity']),
                'unit_type_id' => strip_tags($value['unit_type_id'])
            ];
        }

        // Sync ingredients
        //DB::enableQueryLog();
        $this->ingredients()->sync($ingredients);
        //$query = DB::getQueryLog();
        //dd($query);
    }

    /**
     * Returns all ingredients (eloquent standard method, use getIngredients instead)
     * @return BelongsToMany
     */
    public function ingredients(): BelongsToMany {
        return $this
            ->belongsToMany('App\Models\FoodPlanner\Ingredient', 'fp_dish_ingredient')
            ->withPivot('quantity', 'unit_type_id');
    }

    /**
     * returns weekly menus connected to the dish
     * @return Collection
     */
    public function getWeeklyMenus(): Collection {
        return collect(DB::select('
            SELECT
                WM.date_week_start
            FROM
                fp_weekly_menu_dish WMD
                JOIN fp_weekly_menus WM ON
                    WM.id = WMD.weekly_menu_id
            WHERE
                WMD.dish_id = :dishId AND
                WM.deleted_at IS NULL
            ',
            array(
                'dishId' => $this->id
            )
        ));
    }

    /**
     * validate that the user owns the dish
     * @param int $userId
     */
    public function verifyUserOwnerShip(int $userId): void {
        Household::verifyUserOwnership($userId, $this->id, 'dish');
    }
}
