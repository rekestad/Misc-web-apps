<?php

namespace App\Http\Controllers\FoodPlanner;

use App\Http\Controllers\Controller;
use App\Models\FoodPlanner\Ingredient;
use App\Models\FoodPlanner\IngredientCategory;
use App\Models\FoodPlanner\UnitType;
use App\Models\User;
use App\Models\Util;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * Class IngredientController
 * @package App\Http\Controllers\FoodPlanner
 */
class IngredientController extends Controller
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
     * List all ingredients
     * @return Factory|View
     */
    public function index() {
        $tableRows = Ingredient::getAsHtmlTableRows();

        $data = [
            'title' => 'Ingredients (' . count($tableRows) . ')',
            'buttonCreate' => [
                'route' => 'ingredients.create',
                'title' => 'Add new'
            ],
            'tableRows' => $tableRows
        ];

        return view('FoodPlanner.ingredients.index')->with($data);
    }

    /**
     * Show the form for creating a new ingredient
     * @return Factory|View
     */
    public function create() {
        return $this->createEdit();
    }

    /**
     * returns view containing create/edit ingredient form
     * @param Ingredient|null $ingredient
     * @return Factory|View
     */
    public function createEdit(Ingredient $ingredient = null) {
        $isEdit = !empty($ingredient);

        $data = [
            'action' => ($isEdit ? route('ingredients.update', ($ingredient->id ?? null)) : route('ingredients.store')),
            'unitTypes' => UnitType::getAsSelectOptions(),
            'unitTypeIdSelected' => ($ingredient->unit_type_id ?? UnitType::getDefault()),
            'categories' => IngredientCategory::getAsSelectOptions(),
            'categoryIdSelected' => ($ingredient->category_id ?? IngredientCategory::getDefault()),
            'isEdit' => $isEdit,
            'ingredient' => $ingredient
        ];

        return view('FoodPlanner.ingredients.createEdit')->with($data);
    }

    /**
     * Store a new ingredient
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse {
        return $this->storeUpdate($request);
    }

    /**
     * Store a newly created ingredient
     * @param Request $request
     * @param Ingredient|null $ingredient
     * @return RedirectResponse
     */
    public function storeUpdate(Request $request, Ingredient $ingredient = null): RedirectResponse {
        $isNew = empty($ingredient);

        $this->validateAttributes($request, $ingredient);

        $ingredientName = strtolower(strip_tags($request->get('ingredient_name')));
        $unitTypeId = strip_tags($request->get('unit_type_id'));
        $categoryId = strip_tags($request->get('category_id'));

        // Store
        if ($isNew) {
            Ingredient::create([
                'ingredient_name' => $ingredientName,
                'unit_type_id' => $unitTypeId,
                'category_id' => $categoryId,
                'user_id_update' => $this->user->id,
                'user_id_insert' => $this->user->id
            ]);
            // update
        } else {
            $ingredient->update([
                'ingredient_name' => $ingredientName,
                'unit_type_id' => $unitTypeId,
                'category_id' => $categoryId,
                'user_id_update' => $this->user->id
            ]);
        }

        return $this->returnToIndex('Ingredient has been ' . Util::createdUpdatedTextMsg($isNew));
    }

    /**
     * validate attributes before insert/update
     * @param Request $request
     * @param Ingredient|null $ingredient
     */
    public function validateAttributes(Request $request, Ingredient $ingredient = null): void {
        $request->validate([
            'ingredient_name' => 'required|unique:fp_ingredients,ingredient_name' . (!empty($ingredient) ? ',' . $ingredient->id : ''),
            'unit_type_id' => 'required|integer'
        ]);
    }

    /**
     * returns index route with success or error message
     * @param string $message
     * @param bool $isError
     * @return RedirectResponse
     */
    public function returnToIndex(string $message, bool $isError = false): RedirectResponse {
        return Util::returnToIndexRoute(route('ingredients.index'), $message, $isError);
    }

    /**
     * Show the form for editing the specified ingredient
     * @param Ingredient $ingredient
     * @return Factory|View
     */
    public function edit(Ingredient $ingredient) {
        return $this->createEdit($ingredient);
    }

    /**
     * Update the specified ingredient
     * @param Request $request
     * @param Ingredient $ingredient
     * @return RedirectResponse
     */
    public function update(Request $request, Ingredient $ingredient): RedirectResponse {
        return $this->storeUpdate($request, $ingredient);
    }
}
