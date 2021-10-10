<?php

namespace App\Http\Controllers\FoodPlanner;

use App\Http\Controllers\Controller;
use App\Models\FoodPlanner\Dish;
use App\Models\FoodPlanner\ShoppingList;
use App\Models\FoodPlanner\ShoppingListRow;
use App\Models\FoodPlanner\WeeklyMenu;
use App\Models\User;
use App\Models\Util;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Throwable;

/**
 * Class WeeklyMenuController
 * @package App\Http\Controllers\FoodPlanner
 */
class WeeklyMenuController extends Controller
{
    private ?User $user;
    private ?int $householdId;

    public function __construct() {
        $this->middleware('auth');

        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            $this->householdId = Auth::user()->getHouseholdId();

            return $next($request);
        });
    }

    /**
     * Display a list of all weekly menus for the given household
     *
     * @return Factory|View
     */
    public function index() {

        $weeklyMenusCurrentNext = WeeklyMenu::getWeeklyMenus($this->householdId, true, true, false)->sortBy('date_week_start');
        $weeklyMenusPrevious = WeeklyMenu::getWeeklyMenus($this->householdId, false, false, true)->sortByDesc('date_week_start');

        $data = [
            'buttonCreate' => [
                'route' => 'weeklyMenus.create',
                'title' => 'Add new'
            ],
            'weeklyMenusCurrentNext' => $weeklyMenusCurrentNext,
            'weeklyMenusPrevious' => $weeklyMenusPrevious,
            'hasCurrentOrNextMenus' => (!empty($weeklyMenusCurrentNext) && count($weeklyMenusCurrentNext) > 0),
            'hasPreviousMenus' => (!empty($weeklyMenusPrevious) && count($weeklyMenusPrevious) > 0)
        ];

        return view('FoodPlanner.weeklyMenus.index')->with($data);
    }

    /**
     * Show the form for creating a new weekly menu
     *
     * @return Factory|View
     */
    public function create() {
        // populate new form with random dishes
        $selectedDishes = WeeklyMenu::getRandomDishesForWeekMenu();

        return $this->createEdit($selectedDishes);
    }

    /**
     * Display the create/edit form for weekly menu
     * @param $selectedDishes
     * @param WeeklyMenu|null $weeklyMenu
     * @return Application|Factory|View
     */
    public function createEdit($selectedDishes, WeeklyMenu $weeklyMenu = null) {
        $isEdit = !empty($weeklyMenu);

        $data = [
            'action' => ($isEdit ? route('weeklyMenus.update', ($weeklyMenu->id ?? null)) : route('weeklyMenus.store')),
            'dishes' => Dish::getDishesAsSelectOptions($this->householdId),
            'selectedDishes' => $selectedDishes,
            'weeks' => WeeklyMenu::getWeeksWithoutExistingMenu($this->householdId, ($weeklyMenu->id ?? null)),
            'weeksSelected' => ($weeklyMenu->date_week_start ?? null),
            'isEdit' => $isEdit
        ];

        return view('FoodPlanner.weeklyMenus.createEdit')->with($data);
    }

    /**
     * Store a new weekly menu
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws Exception
     */
    public function store(Request $request): RedirectResponse {
        return $this->storeUpdate($request);
    }

    /**
     * store/update a weekly menu
     * @param Request $request
     * @param WeeklyMenu|null $weeklyMenu
     * @return RedirectResponse
     */
    private function storeUpdate(Request $request, WeeklyMenu $weeklyMenu = null): RedirectResponse {
        $isNew = empty($weeklyMenu);

        $this->validateAttributes($request);

        // fetch form data
        $dateWeekStart = $request->get('date_week_start');
        $weekNo = Carbon::parse($request->get('date_week_start'))->weekOfYear;
        $dishes = $request->get('dishes');

        DB::beginTransaction();

        try {
            if ($isNew) {
                // create weekly menu
                $weeklyMenu = WeeklyMenu::create([
                    'date_week_start' => $dateWeekStart,
                    'week_no' => $weekNo,
                    'household_id' => $this->user->getHouseholdId(),
                    'user_id_insert' => $this->user->id,
                    'user_id_update' => $this->user->id
                ]);

                // create shopping list header
                ShoppingList::create([
                    'weekly_menu_id' => $weeklyMenu->id,
                    'user_id_insert' => $this->user->id,
                    'user_id_update' => $this->user->id
                ]);
            } else {
                // update weekly menu
                $weeklyMenu->user_id_update = $this->user->id;
                $weeklyMenu->update();
            }

            // sync connected dishes
            $weeklyMenu->syncDishes($dishes);

            // sync shopping list rows
            ShoppingListRow::syncWeeklyMenuItemRows(
                $weeklyMenu->getShoppingListId(),
                $weeklyMenu->getWeeklyMenuShoppingListItems()
            );

            DB::commit();
        } catch (Throwable $e) {
            DB::rollback();

            return $this->returnToIndex($e->getMessage(), true);
        }

        return $this->returnToIndex('Menu has been ' . util_createdUpdateTextMsg($isNew));
    }

    /**
     * validate weekly menu before insert/update
     * @param Request $request
     */
    public function validateAttributes(Request $request): void {
        $rules = WeeklyMenu::getValidationRules();
        $messages = WeeklyMenu::getValidationMessages();

        try {
            Validator::make($request->all(), $rules, $messages)->validate();
        } catch (ValidationException $e) {
        }
    }

    /**
     * returns index route with success or error message
     * @param string $message
     * @param bool $isError
     * @return RedirectResponse
     */
    public function returnToIndex(string $message, bool $isError = false): RedirectResponse {
        return Util::returnToIndexRoute(route('weeklyMenus.index'), $message, $isError);
    }

    /**
     * Show the form for editing the weekly menu
     *
     * @param WeeklyMenu $weeklyMenu
     * @return Factory|View
     */
    public function edit(WeeklyMenu $weeklyMenu) {
        $weeklyMenu->verifyUserOwnerShip($this->user->id);

        $dishes = $weeklyMenu->dishes()->get();
        $selectedDishes = array();

        foreach ($dishes as $d) {
            $selectedDishes[$d->pivot->day_of_week - 1] = $d;
        }

        return $this->createEdit($selectedDishes, $weeklyMenu);
    }

    /**
     * Update the specified weekly menu
     *
     * @param Request $request
     * @param WeeklyMenu $weeklyMenu
     * @return RedirectResponse
     * @throws Exception
     */
    public function update(Request $request, WeeklyMenu $weeklyMenu): RedirectResponse {
        $weeklyMenu->verifyUserOwnerShip($this->user->id);

        return $this->storeUpdate($request, $weeklyMenu);
    }

    /**
     * Remove the specified weekly menu
     *
     * @param WeeklyMenu $weeklyMenu
     * @return RedirectResponse
     */
    public function destroy(WeeklyMenu $weeklyMenu): RedirectResponse {
        $weeklyMenu->verifyUserOwnerShip($this->user->id);

        try {
            $weeklyMenu->delete();
        } catch (Exception $e) {
            return $this->returnToIndex($e->getMessage(), true);
        }

        return $this->returnToIndex('Menu has been deleted');
    }
}
