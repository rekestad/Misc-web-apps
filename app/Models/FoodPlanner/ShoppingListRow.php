<?php

namespace App\Models\FoodPlanner;

use App\View\Components\Html\Table\TableRow;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Class ShoppingListRow
 * @package App\Models\FoodPlanner
 */
class ShoppingListRow extends Model
{
    public $timestamps = false;
    protected $table = 'fp_shopping_list_rows'; // table does not use timestamps
    protected $fillable = [
        'shopping_list_id',
        'item_name',
        'quantity',
        'category',
        'weekly_menu_dishes',
        'is_weekly_menu_item',
        'checked_at',
        'user_id_checked'
    ];

    /**
     * sync shopping list rows after weekly menu change
     * make sure to verify user ownership before calling this method!
     * @param $shoppingListId
     * @param $rows
     */
    public static function syncWeeklyMenuItemRows($shoppingListId, $rows): void {
        // delete all existing weekly menu item rows
        ShoppingListRow::where('shopping_list_id', '=', $shoppingListId)
            ->where('is_weekly_menu_item', '=', 1)
            ->delete();

        // prepare new rows
        $insertData = array();

        foreach ($rows as $r) {
            $insertData[] = [
                'shopping_list_id' => $shoppingListId,
                'item_name' => $r->item_name,
                'unit_type' => $r->unit_type,
                'quantity' => $r->quantity,
                'category' => $r->category,
                'weekly_menu_dishes' => $r->ingredient_dishes,
                'is_weekly_menu_item' => 1
            ];
        }

        // insert new rows
        ShoppingListRow::insert($insertData);
    }

    /**
     * insert manual shopping list items (form below shopping list)
     * make sure to verify user ownership before calling this method!
     * @param $shoppingListId
     * @param $items
     */
    public static function insertManualItems($shoppingListId, $items): void {
        // get default ingredient category
        $category = IngredientCategory::getDefault()->category_name;

        // remove empty values
        $items = array_filter(
            $items,
            function ($item) {
                return
                    !empty(strip_tags($item['item_name'])) &&
                    !empty(strip_tags($item['quantity']));
            }
        );

        // prepare new rows
        $insertData = array();

        foreach ($items as $i) {
            $insertData[] = [
                'shopping_list_id' => $shoppingListId,
                'item_name' => $i['item_name'],
                'quantity' => floatval($i['quantity']),
                'category' => $category,
                'is_weekly_menu_item' => 0
            ];
        }

        // insert new rows
        ShoppingListRow::insert($insertData);
    }

    /**
     * returns all list rows for the html table component
     * make sure to verify user ownership before calling this method!
     * @param $shoppingListId
     * @param $isChecked
     * @return Collection
     */
    public static function getAsHtmlTableRows($shoppingListId, $isChecked): Collection {
        return TableRow::convertResultSet(collect(
            DB::select('
                SELECT
                    R.id,
                    CONCAT(
                        IF(
                            R.is_weekly_menu_item = 1,
                            CONCAT(
                               \'<a href="#" class="showDishesBtn" data-id="\',R.id,\'">\',
                               R.item_name,
                               \'</a>\'
                            ),
                            R.item_name
                        ),
                        IF(
                            R.checked_at IS NOT NULL,
                            CONCAT(\'<br><small class="text-muted">\',DATE_FORMAT(R.checked_at,\'%e/%c %H:%i\'),\' by \',U.name,\'</small>\'),
                            \'\'
                        )
                    ) AS item_name,
                    R.quantity,
                    R.unit_type,
                    R.category,
                    JSON_OBJECT(
                        \'id\', R.id,
                        \'set_is_checked_to\', IF(R.checked_at IS NOT NULL, 0, 1)
                    ) AS rowLinkDataAttributesJson,
                    IF(R.checked_at IS NOT NULL, \'text-decoration-line-through\', NULL) AS class
                FROM
                    fp_shopping_list_rows R
                    LEFT JOIN users U ON
                        U.id = R.user_id_checked
                WHERE
                    R.shopping_list_id = :shoppingListId AND
                    (
                        :isChecked1 = 1 AND
                        R.checked_at IS NOT NULL
                        OR
                        :isChecked2 = 0 AND
                        R.checked_at IS NULL
                    )
                ORDER BY
                    IF(:isChecked3 = 1, R.checked_at, NULL) DESC,
                    IF(:isChecked4 = 0, R.category, NULL) COLLATE utf8mb4_swedish_ci,
                    IF(:isChecked5 = 0, R.item_name, NULL) COLLATE utf8mb4_swedish_ci
                ',
                array(
                    'shoppingListId' => $shoppingListId,
                    'isChecked1' => $isChecked,
                    'isChecked2' => $isChecked,
                    'isChecked3' => $isChecked,
                    'isChecked4' => $isChecked,
                    'isChecked5' => $isChecked
                )
            )
        ));
    }

    /**
     * Eloquent ORM relation
     * @return BelongsTo
     */
    public function shoppingList(): BelongsTo {
        return $this->belongsTo(
            'App\Models\FoodPlanner\ShoppingList',
            'shopping_list_id'
        );
    }

    /**
     * validate that the user owns the shopping list
     */
    public function verifyUserOwnerShip(int $userId): void {
        Household::verifyUserOwnership($userId, $this->shopping_list_id, 'shoppingList');
    }
}
