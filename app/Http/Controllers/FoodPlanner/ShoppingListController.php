<?php

namespace App\Http\Controllers\FoodPlanner;

use App\Http\Controllers\Controller;
use App\Models\FoodPlanner\ShoppingList;
use App\Models\FoodPlanner\ShoppingListRow;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * Class ShoppingListController
 * @package App\Http\Controllers\FoodPlanner
 */
class ShoppingListController extends Controller
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
     * Display the specified shopping list
     *
     * @param ShoppingList $shoppingList
     * @param int $displayIsChecked
     * @return Application|Factory|View
     */
    public function show(ShoppingList $shoppingList, int $displayIsChecked = 0) {
        // validate that shopping list belongs to user
        $shoppingList->verifyUserOwnerShip($this->user->id);

        $shoppingListRows = ShoppingListRow::getAsHtmlTableRows($shoppingList->id, $displayIsChecked);

        $data = [
            'title' => 'Shopping list',
            'shoppingList' => $shoppingList,
            'shoppingListRows' => $shoppingListRows,
            'weeklyMenu' => $shoppingList->getWeeklyMenu(),
            'displayIsChecked' => $displayIsChecked
        ];

        return view('FoodPlanner.shoppingLists.show')->with($data);
    }

    /**
     * Show the form for editing the specified shopping list
     *
     * @param ShoppingList $shoppingList
     */
    public function edit(ShoppingList $shoppingList) {
        abort(404); // to be implemented
    }

    /**
     * Update the specified shopping list
     *
     * @param Request $request
     * @param ShoppingList $shoppingList
     */
    public function update(Request $request, ShoppingList $shoppingList) {
        abort(404); // to be implemented
    }

    /**
     * Remove the specified shopping list
     *
     * @param ShoppingList $shoppingList
     */
    public function destroy(ShoppingList $shoppingList) {
        abort(404); // to be implemented
    }
}
