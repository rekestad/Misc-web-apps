<?php

namespace App\Http\Controllers\FoodPlanner;

use App\Http\Controllers\Controller;
use App\Models\FoodPlanner\Dish;
use App\Models\FoodPlanner\WeeklyMenu;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

/**
 * Class DashboardController
 * @package App\Http\Controllers\FoodPlanner
 */
class FoodPlannerDashboardController extends Controller
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
     * Food planner dashboard
     * @return Application|Factory|View
     */
    public function index(): View {
        $carbonDateWeekStart = Carbon::now()->startOfWeek();
        $dateWeekStart = $carbonDateWeekStart->format('Y-m-d');
        $dateNextWeekStart = $carbonDateWeekStart->addDays(7)->format('Y-m-d');

        $currentMenu = WeeklyMenu::where('date_week_start', '=', $dateWeekStart)->first();
        $nextMenu = WeeklyMenu::where('date_week_start', '=', $dateNextWeekStart)->first();
        $weekDayNames = util_getWeekDayNames(false);
        $householdId = $this->user->getHouseholdId();

        // lists for dashboard
        $dishesTopList = Dish::getDishTopList($householdId);
        $dishesNeverEaten = Dish::getDishesNeverEaten($householdId);

        return view('FoodPlanner.dashboard', [
            'page' => 'dashboard',
            'title' => 'Welcome',
            'user' => $this->user,
            'dishesTopList' => $dishesTopList,
            'dishesNeverEaten' => $dishesNeverEaten,
            'currentMenu' => $currentMenu,
            'nextMenu' => $nextMenu,
            'weekDayNames' => $weekDayNames
        ]);
    }
}
