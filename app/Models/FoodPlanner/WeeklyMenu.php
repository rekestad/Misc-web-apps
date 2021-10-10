<?php

namespace App\Models\FoodPlanner;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Class WeeklyMenu
 * @package App\Models\FoodPlanner
 */
class WeeklyMenu extends Model
{
    use SoftDeletes;

    private static array $validationRules = [
        'date_week_start' => 'required'
    ];
    private static array $validationMessages = [
        'date_week_start.required' => 'The field "Week" is required.'
    ];
    protected $table = 'fp_weekly_menus';
    protected $fillable = [
        'date_week_start',
        'week_no',
        'dish_id_sun',
        'household_id',
        'user_id_insert',
        'user_id_update'
    ];

    /**
     * getter $validationRules
     * @return array|string[]
     */
    public static function getValidationRules(): array {
        return self::$validationRules;
    }

    /**
     * getter $validationMessages
     * @return array|string[]
     */
    public static function getValidationMessages(): array {
        return self::$validationMessages;
    }

    /**
     * returns weekly menus connected to householdId
     * @param $householdId
     * @param $includeCurrent
     * @param $includeNext
     * @param $includePrevious
     * @return Collection
     */
    public static function getWeeklyMenus(
        $householdId,
        $includeCurrent,
        $includeNext,
        $includePrevious
    ): Collection {
        // declare
        $currentDateWeekStart = Carbon::now()->startOfWeek()->format('Y-m-d');
        $dateWeekStart = null;
        $operator = null;

        // prepare filter parameters
        if ($includeCurrent && $includeNext && $includePrevious) {
            $dateWeekStart = '1900-01-01';
            $operator = '>';
        } elseif ($includeCurrent && !$includeNext && !$includePrevious) {
            $dateWeekStart = $currentDateWeekStart;
            $operator = '=';
        } elseif ($includeCurrent && $includeNext && !$includePrevious) {
            $dateWeekStart = $currentDateWeekStart;
            $operator = '>=';
        } elseif ($includeCurrent && !$includeNext && $includePrevious) {
            $dateWeekStart = $currentDateWeekStart;
            $operator = '<=';
        } elseif (!$includeCurrent && $includeNext && $includePrevious) {
            $dateWeekStart = $currentDateWeekStart;
            $operator = '<>';
        } elseif (!$includeCurrent && !$includeNext && $includePrevious) {
            $dateWeekStart = $currentDateWeekStart;
            $operator = '<';
        }

        return WeeklyMenu::
        select(
            'id',
            'date_week_start',
            'week_no',
            'menu_rating',
            'household_id',
            'user_id_insert',
            'user_id_update',
            'created_at',
            'updated_at',
            'deleted_at'
        )
            ->selectRaw('IF(date_week_start = ?, 1, 0) AS is_active', [$dateWeekStart])
            ->where('household_id', $householdId)
            ->where('date_week_start', $operator, $dateWeekStart)
            ->get();
    }

    /**
     * returns array of weeks (+/- 10 weeks from today) that have no weekly menu
     * @param $householdId
     * @param $weeklyMenuIdEdit
     * @return Collection
     */
    public static function getWeeksWithoutExistingMenu($householdId, $weeklyMenuIdEdit): Collection {
        // declare
        $carbonDateWeekStart = Carbon::now()->startOfWeek();
        $dateWeekStart = $carbonDateWeekStart->format('Y-m-d');
        $dateWeekStartPlus10Weeks = $carbonDateWeekStart->copy()->addWeeks(10)->format('Y-m-d');
        $dateWeekStartMinus10Weeks = $carbonDateWeekStart->copy()->subWeeks(10)->format('Y-m-d');

        // return weeks with no weekly menu
        return collect(DB::select('
            SELECT
                CT.date AS value,
                CT.week_no,
                CASE
                    WHEN CT.date < :dateWeekStart1 THEN \'Previous weeks\'
                    WHEN CT.date > :dateWeekStart2 THEN \'Coming weeks\'
                    ELSE \'Current week\'
                END AS optionGroup,
                CASE
                    WHEN CT.date < :dateWeekStart5 THEN 3
                    WHEN CT.date > :dateWeekStart6 THEN 2
                    ELSE 1
                END AS optionGroupSortOrder,
                CONCAT(\'Week \', CT.week_no, \' (\', CT.date, \')\') AS label
            FROM
                calendar_table CT
                LEFT JOIN fp_weekly_menus WMEdit ON
                    WMEdit.id = COALESCE(:weeklyMenuIdEdit,0) AND
                    WMEdit.date_week_start = CT.date
            WHERE
                (
                    WMEdit.id IS NOT NULL
                    OR
                    CT.date BETWEEN :dateWeekStartMinus10Weeks AND :dateWeekStartPlus10Weeks
                ) AND
                CT.day_of_week = 1 AND
                NOT EXISTS (
                    SELECT
                        1
                    FROM
                        fp_weekly_menus WM
                    WHERE
                        WM.household_id = :householdId AND
                        WM.date_week_start = CT.date AND
                        WM.deleted_at IS NULL AND
                        (
                            WMEdit.id IS NULL
                            OR
                            WM.id <> WMEdit.id
                        )
                )
            ORDER BY
                IF(CT.date < :dateWeekStart3, CT.date, null) DESC,
                IF(CT.date >= :dateWeekStart4, CT.date, null) ASC
        ',
            array(
                'dateWeekStart1' => $dateWeekStart,
                'dateWeekStart2' => $dateWeekStart,
                'dateWeekStart5' => $dateWeekStart,
                'dateWeekStart6' => $dateWeekStart,
                'weeklyMenuIdEdit' => $weeklyMenuIdEdit,
                'dateWeekStartMinus10Weeks' => $dateWeekStartMinus10Weeks,
                'dateWeekStartPlus10Weeks' => $dateWeekStartPlus10Weeks,
                'householdId' => $householdId,
                'dateWeekStart3' => $dateWeekStart,
                'dateWeekStart4' => $dateWeekStart
            )
        ));
    }

    /**
     * returns seven random dishes to populate new weekly menu form
     * @return Collection
     */
    public static function getRandomDishesForWeekMenu(): Collection {
        return Dish::select('id')->inRandomOrder()->limit(7)->get();
    }

    /**
     * returns all dishes connected to the weekly menu
     * @return Collection
     */
    public function getDishes(): Collection {
        return collect(
            DB::table('fp_weekly_menu_dish AS WMD')
                ->select(
                    'WMD.day_of_week',
                    'WMD.dish_id',
                    'D.dish_name'
                )
                ->join('fp_dishes AS D', 'D.id', '=', 'WMD.dish_id')
                ->where('WMD.weekly_menu_id', '=', $this->id)
                ->orderBy('WMD.day_of_week')
                ->get()
        );
    }

    /**
     * Sync weekly menu dishes when creating/updating weekly menu insert/update (eloquent ORM)
     * @param $dishes
     */
    public function syncDishes($dishes): void {
        // remove all incomplete rows from array before insert
        $dishes = array_filter(
            $dishes,
            function ($item) {
                return
                    !empty(strip_tags($item['day_of_week'])) &&
                    !empty(strip_tags($item['dish_id']));
            }
        );

        // sync dishes (eloquent)
        $this->dishes()->sync($dishes);
    }

    /**
     * Eloquent ORM relation
     * @return BelongsToMany
     */
    public function dishes(): BelongsToMany {
        return $this
            ->belongsToMany('App\Models\FoodPlanner\Dish', 'fp_weekly_menu_dish')
            ->withPivot('day_of_week');
    }

    /**
     * returns summarized list of weekly menu shopping list items for creating
     * shopping list rows
     * @return array
     */
    public function getWeeklyMenuShoppingListItems(): array {
        return DB::select('
            SELECT
                WM.item_name,
                WM.unit_type,
                WM.category,
                WM.quantity,
                WM.ingredient_dishes_json AS ingredient_dishes
            FROM
                view_weeklyMenuShoppingList WM
            WHERE
                WM.weekly_menu_id = :weeklyMenuId
            ',
            array(
                'weeklyMenuId' => $this->id
            )
        );
    }

    /**
     * returns shopping list id connected to weekly menu
     * @return int
     */
    public function getShoppingListId(): int {
        return DB::table('fp_shopping_lists')
            ->where('weekly_menu_id', '=', $this->id)
            ->value('id');
    }

    /**
     * validate that the user owns the weekly menu
     * @param int $userId
     */
    public function verifyUserOwnerShip(int $userId): void {
        Household::verifyUserOwnership($userId, $this->id, 'weeklyMenu');
    }
}
