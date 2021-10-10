<?php

namespace App\Http\Controllers\FoodPlanner;

use App\Http\Controllers\Controller;
use App\Models\FoodPlanner\Dish;
use App\Models\FoodPlanner\Ingredient;
use App\Models\FoodPlanner\IngredientCategory;
use App\Models\FoodPlanner\UnitType;
use App\Models\User;
use App\Models\Util;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

/**
 * Class DishController
 * @package App\Http\Controllers\FoodPlanner
 */
class DishController extends Controller
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
     * Display a list of dishes
     * @return Factory|View
     */
    public function index() {
        $dishes = Dish::all()->sortBy('dish_name');

        $data = [
            'title' => 'Dishes (' . count($dishes) . ')',
            'buttonCreate' => [
                'route' => 'dishes.create',
                'title' => 'Add new'
            ],
            'householdId' => $this->householdId
        ];

        return view('FoodPlanner.dishes.index', compact('dishes'))->with($data);
    }

    /**
     * Show the form for creating a new dish
     * @return Factory|View
     */
    public function create() {
        return $this->createEdit();
    }

    /**
     * returns view for creating/editing dish
     * @param Dish|null $dish
     * @return Application|Factory|\Illuminate\Contracts\View\View
     */
    public function createEdit(Dish $dish = null) {
        $isEdit = !empty($dish);

        $data = [
            'dish' => $dish,
            'action' => ($isEdit ? route('dishes.update', ($dish->id ?? null)) : route('dishes.store')),
            'isEdit' => $isEdit,
            'ingredientOptions' => Ingredient::getAsSelectOptions(),
            'unitTypeOptions' => UnitType::getAsSelectOptions(),
            'categoryOptions' => IngredientCategory::getAsSelectOptions(),
            'dishIngredients' => ($isEdit ? $dish->getIngredients() : null)
        ];

        return view('FoodPlanner.dishes.createEdit')->with($data);
    }

    /**
     * Store a new dish
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse {
        return $this->storeUpdate($request);
    }

    /**
     * store/update dish
     * @param Request $request
     * @param Dish|null $dish
     * @return RedirectResponse
     */
    private function storeUpdate(Request $request, Dish $dish = null): RedirectResponse {
        $isNew = empty($dish);

        // validate
        $this->validateAttributes($request);

        // get form data
        $ingredients = $request->get('ingredients');
        $newIngredients = $request->get('newIngredients');
        $dishName = strip_tags($request->get('dish_name'));
        $dishDescription = strip_tags($request->get('dish_description'));
        $urlRecipe = filter_var($request->get('url_recipe'), FILTER_SANITIZE_URL);
        $dishRating = $request->get('dish_rating');
        $dishDifficulty = $request->get('dish_difficulty');
        $portions = $request->get('portions');

        // begin transaction
        DB::beginTransaction();

        try {
            // create dish
            if ($isNew) {
                $dish = Dish::create([
                    'dish_name' => $dishName,
                    'dish_description' => $dishDescription,
                    'url_recipe' => $urlRecipe,
                    'dish_rating' => $dishRating,
                    'dish_difficulty' => $dishDifficulty,
                    'portions' => $portions,
                    'household_id' => $this->householdId,
                    'user_id_insert' => $this->user->id,
                    'user_id_update' => $this->user->id
                ]);
                // update dish
            } else {
                $dish->update([
                    'dish_name' => $dishName,
                    'dish_description' => $dishDescription,
                    'url_recipe' => $urlRecipe,
                    'dish_rating' => $dishRating,
                    'dish_difficulty' => $dishDifficulty,
                    'portions' => $portions,
                    'user_id_update' => $this->user->id
                ]);
            }

            // create new ingredients and append
            // them to sync array
            $ingredients = array_merge(
                $ingredients,
                Ingredient::createNewIngredientsForDish($newIngredients, $this->user->id)
            );

            // sync ingredients
            $dish->syncIngredients($ingredients);

            // commit
            DB::commit();
        } catch (Throwable $e) {
            DB::rollback();
            return $this->returnToIndex($e->getMessage(), true);
        }
        return $this->returnToIndex('Dish has been ' . util_createdUpdateTextMsg($isNew));
    }

    /**
     * validate attributes before insert/update
     * @param Request $request
     */
    public function validateAttributes(Request $request): void {
        $request->validate([
            'dish_name' => 'required',
            'dish_rating' => 'integer|digits_between:1,5',
            'dish_difficulty' => 'integer|digits_between:1,5',
            'portions' => 'integer'
        ]);
    }

    /**
     * returns index route with success or error message
     * @param string $message
     * @param bool $isError
     * @return RedirectResponse
     */
    public function returnToIndex(string $message, bool $isError = false): RedirectResponse {
        return Util::returnToIndexRoute(route('dishes.index'), $message, $isError);
    }

    /**
     * Display the specified dish
     *
     * @param Dish $dish
     * @return Application|Factory|View
     */
    public function show(Dish $dish) {
        $dish->verifyUserOwnerShip($this->user->id);

        $data = [
            'title' => $dish->dish_name
        ];

        return view('FoodPlanner.dishes.show', compact('dish'))->with($data);
    }

    /**
     * Show the form for editing the specified dish
     *
     * @param Dish $dish
     * @return Factory|View
     */
    public function edit(Dish $dish) {
        $dish->verifyUserOwnerShip($this->user->id);

        return $this->createEdit($dish);
    }

    /**
     * Update the specified dish
     *
     * @param Request $request
     * @param Dish $dish
     * @return RedirectResponse
     */
    public function update(Request $request, Dish $dish): RedirectResponse {
        $dish->verifyUserOwnerShip($this->user->id);

        return $this->storeUpdate($request, $dish);
    }

    /**
     * Remove the specified dish
     *
     * @param Dish $dish
     * @return RedirectResponse
     */
    public function destroy(Dish $dish): RedirectResponse {
        $dish->verifyUserOwnerShip($this->user->id);

        try {
            $dish->delete();
            return $this->returnToIndex('Dish has been deleted');
        } catch (Exception $e) {
            Log::error('Dish controller > destroy: ' . $e->getMessage());
            return $this->returnToIndex('An error occurred', true);
        }
    }
}
