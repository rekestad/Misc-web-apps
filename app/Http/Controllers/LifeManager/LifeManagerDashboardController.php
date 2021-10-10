<?php

namespace App\Http\Controllers\LifeManager;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

/**
 * Class DashboardController
 * @package App\Http\Controllers\LifeManager
 */
class LifeManagerDashboardController extends Controller
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
     * show life manager dashboard
     * @return Application|Factory|View
     */
    public function index() {
        return view('LifeManager.ar_dashboard', [
            'title' => 'Welcome',
            'user' => $this->user,
            'doIncludeButtonMenu' => true
        ]);
    }
}
