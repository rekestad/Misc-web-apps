<?php

use App\Http\Controllers\FoodPlanner\FoodPlannerDashboardController;
use App\Http\Controllers\FoodPlanner\DishController;
use App\Http\Controllers\FoodPlanner\IngredientController;
use App\Http\Controllers\FoodPlanner\ShoppingListController;
use App\Http\Controllers\FoodPlanner\ShoppingListRowController;
use App\Http\Controllers\FoodPlanner\WeeklyMenuController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LifeManager\LifeManagerDashboardController;
use App\Http\Controllers\LifeManager\ToDoController;
use App\Http\Controllers\LifeManager\ToDoGroupController;
use App\Http\Controllers\SingAlong\PublicSongBookController;
use App\Http\Controllers\SingAlong\SingAlongDashboardController;
use App\Http\Controllers\SingAlong\SongBookController;
use App\Http\Controllers\SingAlong\SongController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {return view('welcome');})->name('welcome');
Route::get('/home', [HomeController::class,'index'])->name('home');

require __DIR__.'/auth.php';

####################
### FOOD PLANNER ###
####################

Route::prefix('foodPlanner')->group(function () {
    Route::get('', [FoodPlannerDashboardController::class,'index'])->name('foodPlanner');
    Route::resource('dishes', DishController::class);
    Route::resource('weeklyMenus', WeeklyMenuController::class);
    Route::resource('ingredients', IngredientController::class);
    Route::get('shoppingLists/{shoppingList}/{displayIsChecked?}', [ShoppingListController::class,'show'])->name('shoppingLists.show');
    Route::resource('shoppingLists', ShoppingListController::class, ['except' => ['show']]);
    Route::get('shoppingListRows/showDishes/{shoppingListRow}', [ShoppingListRowController::class,'showDishes'])->name('shoppingListRows.showDishes');
    Route::post('shoppingListRows/{shoppingList}', [ShoppingListRowController::class,'store'])->name('shoppingListRows.store');
    Route::resource('shoppingListRows', ShoppingListRowController::class, ['except' => ['store']]);
});

################
### LIFE LOG ###
################

Route::prefix('lifeManager')->group(function () {
    Route::get('', [LifeManagerDashboardController::class, 'index'])->name('lifeManager');
    Route::delete('toDoGroups/{toDoGroup}/deleteChecked', [ToDoGroupController::class,'deleteChecked'])->name('toDoGroups.deleteChecked');
    Route::resource('toDoGroups', ToDoGroupController::class);
    Route::patch('toDos/checkUncheck', [ToDoController::class,'checkUncheck'])->name('toDos.checkUncheck');
    Route::get('toDos/create/{toDoGroup}', [ToDoController::class,'create'])->name('toDos.create');
    Route::get('toDos/{toDoGroup?}', [ToDoController::class,'index'])->name('toDos.index');
    Route::resource('toDos', ToDoController::class,  ['except' => ['create','index']]);
});

#################
### SONG BOOK ###
#################

Route::prefix('singAlong')->group(function () {
    Route::get('', [SingAlongDashboardController::class,'index'])->name('singAlong');
    Route::resource('songBooks', SongBookController::class);
    Route::get('songs/{song}/chords', [SongController::class,'showChords'])->name('showChords');
    Route::resource('songs', SongController::class);
});

// PUBLIC ROUTES
Route::prefix('showSongBook')->group(function () {
    Route::get('{songBookUrl}/{songNo}/chords', [PublicSongBookController::class,'showChords'])->name('showPublicChords');
    Route::get('{songBookUrl}', [PublicSongBookController::class,'show'])->name('showPublicSongBook');
});
