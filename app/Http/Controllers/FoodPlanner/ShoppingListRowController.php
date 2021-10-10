<?php

namespace App\Http\Controllers\FoodPlanner;

use App\Http\Controllers\Controller;
use App\Models\FoodPlanner\ShoppingList;
use App\Models\FoodPlanner\ShoppingListRow;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Throwable;

/**
 * Class ShoppingListRowController
 * @package App\Http\Controllers\FoodPlanner
 */
class ShoppingListRowController extends Controller
{
    private ?User $user;

    public function __construct() {
        $this->middleware('auth');

        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();

            return $next($request);
        });
    }

    /**
     * Store a new (manual) shopping list row
     *
     * @param Request $request
     * @param ShoppingList $shoppingList
     * @return RedirectResponse
     */
    public function store(Request $request, ShoppingList $shoppingList) {
        $items = $request->get('item');

        // validate that shopping list belongs to user
        $shoppingList->verifyUserOwnerShip($this->user->id);

        // insert manual items
        ShoppingListRow::insertManualItems($shoppingList->id, $items);

        return redirect()->route('shoppingLists.show', [
            'shoppingList' => $shoppingList->id
        ]);
    }

    /**
     * check/uncheck a shopping list row (ajax call)
     *
     * @param Request $request
     * @param ShoppingListRow $shoppingListRow
     * @return JsonResponse
     */
    public function update(Request $request, ShoppingListRow $shoppingListRow): JsonResponse {
        $shoppingListRow->verifyUserOwnerShip($this->user->id);
        try {
            $isSuccess = null;
            $responseMessage = null;

            // derive what action we are performing
            $setIsCheckedTo = boolval($request->get('setIsCheckedTo'));
            $setIsCheckedToText = ($setIsCheckedTo ? 'checked' : 'unchecked');

            // get current status of shopping list row
            $checkedAt_currentValue = $shoppingListRow->checked_at;
            $userIdChecked_currentValue = $shoppingListRow->user_id_checked;
            $isChecked_currentBoolValue = !empty($checkedAt_currentValue);

            if ($setIsCheckedTo && $isChecked_currentBoolValue) {
                // Are we trying to check an already checked item?
                $isSuccess = false;
                $responseMessage = 'Item "'
                    . $shoppingListRow->item_name
                    . '" has already been checked by '
                    . User::getName($userIdChecked_currentValue) . ' '
                    . Carbon::parse($checkedAt_currentValue)->format('Y-m-d H:i');
            } elseif (!$setIsCheckedTo && !$isChecked_currentBoolValue) {
                // Are we trying to uncheck an already unchecked item?
                $isSuccess = false;
                $responseMessage = 'Item has already been unchecked';
            } else {
                // Check/uncheck item
                $shoppingListRow->update([
                    'checked_at' => ($setIsCheckedTo ? Carbon::now() : null),
                    'user_id_checked' => ($setIsCheckedTo ? $this->user->id : null)
                ]);
                $isSuccess = true;
                $responseMessage = 'Item ' . $setIsCheckedToText;
            }
        } catch (Throwable $e) {
            $isSuccess = false;
            $responseMessage = $e->getMessage();
        }

        return response()->json([
            'isSuccess' => $isSuccess,
            'message' => $responseMessage
        ]);
    }

    /**
     * returns the ingredient dishes as unordered list for display in modal (ajax call)
     * @param ShoppingListRow $shoppingListRow
     * @return JsonResponse
     */
    public function showDishes(ShoppingListRow $shoppingListRow): JsonResponse {
        $ingredientDishesDisplay = null;

        foreach (json_decode($shoppingListRow->weekly_menu_dishes) as $d) {
            $ingredientDishesDisplay .= '<li>' . $d->dish_name . ' (' . $d->qty . ' ' . $d->unit . ')</li>';
        }

        $ingredientDishesDisplay = '<ul>' . $ingredientDishesDisplay . '</ul>';

        return response()->json([
            'isSuccess' => true,
            'modalHeader' => $shoppingListRow->item_name,
            'modalBody' => $ingredientDishesDisplay
        ]);
    }
}
