<?php

namespace App\Models\FoodPlanner;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Class Household
 * @package App\Models\FoodPlanner
 */
class Household extends Model
{
    /**
     * verifies that the user owns the household object
     * @param $userId
     * @param $objectId
     * @param $objectType
     */
    public static function verifyUserOwnership($userId, $objectId, $objectType): void {
        $userHasOwnerShip = false;

        // shopping list
        if ($objectType == 'shoppingList') {
            $userHasOwnerShip = DB::table('fp_shopping_lists AS S')
                ->join('fp_weekly_menus AS WM', 'WM.id', '=', 'S.weekly_menu_id')
                ->join('fp_household_member AS HM', 'HM.household_id', '=', 'WM.household_id')
                ->where('S.id', '=', $objectId)
                ->where('HM.user_id', '=', $userId)
                ->exists();
        }

        // weekly menu
        if ($objectType == 'weeklyMenu') {
            $userHasOwnerShip = DB::table('fp_weekly_menus AS WM')
                ->join('fp_household_member AS HM', 'HM.household_id', '=', 'WM.household_id')
                ->where('WM.id', '=', $objectId)
                ->where('HM.user_id', '=', $userId)
                ->exists();
        }

        // dish
        if ($objectType == 'dish') {
            $userHasOwnerShip = DB::table('fp_dishes AS D')
                ->join('fp_household_member AS HM', 'HM.household_id', '=', 'D.household_id')
                ->where('D.id', '=', $objectId)
                ->where('HM.user_id', '=', $userId)
                ->exists();
        }

        // abort if unauthorized
        if (!$userHasOwnerShip) {
            abort(403, 'Unauthorized action.');
        }
    }
}
