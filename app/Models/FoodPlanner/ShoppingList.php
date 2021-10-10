<?php

namespace App\Models\FoodPlanner;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ShoppingList
 * @package App\Models\FoodPlanner
 */
class ShoppingList extends Model
{
    protected $table = 'fp_shopping_lists';
    protected $fillable = [
        'weekly_menu_id',
        'user_id_insert',
        'user_id_update'
    ];

    /**
     * returns weekly menu connected to shopping list
     * @return WeeklyMenu
     */
    public function getWeeklyMenu(): WeeklyMenu {
        return WeeklyMenu::find($this->weekly_menu_id);
    }

    /**
     * validate that the user owns the shopping list
     */
    public function verifyUserOwnerShip(int $userId): void {
        Household::verifyUserOwnership($userId, $this->id, 'shoppingList');
    }
}
